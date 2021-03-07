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
        $EstoqueE = $Estoque->selectByProduto($produto)[0] ?? [];

        $quantidade_antes = $EstoqueE["quantidade"];

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
        extract($_REQUEST);

        $columns = array(
            0 => 'descricao',
            1 => 'tipo_de_movimentacao',
            2 => 'quantidade_antes',
            3 => 'quantidade_movimentada',
            4 => 'quantidade_depois',
            5 => 'momento'
        );

        $search = $search['value'];
        $dir = $order[0]['dir'];
        $order = $columns[$order[0]['column']];
        $start = (int) $start;
        $length = (int) $length;

        $HistoricoEstoque = new HistoricoEstoque();
        $selectAll = $HistoricoEstoque->selectAll();
        $paginatedSearch = $HistoricoEstoque->paginatedSearch($search, $order, $dir);

        $totalData = count($selectAll);

        if (empty($search)) {
            $totalFiltered = $totalData;
        } else {
            $totalFiltered = count($paginatedSearch);
        }

        $paginatedSearch = array_slice($paginatedSearch, $start, $length);
        $data = array();
        foreach ($paginatedSearch as $outer_key => $array) {
            $nestedData = array();
            foreach ($array as $inner_key => $value) {
                if (!(int) $inner_key) {
                    $nestedData[$inner_key] = $value;
                }
            }
            $data[] = $nestedData;
        }

        $this->asJson([
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "records" => $data
        ]);
    }

    public function delete()
    {
        extract($_REQUEST);

        $HistoricoEstoque = new HistoricoEstoque();
        $result = $HistoricoEstoque->delete($produto);
        $this->asJson(["success" => true, "results" => $result]);
    }
}
