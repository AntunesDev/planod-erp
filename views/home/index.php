<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Índice</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home">Índice</a></li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">PRODUTOS</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="divProdutos">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coffee fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">VENDAS</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="divVendas">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">VENDAS NÃO PAGAS</div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="divCobrancas">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Clientes</div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="divClientes">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div id="divEstoqueWarning">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include BASE_PATH . 'assets/inc/template_scripts.php'; ?>
<script src="assets/js/pages/home.js"></script>