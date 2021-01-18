<?php

namespace Models;

use Core;

class Produtos extends Core\Model
{
    private $table_name = "produtos";

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
        return $this->deleteFrom($this->table_name)
            ->where("identificador", $identificador)
            ->execute();
    }
}
