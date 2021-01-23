<?php

namespace Models;

use Core;

class Produtos extends Core\Model
{
    private $table_name = "produtos";
    private $table_estoque = "estoque";

    public function create($descricao, $preco_de_venda, $preco_de_compra)
    {
        return $this->insertInto($this->table_name)
            ->values("descricao", $descricao)
            ->values("preco_de_venda", $preco_de_venda)
            ->values("preco_de_compra", $preco_de_compra)
            ->execute();
    }

    public function selectAll()
    {
        return $this->select("identificador", "descricao", "preco_de_venda", "preco_de_compra")
            ->from($this->table_name)
            ->where("excluido", 0)
            ->execute();
    }

    public function paginatedSearch($searchText, $orderColumn, $orderDir, $start, $rows)
    {
        return $this->select("identificador", "descricao", "preco_de_venda", "preco_de_compra")
            ->from($this->table_name)
            ->orWhereLike("identificador", $searchText)
            ->orWhereLike("descricao", $searchText)
            ->where("excluido", 0)
            ->orderBy($orderColumn, $orderDir)
            ->limit($start, $rows)
            ->execute();
    }

    public function selectAllAtivosComEstoque()
    {
        return $this->select("identificador", "descricao", "quantidade", "preco_de_venda")
            ->from($this->table_name)
            ->leftJoin($this->table_estoque, "produto", "identificador")
            ->where("excluido", 0)
            ->whereNot("quantidade", 0)
            ->execute();
    }

    public function paginatedSearchAtivosComEstoque($searchText, $orderColumn, $orderDir, $start, $rows)
    {
        return $this->select("identificador", "descricao AS produto", "quantidade AS estoque", "preco_de_venda AS preco")
            ->from($this->table_name)
            ->leftJoin($this->table_estoque, "produto", "identificador")
            ->orWhereLike("identificador", $searchText)
            ->orWhereLike("descricao", $searchText)
            ->where("excluido", 0)
            ->whereNot("quantidade", 0)
            ->orderBy($orderColumn, $orderDir)
            ->limit($start, $rows)
            ->execute();
    }

    public function selectById($identificador)
    {
        return $this->select("identificador", "descricao", "preco_de_venda", "preco_de_compra")
            ->from($this->table_name)
            ->where("identificador", $identificador)
            ->execute();
    }

    public function edit($descricao, $preco_de_venda, $preco_de_compra, $identificador)
    {
        return $this->update($this->table_name)
            ->set("descricao", $descricao)
            ->set("preco_de_venda", $preco_de_venda)
            ->set("preco_de_compra", $preco_de_compra)
            ->where("identificador", $identificador)
            ->execute();
    }

    public function delete($identificador)
    {
        return $this->update($this->table_name)
            ->set("excluido", 1)
            ->where("identificador", $identificador)
            ->execute();
    }
}
