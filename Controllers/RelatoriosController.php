<?php

namespace Controllers;

use Core;
use DateTime;
use Models\Venda;
use Models\HistoricoEstoque;
use Models\Estoque;
use Models\Produtos;

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

        $relatorio = PRINT_START . "<table class='table align-items-center table-flush'>
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
                        </table>" . PRINT_END;

        echo $relatorio;
    }

    public function relatorioMovEstoque()
    {
        extract($_REQUEST);

        $periodoInicio = DateTime::createFromFormat("d/m/Y", $start)->format("Ymd");
        $periodoFim = DateTime::createFromFormat("d/m/Y", $end)->format("Ymd");

        $HistoricoEstoque = new HistoricoEstoque();

        $relatorioMovEstoque = $HistoricoEstoque->relatorioMovEstoque($periodoInicio, $periodoFim);

        $relatorio = PRINT_START . "<table class='table align-items-center table-flush'>
                                <thead class='thead-light'>
                                    <tr>
                                        <th class='text-center'>DE</th>
                                        <th class='text-center'>$start</th>
                                        <th class='text-center'>ATÉ</th>
                                        <th class='text-center'>$end</th>
                                    </tr>
                                    <tr>
                                        <th class='text-center'>Produto</th>
                                        <th class='text-center'>Tipo de Movimentação</th>
                                        <th class='text-center'>Quantidade</th>
                                        <th class='text-center'>Dia</th>
                                    </tr>
                                </thead>
                                <tbody>";
        foreach ($relatorioMovEstoque as $line) {
            extract($line);

            $totais[$tipo_de_movimentacao] = ($totais[$tipo_de_movimentacao] ?? 0) + $quantidade_movimentada;

            $relatorio .= "<tr>
                <td>$produto - $descricao</td>
                <td>$tipo_de_movimentacao</td>
                <td>$quantidade_movimentada</td>
                <td>" . DateTime::createFromFormat("Y-m-d", $dia)->format("d/m/Y") . "</td>
            </tr>";
        }

        $relatorio .= "</tbody>
                            </table>
                        </div>
                    <hr>
                    <div class='table-responsive'>
                        <table class='table align-items-center table-flush'>
                            <thead class='thead-light'>";

        foreach ($totais as $tipo => $quantidade) {
            $relatorio .= "<tr>
                <th class='text-center'>'$tipo' totais</th>
                <td class='text-center'>$quantidade</td>
            </tr>";
        }

        $relatorio .= "</tbody>
                        </table>" . PRINT_END;

        echo $relatorio;
    }

    public function relatorioCustoEstoque()
    {
        extract($_REQUEST);

        $Estoque = new Estoque();

        $relatorioCustoEstoque = $Estoque->relatorioCustoEstoque($orderRelCustoEstoque);

        $relatorio = PRINT_START . "<table class='table align-items-center table-flush'>
                                <thead class='thead-light'>
                                    <tr>
                                        <th class='text-center'>Produto</th>
                                        <th class='text-center'>Estoque</th>
                                        <th class='text-center'>Custo (un.)</th>
                                        <th class='text-center'>Custo Total</th>
                                    </tr>
                                </thead>
                                <tbody>";

        $total = 0;
        foreach ($relatorioCustoEstoque as $line) {
            extract($line);

            $total += $preco_de_compra_total;

            $relatorio .= "<tr>
                <td>$produto - $descricao</td>
                <td>$quantidade</td>
                <td>R$ " . number_format($preco_de_compra, 2, ",", ".") . "</td>
                <td>R$ " . number_format($preco_de_compra_total, 2, ",", ".") . "</td>
            </tr>";
        }

        $relatorio .= "</tbody>
                            </table>
                        </div>
                    <hr>
                    <div class='table-responsive'>
                        <table class='table align-items-center table-flush'>
                            <thead class='thead-light'>
                                <tr>
                                    <th class='text-center'>Custo Total</th>
                                    <td class='text-center'>R$ " . number_format($total, 2, ",", ".") . "</td>
                                </tr>
                            </tbody>" . PRINT_END;

        echo $relatorio;
    }

    public function relatorioVendasPorCliente()
    {
        extract($_REQUEST);

        $periodoInicio = DateTime::createFromFormat("d/m/Y", $start)->format("Ymd");
        $periodoFim = DateTime::createFromFormat("d/m/Y", $end)->format("Ymd");

        $Venda = new Venda();

        $relatorioLucratividade = $Venda->relatorioVendasPorCliente($periodoInicio, $periodoFim);

        $relatorio = PRINT_START . "<table class='table align-items-center table-flush'>
                            <thead class='thead-light'>
                                <tr>
                                    <th colspan='3' class='text-center'>DE $start</th>
                                    <th colspan='3' class='text-center'>ATÉ $end</th>
                                </tr>
                                <tr>
                                    <th class='text-center'>Cliente</th>
                                    <th class='text-center'>Data</th>
                                    <th class='text-center'>R$ Venda</th>
                                    <th class='text-center'>R$ Custo</th>
                                    <th class='text-center'>% de Lucro</th>
                                    <th class='text-center'>Lucro Bruto</th>
                                </tr>
                            </thead>
                            <tbody>";

        $venda_total_final = 0;
        $custo_total_final = 0;

        foreach ($relatorioLucratividade as $line) {
            extract($line);

            $lucro_item = $valor_pago - $custo_total;
            $margem_lucro_item = ($lucro_item * 100) / $custo_total;

            $venda_total_final += $valor_pago;
            $custo_total_final += $custo_total;

            $relatorio .= "<tr>
            <td>$nome</td>
            <td>" . DateTime::createFromFormat("Y-m-d", $data)->format("d/m/Y") . "</td>
            <td>R$ " . number_format($valor_pago, 2, ",", "") . "</td>
            <td>R$ " . number_format($custo_total, 2, ",", "") . "</td>
            <td>" . number_format($margem_lucro_item, 2, ".", "") . " %</td>
            <td>R$ " . number_format($lucro_item, 2, ",", "") . "</td>
            </tr>";
        }

        $lucro_total = $venda_total_final - $custo_total_final;
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
                                    <th class='text-center'>% de Lucro Final</th>
                                    <td class='text-center'>" . number_format($margem_lucro_total, 2, ".", "") . " %</td>
                                </tr>
                                <tr>
                                    <th class='text-center'>Lucro Bruto Total</th>
                                    <td class='text-center'>R$ " . number_format($lucro_total, 2, ",", "") . "</td>
                                </tr>
                            </tbody>
                        </table>" . PRINT_END;

        echo $relatorio;
    }

    public function catalogoSemImagens()
    {
        $Produtos = new Produtos();

        $catalogoSemImagens = $Produtos->getCatalogoSemImagens();

        $relatorio = PRINT_START . "<table class='table align-items-center table-flush'>
                            <thead class='thead-light'>
                                <tr>
                                    <th colspan='2' class='text-center'>Tabela válida para o dia " . (new DateTime())->format("d/m/Y") . " - Os preços podem mudar sem aviso prévio</th>
                                </tr>
                                <tr>
                                    <th colspan='2' class='text-center'>Produtos com * podem não possuir estoque no momento</th>
                                </tr>
                            </thead>
                            <tbody>";

        foreach ($catalogoSemImagens as $line) {
            extract($line);

            $relatorio .= "<tr>
                <td>$descricao" . ($quantidade <= 0 ? "*" : "") . "</td>
                <td>R$ " . number_format($preco_de_venda, 2, ",", "") . "</td>
            </tr>";
        }

        $relatorio .= "</tbody>
                        </table>" . PRINT_END;

        echo $relatorio;
    }
}
