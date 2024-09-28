<?php
include_once("class.ap.php");

class PurHists extends AP {

	function insertPurHists($arr) {
		if ($lastid = $this->insertAP("purhists", $arr)) return $lastid;
		return false;
	}

	function deletePurHists($code) {
		$this->query = "DELETE FROM purhists WHERE purhist_id='$code' ";
		if ($this->updateAPRaw()) return true;
		else return false;
	}

	function getPurHists($code) {
		$this->query = "SELECT * FROM purhists WHERE purhist_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getLastPurHists($filter="") {
		$this->query = "SELECT * FROM purhists ORDER BY purhist_id DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPurHists($filter="") {
		$this->query = "SELECT * FROM purhists ORDER BY purhist_id LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPurHists($code, $filter="") {
		$this->query = "SELECT * FROM purhists WHERE purhist_id > '$code' ORDER BY purhist_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPurHists($code, $filter="") {
		$this->query = "SELECT * FROM purhists WHERE purhist_id < '$code' ORDER BY purhist_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPurHistsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPurHists($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPurHists($code, $filter);
					if (!$rec) $rec = $this->getFirstPurHists($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPurHists($code, $filter);
					if (!$rec) $rec = $this->getLastPurHists($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPurHists($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPurHists($filter);
				} else {
					$rec = $this->getPurHists($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPurHists($filter);
				} else {
					$rec = $this->getFirstPurHists($filter);
				}
			}
		}
		return $rec;
	}

	function getPurHistsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM purhists ORDER BY purhist_id ";
			else if ($condition == "sale") $this->query = "SELECT * FROM purhists ORDER BY purhist_purch_id ";
			else $this->query = "SELECT * FROM purhists ORDER BY purhist_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM purhists WHERE purhist_id LIKE '$filtertext%' ORDER BY purhist_id ";
			else if ($condition == "sale") $this->query = "SELECT * FROM purhists WHERE purhist_purch_id LIKE '$filtertext%' ORDER BY purhist_purch_id ";
			else $this->query = "SELECT * FROM purhists WHERE purhist_id LIKE '$filtertext%' ORDER BY purhist_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAP();
	}


	function getPurHistsRows($condition="", $filtertext="") {
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(purhist_id) AS numrows FROM purhists ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(purhist_id) AS numrows FROM purhists WHERE cust_code  LIKE '$filtertext%' ";
			else if ($condition == "sale") $this->query = "SELECT count(purhist_id) AS numrows FROM purhists WHERE cust_name LIKE '$filtertext%' ";
			else $this->query = "SELECT count(cust_code) AS numrows FROM PurHists WHERE cust_code LIKE '$filtertext%' ";
		}
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>