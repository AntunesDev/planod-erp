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

    public function paginatedSearch($exibeVendasAntigas, $searchText, $orderColumn, $orderDir, $start, $rows)
    {
        $query = "SELECT $this->table_name.identificador, nome AS cliente, data, valor_total, valor_desconto, valor_final, IFNULL(valor_pago, 0) AS valor_pago
        FROM $this->table_name
        LEFT JOIN $this->table_clientes ON $this->table_clientes.identificador = cliente
        WHERE nome LIKE :searchText
        " . ($exibeVendasAntigas ? "" : "AND valor_final > IFNULL(valor_pago, 0)") . "
        ORDER BY $orderColumn $orderDir
        LIMIT $start, $rows;";

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
            ->leftJoin($this->table_itens, "venda_itens.venda", "venda.identificador")
            ->leftJoin($this->table_produtos, "venda_itens.produto", "produtos.identificador")
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
}
