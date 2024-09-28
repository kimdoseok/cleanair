<?php
include_once("class.ap.php");

class PorHists extends AP {

	function insertPorHists($arr) {
		if ($lastid = $this->insertAP("porhists", $arr)) return $lastid;
		return false;
	}

	function deletePorHists($code) {
		$this->query = "DELETE FROM porhists WHERE porhist_id='$code' ";
		if ($this->updateAPRaw()) return true;
		else return false;
	}

	function getPorHists($code) {
		$this->query = "SELECT * FROM porhists WHERE porhist_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getLastPorHists($filter="") {
		$this->query = "SELECT * FROM porhists ORDER BY porhist_id DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPorHists($filter="") {
		$this->query = "SELECT * FROM porhists ORDER BY porhist_id LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPorHists($code, $filter="") {
		$this->query = "SELECT * FROM porhists WHERE porhist_id > '$code' ORDER BY porhist_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPorHists($code, $filter="") {
		$this->query = "SELECT * FROM porhists WHERE porhist_id < '$code' ORDER BY porhist_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPorHistsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPorHists($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPorHists($code, $filter);
					if (!$rec) $rec = $this->getFirstPorHists($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPorHists($code, $filter);
					if (!$rec) $rec = $this->getLastPorHists($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPorHists($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPorHists($filter);
				} else {
					$rec = $this->getPorHists($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPorHists($filter);
				} else {
					$rec = $this->getFirstPorHists($filter);
				}
			}
		}
		return $rec;
	}

	function getPorHistsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM porhists ORDER BY porhist_id ";
			else if ($condition == "sale") $this->query = "SELECT * FROM porhists ORDER BY porhist_porcpt_id ";
			else $this->query = "SELECT * FROM porhists ORDER BY porhist_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM porhists WHERE porhist_id LIKE '$filtertext%' ORDER BY porhist_id ";
			else if ($condition == "sale") $this->query = "SELECT * FROM porhists WHERE porhist_porcpt_id LIKE '$filtertext%' ORDER BY porhist_porcpt_id ";
			else $this->query = "SELECT * FROM porhists WHERE porhist_id LIKE '$filtertext%' ORDER BY porhist_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAP();
	}


	function getPorHistsRows($condition="", $filtertext="") {
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(porhist_id) AS numrows FROM porhists ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(porhist_id) AS numrows FROM porhists WHERE cust_code  LIKE '$filtertext%' ";
			else if ($condition == "sale") $this->query = "SELECT count(porhist_id) AS numrows FROM porhists WHERE cust_name LIKE '$filtertext%' ";
			else $this->query = "SELECT count(cust_code) AS numrows FROM PorHists WHERE cust_code LIKE '$filtertext%' ";
		}
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>