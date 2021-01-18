<?php

namespace Models;

use Core;

class Usuarios extends Core\Model
{
    private $table_name = "usuarios";

    public function selectByUserName($userName)
    {
        return $this->select("id", "login", "senha", "ativo")->from($this->table_name)->where("login", $userName)->execute();
    }

    public function updatePassword($id, $password)
    {
        return $this->update($this->table_name)->set("senha", $password)->where("id", $id)->execute();
    }
}
