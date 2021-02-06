<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Produtos</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home">Índice</a></li>
            <li class="breadcrumb-item active" aria-current="page">Produtos</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4" id="tableCard">
                <div class="card-header py-3 d-flex flex-row align-items-end justify-content-between">
                    <button type="button" class="btn btn-primary mb-1" id="newProdutoBtn">Novo Produto</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Identificador</th>
                                    <th class="text-center">Nome</th>
                                    <th class="text-center">Preço de Venda</th>
                                    <th class="text-center">Custo</th>
                                    <th class="text-center">Lucro</th>
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
                            <label for="descricao" class="col-sm-3 col-form-label">Descrição</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="descricao" name="descricao" required="true" placeholder="Descrição">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="preco_de_venda" class="col-sm-3 col-form-label">Preço de Venda</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" class="form-control" id="preco_de_venda" name="preco_de_venda" required="true" placeholder="Preço de Venda">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="preco_de_compra" class="col-sm-3 col-form-label">Custo</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="preco_de_compra" class="form-control" id="preco_de_compra" name="preco_de_compra" required="true" placeholder="Preço de Compra">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="imagem" class="col-sm-3 col-form-label">Imagem</label>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imagem" name="imagem">
                                    <label class="custom-file-label">Selecionar arquivo</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                    <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTableImagens">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center"><i class="fas fa-images"></i></th>
                                    <th class="text-center"><i class="fas fa-bolt"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . 'assets/inc/template_scripts.php'; ?>
<script src="assets/js/static/datatables.js"></script>
<script src="assets/js/pages/produtos.js"></script>
<style>
    .square-thumb {
        width: 150px;
        height: 150px;
        background-position: center center;
        background-repeat: no-repeat;
    }
</style>