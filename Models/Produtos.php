<?php

namespace Models;

use Core;
use PDO;

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

    public function searchByText($searchText)
    {
        return $this->select("identificador AS id", "descricao AS text")
            ->from($this->table_name)
            ->where("excluido", 0)
            ->whereLike("descricao", $searchText)
            ->orderBy("descricao", "asc")
            ->execute();
    }

    public function paginatedSearch($searchText, $orderColumn, $orderDir)
    {
        return $this->select("identificador", "descricao", "preco_de_venda", "preco_de_compra")
            ->from($this->table_name)
            ->orWhereLike("identificador", $searchText)
            ->orWhereLike("descricao", $searchText)
            ->where("excluido", 0)
            ->orderBy($orderColumn, $orderDir)
            ->execute();
    }

    public function selectAllAtivosComEstoque($produtosNoCarrinho)
    {
        $query = "SELECT identificador, descricao, quantidade, preco_de_venda
        FROM $this->table_name
        LEFT JOIN $this->table_estoque ON produto = identificador
        WHERE excluido = 0
        AND quantidade != 0
        ";

        if (count($produtosNoCarrinho) > 0) {
            $query .= "AND identificador NOT IN (" . implode(", ", $produtosNoCarrinho) . ")";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function paginatedSearchAtivosComEstoque($produtosNoCarrinho, $searchText, $orderColumn, $orderDir, $start, $rows)
    {
        $query = "SELECT identificador, descricao AS produto, quantidade AS estoque, preco_de_venda AS preco
        FROM $this->table_name
        LEFT JOIN $this->table_estoque ON produto = identificador
        WHERE (
            identificador LIKE :searchText
            OR descricao LIKE :searchText
        )
        AND excluido = 0
        AND quantidade != 0
        ";

        if (count($produtosNoCarrinho) > 0) {
            $query .= "AND identificador NOT IN (" . implode(", ", $produtosNoCarrinho) . ")\n";
        }

        $query .= "ORDER BY $orderColumn $orderDir";

        if (empty($searchText))
            $searchText = '%';
        else
            $searchText = '%' . $searchText . '%';

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":searchText", $searchText);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
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

    public function getCatalogoSemImagens()
    {
        $query = "SELECT
            descricao,
            preco_de_venda,
            estoque.quantidade
        FROM
            produtos
        LEFT JOIN estoque ON estoque.produto = produtos.identificador
        WHERE
            excluido = 0
        ORDER BY
            REPLACE (descricao, '	', '') ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
}
