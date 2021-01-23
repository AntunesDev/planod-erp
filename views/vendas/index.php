<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Vendas</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home">Índice</a></li>
            <li class="breadcrumb-item active" aria-current="page">Vendas</li>
        </ol>
    </div>
    <div class="row" id="rowPagamento">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-end justify-content-between">
                    <button type="button" class="btn btn-primary mb-1" id="newVendaBtn">Nova Venda</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTableVendas">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Identificador</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Data</th>
                                    <th class="text-center">R$ Total</th>
                                    <th class="text-center">R$ Desconto</th>
                                    <th class="text-center">R$ Final</th>
                                    <th class="text-center">R$ Recebido</th>
                                    <th class="text-center"><i class="fas fa-bolt"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="rowVenda">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-end justify-content-between">
                    <button type="button" class="btn btn-danger mb-1" id="returnBtn">Voltar</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTableProdutos">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Produto</th>
                                    <th class="text-center">Estoque</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center"><i class="fas fa-bolt"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <table class="table align-items-center table-flush table-hover" id="tableCarrinho">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">Produto</th>
                                <th class="text-center">Valor Un.</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-center">Valor Total.</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <hr>
                    <form id="form" onsubmit="return false;">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="cliente">Cliente</label>
                            <div class="col-sm-9">
                                <select class="form-control mb-3" name="cliente" id="cliente" required="true">
                                    <option value="">Selecione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="valor_total" class="col-sm-3 col-form-label">Valor Total</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" class="form-control" id="valor_total" name="valor_total" required="true" placeholder="Total" readonly="true">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="valor_desconto" class="col-sm-3 col-form-label">Desconto</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" class="form-control" id="valor_desconto" name="valor_desconto" placeholder="Desconto" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="valor_final" class="col-sm-3 col-form-label">Valor Final</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" class="form-control" id="valor_final" name="valor_final" required="true" placeholder="Final" readonly="true">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . 'assets/inc/template_scripts.php'; ?>
<script src="assets/js/static/datatables.js"></script>
<script src="assets/js/pages/vendas.js"></script>