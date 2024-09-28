<?php
include_once("class.dbutils.php");

class AR {

	var $query;
	var $numrows;
	var $page;
	var $limit;

	function AR() {
		$this->page = 1;
		$this->limit = 20;
	}

	function insertAR($table, $arr) {
		$dbu = new Dbutils();
		$field = $dbu->getFieldStr($arr);
		$value = $dbu->getValueStr($arr);
		$this->query = "INSERT INTO $table ($field) VALUES ($value) ";
//echo $this->query."<br>";
		if ($last_id = $dbu->insertQry($this->query)) {
			return $dbu->last_id;
		}
		return false;
	}

	function updateAR($table, $keyname, $code, $arr) {
		$dbu = new Dbutils();
		$pair = $dbu->getPairStr($arr);
		$this->query = "UPDATE $table SET $pair WHERE $keyname = '$code' ";
//echo $this->query."<br>";
		if ($dbu->updateQry($this->query)) return true;
		return false;
	}

	function updateARRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
//echo $this->query."<br>";
		if ($dbu->updateQry($this->query)) return true;
		else return false;
	}

	function insertARRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
//echo $this->query."<br>";
		if ($id = $dbu->insertQry($this->query)) return $id;
		else return false;
	}

	function getAR($code="") {
		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values;
		return false;
	}

	function getARFields() {
		$dbu = new Dbutils();
		$dbu->selectQry($this->query);
		if (count($dbu->fields)>0) return $dbu->fields;
		return false;
	}

	function getARRow($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
		if ($dbu->selectQry($this->query)) return $dbu->numrows;
		else return false;
	}

	function getARRaw($query="") {
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

}

?>