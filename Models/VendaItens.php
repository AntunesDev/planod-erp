<?php

namespace Models;

use Core;

class VendaItens extends Core\Model
{
    private $table_name = "venda_itens";

    public function create($venda, $produto, $quantidade, $valor_unitario)
    {
        return $this->insertInto($this->table_name)
            ->values("venda", $venda)
            ->values("produto", $produto)
            ->values("quantidade", $quantidade)
            ->values("valor_unitario", $valor_unitario)
            ->execute();
    }

    public function selectAll()
    {
        return $this->select("venda", "produto", "quantidade", "valor_unitario")
            ->from($this->table_name)
            ->execute();
    }

    public function selectAllByProduto($produto)
    {
        return $this->select("venda", "produto", "quantidade", "valor_unitario")
            ->from($this->table_name)
            ->where("produto", $produto)
            ->execute();
    }

    public function selectAllByVenda($venda)
    {
        return $this->select("venda", "produto", "quantidade", "valor_unitario")
            ->from($this->table_name)
            ->where("venda", $venda)
            ->execute();
    }

    public function edit($venda, $produto, $quantidade, $valor_unitario)
    {
        return $this->update($this->table_name)
            ->set("quantidade", $quantidade)
            ->set("valor_unitario", $valor_unitario)
            ->where("venda", $venda)
            ->where("produto", $produto)
            ->execute();
    }

    public function delete($venda, $produto)
    {
        return $this->deleteFrom($this->table_name)
            ->where("venda", $venda)
            ->where("produto", $produto)
            ->execute();
    }

    public function deleteAll($venda)
    {
        return $this->deleteFrom($this->table_name)
            ->where("venda", $venda)
            ->execute();
    }
}
