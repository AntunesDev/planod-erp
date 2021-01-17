<?php

namespace Models;

use Core;

class HistoricoEstoque extends Core\Model
{
    private $table_name = "historico_estoque";

    public function create($produto, $tipo_de_movimentacao, $quantidade_movimentada, $quantidade_antes, $quantidade_depois, $momento)
    {
        return $this->insertInto($this->table_name)
            ->values("produto", $produto)
            ->values("tipo_de_movimentacao", $tipo_de_movimentacao)
            ->values("quantidade_movimentada", $quantidade_movimentada)
            ->values("quantidade_antes", $quantidade_antes)
            ->values("quantidade_depois", $quantidade_depois)
            ->values("momento", $momento)
            ->execute();
    }

    public function selectAll()
    {
        return $this->select("produto", "tipo_de_movimentacao", "quantidade_movimentada", "quantidade_antes", "quantidade_depois", "momento")
            ->from($this->table_name)
            ->execute();
    }

    public function selectByProduto($produto)
    {
        return $this->select("produto", "tipo_de_movimentacao", "quantidade_movimentada", "quantidade_antes", "quantidade_depois", "momento")
            ->from($this->table_name)
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
