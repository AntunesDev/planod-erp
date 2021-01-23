<?php

namespace Models;

use Core;

class Venda extends Core\Model
{
    private $table_name = "venda";
    private $table_clientes = "clientes";

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

    public function selectAll()
    {
        return $this->select("identificador", "cliente", "data", "valor_total", "valor_desconto", "valor_final", "valor_pago")
            ->from($this->table_name)
            ->execute();
    }

    public function paginatedSearch($searchText, $orderColumn, $orderDir, $start, $rows)
    {
        return $this->select("$this->table_name.identificador", "nome AS cliente", "data", "valor_total", "valor_desconto", "valor_final", "valor_pago")
            ->from($this->table_name)
            ->leftJoin($this->table_clientes, "$this->table_clientes.identificador", "cliente")
            ->whereLike("nome", $searchText)
            ->orderBy($orderColumn, $orderDir)
            ->limit($start, $rows)
            ->execute();
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
}
