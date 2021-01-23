<?php

namespace Controllers;

use Core;
use DateTime;
use Models\Venda;
use Models\VendaItens;
use Models\Estoque;
use Models\HistoricoEstoque;

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
        $items = json_decode($items, true);

        $data = (new DateTime)->format("YmdHis");
        $valor_total = str_replace(",", ".", str_replace(".", "", $valor_total));
        $valor_desconto = str_replace(",", ".", str_replace(".", "", $valor_desconto));
        $valor_final = str_replace(",", ".", str_replace(".", "", $valor_final));

        $Venda = new Venda();
        $result = $Venda->create($cliente, $data, $valor_total, $valor_desconto, $valor_final);
        if ($result != 0) {
            $VendaItens = new VendaItens();

            foreach ($items as $item) {
                extract($item);
                $valor_unitario = str_replace(",", ".", str_replace(".", "", $valor_unitario));
                $VendaItens->create($result, $produto, $quantidade, $valor_unitario);

                $Estoque = new Estoque();
                $EstoqueE = $Estoque->selectByProduto($item["produto"]);

                $quantidade_antes = $EstoqueE->quantidade;
                $tipo_de_movimentacao = "Venda";
                $quantidade_depois = $quantidade_antes - $quantidade;

                $momento = (new DateTime)->format("YmdHis");

                $HistoricoEstoque = new HistoricoEstoque();
                $resultHistorico = $HistoricoEstoque->create($produto, $tipo_de_movimentacao, $quantidade, $quantidade_antes, $quantidade_depois, $momento);

                if ($resultHistorico === "0") {
                    $Estoque->edit($produto, $quantidade_depois, $momento);
                }
            }
        }

        $this->asJson(["success" => $result != 0]);
    }

    public function selectAll()
    {
        extract($_REQUEST);

        $columns = array(
            0 => 'identificador',
            1 => 'cliente',
            2 => 'data',
            3 => 'valor_total',
            4 => 'valor_desconto',
            5 => 'valor_final',
            6 => 'valor_pago'
        );

        $search = $search['value'];
        $dir = $order[0]['dir'];
        $order = $columns[$order[0]['column']];
        $start = (int) $start;
        $length = (int) $length;

        $Venda = new Venda();
        $selectAll = $Venda->selectAll();
        $paginatedSearch = $Venda->paginatedSearch($search, $order, $dir, $start, $length);

        foreach ($paginatedSearch as $index => $produto) {
            if ($produto["valor_pago"] < $produto["valor_final"])
                continue;
            else
                $indexesToKill[] = $index;
        }

        if (isset($indexesToKill)) {
            foreach ($indexesToKill as $indexToKill) {
                unset($paginatedSearch[$indexToKill]);
            }
            $paginatedSearch = array_values($paginatedSearch);
        }

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

    public function updateValorPago()
    {
        extract($_REQUEST);

        $valor_pago = str_replace(",", ".", str_replace(".", "", $valor_pago));

        $Venda = new Venda();
        $VendaE = $Venda->selectById($identificador);

        $result = $Venda->updateValorPago($identificador, ($VendaE->valor_pago + $valor_pago));

        $this->asJson(["success" => $result]);
    }

    public function delete()
    {
        extract($_REQUEST);

        $Venda = new Venda();
        $result = $Venda->delete($identificador);
        $this->asJson(["success" => true, "results" => $result]);
    }
}
