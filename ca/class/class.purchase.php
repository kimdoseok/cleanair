<?php
include_once("class.ap.php");

class Purchases extends AP {

	function insertPurchase($arr) {
		if ($lastid = $this->insertAP("purchs", $arr)) return $lastid;
		return false;
	}

	function updatePurchase($code, $arr) {
		if ($this->updateAP("purchs", "purch_id", $code, $arr)) return true;
		return false;
	}

	function deletePurchase($code) {
		$query = "delete from purchs where purch_id='$code'";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function getPurchase($code) {
		$this->query = "SELECT * FROM purchs WHERE purch_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getPurchaseFields() {
		$this->query = "SELECT * FROM purchs LIMIT 0 ";
		if ($arr = $this->getAPFields()) return $arr;
		return false;
	}

	function getLastPurchase($filter="") {
		$this->query = "SELECT * FROM purchs ORDER BY purch_id LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPurchase($filter="") {
		$this->query = "SELECT * FROM purchs ORDER BY purch_id DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPurchase($code, $filter="") {
		$this->query = "SELECT * FROM purchs WHERE purch_id > '$code' ORDER BY purch_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPurchase($code, $filter="") {
		$this->query = "SELECT * FROM purchs WHERE purch_id  < '$code' ORDER BY purch_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPurchaseFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPurchase($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPurchase($code, $filter);
					if (!$rec) $rec = $this->getFirstPurchase($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPurchase($code, $filter);
					if (!$rec) $rec = $this->getLastPurchase($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPurchase($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPurchase($filter);
				} else {
					$rec = $this->getPurchase($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPurchase($filter);
				} else {
					$rec = $this->getFirstPurchase($filter);
				}
			}
		}
		return $rec;
	}

	function getPurchaseList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM purchs ORDER BY purch_id  ";
			else if ($condition == "cust") $this->query = "SELECT * FROM purchs ORDER BY purch_cust_code  ";
			else if ($condition == "sale") $this->query = "SELECT * FROM purchs ORDER BY purch_sale_id  ";
			else if ($condition == "vend") $this->query = "SELECT * FROM purchs ORDER BY purch_vend_code  ";
			else if ($condition == "date") $this->query = "SELECT * FROM purchs ORDER BY purch_date  ";
			else if ($condition == "tel") $this->query = "SELECT * FROM purchs ORDER BY purch_tel  ";
			else $this->query = "SELECT * FROM purchs ORDER BY purch_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM purchs WHERE purch_id LIKE '$filtertext%' ORDER BY purch_id ";
			else if ($condition == "cust") $this->query = "SELECT * FROM purchs WHERE purch_cust_code  LIKE '$filtertext%' ORDER BY purch_cust_code ";
			else if ($condition == "sale") $this->query = "SELECT * FROM purchs WHERE purch_sale_id  LIKE '$filtertext%' ORDER BY purch_sale_id ";
			else if ($condition == "vend") $this->query = "SELECT * FROM purchs WHERE purch_vend_code  LIKE '$filtertext%' ORDER BY purch_vend_code ";
			else if ($condition == "date") $this->query = "SELECT * FROM purchs WHERE purch_date = '$filtertext' ORDER BY purch_date  ";
			else if ($condition == "tel") $this->query = "SELECT * FROM purchs WHERE purch_tel  LIKE '$filtertext%' ORDER BY purch_tel ";
			else $this->query = "SELECT * FROM purchs WHERE purch_id LIKE '$filtertext%' ORDER BY purch_id ";
		}
		if ($reverse == "t") {
			if ($condition == "code") $this->query .= " ";
			else if ($condition == "cust") $this->query .= " DESC, purch_id DESC ";
			else if ($condition == "sale") $this->query .= " DESC, purch_id DESC ";
			else if ($condition == "vend") $this->query .= " DESC, purch_id DESC ";
			else if ($condition == "date") $this->query .= " , purch_id DESC ";
			else if ($condition == "tel") $this->query .= " purch, sale_id DESC ";
			else $this->query .= " ";
		} else {
			if ($condition == "code") $this->query .= " DESC ";
			else if ($condition == "cust") $this->query .= " , purch_id DESC ";
			else if ($condition == "sale") $this->query .= " , purch_id DESC ";
			else if ($condition == "vend") $this->query .= " , purch_id DESC ";
			else if ($condition == "date") $this->query .= " DESC, purch_id DESC";
			else if ($condition == "tel") $this->query .= " , purch_id DESC";
			else $this->query .= " DESC ";
		}
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAP();
	}

	function getPurchaseRows($vend="") {
		$where = "";
		if (!empty($vend)) if ($vend != "all") $where = " WHERE purch_user_code = '$vend' ";
		$this->query = "SELECT count(purch_id) AS numrows FROM purchs $where";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPurchaseListSales($code) {
		//if ($page < 1) $page = 0;
		//else $page--;
		$this->query = "SELECT * FROM purchs WHERE purch_sale_id='$code' ORDER BY purch_id ";
		return $this->getAPRaw();
	}

}

?>