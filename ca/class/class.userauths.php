<?php
include_once("class.sy.php");

class UserAuths extends SY {

	function insertUserAuths($arr) {
		if ($lastid = $this->insertSY("userauths", $arr)) return $lastid;
		return false;
	}

	function updateUserAuths($code, $arr) {
		if ($this->updateSY("userauths", "userauth_id", $code, $arr)) return true;
		return false;
	}

	function deleteUserAuths($code) {
		$this->query = "DELETE FROM userauths WHERE userauth_id='$code' ";
		if ($this->updateSYRaw()) return true;
		else return false;
	}

	function deleteUserAuthsAll() {
		$this->query = "DELETE FROM userauths ";
		if ($this->updateSYRaw()) return true;
		else return false;
	}

	function deleteUserAuthsByAuth($code) {
		$this->query = "DELETE FROM userauths WHERE userauth_auth_id='$code' ";
		if ($this->updateSYRaw()) return true;
		else return false;
	}

	function deleteUserAuthsByUser($code) {
		$this->query = "DELETE FROM userauths WHERE userauth_user_id='$code' ";
		if ($this->updateSYRaw()) return true;
		else return false;
	}

	function getUserAuths($code) {
		$this->query = "SELECT * FROM userauths WHERE userauth_id = '$code' LIMIT 1 ";
		if ($arr = $this->getSY($code)) return $arr[0];
		return false;
	}

	function getUserAuthsTwoID($user, $auth) {
		$this->query = "SELECT * FROM userauths WHERE userauth_user_id = $user AND userauth_auth_id = $auth LIMIT 1 ";
		if ($arr = $this->getSY($code)) return $arr[0];
		return false;
	}

	function getUserAuthsTwoCode($user, $auth) {
		$this->query = "SELECT * FROM userauths ua, users u, auths a WHERE ua.userauth_user_id=u.user_id AND ua.userauth_auth_id=a.auth_id AND u.user_code = '$user' AND a.auth_code = '$auth' LIMIT 1 ";
//echo $this->query."<br>";
		$arr = $this->getSY("");
		if ($arr) return $arr[0];
		return false;
	}

	function getUserAuthsFields() {
		$this->query = "SELECT * FROM userauths LIMIT 0 ";
		if ($arr = $this->getSYFields($this->query)) return $arr;
		return false;
	}

	function getLastUserAuths($filter="") {
		$this->query = "SELECT * FROM userauths ORDER BY userauth_id DESC LIMIT 1 ";
		$arr = $this->getSY();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstUserAuths($filter="") {
		$this->query = "SELECT * FROM userauths ORDER BY userauth_id LIMIT 1 ";
		$arr = $this->getSY();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextUserAuths($code, $filter="") {
		$this->query = "SELECT * FROM userauths WHERE userauth_id > '$code' ORDER BY userauth_id LIMIT 1 ";
		$arr = $this->getSY($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevUserAuths($code, $filter="") {
		$this->query = "SELECT * FROM userauths WHERE userauth_id  < '$code' ORDER BY userauth_id DESC LIMIT 1 ";
		$arr = $this->getSY($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getuserauthsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getUserAuths($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevUserAuths($code, $filter);
					if (!$rec) $rec = $this->getFirstUserAuths($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextUserAuths($code, $filter);
					if (!$rec) $rec = $this->getLastUserAuths($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstUserAuths($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastUserAuths($filter);
				} else {
					$rec = $this->getUserAuths($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastUserAuths($filter);
				} else {
					$rec = $this->getFirstUserAuths($filter);
				}
			}
		}
		return $rec;
	}

	function getUserAuthsRange($fr="", $to="", $ord="", $rev="t") {
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND userauth_id >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND userauth_id <= '$to' ";
		}
		if (!empty($ord)) $orderby = " ORDER BY $ord ";
		else $orderby = " ORDER BY userauth_id ";
		if ($rev != "t") $orderby .= " DESC ";
		$this->query = "SELECT * FROM userauths $where $orderby ";
		return $this->getSYRaw();
	}

	function getUserAuthsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM userauths ORDER BY userauth_id  ";
			else if ($condition == "name") $this->query = "SELECT * FROM userauths ORDER BY userauth_name  ";
			else $this->query = "SELECT * FROM userauths ORDER BY userauth_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM userauths WHERE userauth_id  LIKE '$filtertext%' ORDER BY userauth_id ";
			else if ($condition == "name") $this->query = "SELECT * FROM userauths WHERE userauth_name  LIKE '$filtertext%' ORDER BY userauth_name  ";
			else $this->query = "SELECT * FROM userauths WHERE userauth_id  LIKE '$filtertext%' ORDER BY userauth_id ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getSY();
	}

	function getUserAuthsRows($condition="", $filtertext="") {
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(userauth_id) AS numrows FROM userauths ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(userauth_id) AS numrows FROM userauths WHERE userauth_id  LIKE '$filtertext%' ";
			else if ($condition == "name") $this->query = "SELECT count(userauth_id) AS numrows FROM userauths WHERE userauth_name  LIKE '$filtertext%' ";
			else $this->query = "SELECT count(userauth_id) AS numrows FROM userauths WHERE userauth_id  LIKE '$filtertext%' ";
		}
//echo $this->query;
		$arr = $this->getSY();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>