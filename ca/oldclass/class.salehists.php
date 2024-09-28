<?php
include_once("class.ar.php");

class SaleHists extends AR {

	function insertSaleHists($arr) {
		if ($lastid = $this->insertAR("salehists", $arr)) return $lastid;
		return false;
	}

	function deleteSaleHists($code) {
		$this->query = "DELETE FROM salehists WHERE salehist_id='$code' ";
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function getLastEditUser($code) {
		$this->query = "SELECT * FROM salehists WHERE salehist_type IN('u', 'd') AND salehist_sale_id = '$code' ORDER BY salehist_id DESC LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getSaleHists($code) {
		$this->query = "SELECT * FROM salehists WHERE salehist_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getLastSaleHists($filter="") {
		$this->query = "SELECT * FROM salehists ORDER BY salehist_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstSaleHists($filter="") {
		$this->query = "SELECT * FROM salehists ORDER BY salehist_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextSaleHists($code, $filter="") {
		$this->query = "SELECT * FROM salehists WHERE salehist_id > '$code' ORDER BY salehist_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevSaleHists($code, $filter="") {
		$this->query = "SELECT * FROM salehists WHERE salehist_id < '$code' ORDER BY salehist_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getSaleHistsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getSaleHists($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevSaleHists($code, $filter);
					if (!$rec) $rec = $this->getFirstSaleHists($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextSaleHists($code, $filter);
					if (!$rec) $rec = $this->getLastSaleHists($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstSaleHists($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastSaleHists($filter);
				} else {
					$rec = $this->getSaleHists($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastSaleHists($filter);
				} else {
					$rec = $this->getFirstSaleHists($filter);
				}
			}
		}
		return $rec;
	}

	function getSaleHistsReport($sid="", $eid="", $sdt="", $edt="") {
		$this->query = "SELECT * FROM salehists WHERE 1 ";
		if (!empty($sid)) $this->query .= " AND salehist_sale_id>=$sid "; 
		if (!empty($eid)) $this->query .= " AND salehist_sale_id<=$eid "; 
		if (!empty($sdt)) $this->query .= " AND salehist_modified>='$sdt' "; 
		if (!empty($edt)) $this->query .= " AND salehist_modified<='$edt' "; 
		$this->query .= " ORDER BY salehist_sale_id, salehist_modified ";
		//print $this->query;
		return $this->getARRaw();
	}

	function getSaleHistsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM salehists ORDER BY salehist_id ";
			else if ($condition == "sale") $this->query = "SELECT * FROM salehists ORDER BY salehist_sale_id ";
			else $this->query = "SELECT * FROM salehists ORDER BY salehist_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM salehists WHERE salehist_id LIKE '$filtertext%' ORDER BY salehist_id ";
			else if ($condition == "sale") $this->query = "SELECT * FROM salehists WHERE salehist_sale_id LIKE '$filtertext%' ORDER BY salehist_sale_id ";
			else $this->query = "SELECT * FROM salehists WHERE salehist_id LIKE '$filtertext%' ORDER BY salehist_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}


	function getSaleHistsRows($condition="", $filtertext="") {
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(salehist_id) AS numrows FROM salehists ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(salehist_id) AS numrows FROM salehists WHERE cust_code  LIKE '$filtertext%' ";
			else if ($condition == "sale") $this->query = "SELECT count(salehist_id) AS numrows FROM salehists WHERE cust_name LIKE '$filtertext%' ";
			else $this->query = "SELECT count(cust_code) AS numrows FROM SaleHists WHERE cust_code LIKE '$filtertext%' ";
		}
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>