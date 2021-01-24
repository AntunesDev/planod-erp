<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Relatórios</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home">Índice</a></li>
            <li class="breadcrumb-item active" aria-current="page">Relatórios</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Lucratividade</h6>
                </div>
                <div class="card-body">
                    <form onsubmit="return false;" id="relLucratividade">
                        <div class="form-group">
                            <label for="rangeRelLucratividade">Período</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="input-sm form-control" name="start" required="true" />
                                <div class="input-group-prepend">
                                    <span class="input-group-text">até</span>
                                </div>
                                <input type="text" class="input-sm form-control" name="end" required="true" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Emitir</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Movimentação de Estoque</h6>
                </div>
                <div class="card-body">
                    <form onsubmit="return false;" id="relMovEstoque">
                        <div class="form-group">
                            <label for="rangeRelMovEstoque">Período</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="input-sm form-control" name="start" required="true" />
                                <div class="input-group-prepend">
                                    <span class="input-group-text">até</span>
                                </div>
                                <input type="text" class="input-sm form-control" name="end" required="true" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Emitir</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Custo de Estoque</h6>
                </div>
                <div class="card-body">
                    <form onsubmit="return false;" id="relCustoEstoque">
                        <div class="form-group">
                            <label for="rangeRelCustoEstoque">Período</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="input-sm form-control" name="start" required="true" />
                                <div class="input-group-prepend">
                                    <span class="input-group-text">até</span>
                                </div>
                                <input type="text" class="input-sm form-control" name="end" required="true" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Emitir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Vendas por Cliente</h6>
                </div>
                <div class="card-body">
                    <form onsubmit="return false;" id="relVendasCliente">
                        <div class="form-group">
                            <label for="rangeRelVendasCliente">Período</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="input-sm form-control" name="start" required="true" />
                                <div class="input-group-prepend">
                                    <span class="input-group-text">até</span>
                                </div>
                                <input type="text" class="input-sm form-control" name="end" required="true" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Emitir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . 'assets/inc/template_scripts.php'; ?>
<script src="assets/js/pages/relatorios.js"></script>