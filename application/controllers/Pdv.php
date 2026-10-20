<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pdv extends MY_Controller
{
    private const CONSUMIDOR_FINAL = 'Consumidor Final';

    private const FORMAS_PGTO = ['Dinheiro', 'Pix', 'Cartão de Crédito', 'Débito', 'Boleto', 'Crediário'];

    // Formas de pagamento que geram lançamento em aberto (não baixado)
    private const FORMAS_A_PRAZO = ['Boleto', 'Crediário'];

    public function __construct()
    {
        parent::__construct();

        $this->data['menuPdv'] = 'PDV';
    }

    public function index()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para realizar vendas.');
            redirect(base_url());
        }

        $this->data['usuarios'] = $this->db
            ->select('idUsuarios, nome')
            ->where('situacao', 1)
            ->order_by('nome', 'asc')
            ->get('usuarios')
            ->result();
        $this->data['formasPgto'] = self::FORMAS_PGTO;
        $this->data['view'] = 'pdv/pdv';

        return $this->layout();
    }

    public function buscarProduto()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aVenda')) {
            return $this->json(['result' => false, 'messages' => 'Sem permissão.'], 403);
        }

        $termo = trim((string) $this->input->get('termo'));
        if ($termo === '') {
            return $this->json(['result' => true, 'produtos' => []]);
        }

        // Código de barras exato tem prioridade (leitor de código de barras)
        $produtos = $this->db
            ->select('idProdutos, codDeBarra, descricao, unidade, precoVenda, estoque')
            ->where('codDeBarra', $termo)
            ->limit(1)
            ->get('produtos')
            ->result();

        if (! $produtos) {
            $produtos = $this->db
                ->select('idProdutos, codDeBarra, descricao, unidade, precoVenda, estoque')
                ->group_start()
                ->like('descricao', $termo)
                ->or_like('codDeBarra', $termo)
                ->group_end()
                ->order_by('descricao', 'asc')
                ->limit(15)
                ->get('produtos')
                ->result();
        }

        return $this->json(['result' => true, 'produtos' => $produtos]);
    }

    public function buscarCliente()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aVenda')) {
            return $this->json(['result' => false, 'messages' => 'Sem permissão.'], 403);
        }

        $termo = trim((string) $this->input->get('termo'));

        $clientes = $this->db
            ->select('idClientes, nomeCliente, documento, celular, telefone')
            ->group_start()
            ->like('nomeCliente', $termo)
            ->or_like('documento', $termo)
            ->group_end()
            ->order_by('nomeCliente', 'asc')
            ->limit(15)
            ->get('clientes')
            ->result();

        return $this->json(['result' => true, 'clientes' => $clientes]);
    }

    public function finalizar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aVenda')) {
            return $this->json(['result' => false, 'messages' => 'Você não tem permissão para realizar vendas.'], 403);
        }

        $itens = json_decode((string) $this->input->post('itens'), true);
        if (! is_array($itens) || count($itens) === 0) {
            return $this->json(['result' => false, 'messages' => 'Adicione ao menos um produto.'], 422);
        }

        $formaPgto = $this->input->post('forma_pgto');
        if (! in_array($formaPgto, self::FORMAS_PGTO, true)) {
            return $this->json(['result' => false, 'messages' => 'Selecione a forma de pagamento.'], 422);
        }

        $tipoDesconto = $this->input->post('tipo_desconto') === 'porcento' ? 'porcento' : 'real';
        $desconto = max(0, (float) $this->input->post('desconto'));

        // Agrupa as quantidades por produto
        $quantidades = [];
        foreach ($itens as $item) {
            $id = (int) ($item['id'] ?? 0);
            $qtd = (int) ($item['quantidade'] ?? 0);
            if ($id <= 0 || $qtd <= 0) {
                return $this->json(['result' => false, 'messages' => 'Item inválido na venda.'], 422);
            }
            $quantidades[$id] = ($quantidades[$id] ?? 0) + $qtd;
        }

        // Preços sempre vêm do banco, nunca do navegador
        $produtos = $this->db
            ->where_in('idProdutos', array_keys($quantidades))
            ->get('produtos')
            ->result();
        $produtos = array_column($produtos, null, 'idProdutos');

        $controlaEstoque = (bool) $this->data['configuration']['control_estoque'];
        $valorTotal = 0;
        foreach ($quantidades as $id => $qtd) {
            if (! isset($produtos[$id])) {
                return $this->json(['result' => false, 'messages' => 'Produto não encontrado. ID: ' . $id], 422);
            }
            if ($controlaEstoque && $produtos[$id]->estoque < $qtd) {
                return $this->json([
                    'result' => false,
                    'messages' => 'Estoque insuficiente para ' . $produtos[$id]->descricao . ' (disponível: ' . $produtos[$id]->estoque . ').',
                ], 422);
            }
            $valorTotal += $produtos[$id]->precoVenda * $qtd;
        }
        $valorTotal = round($valorTotal, 2);

        $valorDesconto = $tipoDesconto === 'porcento' ? $valorTotal * min($desconto, 100) / 100 : $desconto;
        $valorDesconto = round(min($valorTotal, $valorDesconto), 2);
        $valorComDesconto = round($valorTotal - $valorDesconto, 2);

        $usuarioId = (int) $this->input->post('usuarios_id') ?: $this->session->userdata('id_admin');
        $hoje = date('Y-m-d');
        $aPrazo = in_array($formaPgto, self::FORMAS_A_PRAZO, true);

        $this->db->trans_start();

        $cliente = $this->clienteDaVenda((int) $this->input->post('clientes_id'));

        $this->db->insert('vendas', [
            'dataVenda' => $hoje,
            'valorTotal' => $valorTotal,
            'desconto' => $desconto,
            'tipo_desconto' => $desconto > 0 ? $tipoDesconto : null,
            'valor_desconto' => $valorComDesconto,
            'faturado' => 1,
            'clientes_id' => $cliente->idClientes,
            'usuarios_id' => $usuarioId,
            'status' => 'Faturado',
            'observacoes' => 'Venda realizada pelo PDV - ' . $formaPgto,
        ]);
        $idVenda = $this->db->insert_id();

        foreach ($quantidades as $id => $qtd) {
            $this->db->insert('itens_de_vendas', [
                'vendas_id' => $idVenda,
                'produtos_id' => $id,
                'quantidade' => $qtd,
                'preco' => $produtos[$id]->precoVenda,
                'subTotal' => round($produtos[$id]->precoVenda * $qtd, 2),
            ]);

            if ($controlaEstoque) {
                $this->db->query('UPDATE produtos SET estoque = estoque - ? WHERE idProdutos = ?', [$qtd, $id]);
            }
        }

        $this->db->insert('lancamentos', [
            'vendas_id' => $idVenda,
            'descricao' => "Fatura de Venda - #{$idVenda}",
            'valor' => $valorTotal,
            'desconto' => $valorDesconto,
            'tipo_desconto' => 'real',
            'valor_desconto' => $valorComDesconto,
            'clientes_id' => $cliente->idClientes,
            'cliente_fornecedor' => $cliente->nomeCliente,
            'data_vencimento' => $hoje,
            'data_pagamento' => $aPrazo ? null : $hoje,
            'baixado' => $aPrazo ? 0 : 1,
            'forma_pgto' => $formaPgto,
            'tipo' => 'receita',
            'usuarios_id' => $this->session->userdata('id_admin'),
        ]);
        $idLancamento = $this->db->insert_id();

        $this->db->where('idVendas', $idVenda)->update('vendas', ['lancamentos_id' => $idLancamento]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return $this->json(['result' => false, 'messages' => 'Ocorreu um erro ao finalizar a venda.'], 500);
        }

        log_info('Realizou uma venda pelo PDV. ID: ' . $idVenda);

        return $this->json([
            'result' => true,
            'messages' => 'Venda #' . $idVenda . ' finalizada com sucesso!',
            'idVenda' => $idVenda,
            'imprimir' => site_url('vendas/imprimirTermica/' . $idVenda),
        ]);
    }

    private function clienteDaVenda($clienteId)
    {
        if ($clienteId > 0) {
            $cliente = $this->db->where('idClientes', $clienteId)->get('clientes')->row();
            if ($cliente) {
                return $cliente;
            }
        }

        $cliente = $this->db->where('nomeCliente', self::CONSUMIDOR_FINAL)->limit(1)->get('clientes')->row();
        if ($cliente) {
            return $cliente;
        }

        $this->db->insert('clientes', [
            'nomeCliente' => self::CONSUMIDOR_FINAL,
            'pessoa_fisica' => 1,
            'documento' => '',
            'telefone' => '',
            'email' => '',
            'senha' => '',
            'dataCadastro' => date('Y-m-d'),
        ]);

        return $this->db->where('idClientes', $this->db->insert_id())->get('clientes')->row();
    }

    private function json($dados, $status = 200)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($status)
            ->set_output(json_encode($dados));
    }
}
