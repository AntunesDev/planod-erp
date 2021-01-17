<?php

namespace Controllers;

use Core;
use Models\Clientes;

class ClientesController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->controller_name = str_replace("Controller", "", basename(__FILE__, '.php'));
        $this->page_name = "Clientes";
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('clientes/index', []);
    }

    public function create()
    {
        extract($_REQUEST);

        $Clientes = new Clientes();
        $result = $Clientes->create($nome, $telefone, $telefone);
        $this->asJson(["success" => $result]);
    }

    public function selectAll()
    {
        $Clientes = new CLientes();
        $result = $Clientes->selectAll();
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function selectById()
    {
        extract($_REQUEST);

        $Clientes = new Clientes();
        $result = $Clientes->selectById($identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function edit()
    {
        extract($_REQUEST);

        $Clientes = new Clientes();
        $result = $Clientes->edit($identificador, $nome, $telefone, $email);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function delete()
    {
        extract($_REQUEST);

        $Clientes = new Clientes();
        $result = $Clientes->delete($identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }
}
