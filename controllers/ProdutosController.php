<?php

namespace Controllers;

use Core;
use Models\Produtos;

class ProdutosController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->controller_name = str_replace("Controller", "", basename(__FILE__, '.php'));
        $this->page_name = "Produtos";
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('produtos/index', []);
    }

    public function create()
    {
        extract($_REQUEST);

        $Produtos = new Produtos();
        $result = $Produtos->create($descricao, $preco_de_venda, $preco_de_compra);
        $this->asJson(["success" => $result]);
    }

    public function selectAll()
    {
        $Produtos = new Produtos();
        $result = $Produtos->selectAll();
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function selectById()
    {
        extract($_REQUEST);

        $Produtos = new Produtos();
        $result = $Produtos->selectById($identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function edit()
    {
        extract($_REQUEST);

        $Produtos = new Produtos();
        $result = $Produtos->edit($descricao, $preco_de_venda, $preco_de_compra, $identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function delete()
    {
        extract($_REQUEST);

        $Produtos = new Produtos();
        $result = $Produtos->delete($identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }
}
