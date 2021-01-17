<?php

namespace Controllers;

use Core;
use Models\VendaItens;

class VendaItensController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
    }

    public function create()
    {
        extract($_REQUEST);

        $VendaItens = new VendaItens();
        $result = $VendaItens->create($venda, $produto, $quantidade, $valor_unitario);
        $this->asJson(["success" => $result]);
    }

    public function selectAll()
    {
        $VendaItens = new VendaItens();
        $result = $VendaItens->selectAll();
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function selectAllByProduto()
    {
        extract($_REQUEST);

        $VendaItens = new VendaItens();
        $result = $VendaItens->selectAllByProduto($produto);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function selectAllByVenda()
    {
        extract($_REQUEST);

        $VendaItens = new VendaItens();
        $result = $VendaItens->selectAllByVenda($venda);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function edit()
    {
        extract($_REQUEST);

        $VendaItens = new VendaItens();
        $result = $VendaItens->edit($venda, $produto, $quantidade, $valor_unitario);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function delete()
    {
        extract($_REQUEST);

        $VendaItens = new VendaItens();
        $result = $VendaItens->delete($venda, $produto);
        $this->asJson(["success" => true, "results" => $result]);
    }
}
