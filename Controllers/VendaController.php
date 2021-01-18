<?php

namespace Controllers;

use Core;
use Models\Venda;

class VendaController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->controller_name = str_replace("Controller", "", basename(__FILE__, '.php'));
        $this->page_name = "Vendas";
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('vendas/index', []);
    }

    public function create()
    {
        extract($_REQUEST);

        $Venda = new Venda();
        $result = $Venda->create($cliente, $data, $valor_total, $valor_desconto, $valor_final);
        $this->asJson(["success" => $result]);
    }

    public function selectAll()
    {
        $Venda = new Venda();
        $result = $Venda->selectAll();
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function selectById()
    {
        extract($_REQUEST);

        $Venda = new Venda();
        $result = $Venda->selectById($identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function edit()
    {
        extract($_REQUEST);

        $Venda = new Venda();
        $result = $Venda->edit($cliente, $data, $valor_total, $valor_desconto, $valor_final, $valor_pago, $identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function delete()
    {
        extract($_REQUEST);

        $Venda = new Venda();
        $result = $Venda->delete($identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }
}
