<?php
include_once("class.dbutils.php");

class SY {

	var $query;
	var $numrows;
	var $page;
	var $limit;

	function SY() {
		$this->page = 1;
		$this->limit = 20;
	}

	function insertSY($table, $arr) {
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

	function updateSY($table, $keyname, $code, $arr) {
		$dbu = new Dbutils();
		$pair = $dbu->getPairStr($arr);
		$this->query = "UPDATE $table SET $pair WHERE $keyname = '$code' ";
		if ($dbu->updateQry($this->query)) return true;
		return false;
	}

	function updateSYRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
//echo $this->query."<br>";
		if ($dbu->updateQry($this->query)) return true;
		else return false;
	}

	function insertSYRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
//echo $this->query."<br>";
		if ($id = $dbu->insertQry($this->query)) return $id;
		else return false;
	}

	function getSY($code="") {
		$dbu = new Dbutils();
		$out = $dbu->selectQry($this->query);
		if ($out) return $dbu->values;
		return array();
	}

	function getSYFields() {
		$dbu = new Dbutils();
		$dbu->selectQry($this->query);
		if (count($dbu->fields)>0) {
			$outarr = array();
			for ($i=0;$i<count($dbu->fields);$i++) {
				array_push($outarr, $dbu->fields[$i]->name);
			}
			return $outarr;
		} else {
			return array();
		}
	}

	function getSYRow($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
		if ($dbu->selectQry($this->query)) return $dbu->numrows;
		else return false;
	}

	function getSYRaw($query="") {
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