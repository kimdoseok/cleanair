<?php
include_once("class.ar.php");

class CmemoDtl extends AR {

	function insertCmemoDtl($arr) {
		$lastid = $this->insertAR("cmemodtls", $arr);
//echo "$lastid";
		if ($lastid>0) return $lastid;
		else return false;
	}

	function updateCmemoDtl($code, $arr) {
		if ($this->updateAR("cmemodtls", "cmemodtl_id", $code, $arr)) return true;
		return false;
	}

	function updateCmemoDtlQtyPicked($code, $newqty, $oldqty) {
		$qty = $newqty - $oldqty;
		$this->query = "UPDATE cmemodtls SET cmemodtl_qty_picked = cmemodtl_qty_picked + $qty WHERE cmemodtl_id='$code' ";
		if ($this->updateARRaw($this->query)) return true;
		else return false;
	}

	function deleteCmemoDtlSI($code) {
		$query = "DELETE FROM cmemodtls WHERE cmemodtl_cmemo_id = '$code' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function deleteCmemoDtl($code) {
		$query = "DELETE FROM cmemodtls WHERE cmemodtl_id = '$code' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getCmemoDtl($code) {
		$select = "SELECT s.*, i.item_desc, i.item_tax ";
		$from = "FROM cmemodtls s, items i ";
		$where = "WHERE s.cmemodtl_item_code=i.item_code AND s.cmemodtl_id = '$code' ";
		$limit = "LIMIT 1 ";
		$this->query = "$select $from $where $limit";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getCmemoDtlHdrs($code) {
		$select = "SELECT s.*, i.item_desc, i.item_tax ";
		$from = "FROM cmemodtls s, items i ";
		$where = "WHERE s.cmemodtl_item_code=i.item_code AND s.cmemodtl_cmemo_id = '$code' ";
		$limit = "";
		$this->query = "$select $from $where $limit";
		if ($arr = $this->getARRaw()) return $arr;
		return false;
	}

	function getCmemoDtlFields() {
		$this->query = "SELECT * FROM cmemodtls LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastCmemoDtl($filter) {
		$this->query = "SELECT * FROM cmemodtls WHERE cmemodtl_cmemo_id='$filter' ORDER BY cmemodtl_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstCmemoDtl($filter) {
		$this->query = "SELECT * FROM cmemodtls WHERE cmemodtl_cmemo_id='$filter' ORDER BY cmemodtl_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextCmemoDtl($code, $filter) {
		$this->query = "SELECT * FROM cmemodtls WHERE cmemodtl_id > '$code' AND cmemodtl_cmemo_id='$filter' ORDER BY cmemodtl_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevCmemoDtl($code, $filter) {
		$this->query = "SELECT * FROM cmemodtls WHERE cmemodtl_id  < '$code' AND cmemodtl_cmemo_id='$filter' ORDER BY cmemodtl_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getCmemoDtlFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getCmemoDtl($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevCmemoDtl($code, $filter);
					if (!$rec) $rec = $this->getFirstCmemoDtl($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextCmemoDtl($code, $filter);
					if (!$rec) $rec = $this->getLastCmemoDtl($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstCmemoDtl($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastCmemoDtl($filter);
				} else {
					$rec = $this->getCmemoDtl($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastCmemoDtl($filter);
				} else {
					$rec = $this->getFirstCmemoDtl($filter);
				}
			}
		}
		return $rec;
	}

	function getCmemoDtlRowsAvl($cust_code) {
		$SELECT = " SELECT count(d.cmemodtl_id) AS numrows ";
		$FROM = " FROM cmemos s, cmemodtls d ";
		$WHERE = "WHERE d.cmemodtl_cmemo_id=s.cmemo_id ";
		$WHERE .= " AND d.cmemodtl_qty > d.cmemodtl_qty_picked ";
		$WHERE .= " AND s.cmemo_cust_code='$cust_code' ";
		$this->query = "$SELECT $FROM $WHERE ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getCmemoDtlListAvl($cust_code, $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM cmemodtls d, cmemos s ";
		$where = " WHERE d.cmemodtl_cmemo_id = s.cmemo_id " ;
		$where .= " AND d.cmemodtl_qty > d.cmemodtl_qty_picked ";
		$where .= " AND s.cmemo_cust_code = '$cust_code' ";
		$orderby = " ORDER BY d.cmemodtl_id ";
		if ($reverse != "t") $orderby .= " DESC ";
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit";
		return $this->getAR();
	}

	function getCmemoDtlList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM cmemodtls ";
		$where = " WHERE cmemodtl_cmemo_id='$condition' " ;
		$orderby = " ORDER BY cmemodtl_id ";
		if ($reverse != "t") $orderby .= " DESC ";
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit";
		return $this->getAR();
	}

	function getCmemoDtlHistList($item, $cust, $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$SELECT = " SELECT d.cmemodtl_item_code, d.cmemodtl_qty, d.cmemodtl_cost, h.cmemo_date, h.cmemo_id, i.item_msrp ";
		$FROM = " FROM cmemos h, cmemodtls d, items i ";
		$WHERE = "WHERE d.cmemodtl_cmemo_id=h.cmemo_id AND d.cmemodtl_item_code=i.item_code AND d.cmemodtl_item_code='$item' AND h.cmemo_cust_code='$cust' ";
		$ORDERBY = " ORDER BY h.cmemo_date DESC ";
		$this->query = "$SELECT $FROM $WHERE $ORDERBY ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getCmemoDtlHistRows($item, $cust) {
		$SELECT = " SELECT count(d.cmemodtl_id) AS numrows ";
		$FROM = " FROM cmemos h, cmemodtls d ";
		$WHERE = "WHERE d.cmemodtl_cmemo_id=h.cmemo_id AND d.cmemodtl_item_code='$item' AND h.cmemo_cust_code='$code' ";
		$this->query = "$SELECT $FROM $WHERE ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}



	function getCmemoDtlRows($purch_id="") {
		$this->query = "SELECT count(cmemodtl_id) AS numrows FROM cmemodtls WHERE cmemodtl_cmemo_id='$purch_id'";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>