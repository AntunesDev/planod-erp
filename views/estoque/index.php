<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Estoque</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home">Índice</a></li>
            <li class="breadcrumb-item active" aria-current="page">Estoque</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4" id="tableCard">
                <div class="card-header py-3 d-flex flex-row align-items-end justify-content-between">
                    <button type="button" class="btn btn-primary mb-1" id="newEstoqueBtn">Nova Movimentação</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Produto</th>
                                    <th class="text-center">Quantidade</th>
                                    <th class="text-center">Última Movimentação</th>
                                    <th class="text-center"><i class="fas fa-bolt"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card mb-4" id="formCard">
                <div class="card-header py-3 d-flex flex-row align-items-end justify-content-between">
                    <button type="button" class="btn btn-danger mb-1" id="returnBtn">Voltar</button>
                </div>
                <div class="card-body">
                    <form id="form" onsubmit="return false;">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="produto">Produto</label>
                            <div class="col-sm-9">
                                <select class="form-control mb-3" name="produto" id="produto" required="true">
                                    <option value="">Selecione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="tipo_de_movimentacao">Tipo de Movimentação</label>
                            <div class="col-sm-9">
                                <select class="form-control mb-3" name="tipo_de_movimentacao" id="tipo_de_movimentacao" required="true">
                                    <option value="">Selecione...</option>
                                    <option>Entrada</option>
                                    <option>Saída manual</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="quantidade_movimentada">Quantidade Movimentada</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="quantidade_movimentada" name="quantidade_movimentada" required="true" placeholder="Quantidade" min=1>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . 'assets/inc/template_scripts.php'; ?>
<script src="assets/js/static/datatables.js"></script>
<script src="assets/js/pages/estoque.js"></script>