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
        extract($_REQUEST);

        $columns = array(
            0 => 'produto',
            1 => 'quantidade',
            2 => 'ultima_movimentacao'
        );

        $search = $search['value'];
        $dir = $order[0]['dir'];
        $order = $columns[$order[0]['column']];
        $start = (int) $start;
        $length = (int) $length;

        $Estoque = new Estoque();
        $selectAll = $Estoque->selectAll();
        $paginatedSearch = $Estoque->paginatedSearch($search, $order, $dir);

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
