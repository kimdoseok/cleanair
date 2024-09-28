<?php
include_once("class.ap.php");

class Disburs extends AP {

	function insertDisburs($arr) {
		if ($lastid = $this->insertAP("disburs", $arr)) return $lastid;
		return false;
	}

	function updateDisburs($code, $arr) {
		if ($this->updateAP("disburs", "disbur_id", $code, $arr)) return true;
		return false;
	}

	function deleteDisburs($code) {
		$query = "delete from disburs where disbur_id='$code'";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function updateDisbursAmt($code, $newamt, $oldamt, $flg) {
		$amt = $newamt - $oldamt;
		if ($flg == "t") {
			$this->query = "UPDATE disburs SET x = x + $amt WHERE disbur_id='$code' ";
		} else {
			$this->query = "UPDATE disburs SET x = x + $amt WHERE disbur_id='$code' ";
		}
		if ($this->updateAPRaw()) return true;
		else return false;
	}

	function getDisburs($code) {
		$this->query = "SELECT * FROM disburs WHERE disbur_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getDisbursFields() {
		$this->query = "SELECT * FROM disburs LIMIT 0 ";
		if ($arr = $this->getAPFields($this->query)) return $arr;
		return false;
	}

	function getLastDisburs($filter="") {
		$this->query = "SELECT * FROM disburs ORDER BY disbur_id DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstDisburs($filter="") {
		$this->query = "SELECT * FROM disburs ORDER BY disbur_id LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextDisburs($code, $filter="") {
		$this->query = "SELECT * FROM disburs WHERE disbur_id > '$code' ORDER BY disbur_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevDisburs($code, $filter="") {
		$this->query = "SELECT * FROM disburs WHERE disbur_id  < '$code' ORDER BY disbur_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getDisbursFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getDisburs($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevDisburs($code, $filter);
					if (!$rec) $rec = $this->getFirstDisburs($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextDisburs($code, $filter);
					if (!$rec) $rec = $this->getLastDisburs($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstDisburs($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastDisburs($filter);
				} else {
					$rec = $this->getDisburs($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastDisburs($filter);
				} else {
					$rec = $this->getFirstDisburs($filter);
				}
			}
		}
		return $rec;
	}

	function getDisbursList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM disburs ORDER BY disbur_id  ";
			else if ($condition == "desc") $this->query = "SELECT * FROM disburs ORDER BY disbur_desc  ";
			else $this->query = "SELECT * FROM disburs ORDER BY disbur_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM disburs WHERE disbur_id  LIKE '$filtertext%' ORDER BY disbur_id ";
			else if ($condition == "desc") $this->query = "SELECT * FROM disburs WHERE disbur_desc  LIKE '$filtertext%' ORDER BY disbur_desc  ";
			else $this->query = "SELECT * FROM disburs ORDER BY disbur_id ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAP();
	}

	function getDisbursRows() {
		$this->query = "SELECT count(disbur_id) AS numrows FROM disburs ";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>