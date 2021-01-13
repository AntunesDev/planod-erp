<?php

namespace Core;

use PDO;
use Exception;

class Model
{
	private const INSERT = 1;
	private const SELECT = 2;
	private const UPDATE = 3;
	private const DELETE = 4;

	protected $db;
	private $query = "";
	private $operation = 0;
	private $table = "";
	private $update_values = [];
	private $insert_values = [];
	private $select_fields = [];
	private $join_conditions = [];
	private $where_conditions = [];
	private $group_by_fields = [];
	private $order_by_conditions = [];
	private $params = [];

	public function __construct()
	{
		try {
			if (DB_OS === 'windows') {
				if (DB_ENGINE === 'mysql') {
					$this->db = new PDO("mysql:dbname=" . DB_NAME . ";charset=utf8;host=" . DB_HOST, DB_USER, DB_PASSWORD);
					$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} elseif (DB_ENGINE === 'sqlserver') {
					$this->db = new PDO("sqlsrv:Server=" . DB_HOST . ";Database=" . DB_NAME, DB_USER, DB_PASSWORD);
					$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}
			} elseif (DB_OS === 'linux') {
				if (DB_ENGINE === 'mysql') {
					$this->db = new PDO("mysql:dbname=" . DB_NAME . ";charset=utf8;host=" . DB_HOST, DB_USER, DB_PASSWORD);
					$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} elseif (DB_ENGINE === 'sqlserver') {
					$this->db = new PDO("dblib:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASSWORD);
					$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}
			} else {
				throw new \Exception("Verifique as suas configurações de banco de dados.", 500);
			}
		} catch (Exception $exception) {
			throw new \Exception($exception, 500);
		}
	}

	public function dump()
	{
		$this->set_query();
		dd([
			"query" => $this->query,
			"operation" => $this->operation,
			"table" => $this->table,
			"update_values" => $this->update_values,
			"insert_values" => $this->insert_values,
			"select_fields" => $this->select_fields,
			"join_conditions" => $this->join_conditions,
			"where_conditions" => $this->where_conditions,
			"group_by_fields" => $this->group_by_fields,
			"order_by_conditions" => $this->order_by_conditions,
			"params" => $this->params
		]);
	}

	private function clearAuxiliaryVariables()
	{
		$this->query = "";
		$this->table = "";
		$this->update_values = [];
		$this->insert_values = [];
		$this->select_fields = [];
		$this->join_conditions = [];
		$this->where_conditions = [];
		$this->group_by_fields = [];
		$this->order_by_conditions = [];
		$this->params = [];
	}

	private function select_table(string $table)
	{
		$this->table = $table;
		return $this;
	}

	public function select(...$fields)
	{
		$this->operation = Model::SELECT;
		$this->select_fields = array_merge($this->select_fields, $fields);
		return $this;
	}

	public function from(string $table)
	{
		return $this->select_table($table);
	}

	public function join(string $table, string $field, $value, string $type = null)
	{
		$this->join_conditions[$table] = array_merge_recursive($this->join_conditions[$table] ?? [], ["fields" => [$field => $value], "type" => ($type != null ? " $type" : "")]);
		array_splice($this->join_conditions[$table]["type"], 1);
		return $this;
	}

	public function leftJoin(string $table, string $field, $value)
	{
		return $this->join($table, $field, $value, "LEFT");
	}

	public function where(string $field, $value)
	{
		$this->where_conditions = array_merge($this->where_conditions, [$field => $value]);
		return $this;
	}

	public function groupBy(...$fields)
	{
		$this->group_by_fields = array_merge($this->group_by_fields, $fields);
		return $this;
	}

	public function orderBy(string $field, string $orientation)
	{
		$this->order_by_conditions = array_merge($this->order_by_conditions, [$field => $orientation]);
		return $this;
	}

	public function update(string $table)
	{
		$this->operation = Model::UPDATE;
		return $this->select_table($table);
	}

	public function set(string $field, $value)
	{
		$this->update_values = array_merge($this->update_values, [$field => $value]);
		return $this;
	}

	public function insertInto(string $table)
	{
		$this->operation = Model::INSERT;
		return $this->select_table($table);
	}

	public function values(string $field, $value)
	{
		$this->insert_values = array_merge($this->insert_values, [$field => $value]);
		return $this;
	}

	public function deleteFrom($table)
	{
		$this->operation = Model::DELETE;
		return $this->select_table($table);
	}

	private function add_param($value)
	{
		$this->params[] = $value;
		return "?";
	}

	private function set_query()
	{
		if ($this->operation == Model::INSERT) {
			$this->query = "INSERT INTO " . $this->table . " " . PHP_EOL;
			foreach ($this->insert_values as $field => $value) {
				$fields[] = $field;
				$this->add_param($value);
			}
			$this->query .= "(" . implode(",", $fields) . ")" . PHP_EOL . "VALUES (" . implode(",", array_fill(0, count($fields), "?")) . ")";
		} else {
			switch ($this->operation) {
				case Model::SELECT;
					$this->query = "SELECT " . implode(", ", $this->select_fields) . " FROM " . $this->table . PHP_EOL;
					break;
				case Model::UPDATE;
					$this->query = "UPDATE " . $this->table . " SET ";
					foreach ($this->update_values as $field => $value) {
						$update_values[] = "$field = ?";
						$this->add_param($value);
					}
					$this->query .= implode(", ", $update_values) . " " . PHP_EOL;
					break;
				case Model::DELETE;
					$this->query = "DELETE FROM " . $this->table . PHP_EOL;
					break;
			}

			$this->query .= "WHERE ";
			foreach ($this->where_conditions as $field => $value) {
				$where_conditions[] = "$field = ?";
				$this->add_param($value);
			}
			$this->query .= implode(" AND ", $where_conditions) . " " . PHP_EOL;
		}

		if (count($this->group_by_fields) > 0) {
			$this->query .= "GROUP BY " . implode(", ", $this->group_by_fields) . PHP_EOL;
		}

		if (count($this->order_by_conditions) > 0) {
			$this->query .= "ORDER BY ";
			foreach ($this->order_by_conditions as $field => $orientation) {
				$order_by_conditions[] = "$field $orientation";
			}
			$this->query .= implode(", ", $order_by_conditions) . PHP_EOL;
		}
	}

	public function execute()
	{
		try {
			$this->set_query();
			switch ($this->operation) {
				case Model::SELECT:
					$stmt = $this->db->prepare($this->query);
					break;
				case Model::INSERT;
				case Model::UPDATE;
				case Model::DELETE;
					$stmt = $this->db->prepare($this->query, [PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL]);
					break;
			}

			if (count($this->params) > 0) {
				foreach ($this->params as $param => &$value) {
					$index = $param + 1;
					$stmt->bindParam($index, $value);
				}
				unset($value);
			}

			$stmt->execute();

			$this->clearAuxiliaryVariables();

			switch ($this->operation) {
				case Model::SELECT:
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) == 0) {
						return false;
					} else if (count($result) == 1) {
						return (object) $result[0];
					} else {
						return $result;
					}
					break;
				case Model::INSERT:
				case Model::UPDATE:
				case Model::DELETE;
					return $stmt->rowCount() > 0;
					break;
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 500);
		}
	}

	public function beginTransaction()
	{
		$this->db->beginTransaction();
		return $this;
	}

	public function commit()
	{
		$this->db->commit();
		return $this;
	}

	public function rollBack()
	{
		$this->db->rollBack();
		return $this;
	}
}
