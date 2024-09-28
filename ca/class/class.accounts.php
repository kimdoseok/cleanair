<?php
include_once("class.gl.php");

class Accts extends GL {

	function insertAccts($arr) {
		if ($lastid = $this->insertGL("accts", $arr)) return $lastid;
		return false;
	}

	function updateAccts($code, $arr) {
		if ($this->updateGL("accts", "acct_code", $code, $arr)) return true;
		return false;
	}

	function deleteAccts($code) {
		$query = "delete from accts where acct_code='$code'";
		if ($this->updateGLRaw($query)) return true;
		return false;
	}

	function getAccts($code) {
		$this->query = "SELECT * FROM accts WHERE acct_code = '$code' LIMIT 1 ";
		if ($arr = $this->getGL($code)) return $arr[0];
		return false;
	}

	function getAcctsFields() {
		$this->query = "SELECT * FROM accts LIMIT 0 ";
		if ($arr = $this->getGLFields()) return $arr;
		return false;
	}

	function getLastAccts($filter="") {
		$this->query = "SELECT * FROM accts ORDER BY acct_code DESC LIMIT 1 ";
		$arr = $this->getGL();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstAccts($filter="") {
		$this->query = "SELECT * FROM accts ORDER BY acct_code LIMIT 1 ";
		$arr = $this->getGL();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextAccts($code, $filter="") {
		$this->query = "SELECT * FROM accts WHERE acct_code > '$code' ORDER BY acct_code LIMIT 1 ";
		$arr = $this->getGL($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevAccts($code, $filter="") {
		$this->query = "SELECT * FROM accts WHERE acct_code  < '$code' ORDER BY acct_code DESC LIMIT 1 ";
		$arr = $this->getGL($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getAcctsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getAccts($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevAccts($code, $filter);
					if (!$rec) $rec = $this->getFirstAccts($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextAccts($code, $filter);
					if (!$rec) $rec = $this->getLastAccts($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstAccts($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastAccts($filter);
				} else {
					$rec = $this->getAccts($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastAccts($filter);
				} else {
					$rec = $this->getFirstAccts($filter);
				}
			}
		}
		return $rec;
	}

	function getAcctsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "as") $this->query = "SELECT * FROM accts WHERE acct_type='as' ORDER BY acct_code  ";
			else if ($condition == "li") $this->query = "SELECT * FROM accts WHERE acct_type='li' ORDER BY acct_code  ";
			else if ($condition == "eq") $this->query = "SELECT * FROM accts WHERE acct_type='eq' ORDER BY acct_code  ";
			else if ($condition == "in") $this->query = "SELECT * FROM accts WHERE acct_type='in' ORDER BY acct_code  ";
			else if ($condition == "cs") $this->query = "SELECT * FROM accts WHERE acct_type='cs' ORDER BY acct_code  ";
			else if ($condition == "ex") $this->query = "SELECT * FROM accts WHERE acct_type='ex' ORDER BY acct_code  ";
			else if ($condition == "mi") $this->query = "SELECT * FROM accts WHERE acct_type='mi' ORDER BY acct_code  ";
			else if ($condition == "me") $this->query = "SELECT * FROM accts WHERE acct_type='me' ORDER BY acct_code  ";
			else $this->query = "SELECT * FROM accts ORDER BY acct_code ";
		} else {
			if ($condition == "as") $this->query = "SELECT * FROM accts WHERE acct_type='as' AND acct_code  LIKE '$filtertext%' ORDER BY acct_code  ";
			else if ($condition == "li") $this->query = "SELECT * FROM accts WHERE acct_type='li' AND acct_code  LIKE '$filtertext%' ORDER BY acct_code  ";
			else if ($condition == "eq") $this->query = "SELECT * FROM accts WHERE acct_type='eq' AND acct_code  LIKE '$filtertext%' ORDER BY acct_code  ";
			else if ($condition == "in") $this->query = "SELECT * FROM accts WHERE acct_type='in' AND acct_code  LIKE '$filtertext%' ORDER BY acct_code  ";
			else if ($condition == "cs") $this->query = "SELECT * FROM accts WHERE acct_type='cs' AND acct_code  LIKE '$filtertext%' ORDER BY acct_code  ";
			else if ($condition == "ex") $this->query = "SELECT * FROM accts WHERE acct_type='ex' AND acct_code  LIKE '$filtertext%' ORDER BY acct_code  ";
			else if ($condition == "mi") $this->query = "SELECT * FROM accts WHERE acct_type='mi' AND acct_code  LIKE '$filtertext%' ORDER BY acct_code  ";
			else if ($condition == "me") $this->query = "SELECT * FROM accts WHERE acct_type='me' AND acct_code  LIKE '$filtertext%' ORDER BY acct_code  ";
			else $this->query = "SELECT * FROM accts WHERE acct_code LIKE '$filtertext%' ORDER BY acct_code ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getGL();
	}

	function getAcctsRows($condition="", $filtertext="") {
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "as") $where = "WHERE acct_type='as'";
			else if ($condition == "li") $where = "WHERE acct_type='li'";
			else if ($condition == "eq") $where = "WHERE acct_type='eq'";
			else if ($condition == "in") $where = "WHERE acct_type='in'";
			else if ($condition == "cs") $where = "WHERE acct_type='cs'";
			else if ($condition == "ex") $where = "WHERE acct_type='ex'";
			else if ($condition == "mi") $where = "WHERE acct_type='mi'";
			else if ($condition == "me") $where = "WHERE acct_type='me'";
			else $where = "";
		} else {
			if ($condition == "as") $where = "WHERE acct_type='as' AND acct_code LIKE '$filtertext%'";
			else if ($condition == "li") $where = "WHERE acct_type='li' AND acct_code LIKE '$filtertext%'";
			else if ($condition == "eq") $where = "WHERE acct_type='eq' AND acct_code LIKE '$filtertext%'";
			else if ($condition == "in") $where = "WHERE acct_type='in' AND acct_code LIKE '$filtertext%'";
			else if ($condition == "cs") $where = "WHERE acct_type='cs' AND acct_code LIKE '$filtertext%'";
			else if ($condition == "ex") $where = "WHERE acct_type='ex' AND acct_code LIKE '$filtertext%'";
			else if ($condition == "mi") $where = "WHERE acct_type='mi' AND acct_code LIKE '$filtertext%'";
			else if ($condition == "me") $where = "WHERE acct_type='me' AND acct_code LIKE '$filtertext%'";
			else $where = "WHERE acct_code LIKE '$filtertext%'";
		}
		$this->query = "SELECT count(acct_code) AS numrows FROM accts $where";
		$arr = $this->getGL();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>