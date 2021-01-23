<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Movimentações</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home">Índice</a></li>
            <li class="breadcrumb-item active" aria-current="page">Movimentações</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4" id="tableCard">
                <div class="card-body">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Produto</th>
                                    <th class="text-center">Movimentação</th>
                                    <th class="text-center">Qtde. Antes</th>
                                    <th class="text-center">Qtde. Movimentada</th>
                                    <th class="text-center">Qtde. Depois</th>
                                    <th class="text-center">Momento</th>
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
<script src="assets/js/pages/estoqueHistorico.js"></script>