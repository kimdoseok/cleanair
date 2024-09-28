<?php
include_once("class/map.default.php");
include_once("class.dbutils.php");

class GL {

	var $query;
	var $numrows;

	function insertGL($table, $arr) {
		$dbu = new Dbutils();
		$field = $dbu->getFieldStr($arr);
		$value = $dbu->getValueStr($arr);
		if (!empty($field) && !empty($value)) {
			$this->query = "INSERT INTO $table ($field) VALUES ($value) ";
			if ($dbu->insertQry($this->query)) return $dbu->last_id;
		}
		return false;
	}

	function updateGL($table, $keyname, $code, $arr) {
		$dbu = new Dbutils();
		$pair = $dbu->getPairStr($arr);
		$this->query = "UPDATE $table SET $pair WHERE $keyname = '$code' ";
		if ($dbu->updateQry($this->query)) return true;
		return false;
	}

	function updateGLRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
		if ($dbu->updateQry($this->query)) return true;
		else return false;
	}

	function insertGLRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
		if ($id = $dbu->insertQry($this->query)) return $id;
		else return false;
	}

	function getGL($code="") {
		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values;
		return false;
	}

	function getGLFields() {
		$dbu = new Dbutils();
		$dbu->selectQry($this->query);
		if (count($dbu->fields)>0) return $dbu->fields;
		return false;
	}

}

?>