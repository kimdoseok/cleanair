<?php
include_once("class.dbutils.php");

class Memos {
	var $query;
	var $numrows;

	function updateARRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
		if ($dbu->updateQry($this->query)) return true;
		else return false;
	}

	function insertARRaw($query="") {
		$dbu = new Dbutils();
		if (!empty($query)) $this->query = $query;
		if ($id = $dbu->insertQry($this->query)) return $id;
		else return false;
	}

//------------------------------------------------------------------------------------------
	function insertMemos($arr) {
		$dbu = new Dbutils();
		$field = $dbu->getFieldStr($arr);
		$value = $dbu->getValueStr($arr);
		$this->query = "INSERT INTO memos ($field) VALUES ($value) ";
		if ($dbu->insertQry($this->query)) return $dbu->last_id;
		return false;
	}

	function updateMemos($code, $arr) {

		$dbu = new Dbutils();
		$pair = $dbu->getPairStr($arr);
		if (!empty($pair)) {
			$this->query = "UPDATE memos SET $pair WHERE (memo_user_code='$_SERVER["PHP_AUTH_USER"]' OR memo_rcpt_code='$code' OR memo_rcpt_code='ALL') AND memo_id = '$code' ";
			if ($dbu->updateQry($this->query)) return true;
		}
		return false;
	}

	function deleteMemos($code) {
		$dbu = new Dbutils();
		$this->query = "DELETE FROM memos WHERE (memo_user_code='$_SERVER["PHP_AUTH_USER"]' OR memo_rcpt_code='$code' OR memo_rcpt_code='ALL') AND memo_id = '$code' ";
		if ($dbu->updateQry($this->query)) return true;
		return false;
	}

	function getMemos($code) {
		$this->query = "SELECT * FROM memos WHERE (memo_user_code='$_SERVER["PHP_AUTH_USER"]' OR memo_rcpt_code='$code' OR memo_rcpt_code='ALL') AND memo_id = '$code' LIMIT 1 ";
		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values[0];
		return false;
	}

	function getMemosFields() {
		$this->query = "SELECT * FROM memos LIMIT 0 ";
		$dbu = new Dbutils();
		$dbu->selectQry($this->query);
		if (count($dbu->fields)>0) return $dbu->fields;
		return false;
	}

	function getLastMemos($filter="") {
		$this->query = "SELECT * FROM memos WHERE memo_user_code='$_SERVER["PHP_AUTH_USER"]' OR memo_rcpt_code='$code' OR memo_rcpt_code='ALL' ORDER BY memo_id DESC LIMIT 1 ";
		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values[0];
		return false;
	}

	function getFirstMemos($filter="") {
		$this->query = "SELECT * FROM memos WHERE memo_user_code='$_SERVER["PHP_AUTH_USER"]' OR memo_rcpt_code='$code' OR memo_rcpt_code='ALL' ORDER BY memo_id LIMIT 1 ";
		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values[0];
		return false;
	}

	function getNextMemos($code, $filter="") {
		$this->query = "SELECT * FROM memos WHERE (memo_user_code='$_SERVER["PHP_AUTH_USER"]' OR memo_rcpt_code='$code' OR memo_rcpt_code='ALL') AND memo_id > '$code' ORDER BY memo_id LIMIT 1 ";
		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values[0];
		return false;
	}

	function getPrevMemos($code, $filter="") {
		$this->query = "SELECT * FROM memos WHERE (memo_user_code='$_SERVER["PHP_AUTH_USER"]' OR memo_rcpt_code='$code' OR memo_rcpt_code='ALL') AND memo_id  < '$code' ORDER BY memo_id DESC LIMIT 1 ";
		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getMemosFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getMemos($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevMemos($code, $filter);
					if (!$rec) $rec = $this->getFirstMemos($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextMemos($code, $filter);
					if (!$rec) $rec = $this->getLastMemos($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstMemos($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastMemos($filter);
				} else {
					$rec = $this->getMemos($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastMemos($filter);
				} else {
					$rec = $this->getFirstMemos($filter);
				}
			}
		}
		return $rec;
	}

	function getMemosList($code, $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$this->query = "SELECT * FROM memos WHERE memo_user_code='$_SERVER["PHP_AUTH_USER"]' OR memo_rcpt_code='$code' OR memo_rcpt_code='ALL' ORDER BY memo_id DESC ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";

		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values;
		return false;
	}

	function getMemosRows($code) {
		$this->query = "SELECT count(memo_id) AS numrows FROM memos WHERE memo_user_code='$_SERVER["PHP_AUTH_USER"]' OR memo_rcpt_code='$code' OR memo_rcpt_code='ALL' ";
		$dbu = new Dbutils();
		if ($dbu->selectQry($this->query)) return $dbu->values[0]["numrows"];
		return false;
	}


}

?>