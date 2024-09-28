<?php
include_once("class.dbs.php");
include_once("class.dbconfig.php");
include_once("class.datex.php");

//define("MAXINT",0x8FFFFFFF);

class Dbutils extends dbs {

	var $values;
	var $last_id;
	var $offset;
	var $limit;
	var $pages;
	var $page;
	var $nextpage;
	var $prevpage;

	function Dbutils($ctype="default") {
		$this->values = array();
		$this->setDbType("odbc");
		$this->limit = 1000;
		$this->page = 1;
		$this->pages = 1;
	}

	function setPage($page) {
		if ($this->numrows > 0) $this->pages = ceil($this->numrows / $this->limit);
		if ($this->pages >= $page && $page >= 1) {
			$this->page = $page;
			if ($page <= 1) $this->prevpage = 1;
			else $this->prevpage = $page - 1;
			if ($page >= $this->pages) $this->nextpage = $page;
			else $this->nextpage = $page + 1;
		}
	}

	function getPage() {
		return $this->page;
	}

	function getNextPage() {
		if ($this->page >= $this->pages) $this->nextpage = $this->page;
		else $this->nextpage = $this->page + 1;
		return $this->nextpage;
	}

	function getPrevPage() {
		if ($this->page <= 1) $this->prevpage = 1;
		else $this->prevpage = $this->page - 1;
		$this->prevpage;
	}

	function setOffset($offset) {
		$this->offset = $offset;
	}

	function setLimit($limit) {
		$this->limit = $limit;
	}

	function selectQry($query,$ctype="select") {
		$dbc = new dbConfig();
		$cfg = $dbc->getConn($ctype);
		$v = array();
		if ($this->pconnect($cfg["hostname"], $cfg["dbname"], $cfg["username"], $cfg["password"])) {
			$this->dbSelect($query);

			for ($i=0; $i<(($this->page-1) * $this->limit); $i++) {
				if (!$this->fetch_row()) break;
			}

			$i = 0;
			while ($this->fetch_row()) {
				$v[$i] = $this->record;
				$i++;
				if ($i >= $this->limit) break;
			}
			$this->values = $v;
		}
		return $i;
	}

	function selectQryRaw($query,$ctype="select") {
		$dbc = new dbConfig();
		$cfg = $dbc->getConn($ctype);
		if (!$this->conn) $this->pconnect($cfg["hostname"], $cfg["dbname"], $cfg["username"], $cfg["password"]);
		return $this->dbSelect($query);
	}

	function getPairStr($arr) {
		$pairs = "";
		$od = new Datex();
		foreach ($arr as $k => $v) {
			$v = addslashes($v);
			if ($od->isUsaDate($v)) $v = $od->isoDate($v);
			$pairs .= "$k='$v',";
		}
		if ($pairs != "") $pairs = substr($pairs, 0, strlen($pairs)-1); 
		return $pairs;
	}

	function updateQry($query,$ctype="update") {
		$dbc = new dbConfig();
		$cfg = $dbc->getConn($ctype);
		if ($this->pconnect($cfg["hostname"], $cfg["dbname"], $cfg["username"], $cfg["password"])) {
			return $this->dbUpdate($query);
		} else {
			return false;
		}
	}

	function deleteQry($query,$ctype="delete") {
		return updateQry($query,$ctype);
	}

	function getFieldStr($arr) {
		$fields = "";
		foreach ($arr as $k => $v) $fields .= "$k,";
		if ($fields != "") $fields = substr($fields, 0, strlen($fields)-1); 
		return $fields;
	}

	function getValueStr($arr) {
		$values = "";
		$odate = new datex();
		foreach ($arr as $k => $v) {
			$v = addslashes(trim($v));
			if ($odate->isUsaDate($v)) $v = $odate->isoDate($v);
			$values .= "'$v',";
		}
		if ($values != "") $values = substr($values, 0, strlen($values)-1); 
		return $values;
	}

	function insertQry($query,$ctype="insert") {
		$dbc = new dbConfig();
		$cfg = $dbc->getConn($ctype);
		$this->last_id = 0;
		if ($this->pconnect($cfg["hostname"], $cfg["dbname"], $cfg["username"], $cfg["password"])) {
			if ($rs = $this->dbUpdate($query)) {
				$this->last_id = $this->getLastId();
				if ($this->last_id>0) return $this->last_id;
				else return true;
			}
			else return false;
		} else {
			return false;
		}
	}




}
?>