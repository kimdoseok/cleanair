<?php
include_once("class.dbutils.php");

class IC {

	var $query;
	var $numrows;

	function insertIC($table, $arr) {
		$dbu = new Dbutils();
		$field = $dbu->getFieldStr($arr);
		$value = $dbu->getValueStr($arr);
		$this->query = "INSERT INTO $table ($field) VALUES ($value) ";
//echo $this->query."<br>";

		if (!empty($table) && !empty($field) && !empty($value) && $dbu->insertQry($this->query)) return $dbu->last_id;
		return false;
	}

	function updateIC($table, $keyname, $code, $arr) {
		$dbu = new Dbutils();
		$pair = $dbu->getPairStr($arr);
		$this->query = "UPDATE $table SET $pair WHERE $keyname = '$code' ";
//echo $this->query."<br>";
		if (!empty($table) && !empty($pair)) if ($dbu->updateQry($this->query)) return true;
		return false;
	}

	function updateICRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
//echo $this->query."<br>";

		if ($dbu->updateQry($this->query)) return true;
		else return false;
	}

	function insertICRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
//echo $this->query."<br>";

		if ($id = $dbu->insertQry($this->query)) return $id;
		else return false;
	}

	function getIC($code="") {
		$dbu = new Dbutils();
//echo $this->query."<br>";
		if ($dbu->selectQry($this->query)) return $dbu->values;
		return false;
	}

	function getICRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
		if ($dbu->selectQry($this->query)) {
			if ($dbu->limit < $dbu->numrows) {
				$dbu->limit = $dbu->numrows;
				if ($dbu->selectQry($this->query)) return $dbu->values;
			}
			return $dbu->values;
		}
		else return false;
	}

	function getICFields($table="") {
		$dbu = new Dbutils();
		$dbu->selectQry($this->query);
		if (count($dbu->fields)>0) return $dbu->fields;
		return false;
	}

}

?>