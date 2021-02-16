<?php

namespace Models;

use Core;

class Estoque extends Core\Model
{
    private $table_name = "estoque";
    private $table_produtos = "produtos";

    public function create($produto, $quantidade)
    {
        return $this->insertInto($this->table_name)
            ->values("produto", $produto)
            ->values("quantidade", $quantidade)
            ->execute();
    }

    public function selectAll()
    {
        return $this->select("produto", "descricao", "quantidade", "ultima_movimentacao")
            ->from($this->table_name)
            ->leftJoin($this->table_produtos, "identificador", "produto")
            ->where("excluido", 0)
            ->execute();
    }

    public function paginatedSearch($searchText, $orderColumn, $orderDir, $start, $rows)
    {
        return $this->select("identificador", "descricao AS produto", "quantidade", "ultima_movimentacao")
            ->from($this->table_name)
            ->leftJoin($this->table_produtos, "identificador", "produto")
            ->where("excluido", 0)
            ->orWhereLike("produto", $searchText)
            ->orWhereLike("descricao", $searchText)
            ->orWhereLike("ultima_movimentacao", $searchText)
            ->orderBy($orderColumn, $orderDir)
            ->limit($start, $rows)
            ->execute();
    }

    public function selectByProduto($produto)
    {
        return $this->select("produto", "quantidade", "ultima_movimentacao")
            ->from($this->table_name)
            ->where("produto", $produto)
            ->execute();
    }

    public function edit($produto, $quantidade, $ultima_movimentacao)
    {
        return $this->update($this->table_name)
            ->set("quantidade", $quantidade)
            ->set("ultima_movimentacao", $ultima_movimentacao)
            ->where("produto", $produto)
            ->execute();
    }

    public function delete($produto)
    {
        return $this->deleteFrom($this->table_name)
            ->where("produto", $produto)
            ->execute();
    }
}
