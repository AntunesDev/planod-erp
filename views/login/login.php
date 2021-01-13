<?php include BASE_PATH . 'assets/inc/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PlanoD">
    <meta name="author" content="Lucão">
    <link href="assets/img/logo/planod-bolota.png" rel="icon">
    <title><?php echo $template['title'] ?></title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/ruang-admin.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-login">
    <!-- Login Content -->
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <img src="assets/img/logo/planod-bolota.png" style="width:10%">
                                        <h1 class="h4 text-gray-900 mb-4">Login</h1>
                                    </div>
                                    <form class="user">
                                        <div class="form-group">
                                            <input type="login" class="form-control" id="inputLogin" aria-describedby="loginHelp" placeholder="Login">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="inputPassword" placeholder="Senha">
                                        </div>
                                        <div class="form-group">
                                            <a href="javascript:void(0);" id="btnLogin" class="btn btn-primary btn-block">Login</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <script src="https://apps.elfsight.com/p/platform.js"></script>
            <div class="elfsight-app-73dc6f5d-f105-4e6f-ba5c-fd37e2c44c9b"></div>
        </div>
    </div>
    <!-- Login Content -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/vendor/arrive/arrive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="assets/js/pages/login.js"></script>
    <script>

    </script>
</body>

</html>