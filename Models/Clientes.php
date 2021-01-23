<?php

namespace Models;

use Core;

class Clientes extends Core\Model
{
    private $table_name = "clientes";

    public function create($nome, $telefone, $email)
    {
        return $this->insertInto($this->table_name)
            ->values("nome", $nome)
            ->values("telefone", $telefone)
            ->values("email", $email)
            ->execute();
    }

    public function selectAll()
    {
        return $this->select("identificador", "nome", "telefone", "email")
            ->from($this->table_name)
            ->execute();
    }

    public function paginatedSearch($searchText, $orderColumn, $orderDir, $start, $rows)
    {
        return $this->select("identificador", "nome", "telefone", "email")
            ->from($this->table_name)
            ->orWhereLike("identificador", $searchText)
            ->orWhereLike("nome", $searchText)
            ->orderBy($orderColumn, $orderDir)
            ->limit($start, $rows)
            ->execute();
    }

    public function selectById($identificador)
    {
        return $this->select("identificador", "nome", "telefone", "email")
            ->from($this->table_name)
            ->where("identificador", $identificador)
            ->execute();
    }

    public function edit($identificador, $nome, $telefone, $email)
    {
        return $this->update($this->table_name)
            ->set("nome", $nome)
            ->set("telefone", $telefone)
            ->set("email", $email)
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
