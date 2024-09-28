<?php
include_once("class.sy.php");

class Users extends SY {

	function insertUsers($arr) {
		if ($lastid = $this->insertSY("users", $arr)) return $lastid;
		return false;
	}

	function updateUsers($code, $arr) {
		if ($this->updateSY("users", "user_code", $code, $arr)) return true;
		return false;
	}

	function deleteUsers($code) {
		$this->query = "DELETE FROM users WHERE user_code='$code' ";
		//echo $this->query;
		if ($this->updateSYRaw()) return true;
		else return false;
	}

	function getUsers($code) {
		$this->query = "SELECT * FROM users WHERE user_code = '$code' LIMIT 1 ";
		//echo $this->query;
		if ($arr = $this->getSY($code)) return $arr[0];
		return false;
	}

	function getUsersFields() {
		$this->query = "SELECT * FROM users LIMIT 0 ";
		if ($arr = $this->getSYFields($this->query)) return $arr;
		return false;
	}

	function getLastUsers($filter="") {
		$this->query = "SELECT * FROM users ORDER BY user_code DESC LIMIT 1 ";
		$arr = $this->getSY();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstUsers($filter="") {
		$this->query = "SELECT * FROM users ORDER BY user_code LIMIT 1 ";
		$arr = $this->getSY();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextUsers($code, $filter="") {
		$this->query = "SELECT * FROM users WHERE user_code > '$code' ORDER BY user_code LIMIT 1 ";
		$arr = $this->getSY($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevUsers($code, $filter="") {
		$this->query = "SELECT * FROM users WHERE user_code  < '$code' ORDER BY user_code DESC LIMIT 1 ";
		$arr = $this->getSY($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function checkUser($userid,$passwd) {
		$this->query = sprintf("SELECT user_code FROM users WHERE user_code='%s' AND user_passwd='%s' ",addslashes($userid),addslashes($passwd));
		if ($this->getSY()) return true;
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getusersFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getUsers($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevUsers($code, $filter);
					if (!$rec) $rec = $this->getFirstUsers($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextUsers($code, $filter);
					if (!$rec) $rec = $this->getLastUsers($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstUsers($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastUsers($filter);
				} else {
					$rec = $this->getUsers($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastUsers($filter);
				} else {
					$rec = $this->getFirstUsers($filter);
				}
			}
		}
		return $rec;
	}

	function getUsersRange($fr="", $to="", $ord="", $rev="t") {
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND user_code >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND user_code <= '$to' ";
		}
		if (!empty($ord)) $orderby = " ORDER BY $ord ";
		else $orderby = " ORDER BY user_code ";
		if ($rev != "t") $orderby .= " DESC ";
		$this->query = "SELECT * FROM users $where $orderby ";
		return $this->getSYRaw();
	}

	function getUsersList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM users ORDER BY user_code  ";
			else if ($condition == "name") $this->query = "SELECT * FROM users ORDER BY user_name  ";
			else $this->query = "SELECT * FROM users ORDER BY user_code ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM users WHERE user_code  LIKE '$filtertext%' ORDER BY user_code ";
			else if ($condition == "name") $this->query = "SELECT * FROM users WHERE user_name  LIKE '$filtertext%' ORDER BY user_name  ";
			else if ($condition == "active") $this->query = "SELECT * FROM users WHERE user_active = $filtertext ORDER BY user_name  ";
			else $this->query = "SELECT * FROM users WHERE user_code  LIKE '$filtertext%' ORDER BY user_code ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getSY();
	}

	function getUsersRows($condition="", $filtertext="") {
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(user_code) AS numrows FROM users ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(user_code) AS numrows FROM users WHERE user_code  LIKE '$filtertext%' ";
			else if ($condition == "name") $this->query = "SELECT count(user_code) AS numrows FROM users WHERE user_name  LIKE '$filtertext%' ";
			else $this->query = "SELECT count(user_code) AS numrows FROM users WHERE user_code  LIKE '$filtertext%' ";
		}
		$arr = $this->getSY();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>