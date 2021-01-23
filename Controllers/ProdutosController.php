<?php

namespace Controllers;

use Core;
use Models\Estoque;
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

        $preco_de_compra = str_replace(",", ".", str_replace(".", "", $preco_de_compra));
        $preco_de_venda = str_replace(",", ".", str_replace(".", "", $preco_de_venda));

        $Produtos = new Produtos();
        $result = $Produtos->create($descricao, $preco_de_venda, $preco_de_compra);

        if ($result != false) {
            (new Estoque)->create($result, 0);
        }

        $this->asJson(["success" => $result]);
    }

    public function listAll()
    {
        $Produtos = new Produtos();
        $result = $Produtos->selectAll();
        $this->asJson($result);
    }

    public function selectAll()
    {
        extract($_REQUEST);

        $columns = array(
            0 => 'identificador',
            1 => 'descricao',
            2 => 'preco_de_venda',
            3 => 'preco_de_compra',
            4 => '((preco_de_venda - preco_de_compra) * 100) / preco_de_compra'
        );

        $search = $search['value'];
        $dir = $order[0]['dir'];
        $order = $columns[$order[0]['column']];
        $start = (int) $start;
        $length = (int) $length;

        $Produtos = new Produtos();
        $selectAll = $Produtos->selectAll();
        $paginatedSearch = $Produtos->paginatedSearch($search, $order, $dir, $start, $length);

        if ($selectAll == false)
            $totalData = 0;
        else
            $totalData = count($selectAll);

        if (empty($search)) {
            $totalFiltered = $totalData;
        } else {
            $totalFiltered = count($paginatedSearch);
        }

        $data = array();
        if ($paginatedSearch != false && is_array($paginatedSearch) == false) {
            $paginatedSearch = [(array) $paginatedSearch];
        }

        if ($paginatedSearch != false) {
            foreach ($paginatedSearch as $outer_key => $array) {
                $nestedData = array();
                foreach ($array as $inner_key => $value) {
                    if (!(int) $inner_key) {
                        $nestedData[$inner_key] = $value;
                    }
                }
                $data[] = $nestedData;
            }
        }

        $this->asJson([
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "records" => $data
        ]);
    }

    public function selectAllAtivosComEstoque()
    {
        extract($_REQUEST);

        $columns = array(
            0 => 'descricao',
            1 => 'quantidade',
            2 => 'preco_de_venda'
        );

        $search = $search['value'];
        $dir = $order[0]['dir'];
        $order = $columns[$order[0]['column']];
        $start = (int) $start;
        $length = (int) $length;

        $Produtos = new Produtos();
        $selectAll = $Produtos->selectAllAtivosComEstoque();
        $paginatedSearch = $Produtos->paginatedSearchAtivosComEstoque($search, $order, $dir, $start, $length);
        if ($paginatedSearch != false && is_array($paginatedSearch) == false) {
            $paginatedSearch = [(array) $paginatedSearch];
        }

        if ($paginatedSearch != false) {
            if (count($paginatedSearch) > 0) {
                foreach ($paginatedSearch as $index => $produto) {
                    if ($produto["estoque"] <= 0)
                        $indexesToKill[] = $index;
                    else
                        continue;
                }
            }
        }

        if (isset($postData)) {
            foreach ($paginatedSearch as $index => $produto) {
                if (in_array($produto["identificador"], $postData) || $produto["estoque"] <= 0)
                    $indexesToKill[] = $index;
                else
                    continue;
            }
        }

        if (isset($indexesToKill)) {
            foreach ($indexesToKill as $indexToKill) {
                unset($paginatedSearch[$indexToKill]);
            }
            $paginatedSearch = array_values($paginatedSearch);
        }

        if ($selectAll == false)
            $totalData = 0;
        else
            $totalData = count($selectAll);

        if (empty($search)) {
            $totalFiltered = $totalData;
        } else {
            $totalFiltered = count($paginatedSearch);
        }

        $data = array();
        if ($paginatedSearch != false) {
            foreach ($paginatedSearch as $outer_key => $array) {
                $nestedData = array();
                foreach ($array as $inner_key => $value) {
                    if (!(int) $inner_key) {
                        $nestedData[$inner_key] = $value;
                    }
                }
                $data[] = $nestedData;
            }
        }

        $this->asJson([
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "records" => $data
        ]);
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

        $preco_de_compra = str_replace(",", ".", str_replace(".", "", $preco_de_compra));
        $preco_de_venda = str_replace(",", ".", str_replace(".", "", $preco_de_venda));

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
