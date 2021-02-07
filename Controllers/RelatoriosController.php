<?php

namespace Controllers;

use Core;
use DateTime;
use Models\Venda;
use Core\PDFPrinter;

class RelatoriosController extends Core\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->controller_name = str_replace("Controller", "", basename(__FILE__, '.php'));
        $this->page_name = "Relatórios";
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('relatorios/index', []);
    }

    public function relatorioLucratividade()
    {
        extract($_REQUEST);

        $periodoInicio = DateTime::createFromFormat("d/m/Y", $start)->format("Ymd");
        $periodoFim = DateTime::createFromFormat("d/m/Y", $end)->format("Ymd");

        $Venda = new Venda();

        $relatorioLucratividade = $Venda->relatorioLucratividade($periodoInicio, $periodoFim);
        $totalDesconto = $Venda->getTotalDesconto($periodoInicio, $periodoFim)[0]["valor_desconto"] ?? [];

        $venda_total_final = 0;
        $custo_total_final = 0;
        $quantidade_total_final = 0;

        $relatorio = "<!DOCTYPE html>
        <head>
          <meta charset='utf-8'>
          <meta http-equiv='X-UA-Compatible' content='IE=edge'>
          <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
          <meta name='description' content='PlanoD'>
          <meta name='author' content='Lucão'>
          <style>
            " . file_get_contents(BASE_PATH . 'assets/vendor/fontawesome-free/css/all.min.css') . "
            " . file_get_contents(BASE_PATH . 'assets/vendor/bootstrap/css/bootstrap.min.css') . "
            " . file_get_contents(BASE_PATH . 'assets/css/ruang-admin.min.css') . "
          </style>
        </head>
        <body id='page-top'>
          <div id='wrapper'>
            <div id='content-wrapper' class='d-flex flex-column'>
              <div id='content'>
                <div class='container-fluid' id='container-wrapper'>
                  <div class='row'>
                    <div class='col-lg-6'>
                      <div class='card mb-4'><div class='table-responsive'>
                        <table class='table align-items-center table-flush'>
                            <thead class='thead-light'>
                                <tr>
                                    <th colspan='2' class='text-center'>DE</th>
                                    <th colspan='2' class='text-center'>$start</th>
                                    <th colspan='2' class='text-center'>ATÉ</th>
                                    <th colspan='4' class='text-center'>$end</th>
                                </tr>
                                <tr>
                                    <th colspan='2'></th>
                                    <th colspan='2' class='text-center'>Unitário</th>
                                    <th colspan='4' class='text-center'>Total</th>
                                </tr>
                                <tr>
                                    <th class='text-center'>Produto</th>
                                    <th class='text-center'>Vendas</th>
                                    <th class='text-center'>R$ Venda</th>
                                    <th class='text-center'>R$ Custo</th>
                                    <th class='text-center'>R$ Venda</th>
                                    <th class='text-center'>R$ Custo</th>
                                    <th class='text-center'>% de Lucro</th>
                                    <th class='text-center'>Lucro Bruto</th>
                                </tr>
                            </thead>
                            <tbody>";
        foreach ($relatorioLucratividade as $line) {
            extract($line);

            $venda_total_item = $valor_unitario * $quantidade_total;
            $custo_total_item = $preco_de_compra * $quantidade_total;

            $lucro_item = $venda_total_item - $custo_total_item;
            $margem_lucro_item = ($lucro_item * 100) / $custo_total_item;

            $venda_total_final += $venda_total_item;
            $custo_total_final += $custo_total_item;
            $quantidade_total_final += $quantidade_total;

            $relatorio .= "<tr>
            <td>$produto - $descricao</td>
            <td>$quantidade_total</td>
            <td>R$ " . number_format($valor_unitario, 2, ",", "") . "</td>
            <td>R$ " . number_format($preco_de_compra, 2, ",", "") . "</td>
            <td>R$ " . number_format($venda_total_item, 2, ",", "") . "</td>
            <td>R$ " . number_format($custo_total_item, 2, ",", "") . "</td>
            <td>" . number_format($margem_lucro_item, 2, ".", "") . " %</td>
            <td>R$ " . number_format($lucro_item, 2, ",", "") . "</td>
            </tr>";
        }

        $venda_total_final_com_desconto = $venda_total_final - $totalDesconto;

        $lucro_total = $venda_total_final_com_desconto - $custo_total_final;
        $dizimo = $lucro_total * 0.1;
        $margem_lucro_total = ($lucro_total * 100) / $custo_total_final;

        $relatorio .= "</tbody>
                        </table>
                    </div>
                    <hr>
                    <div class='table-responsive'>
                        <table class='table align-items-center table-flush'>
                            <thead class='thead-light'>
                                <tr>
                                    <th class='text-center'>Total de Vendas</th>
                                    <td class='text-center'>R$ " . number_format($venda_total_final, 2, ",", "") . "</td>
                                </tr>
                                <tr>
                                    <th class='text-center'>Total de Custo</th>
                                    <td class='text-center'>R$ " . number_format($custo_total_final, 2, ",", "") . "</td>
                                </tr>
                                <tr>
                                    <th class='text-center'>Total de Descontos</th>
                                    <td class='text-center'>R$ " . number_format($totalDesconto, 2, ",", "") . "</td>
                                </tr>
                                <tr>
                                    <th class='text-center'>Total Final</th>
                                    <td class='text-center'>R$ " . number_format($venda_total_final_com_desconto, 2, ",", "") . "</td>
                                </tr>
                                <tr>
                                    <th class='text-center'>% de Lucro Final</th>
                                    <td class='text-center'>" . number_format($margem_lucro_total, 2, ".", "") . " %</td>
                                </tr>
                                <tr>
                                    <th class='text-center'>Lucro Bruto Total</th>
                                    <td class='text-center'>R$ " . number_format($lucro_total, 2, ",", "") . "</td>
                                </tr>
                                <tr>
                                    <th class='text-center'>Dízimo</th>
                                    <td class='text-center'>R$ " . number_format($dizimo, 2, ",", "") . "</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </body>
        </html>";

        echo $relatorio;
        //file_put_contents(BASE_PATH . "views/relatorio.html", $relatorio);
    }
}
