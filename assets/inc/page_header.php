<nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="assets/img/logo/planod-bolota.png" style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small"><?php echo ucfirst($_SESSION[SESSION_NAME]["usuario"]["login"]); ?> <i class="fas fa-angle-down"></i></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="javascript:void(0);" id="btnUpdateSenha">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Alterar senha
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Sair
                </a>
            </div>
        </li>
    </ul>
</nav>