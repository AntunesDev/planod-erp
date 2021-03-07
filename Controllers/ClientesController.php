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
        $result = $Clientes->create($nome, $telefone, $email);
        $this->asJson(["success" => $result]);
    }

    public function listAll()
    {
        $Clientes = new Clientes();
        $result = $Clientes->selectAll();
        $this->asJson($result);
    }

    public function selectAll()
    {
        extract($_REQUEST);

        $columns = array(
            0 => 'identificador',
            1 => 'nome',
            2 => 'telefone',
            3 => 'email'
        );

        $search = $search['value'];
        $dir = $order[0]['dir'];
        $order = $columns[$order[0]['column']];
        $start = (int) $start;
        $length = (int) $length;

        $Clientes = new CLientes();
        $selectAll = $Clientes->selectAll();
        $paginatedSearch = $Clientes->paginatedSearch($search, $order, $dir, $start, $length);

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

    public function searchByText()
    {
        extract($_REQUEST);

        $Clientes = new Clientes();
        $result = $Clientes->searchByText($searchText ?? "%");

        $this->asJson(["success" => true, "results" => $result]);
    }
}
