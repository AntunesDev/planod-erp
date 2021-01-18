<?php

namespace Controllers;

use Core;
use Models\Estoque;

class EstoqueController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->controller_name = str_replace("Controller", "", basename(__FILE__, '.php'));
        $this->page_name = "Estoque";
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('estoque/index', []);
    }

    public function create()
    {
        extract($_REQUEST);

        $Estoque = new Estoque();
        $result = $Estoque->create($produto, $quantidade);
        $this->asJson(["success" => $result]);
    }

    public function selectAll()
    {
        $Estoque = new Estoque();
        $result = $Estoque->selectAll();
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function selectByProduto()
    {
        extract($_REQUEST);

        $Estoque = new Estoque();
        $result = $Estoque->selectByProduto($produto);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function edit()
    {
        extract($_REQUEST);

        $Estoque = new Estoque();
        $result = $Estoque->edit($produto, $quantidade, $ultima_movimentacao);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function delete()
    {
        extract($_REQUEST);

        $Estoque = new Estoque();
        $result = $Estoque->delete($produto);
        $this->asJson(["success" => true, "results" => $result]);
    }
}
