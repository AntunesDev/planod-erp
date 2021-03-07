<?php

namespace Models;

use Core;
use PDO;

class Venda extends Core\Model
{
    private $table_name     = "venda";
    private $table_clientes = "clientes";
    private $table_itens    = "venda_itens";
    private $table_produtos = "produtos";

    public function create($cliente, $data, $valor_total, $valor_desconto, $valor_final)
    {
        return $this->insertInto($this->table_name)
            ->values("cliente", $cliente)
            ->values("data", $data)
            ->values("valor_total", $valor_total)
            ->values("valor_desconto", $valor_desconto)
            ->values("valor_final", $valor_final)
            ->execute();
    }

    public function selectAll($exibeVendasAntigas)
    {
        $query = "SELECT *
        FROM $this->table_name
        ";

        if ($exibeVendasAntigas === false) {
            $query .= "WHERE valor_final > IFNULL(valor_pago, 0);";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function paginatedSearch($exibeVendasAntigas, $searchText, $orderColumn, $orderDir)
    {
        $query = "SELECT $this->table_name.identificador, nome AS cliente, data, valor_total, valor_desconto, valor_final, IFNULL(valor_pago, 0) AS valor_pago
        FROM $this->table_name
        LEFT JOIN $this->table_clientes ON $this->table_clientes.identificador = cliente
        WHERE nome LIKE :searchText
        " . ($exibeVendasAntigas ? "" : "AND valor_final > IFNULL(valor_pago, 0)") . "
        ORDER BY $orderColumn $orderDir;";

        if (empty($searchText))
            $searchText = '%';
        else
            $searchText = '%' . $searchText . '%';

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":searchText", $searchText);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function selectById($identificador)
    {
        return $this->select("identificador", "cliente", "data", "valor_total", "valor_desconto", "valor_final", "valor_pago")
            ->from($this->table_name)
            ->where("identificador", $identificador)
            ->execute();
    }

    public function edit($cliente, $data, $valor_total, $valor_desconto, $valor_final, $valor_pago, $identificador)
    {
        return $this->update($this->table_name)
            ->values("cliente", $cliente)
            ->values("data", $data)
            ->values("valor_total", $valor_total)
            ->values("valor_desconto", $valor_desconto)
            ->values("valor_final", $valor_final)
            ->values("valor_pago", $valor_pago)
            ->where("identificador", $identificador)
            ->execute();
    }

    public function updateValorPago($identificador, $valor_pago)
    {
        return $this->update($this->table_name)
            ->set("valor_pago", $valor_pago)
            ->where("identificador", $identificador)
            ->execute();
    }

    public function delete($identificador)
    {
        return $this->deleteFrom($this->table_name)
            ->where("identificador", $identificador)
            ->execute();
    }

    public function relatorioLucratividade($periodoInicio, $periodoFim)
    {
        return $this->select("venda_itens.produto", "produtos.descricao", "SUM(venda_itens.quantidade) AS quantidade_total", "venda_itens.valor_unitario", "produtos.preco_de_compra")
            ->from($this->table_name)
            ->join($this->table_itens, "venda_itens.venda", "venda.identificador")
            ->join($this->table_produtos, "venda_itens.produto", "produtos.identificador")
            ->whereBetween("venda.data", $periodoInicio, $periodoFim)
            ->groupBy("venda_itens.produto", "produtos.descricao", "venda_itens.valor_unitario", "produtos.preco_de_compra")
            ->execute();
    }

    public function getTotalDesconto($periodoInicio, $periodoFim)
    {
        return $this->select("SUM(venda.valor_desconto) AS valor_desconto")
            ->from($this->table_name)
            ->whereBetween("venda.data", $periodoInicio, $periodoFim)
            ->execute();
    }

    public function relatorioVendasPorCliente($periodoInicio, $periodoFim)
    {
        $query = "SELECT
            clientes.identificador,
            nome,
            SUM(main.valor_pago) AS valor_pago,
            CAST(main.data AS DATE) AS data,
            d.custo_total
        FROM
            venda AS main
        LEFT JOIN clientes ON cliente = clientes.identificador
        LEFT JOIN (
            SELECT
                a.data,
                a.cliente,
                SUM(
                    b.quantidade * c.preco_de_compra
                ) AS custo_total
            FROM
                venda AS a
            LEFT JOIN venda_itens AS b ON b.venda = a.identificador
            LEFT JOIN produtos AS c ON c.identificador = b.produto
            GROUP BY
                a.data,
                a.cliente
        ) AS d ON d.data = main.data
        AND d.cliente = main.cliente
        WHERE
            main.valor_pago >= valor_final
            AND main.data BETWEEN :periodoInicio AND :periodoFim
        GROUP BY
            clientes.identificador,
            nome,
            CAST(main.data AS DATE),
            d.custo_total;";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":periodoInicio", $periodoInicio);
        $stmt->bindParam(":periodoFim", $periodoFim);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
}
