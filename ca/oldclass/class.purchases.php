<?php
include_once("class.ap.php");

class Purchases extends AP {

	function insertPurchases($arr) {
		if ($lastid = $this->insertAP("purchs", $arr)) return $lastid;
		return false;
	}

	function updatePurchases($code, $arr) {
		if ($this->updateAP("purchs", "purch_id", $code, $arr)) return true;
		return false;
	}

	function deletePurchases($code) {
		$query = "delete from purchs where purch_id='$code'";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function getPurchases($code) {
		$this->query = "SELECT * FROM purchs WHERE purch_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getPurchasesFields() {
		$this->query = "SELECT * FROM purchs LIMIT 0 ";
		if ($arr = $this->getAPFields()) return $arr;
		return false;
	}

	function getLastPurchases($filter="") {
		$this->query = "SELECT * FROM purchs ORDER BY purch_id LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPurchases($filter="") {
		$this->query = "SELECT * FROM purchs ORDER BY purch_id DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPurchases($code, $filter="") {
		$this->query = "SELECT * FROM purchs WHERE purch_id > '$code' ORDER BY purch_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPurchases($code, $filter="") {
		$this->query = "SELECT * FROM purchs WHERE purch_id  < '$code' ORDER BY purch_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPurchasesFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPurchases($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPurchases($code, $filter);
					if (!$rec) $rec = $this->getFirstPurchases($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPurchases($code, $filter);
					if (!$rec) $rec = $this->getLastPurchases($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPurchases($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPurchases($filter);
				} else {
					$rec = $this->getPurchases($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPurchases($filter);
				} else {
					$rec = $this->getFirstPurchases($filter);
				}
			}
		}
		return $rec;
	}

	function getPurchasesList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$this->query = "SELECT * FROM purchs ORDER BY purch_id ";
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAP();
	}

	function getPurchasesRows($vend="") {
		if (!empty($vend)) if ($vend != "all") $where = " WHERE purch_user_code = '$vend' ";
		$this->query = "SELECT count(purch_id) AS numrows FROM purchs $where";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>