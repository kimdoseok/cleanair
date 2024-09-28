<?php
include_once("class.dbutils.php");

class AP {

	var $query;
	var $numrows;

	function insertAP($table, $arr) {
		$dbu = new Dbutils();
		$field = $dbu->getFieldStr($arr);
		$value = $dbu->getValueStr($arr);
		if (!empty($field) && !empty($field)) {
			$this->query = "INSERT INTO $table ($field) VALUES ($value) ";
//echo $this->query;
			if ($last_id = $dbu->insertQry($this->query)) return $last_id;
		}
		return false;
	}

	function updateAP($table, $keyname, $code, $arr) {
		$dbu = new Dbutils();
		$pair = $dbu->getPairStr($arr);
		if (strlen($pair)>0) {
			$this->query = "UPDATE $table SET $pair WHERE $keyname = '$code' ";
			if ($dbu->updateQry($this->query)) return true;
		}
		return false;
	}

	function updateAPRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
		if ($dbu->updateQry($this->query)) return true;
		else return false;
	}

	function insertAPRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
		if ($id = $dbu->insertQry($this->query)) return $id;
		else return false;
	}

	function getAP($code="") {
		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values;
		return false;
	}

	function getAPRaw($query="") {
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

	function getAPFields() {
		$dbu = new Dbutils();
		$dbu->selectQry($this->query);
		if (count($dbu->fields)>0) return $dbu->fields;
		return false;
	}

}

?>