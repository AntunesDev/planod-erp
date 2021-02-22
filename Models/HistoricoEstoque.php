<?php

namespace Models;

use Core;

class HistoricoEstoque extends Core\Model
{
    private $table_name = "historico_estoque";
    private $table_produtos = "produtos";

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
            ->leftJoin($this->table_produtos, "identificador", "produto")
            ->where("excluido", 0)
            ->execute();
    }

    public function paginatedSearch($searchText, $orderColumn, $orderDir, $start, $rows)
    {
        return $this->select("descricao AS produto", "tipo_de_movimentacao", "quantidade_movimentada", "quantidade_antes", "quantidade_depois", "momento")
            ->from($this->table_name)
            ->leftJoin($this->table_produtos, "identificador", "produto")
            ->where("excluido", 0)
            ->orWhereLike("identificador", $searchText)
            ->orWhereLike("descricao", $searchText)
            ->orWhereLike("tipo_de_movimentacao", $searchText)
            ->orderBy($orderColumn, $orderDir)
            ->limit($start, $rows)
            ->execute();
    }

    public function delete($produto)
    {
        return $this->deleteFrom($this->table_name)
            ->where("produto", $produto)
            ->execute();
    }

    public function relatorioMovEstoque($periodoInicio, $periodoFim)
    {
        return $this->select("produto", "descricao", "tipo_de_movimentacao", "SUM(quantidade_movimentada) AS quantidade_movimentada", "CAST(momento AS DATE) AS dia")
            ->from($this->table_name)
            ->leftJoin($this->table_produtos, "produto", "identificador")
            ->whereBetween("CAST(momento AS DATE)", $periodoInicio, $periodoFim)
            ->groupBy("produto", "tipo_de_movimentacao", "CAST(momento AS DATE)")
            ->orderBy("CAST(momento AS DATE)", "ASC")
            ->execute();
    }
}
