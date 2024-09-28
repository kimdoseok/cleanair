<?php
include_once("class.sy.php");

class Auths extends SY {

	function insertAuths($arr) {
		if ($lastid = $this->insertSY("auths", $arr)) return $lastid;
		return false;
	}

	function updateAuths($code, $arr) {
		if ($this->updateSY("auths", "auth_code", $code, $arr)) return true;
		return false;
	}

	function deleteAuths($code) {
		$this->query = "DELETE FROM auths WHERE auth_code='$code' ";
		if ($this->updateSYRaw()) return true;
		else return false;
	}

	function getAuths($code) {
		$this->query = "SELECT * FROM auths WHERE auth_code = '$code' LIMIT 1 ";
		if ($arr = $this->getSY($code)) return $arr[0];
		return false;
	}

	function getAuthsFields() {
		$this->query = "SELECT * FROM auths LIMIT 0 ";
		if ($arr = $this->getSYFields($this->query)) return $arr;
		return false;
	}

	function getLastAuths($filter="") {
		$this->query = "SELECT * FROM auths ORDER BY auth_code DESC LIMIT 1 ";
		$arr = $this->getSY();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstAuths($filter="") {
		$this->query = "SELECT * FROM auths ORDER BY auth_code LIMIT 1 ";
		$arr = $this->getSY();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextAuths($code, $filter="") {
		$this->query = "SELECT * FROM auths WHERE auth_code > '$code' ORDER BY auth_code LIMIT 1 ";
		$arr = $this->getSY($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevAuths($code, $filter="") {
		$this->query = "SELECT * FROM auths WHERE auth_code  < '$code' ORDER BY auth_code DESC LIMIT 1 ";
		$arr = $this->getSY($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getauthsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getAuths($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevAuths($code, $filter);
					if (!$rec) $rec = $this->getFirstAuths($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextAuths($code, $filter);
					if (!$rec) $rec = $this->getLastAuths($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstAuths($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastAuths($filter);
				} else {
					$rec = $this->getAuths($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastAuths($filter);
				} else {
					$rec = $this->getFirstAuths($filter);
				}
			}
		}
		return $rec;
	}

	function getAuthsRange($fr="", $to="", $ord="", $rev="t") {
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND auth_code >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND auth_code <= '$to' ";
		}
		if (!empty($ord)) $orderby = " ORDER BY $ord ";
		else $orderby = " ORDER BY auth_code ";
		if ($rev != "t") $orderby .= " DESC ";
		$this->query = "SELECT * FROM auths $where $orderby ";
		return $this->getSYRaw();
	}

	function getAuthsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM auths ORDER BY auth_code  ";
			else if ($condition == "name") $this->query = "SELECT * FROM auths ORDER BY auth_name  ";
			else $this->query = "SELECT * FROM auths ORDER BY auth_code ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM auths WHERE auth_code  LIKE '$filtertext%' ORDER BY auth_code ";
			else if ($condition == "name") $this->query = "SELECT * FROM auths WHERE auth_name  LIKE '$filtertext%' ORDER BY auth_name  ";
			else $this->query = "SELECT * FROM auths WHERE auth_code  LIKE '$filtertext%' ORDER BY auth_code ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getSY();
	}

	function getAuthsRows($condition="", $filtertext="") {
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(auth_code) AS numrows FROM auths ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(auth_code) AS numrows FROM auths WHERE auth_code  LIKE '$filtertext%' ";
			else if ($condition == "name") $this->query = "SELECT count(auth_code) AS numrows FROM auths WHERE auth_name  LIKE '$filtertext%' ";
			else $this->query = "SELECT count(auth_code) AS numrows FROM auths WHERE auth_code  LIKE '$filtertext%' ";
		}
		$arr = $this->getSY();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>