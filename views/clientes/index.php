<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Clientes</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home">Índice</a></li>
            <li class="breadcrumb-item active" aria-current="page">Clientes</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4" id="tableCard">
                <div class="card-header py-3 d-flex flex-row align-items-end justify-content-between">
                    <button type="button" class="btn btn-primary mb-1" id="newClientBtn">Novo Cliente</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Identificador</th>
                                    <th class="text-center">Nome</th>
                                    <th class="text-center">Telefone</th>
                                    <th class="text-center">E-mail</th>
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
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required="true" placeholder="Nome">
                        </div>
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" required="true" placeholder="Telefone">
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail de contato</label>
                            <input type="email" class="form-control" id="email" name="email" required="true" placeholder="Email">
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
<script src="assets/js/pages/clientes.js"></script>