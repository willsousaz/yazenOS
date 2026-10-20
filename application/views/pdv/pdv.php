<style>
    .pdv { --pdv-borda: rgba(0, 0, 0, .08); --pdv-azul: #2c88ff; --pdv-verde: #198754; --pdv-vermelho: #dc3545; --pdv-escuro: #04395b; --pdv-cinza: #72737a; }
    .pdv *, .pdv *::before, .pdv *::after { box-sizing: border-box; }
    .pdv .pdv-card { background: #fff; border: 1px solid var(--pdv-borda); border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, .04); margin-bottom: 16px; }
    .pdv .pdv-card-titulo { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; border-bottom: 1px solid var(--pdv-borda); font-weight: 600; color: var(--pdv-escuro); font-size: 14px; }
    .pdv .pdv-card-titulo i { font-size: 18px; vertical-align: middle; margin-right: 6px; }
    .pdv .pdv-card-corpo { padding: 14px 16px; }
    .pdv input[type=text], .pdv input[type=number], .pdv select { width: 100%; height: 36px; margin: 0; border-radius: 5px; padding: 4px 10px; }
    .pdv label { font-size: 12px; color: var(--pdv-cinza); margin-bottom: 4px; }

    .pdv .pdv-busca { position: relative; display: flex; gap: 8px; align-items: stretch; }
    .pdv .pdv-busca .campo { position: relative; flex: 1; }
    .pdv .pdv-busca .campo i { position: absolute; left: 12px; top: 12px; font-size: 22px; color: var(--pdv-cinza); }
    .pdv .pdv-busca #pdvBusca { height: 46px; font-size: 16px; padding-left: 44px; border: 2px solid var(--pdv-azul); }
    .pdv .pdv-busca #pdvQtd { width: 90px; height: 46px; font-size: 16px; text-align: center; }
    .pdv .pdv-busca .btn { height: 46px; padding: 0 20px; font-size: 15px; }

    .pdv .pdv-sugestoes { position: absolute; z-index: 50; left: 0; right: 0; top: 100%; background: #fff; border: 1px solid var(--pdv-borda); border-radius: 0 0 5px 5px; box-shadow: 0 8px 24px rgba(4, 57, 91, .12); max-height: 320px; overflow-y: auto; display: none; }
    .pdv .pdv-sugestoes div { padding: 8px 12px; cursor: pointer; display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid rgba(0, 0, 0, .04); }
    .pdv .pdv-sugestoes div.ativo, .pdv .pdv-sugestoes div:hover { background: #eef5ff; }
    .pdv .pdv-sugestoes small { color: var(--pdv-cinza); }

    .pdv .pdv-tabela { width: 100%; margin: 0; }
    .pdv .pdv-tabela th { background: #f8f9fa; color: var(--pdv-cinza); font-size: 11px; text-transform: uppercase; font-weight: 500; padding: 8px 10px; text-align: left; }
    .pdv .pdv-tabela td { padding: 8px 10px; border-top: 1px solid rgba(0, 0, 0, .05); vertical-align: middle; }
    .pdv .pdv-tabela tbody tr:nth-child(even) { background: #fcfcfd; }
    .pdv .pdv-tabela .num { text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .pdv .pdv-tabela .qtd { display: inline-flex; align-items: center; gap: 4px; }
    .pdv .pdv-tabela .qtd button { width: 26px; height: 26px; padding: 0; border: 1px solid var(--pdv-borda); background: #fff; border-radius: 4px; }
    .pdv .pdv-tabela .qtd span { min-width: 28px; text-align: center; font-weight: 600; }
    .pdv .pdv-tabela .codigo { color: var(--pdv-cinza); font-size: 11px; }
    .pdv .pdv-tabela .remover { color: var(--pdv-vermelho); background: none; border: 0; font-size: 18px; }
    .pdv .pdv-vazio { text-align: center; color: var(--pdv-cinza); padding: 40px 0 !important; }
    .pdv .pdv-rodape-tabela { padding: 8px 16px; font-size: 12px; color: var(--pdv-cinza); border-top: 1px solid var(--pdv-borda); }

    .pdv .pdv-atalhos { display: flex; flex-wrap: wrap; gap: 14px; font-size: 12px; color: var(--pdv-cinza); }
    .pdv .pdv-atalhos kbd { background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; padding: 1px 6px; font-size: 11px; color: #333; }

    .pdv .pdv-cliente { position: relative; }
    .pdv .pdv-cliente-atual { display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: #f3f4f6; border-radius: 5px; margin-bottom: 8px; font-weight: 600; }
    .pdv .pdv-cliente-atual a { font-weight: 400; font-size: 12px; }

    .pdv .pdv-linha { display: flex; justify-content: space-between; align-items: center; padding: 4px 0; }
    .pdv .pdv-desconto { display: flex; gap: 6px; align-items: center; }
    .pdv .pdv-desconto input { width: 110px !important; text-align: right; }
    .pdv .pdv-toggle { display: inline-flex; border: 1px solid var(--pdv-borda); border-radius: 5px; overflow: hidden; }
    .pdv .pdv-toggle button { border: 0; background: #fff; padding: 0 10px; height: 36px; font-size: 12px; }
    .pdv .pdv-toggle button.ativo { background: var(--pdv-escuro); color: #fff; }
    .pdv .pdv-total { border-top: 1px dashed var(--pdv-borda); margin-top: 8px; padding-top: 10px; text-align: right; }
    .pdv .pdv-total small { display: block; font-size: 11px; color: var(--pdv-cinza); text-transform: uppercase; letter-spacing: .04em; }
    .pdv .pdv-total strong { font-size: 42px; line-height: 1.1; color: var(--pdv-verde); font-variant-numeric: tabular-nums; }

    .pdv .pdv-formas { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 12px; }
    .pdv .pdv-formas button { height: 64px; border: 1px solid var(--pdv-borda); border-radius: 5px; background: #fff; font-size: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px; }
    .pdv .pdv-formas button i { font-size: 22px; color: var(--pdv-cinza); }
    .pdv .pdv-formas button.ativo { border: 2px solid var(--pdv-azul); background: #eef5ff; color: var(--pdv-escuro); font-weight: 600; }
    .pdv .pdv-formas button.ativo i { color: var(--pdv-azul); }
    .pdv .pdv-troco { display: flex; gap: 10px; }
    .pdv .pdv-troco > div { flex: 1; }
    .pdv .pdv-troco .valor { height: 36px; line-height: 36px; padding: 0 10px; border-radius: 5px; background: #e8f5ee; color: var(--pdv-verde); font-weight: 700; text-align: right; }

    .pdv .pdv-finalizar { width: 100%; height: 60px; font-size: 20px; font-weight: 700; letter-spacing: .02em; margin-bottom: 8px; }
    .pdv .pdv-cancelar { width: 100%; }
</style>

<div class="pdv">
    <div class="row-fluid">
        <!-- Coluna esquerda: busca e itens -->
        <div class="span8">
            <div class="pdv-card">
                <div class="pdv-card-corpo">
                    <div class="pdv-busca">
                        <div class="campo">
                            <i class='bx bx-barcode-reader'></i>
                            <input type="text" id="pdvBusca" autocomplete="off" placeholder="Leia o código de barras ou digite o nome do produto (F2)">
                            <div class="pdv-sugestoes" id="pdvSugestoes"></div>
                        </div>
                        <input type="number" id="pdvQtd" min="1" value="1" title="Quantidade">
                        <button type="button" class="btn btn-primary" id="pdvAdicionar"><i class='bx bx-plus'></i> Adicionar</button>
                    </div>
                </div>
            </div>

            <div class="pdv-card">
                <div class="pdv-card-titulo">
                    <span><i class='bx bx-cart-alt'></i>Itens da Venda</span>
                    <span class="badge badge-info" id="pdvContador">0 itens</span>
                </div>
                <table class="pdv-tabela">
                    <thead>
                        <tr>
                            <th style="width: 40px">#</th>
                            <th>Produto</th>
                            <th style="width: 120px">Qtd</th>
                            <th class="num" style="width: 110px">Preço Unit.</th>
                            <th class="num" style="width: 110px">Subtotal</th>
                            <th style="width: 50px"></th>
                        </tr>
                    </thead>
                    <tbody id="pdvItens"></tbody>
                </table>
                <div class="pdv-rodape-tabela" id="pdvRodapeTabela">Total de itens: 0</div>
            </div>

            <div class="pdv-atalhos">
                <span><kbd>F2</kbd> Buscar produto</span>
                <span><kbd>F4</kbd> Cliente</span>
                <span><kbd>F6</kbd> Desconto</span>
                <span><kbd>F8</kbd> Cancelar venda</span>
                <span><kbd>F10</kbd> Finalizar</span>
            </div>
        </div>

        <!-- Coluna direita: cliente, resumo e pagamento -->
        <div class="span4">
            <div class="pdv-card">
                <div class="pdv-card-titulo">
                    <span><i class='bx bx-user'></i>Cliente &amp; Vendedor</span>
                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) { ?>
                        <a href="<?= site_url('clientes/adicionar') ?>" target="_blank" style="font-size: 12px; font-weight: 400">+ Novo cliente</a>
                    <?php } ?>
                </div>
                <div class="pdv-card-corpo">
                    <div class="pdv-cliente">
                        <div class="pdv-cliente-atual">
                            <span id="pdvClienteNome">Consumidor Final</span>
                            <a href="#" id="pdvClienteLimpar" style="display: none">trocar</a>
                        </div>
                        <input type="text" id="pdvClienteBusca" autocomplete="off" placeholder="Buscar cliente por nome ou CPF/CNPJ (F4)">
                        <div class="pdv-sugestoes" id="pdvClienteSugestoes"></div>
                    </div>
                    <label for="pdvVendedor" style="margin-top: 10px">Vendedor</label>
                    <select id="pdvVendedor">
                        <?php foreach ($usuarios as $u) { ?>
                            <option value="<?= $u->idUsuarios ?>" <?= $u->idUsuarios == $this->session->userdata('id_admin') ? 'selected' : '' ?>><?= htmlspecialchars($u->nome) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="pdv-card">
                <div class="pdv-card-titulo"><span><i class='bx bx-receipt'></i>Resumo</span></div>
                <div class="pdv-card-corpo">
                    <div class="pdv-linha"><span>Subtotal</span><strong id="pdvSubtotal">R$ 0,00</strong></div>
                    <div class="pdv-linha">
                        <span>Desconto</span>
                        <div class="pdv-desconto">
                            <div class="pdv-toggle">
                                <button type="button" data-tipo="real" class="ativo">R$</button>
                                <button type="button" data-tipo="porcento">%</button>
                            </div>
                            <input type="text" id="pdvDesconto" value="0,00" inputmode="decimal">
                        </div>
                    </div>
                    <div class="pdv-total">
                        <small>Total a pagar</small>
                        <strong id="pdvTotal">R$ 0,00</strong>
                    </div>
                </div>
            </div>

            <div class="pdv-card">
                <div class="pdv-card-titulo"><span><i class='bx bx-wallet'></i>Forma de Pagamento</span></div>
                <div class="pdv-card-corpo">
                    <?php $icones = ['Dinheiro' => 'bx-money', 'Pix' => 'bx-qr', 'Cartão de Crédito' => 'bx-credit-card', 'Débito' => 'bx-credit-card-front', 'Boleto' => 'bx-barcode', 'Crediário' => 'bx-calendar']; ?>
                    <div class="pdv-formas">
                        <?php foreach ($formasPgto as $i => $forma) { ?>
                            <button type="button" data-forma="<?= $forma ?>" class="<?= $i === 0 ? 'ativo' : '' ?>">
                                <i class='bx <?= $icones[$forma] ?>'></i><?= $forma ?>
                            </button>
                        <?php } ?>
                    </div>
                    <div class="pdv-troco" id="pdvTrocoBox">
                        <div>
                            <label for="pdvRecebido">Valor recebido (R$)</label>
                            <input type="text" id="pdvRecebido" placeholder="0,00" inputmode="decimal">
                        </div>
                        <div>
                            <label>Troco</label>
                            <div class="valor" id="pdvTroco">R$ 0,00</div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-success pdv-finalizar" id="pdvFinalizar"><i class='bx bx-check-circle'></i> FINALIZAR VENDA (F10)</button>
            <button type="button" class="btn btn-danger pdv-cancelar" id="pdvCancelar" style="background: #fff; color: #dc3545; border: 1px solid #dc3545; text-shadow: none"><i class='bx bx-x-circle'></i> Cancelar venda (F8)</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var urls = {
            produto: '<?= site_url('pdv/buscarProduto') ?>',
            cliente: '<?= site_url('pdv/buscarCliente') ?>',
            finalizar: '<?= site_url('pdv/finalizar') ?>'
        };
        var controlaEstoque = <?= $configuration['control_estoque'] ? 'true' : 'false' ?>;

        var itens = [];
        var cliente = { id: 0, nome: 'Consumidor Final' };
        var tipoDesconto = 'real';
        var formaPgto = $('.pdv-formas button.ativo').data('forma');
        var sugestoes = [];
        var enviando = false;

        function dinheiro(valor) {
            return 'R$ ' + valor.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function numero(texto) {
            texto = String(texto || '').replace(/[^\d,.-]/g, '');
            if (texto.indexOf(',') >= 0) {
                texto = texto.replace(/\./g, '').replace(',', '.');
            }
            return parseFloat(texto) || 0;
        }

        function escapar(texto) {
            return $('<div>').text(texto == null ? '' : texto).html();
        }

        function totais() {
            var subtotal = itens.reduce(function (soma, item) { return soma + item.preco * item.quantidade; }, 0);
            var desconto = numero($('#pdvDesconto').val());
            var valorDesconto = tipoDesconto === 'porcento' ? subtotal * Math.min(desconto, 100) / 100 : desconto;
            valorDesconto = Math.min(subtotal, Math.max(0, valorDesconto));
            return { subtotal: subtotal, desconto: valorDesconto, total: subtotal - valorDesconto };
        }

        function render() {
            var html = '';
            var pecas = 0;
            itens.forEach(function (item, i) {
                pecas += item.quantidade;
                html += '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + escapar(item.descricao) + '<br><small class="codigo">' + escapar(item.codigo) + '</small></td>' +
                    '<td><div class="qtd"><button type="button" data-acao="menos" data-i="' + i + '">−</button><span>' + item.quantidade + '</span><button type="button" data-acao="mais" data-i="' + i + '">+</button></div></td>' +
                    '<td class="num">' + dinheiro(item.preco) + '</td>' +
                    '<td class="num"><strong>' + dinheiro(item.preco * item.quantidade) + '</strong></td>' +
                    '<td><button type="button" class="remover" data-acao="remover" data-i="' + i + '" title="Remover"><i class="bx bx-trash"></i></button></td>' +
                    '</tr>';
            });
            if (!itens.length) {
                html = '<tr><td colspan="6" class="pdv-vazio"><i class="bx bx-cart" style="font-size: 32px"></i><br>Nenhum produto adicionado</td></tr>';
            }
            $('#pdvItens').html(html);
            $('#pdvContador').text(itens.length + (itens.length === 1 ? ' item' : ' itens'));
            $('#pdvRodapeTabela').text('Total de itens: ' + itens.length + ' (' + pecas + (pecas === 1 ? ' unidade' : ' unidades') + ')');

            var t = totais();
            $('#pdvSubtotal').text(dinheiro(t.subtotal));
            $('#pdvTotal').text(dinheiro(t.total));
            var recebido = numero($('#pdvRecebido').val());
            $('#pdvTroco').text(dinheiro(Math.max(0, recebido - t.total)));
        }

        function adicionar(produto) {
            var qtd = Math.max(1, parseInt($('#pdvQtd').val(), 10) || 1);
            var existente = itens.find(function (item) { return item.id == produto.idProdutos; });
            var novaQtd = (existente ? existente.quantidade : 0) + qtd;

            if (controlaEstoque && novaQtd > Number(produto.estoque)) {
                swal({ icon: 'warning', title: 'Estoque insuficiente', text: produto.descricao + ' tem ' + produto.estoque + ' em estoque.' });
                return;
            }

            if (existente) {
                existente.quantidade = novaQtd;
            } else {
                itens.push({
                    id: produto.idProdutos,
                    codigo: produto.codDeBarra,
                    descricao: produto.descricao,
                    preco: Number(produto.precoVenda),
                    estoque: Number(produto.estoque),
                    quantidade: qtd
                });
            }
            $('#pdvBusca').val('');
            $('#pdvQtd').val(1);
            esconderSugestoes('#pdvSugestoes');
            render();
            $('#pdvBusca').focus();
        }

        function esconderSugestoes(alvo) {
            $(alvo).hide().empty();
        }

        function mostrarSugestoesProduto(produtos) {
            sugestoes = produtos;
            if (!produtos.length) {
                $('#pdvSugestoes').html('<div><small>Nenhum produto encontrado</small></div>').show();
                return;
            }
            var html = produtos.map(function (p, i) {
                return '<div data-i="' + i + '" class="' + (i === 0 ? 'ativo' : '') + '">' +
                    '<span>' + escapar(p.descricao) + ' <small>' + escapar(p.codDeBarra) + '</small></span>' +
                    '<span><strong>' + dinheiro(Number(p.precoVenda)) + '</strong> <small>Est: ' + p.estoque + '</small></span></div>';
            }).join('');
            $('#pdvSugestoes').html(html).show();
        }

        function buscarProduto(adicionarSeUnico) {
            var termo = $.trim($('#pdvBusca').val());
            if (!termo) {
                return;
            }
            $.getJSON(urls.produto, { termo: termo }, function (resp) {
                if (adicionarSeUnico && resp.produtos.length === 1) {
                    adicionar(resp.produtos[0]);
                } else {
                    mostrarSugestoesProduto(resp.produtos);
                }
            });
        }

        function moverSugestao(alvo, direcao) {
            var opcoes = $(alvo + ' div[data-i]');
            if (!opcoes.length) {
                return;
            }
            var atual = opcoes.index(opcoes.filter('.ativo'));
            var proxima = Math.min(opcoes.length - 1, Math.max(0, atual + direcao));
            opcoes.removeClass('ativo').eq(proxima).addClass('ativo')[0].scrollIntoView({ block: 'nearest' });
        }

        function limparVenda() {
            itens = [];
            definirCliente(0, 'Consumidor Final');
            $('#pdvDesconto').val('0,00');
            $('#pdvRecebido').val('');
            render();
            $('#pdvBusca').val('').focus();
        }

        function definirCliente(id, nome) {
            cliente = { id: id, nome: nome };
            $('#pdvClienteNome').text(nome);
            $('#pdvClienteLimpar').toggle(id > 0);
            $('#pdvClienteBusca').val('');
            esconderSugestoes('#pdvClienteSugestoes');
        }

        function cancelar() {
            if (!itens.length) {
                limparVenda();
                return;
            }
            swal({
                icon: 'warning',
                title: 'Cancelar venda?',
                text: 'Todos os itens serão removidos.',
                buttons: ['Voltar', 'Cancelar venda'],
                dangerMode: true
            }).then(function (confirmou) {
                if (confirmou) {
                    limparVenda();
                }
            });
        }

        function finalizar() {
            if (enviando) {
                return;
            }
            if (!itens.length) {
                swal({ icon: 'warning', title: 'Venda vazia', text: 'Adicione ao menos um produto.' });
                return;
            }
            var t = totais();
            if (formaPgto === 'Dinheiro' && $('#pdvRecebido').val() !== '' && numero($('#pdvRecebido').val()) < t.total) {
                swal({ icon: 'warning', title: 'Valor recebido menor que o total' });
                return;
            }

            enviando = true;
            $('#pdvFinalizar').prop('disabled', true);

            $.ajax({
                url: urls.finalizar,
                type: 'POST',
                dataType: 'json',
                data: {
                    itens: JSON.stringify(itens.map(function (item) { return { id: item.id, quantidade: item.quantidade }; })),
                    clientes_id: cliente.id,
                    usuarios_id: $('#pdvVendedor').val(),
                    desconto: numero($('#pdvDesconto').val()),
                    tipo_desconto: tipoDesconto,
                    forma_pgto: formaPgto
                }
            }).done(function (resp) {
                var troco = formaPgto === 'Dinheiro' ? Math.max(0, numero($('#pdvRecebido').val()) - t.total) : 0;
                swal({
                    icon: 'success',
                    title: resp.messages,
                    text: 'Total: ' + dinheiro(t.total) + (troco > 0 ? '\nTroco: ' + dinheiro(troco) : ''),
                    buttons: { imprimir: { text: 'Imprimir cupom', value: 'imprimir' }, nova: { text: 'Nova venda', value: 'nova' } }
                }).then(function (opcao) {
                    if (opcao === 'imprimir') {
                        window.open(resp.imprimir, '_blank');
                    }
                    limparVenda();
                });
            }).fail(function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.messages) || 'Não foi possível finalizar a venda.';
                swal({ icon: 'error', title: 'Atenção', text: msg });
            }).always(function () {
                enviando = false;
                $('#pdvFinalizar').prop('disabled', false);
            });
        }

        // Busca de produto
        var temporizador;
        $('#pdvBusca').on('keydown', function (e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                moverSugestao('#pdvSugestoes', e.key === 'ArrowDown' ? 1 : -1);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(temporizador);
                var ativo = $('#pdvSugestoes div.ativo[data-i]');
                if ($('#pdvSugestoes').is(':visible') && ativo.length) {
                    adicionar(sugestoes[ativo.data('i')]);
                } else {
                    buscarProduto(true);
                }
            } else if (e.key === 'Escape') {
                esconderSugestoes('#pdvSugestoes');
            }
        }).on('input', function () {
            clearTimeout(temporizador);
            if ($.trim(this.value).length < 2) {
                esconderSugestoes('#pdvSugestoes');
                return;
            }
            temporizador = setTimeout(function () { buscarProduto(false); }, 300);
        });
        $('#pdvSugestoes').on('mousedown', 'div[data-i]', function (e) {
            e.preventDefault();
            adicionar(sugestoes[$(this).data('i')]);
        });
        $('#pdvAdicionar').on('click', function () { buscarProduto(true); });

        // Ações na tabela
        $('#pdvItens').on('click', 'button', function () {
            var i = $(this).data('i');
            var acao = $(this).data('acao');
            if (acao === 'mais') {
                if (controlaEstoque && itens[i].quantidade + 1 > itens[i].estoque) {
                    swal({ icon: 'warning', title: 'Estoque insuficiente', text: itens[i].descricao + ' tem ' + itens[i].estoque + ' em estoque.' });
                    return;
                }
                itens[i].quantidade++;
            } else if (acao === 'menos') {
                if (itens[i].quantidade > 1) {
                    itens[i].quantidade--;
                }
            } else if (acao === 'remover') {
                itens.splice(i, 1);
            }
            render();
        });

        // Cliente
        var clientesEncontrados = [];
        var temporizadorCliente;
        $('#pdvClienteBusca').on('input', function () {
            clearTimeout(temporizadorCliente);
            var termo = $.trim(this.value);
            if (termo.length < 2) {
                esconderSugestoes('#pdvClienteSugestoes');
                return;
            }
            temporizadorCliente = setTimeout(function () {
                $.getJSON(urls.cliente, { termo: termo }, function (resp) {
                    clientesEncontrados = resp.clientes;
                    var html = resp.clientes.map(function (c, i) {
                        return '<div data-i="' + i + '" class="' + (i === 0 ? 'ativo' : '') + '"><span>' + escapar(c.nomeCliente) + '</span><small>' + escapar(c.documento || c.celular || '') + '</small></div>';
                    }).join('') || '<div><small>Nenhum cliente encontrado</small></div>';
                    $('#pdvClienteSugestoes').html(html).show();
                });
            }, 300);
        }).on('keydown', function (e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                moverSugestao('#pdvClienteSugestoes', e.key === 'ArrowDown' ? 1 : -1);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                var ativo = $('#pdvClienteSugestoes div.ativo[data-i]');
                if (ativo.length) {
                    var c = clientesEncontrados[ativo.data('i')];
                    definirCliente(c.idClientes, c.nomeCliente);
                    $('#pdvBusca').focus();
                }
            } else if (e.key === 'Escape') {
                esconderSugestoes('#pdvClienteSugestoes');
            }
        }).on('blur', function () {
            setTimeout(function () { esconderSugestoes('#pdvClienteSugestoes'); }, 150);
        });
        $('#pdvClienteSugestoes').on('mousedown', 'div[data-i]', function (e) {
            e.preventDefault();
            var c = clientesEncontrados[$(this).data('i')];
            definirCliente(c.idClientes, c.nomeCliente);
        });
        $('#pdvClienteLimpar').on('click', function (e) {
            e.preventDefault();
            definirCliente(0, 'Consumidor Final');
            $('#pdvClienteBusca').focus();
        });

        // Desconto e pagamento
        $('.pdv-toggle button').on('click', function () {
            $('.pdv-toggle button').removeClass('ativo');
            $(this).addClass('ativo');
            tipoDesconto = $(this).data('tipo');
            render();
        });
        $('#pdvDesconto, #pdvRecebido').on('input', render);
        $('.pdv-formas button').on('click', function () {
            $('.pdv-formas button').removeClass('ativo');
            $(this).addClass('ativo');
            formaPgto = $(this).data('forma');
            $('#pdvTrocoBox').toggle(formaPgto === 'Dinheiro');
        });

        $('#pdvFinalizar').on('click', finalizar);
        $('#pdvCancelar').on('click', cancelar);
        $('#pdvBusca').on('blur', function () {
            setTimeout(function () { esconderSugestoes('#pdvSugestoes'); }, 150);
        });

        // Atalhos de teclado
        $(document).on('keydown', function (e) {
            if ($('.swal-overlay--show-modal').length) {
                return;
            }
            var atalhos = {
                F2: function () { $('#pdvBusca').focus().select(); },
                F4: function () { $('#pdvClienteBusca').focus(); },
                F6: function () { $('#pdvDesconto').focus().select(); },
                F8: cancelar,
                F10: finalizar
            };
            if (atalhos[e.key]) {
                e.preventDefault();
                atalhos[e.key]();
            }
        });

        render();
        $('#pdvBusca').focus();
    });
</script>
