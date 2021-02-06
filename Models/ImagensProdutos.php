<?php

namespace Models;

use Core;

class ImagensProdutos extends Core\Model
{
    private $table_name = "imagens_produtos";

    public function create($produto, $imagem)
    {
        return $this->insertInto($this->table_name)
            ->values("produto", $produto)
            ->values("imagem", $imagem)
            ->execute();
    }

    public function selectAll($produto)
    {
        return $this->select("identificador", "produto", "imagem")
            ->from($this->table_name)
            ->where("produto", $produto)
            ->execute();
    }

    public function paginatedSearch($produto, $searchText, $orderColumn, $orderDir, $start, $rows)
    {
        return $this->select("identificador", "produto", "imagem")
            ->from($this->table_name)
            ->where("produto", $produto)
            ->orderBy($orderColumn, $orderDir)
            ->limit($start, $rows)
            ->execute();
    }

    public function selectById($identificador)
    {
        return $this->select("identificador", "produto", "imagem")
            ->from($this->table_name)
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
