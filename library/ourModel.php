<?php
namespace taskApp;

class mydb{

	private $db;
	public $Id;
	function __construct(){
		$this->db= new \mysqli("localhost", "root", "", "todo");
	}

	private function help($data){
		return $this->db-> real_escape_string($data);
	}

	function insert($table, $data){
		$sql= "";
		foreach ($data as $key => $value) {
			if ($sql != "") {
				$sql .= ",";
			}
			$sql .= "{$key}='". $this->help($value) ."'";
		}

		$sql= "insert into {$table} set {$sql}";
		//echo "$sql";

		if ($this->db->query($sql)) {
			$this->Id= $this->db->insert_id;
			return true;
		}
		else{
			return false;
		}
	}

	function view($table, $select, $order="", $where="", $rel=""){
		$sql= "select {$select} from {$table}";
		$sqlOrder= "";
		$sqlWhere= "";
		$sqlRel= "";

		if ($order) {
			$sqlOrder= " order by {$order[0]} {$order[1]}";
		}
		if ($where) {
			foreach ($where as $key => $value) {
				if ($sqlWhere != "") {
					$sqlWhere .= " and ";
				}
				else{
					$sqlWhere = " where ";
				}
			$sqlWhere .= "{$key}='" .$value ."'";
			}
		}

		if ($rel) {
			foreach ($rel as $key => $value) {
				if ($sqlRel != "") {
					$sqlRel .= " and ";
				}
				else if(!$sqlWhere){
					$sqlRel = " where ";
				}
				else if(!$sqlRel && $sqlWhere){
					$sqlRel .= " and ";
				}
				$sqlRel .= "{$key}={$value}";
			}
		}

		$sql .= $sqlWhere. $sqlRel. $sqlOrder;
		//echo $sql;
		$view= $this->db->query($sql);
		return $view;
	}

	function update($table, $data, $where){
		$sql= "";
		$sqlWhere= "";
		foreach ($data as $key => $value) {
			if ($sql != "") {
				$sql .= ",";
			}
			$sql .= "{$key}='". $this->help($value) ."'";
		}
		if ($where) {
			foreach ($where as $key => $value) {
				if ($sqlWhere != "") {
					$sqlWhere .= " and ";
				}
				else{
					$sqlWhere = " where ";
				}
			$sqlWhere .= "{$key}='" .$value ."'";
			}
		}

		$sql= "update {$table} set {$sql} {$sqlWhere}";
		$this->db->query($sql);
		if ($this->db->affected_rows >-1) {
			return true;
		}
		else{
			return false;
		}
	}

	function delete($table, $where){
		
		$sqlWhere= "";
		
		if ($where) {
			foreach ($where as $key => $value) {
				if ($sqlWhere != "") {
					$sqlWhere .= " and ";
				}
				else{
					$sqlWhere = " where ";
				}
			$sqlWhere .= "{$key}='" .$value ."'";
			}
		}

		$sql= "delete from {$table} {$sqlWhere}";
		//echo "$sql";
		$this->db->query($sql);
		if ($this->db->affected_rows >0) {
			return true;
		}
		else{
			return false;
		}
	}

	public function dbraw($sql){
		//echo $sql;
		return $this->db->query($sql);
	}
}