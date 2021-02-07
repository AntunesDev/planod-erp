<?php

namespace Controllers;

use Core;
use Models\Produtos;
use Models\Venda;
use Models\Clientes;
use Models\Estoque;

class HomeController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->controller_name = str_replace("Controller", "", basename(__FILE__, '.php'));
        $this->page_name = "Início";
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('home/index', []);
    }

    public function getIndexTotalizers()
    {
        $produtos = (new Produtos)->selectAll();
        $vendas = (new Venda)->selectAll();
        $clientes = (new Clientes)->selectAll();

        $cobrancas = 0;
        foreach ($vendas as $venda) {
            if ($venda["valor_pago"] < $venda["valor_final"]) {
                $cobrancas++;
            }
        }

        $this->asJson(["produtos" => count($produtos), "vendas" => count($vendas), "cobrancas" => $cobrancas, "clientes" => count($clientes)]);
    }

    public function getLowStock()
    {
        $estoque = (new Estoque)->selectAll();

        usort($estoque, function ($a, $b) {
            return $a['quantidade'] > $b['quantidade'];
        });

        $this->asJson($estoque);
    }
}
