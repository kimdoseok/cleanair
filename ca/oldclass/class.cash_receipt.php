<?php
include_once("class.ar.php");

class Rcpts extends AR {

	function insertRcpts($arr) {
		if ($lastid = $this->insertAR("rcpts", $arr)) return $lastid;
		return false;
	}

	function updateRcpts($code, $arr) {
		if ($this->updateAR("rcpts", "rcpt_id", $code, $arr)) return true;
		return false;
	}

	function deleteRcpts($code) {
		$query = "delete from rcpts where rcpt_id='$code'";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function updateRcptsAmt($code, $newamt, $oldamt, $flg) {
		$amt = $newamt - $oldamt;
		if ($flg == "t") {
			$this->query = "UPDATE rcpts SET x = x + $amt WHERE rcpt_id='$code' ";
		} else {
			$this->query = "UPDATE rcpts SET x = x + $amt WHERE rcpt_id='$code' ";
		}
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function getRcpts($code) {
		$this->query = "SELECT * FROM rcpts WHERE rcpt_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getRcptsFields() {
		$this->query = "SELECT * FROM rcpts LIMIT 0 ";
		if ($arr = $this->getARFields($this->query)) return $arr;
		return false;
	}

	function getLastRcpts($filter="") {
		$this->query = "SELECT * FROM rcpts ORDER BY rcpt_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstRcpts($filter="") {
		$this->query = "SELECT * FROM rcpts ORDER BY rcpt_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextRcpts($code, $filter="") {
		$this->query = "SELECT * FROM rcpts WHERE rcpt_id > '$code' ORDER BY rcpt_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevRcpts($code, $filter="") {
		$this->query = "SELECT * FROM rcpts WHERE rcpt_id  < '$code' ORDER BY rcpt_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getRcptsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getRcpts($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevRcpts($code, $filter);
					if (!$rec) $rec = $this->getFirstRcpts($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextRcpts($code, $filter);
					if (!$rec) $rec = $this->getLastRcpts($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstRcpts($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastRcpts($filter);
				} else {
					$rec = $this->getRcpts($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastRcpts($filter);
				} else {
					$rec = $this->getFirstRcpts($filter);
				}
			}
		}
		return $rec;
	}

	function getRcptsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM rcpts ORDER BY rcpt_id  ";
			else if ($condition == "desc") $this->query = "SELECT * FROM rcpts ORDER BY rcpt_desc  ";
			else $this->query = "SELECT * FROM rcpts ORDER BY rcpt_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM rcpts WHERE rcpt_id  LIKE '$filtertext%' ORDER BY rcpt_id ";
			else if ($condition == "desc") $this->query = "SELECT * FROM rcpts WHERE rcpt_desc  LIKE '$filtertext%' ORDER BY rcpt_desc  ";
			else $this->query = "SELECT * FROM rcpts ORDER BY rcpt_id ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getRcptsRows() {
		$this->query = "SELECT count(rcpt_id) AS numrows FROM rcpts ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>