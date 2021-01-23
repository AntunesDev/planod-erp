<?php

namespace Controllers;

use Core;
use DateTime;
use Models\Estoque;
use Models\HistoricoEstoque;

class HistoricoEstoqueController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->controller_name = str_replace("Controller", "", basename(__FILE__, '.php'));
        $this->page_name = "Movimentações";
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('estoque/historico', []);
    }

    public function create()
    {
        extract($_REQUEST);

        $Estoque = new Estoque();
        $EstoqueE = $Estoque->selectByProduto($produto);

        $quantidade_antes = $EstoqueE->quantidade;

        if ($tipo_de_movimentacao == "Entrada") {
            $quantidade_depois = $quantidade_antes + $quantidade_movimentada;
        } else {
            $quantidade_depois = $quantidade_antes - $quantidade_movimentada;
        }

        $momento = (new DateTime)->format("YmdHis");

        $HistoricoEstoque = new HistoricoEstoque();
        $result = $HistoricoEstoque->create($produto, $tipo_de_movimentacao, $quantidade_movimentada, $quantidade_antes, $quantidade_depois, $momento);

        if ($result === "0") {
            $Estoque->edit($produto, $quantidade_depois, $momento);
        }

        $this->asJson(["success" => $result === "0"]);
    }

    public function selectAll()
    {
        $HistoricoEstoque = new HistoricoEstoque();
        $result = $HistoricoEstoque->selectAll();
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function selectByProduto()
    {
        extract($_REQUEST);

        $HistoricoEstoque = new HistoricoEstoque();
        $result = $HistoricoEstoque->selectByProduto($produto);
        $this->asJson(["success" => true, "results" => $result]);
    }

    public function delete()
    {
        extract($_REQUEST);

        $HistoricoEstoque = new HistoricoEstoque();
        $result = $HistoricoEstoque->delete($produto);
        $this->asJson(["success" => true, "results" => $result]);
    }
}
