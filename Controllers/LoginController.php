<?php

namespace Controllers;

use Core, Models;

class LoginController extends Core\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->isLoggedOut();
        $this->loadTemplateLogin('Login/login', []);
    }

    public function login()
    {
        extract($_REQUEST);

        $Usuarios = new Models\Usuarios;
        $usuario = $Usuarios->selectByUserName($login)[0] ?? [];

        if ($usuario == false) {
            $this->asJson(["success" => false, "message" => "Nome de usuário inválido!"]);
        } else if (verifyPassword($password, $usuario["senha"]) == false) {
            $this->asJson(["success" => false, "message" => "Combinação usuário/senha inválida!"]);
        } else if ($usuario["ativo"] == 0) {
            $this->asJson(["success" => false, "message" => "Seu usuário está desativado!"]);
        } else {
            $_SESSION[SESSION_NAME]["usuario"] = $usuario;
            $this->asJson(["success" => true]);
        }
    }

    public function updatePassword()
    {
        extract($_REQUEST);

        $Usuarios = new Models\Usuarios;

        $result = $Usuarios->updatePassword($_SESSION[SESSION_NAME]["usuario"]["id"], hashPassword($password));

        $this->asJson(["success" => $result]);
    }

    public function logout()
    {
        unset($_SESSION[SESSION_NAME]);
        die(header('Location: ' . BASE_URL));
    }

    public function extendSession()
    {
        $this->renewSession();
    }
}
