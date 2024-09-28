<?php
include_once("class.ar.php");

class PenDtls extends AR {

	function insertPenDtls($arr) {
		$lastid = $this->insertAR("pendtls", $arr);
//echo "$lastid";
		if ($lastid>0) return $lastid;
		else return false;
	}

	function updatePenDtls($code, $arr) {
		if ($this->updateAR("pendtls", "pendtl_id", $code, $arr)) return true;
		return false;
	}

	function deletePenDtlsAll($code) {
		$query = "DELETE FROM pendtls WHERE pendtl_pend_id = '$code' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function deletePenDtls($code) {
		$query = "DELETE FROM pendtls WHERE pendtl_id = '$code' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getPenDtls($code) {
		$select = "SELECT s.*, i.item_desc, i.item_tax ";
		$from = "FROM pendtls s, items i ";
		$where = "WHERE s.pendtl_item_code=i.item_code AND s.pendtl_id = '$code' ";
		$limit = "LIMIT 1 ";
		$this->query = "$select $from $where $limit";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getPendDtlHdrs($code) {
		$select = "SELECT s.*, i.item_desc, i.item_tax ";
		$from = "FROM pendtls s, items i ";
		$where = "WHERE s.pendtl_item_code=i.item_code AND s.pendtl_pend_id = '$code' ";
		$orderby = "ORDER BY s.pendtl_item_code ";
		$limit = "";
		$this->query = "$select $from $where $limit $orderby";
		if ($arr = $this->getARRaw()) return $arr;
		return false;
	}

	function getPendDtlHdrSum($code) {
		$select = "SELECT sum(pendtl_cost) as pendtl_sum, sum(if(pendtl_taxable='t', pendtl_cost,0)) as pendtl_tax_sum ";
		$from = "FROM pendtls ";
		$where = "WHERE pendtl_pend_id = '$code' ";
		$this->query = "$select $from $where";
		if ($arr = $this->getAR()) return $arr;
		return false;
	}

	function getPenDtlsFields() {
		$this->query = "SELECT * FROM pendtls LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastPenDtls($filter) {
		$this->query = "SELECT * FROM pendtls WHERE pendtl_pend_id='$filter' ORDER BY pendtl_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPenDtls($filter) {
		$this->query = "SELECT * FROM pendtls WHERE pendtl_pend_id='$filter' ORDER BY pendtl_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPenDtls($code, $filter) {
		$this->query = "SELECT * FROM pendtls WHERE pendtl_id > '$code' AND pendtl_pend_id='$filter' ORDER BY pendtl_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPenDtls($code, $filter) {
		$this->query = "SELECT * FROM pendtls WHERE pendtl_id  < '$code' AND pendtl_pend_id='$filter' ORDER BY pendtl_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPenDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPenDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPenDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstPenDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPenDtls($code, $filter);
					if (!$rec) $rec = $this->getLastPenDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPenDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPenDtls($filter);
				} else {
					$rec = $this->getPenDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPenDtls($filter);
				} else {
					$rec = $this->getFirstPenDtls($filter);
				}
			}
		}
		return $rec;
	}

  function getPenDtlsBOQty($sale_id, $item_code) {
    $this->query = "SELECT sum(d.pendtl_qty) as qty FROM pendtls d, pends h ";
    $this->query .= "WHERE h.pend_id=d.pendtl_pend_id AND pend_status>0 ";
    $this->query .= "AND d.pendtl_item_code='$item_code' ";
    $this->query .= "AND (h.pend_origin='$sale_id' OR h.pend_parent='$sale_id') ";
		$arr = $this->getAR();
		if (!$arr) return 0;
		else return $arr[0]["qty"];

  }

	function getPenDtlsRowsAvl($cust_code) {
		$SELECT = " SELECT count(d.pendtl_id) AS numrows ";
		$FROM = " FROM pends s, pendtls d ";
		$WHERE = "WHERE d.pendtl_pend_id=s.pend_id ";
		$WHERE .= " AND d.pendtl_qty > d.pendtl_qty_picked ";
		$WHERE .= " AND s.pend_cust_code='$cust_code' ";
		$this->query = "$SELECT $FROM $WHERE ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPenDtlsListAvl($cust_code, $filtertext="", $reverse="f", $page=1, $limit=2000) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM pendtls d, pends s ";
		$where = " WHERE d.pendtl_pend_id = s.pend_id " ;
		$where .= " AND d.pendtl_qty > d.pendtl_qty_picked ";
		$where .= " AND s.pend_cust_code = '$cust_code' ";
		$orderby = " ORDER BY d.pendtl_id ";
		if ($reverse == "t") $orderby .= " DESC ";
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit";
		return $this->getAR();
	}

	function getPenDtlsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=2000) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM pendtls ";
		$where = " WHERE pendtl_pend_id='$condition' " ;
		$orderby = " ORDER BY pendtl_sort, pendtl_id ";
		if ($reverse == "t") $orderby .= " DESC ";
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getPenDtlsListEx($condition="", $filtertext="", $reverse="f", $page=1, $limit=2000) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM pendtls s, items i ";
		$where = " WHERE s.pendtl_item_code=i.item_code AND pendtl_pend_id='$condition' " ;
		$orderby = " ORDER BY  s.pendtl_id, i.item_type, s.pendtl_item_code ";
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit";
//echo $this->query;
//echo "<br>";
		return $this->getAR();
	}

	function getPenDtlsHistList($item, $cust, $page=1, $limit=2000, $ex="f") {
		if ($page < 1) $page = 0;
		else $page--;
		$SELECT = " SELECT d.pendtl_item_desc, d.pendtl_item_code, d.pendtl_qty, d.pendtl_cost, h.pend_date, h.pend_id ";
		$FROM = " FROM pends h, pendtls d ";
		$WHERE = "WHERE d.pendtl_pend_id=h.pend_id ";
		$WHERE .= " AND h.pend_cust_code='$cust' ";
		if (!empty($item)) {
			if ($ex == "t") $WHERE .= " AND d.pendtl_item_code LIKE '$item%' ";
			else $WHERE .= " AND d.pendtl_item_code='$item' ";
		}
		$ORDERBY = " ORDER BY h.pend_date DESC, d.pendtl_id DESC ";
		$this->query = "$SELECT $FROM $WHERE $ORDERBY ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getPenDtlsHistRows($item, $cust, $ex="f") {
		$SELECT = " SELECT count(d.pendtl_id) AS numrows ";
		$FROM = " FROM pends h, pendtls d ";
		$WHERE = "WHERE d.pendtl_pend_id=h.pend_id ";
		$WHERE .= " AND h.pend_cust_code='$cust' ";
		if (!empty($item)) {
			if ($ex == "t") $WHERE .= " AND d.pendtl_item_code LIKE '$item%' ";
			else $WHERE .= " AND d.pendtl_item_code='$item' ";
		}
		$this->query = "$SELECT $FROM $WHERE ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


	function getPenDtlsRows($pend_id="") {
		$this->query = "SELECT count(pendtl_id) AS numrows FROM pendtls WHERE pendtl_pend_id='$pend_id'";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPenDtlsRowsItem($item) {
		$this->query = "SELECT count(pendtl_id) AS numrows FROM pendtls WHERE pendtl_item_code='$item'";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPenDtlsHdrSum($code) {
		$this->query = "SELECT sum(pendtl_qty-pendtl_qty_cancel) AS sum_qty FROM pendtls WHERE pendtl_pend_id='$code'";
		if ($arr = $this->getAR()) return $arr[0]["sum_qty"];
		else return false;
	}

	function getPenDtlsListPicks($code) {
		$select = " SELECT p.pickdtl_pick_id ";
		$from = " FROM pickdtls p, pendtls s ";
		$where = "WHERE p.pickdtl_pendtl_id=s.pendtl_id ";
		$where .= " AND s.pendtl_pend_id='$code'";
		$groupby = " GROUP BY p.pickdtl_pick_id ";
		$orderby = "ORDER BY p.pickdtl_pick_id ";
		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query;
		return $this->getARRaw($this->query);
	}

	function getPenDtlsListPicksEx($code) {
		$select = " SELECT k.* ";
		$from = " FROM pickdtls p, pendtls s, picks k ";
		$where = "WHERE p.pickdtl_pendtl_id=s.pendtl_id ";
		$where .= "AND p.pickdtl_pick_id=k.pick_id ";
		$where .= " AND s.pendtl_pend_id='$code'";
		$groupby = " GROUP BY p.pickdtl_pick_id ";
		$orderby = "ORDER BY k.pick_date ";
		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query;
		return $this->getARRaw($this->query);
	}

	function getPenDtlsRange($dfr="", $dto="", $ifr="", $ito="", $ty="s") { // s for summary, d for detail
		if ($page < 1) $page = 0;
		else $page--;
		$where = "WHERE l.pendtl_pend_id=h.pend_id AND h.pend_status>0 ";
		if ($ty=="s") {
			$select = " SELECT l.pendtl_item_code, l.pendtl_item_desc, sum(l.pendtl_qty) as qty, avg(l.pendtl_cost) as prc, sum(l.pendtl_qty*l.pendtl_cost) as amt ";
			$groupby = " GROUP BY l.pendtl_item_code ";
			$orderby = " ORDER BY l.pendtl_item_code ";
			$from = " FROM pendtls l, pends h ";
		} else {
			$select = " SELECT h.pend_id, h.pend_date, c.cust_code, c.cust_name, c.cust_city, c.cust_state, l.pendtl_item_code, l.pendtl_item_desc, l.pendtl_qty, l.pendtl_cost ";
			$orderby = " ORDER BY l.pendtl_item_code, h.pend_date ";
			$from = " FROM pends h, pendtls l, custs c ";
			$where .= " AND h.pend_cust_code=c.cust_code ";
		}
		if (!empty($dfr)) $where .= " AND h.pend_date >= '$dfr' ";
		if (!empty($dto)) $where .= " AND h.pend_date <= '$dto' ";
		if (!empty($ifr)) $where .= " AND l.pendtl_item_code >= '$ifr' ";
		if (!empty($ito)) $where .= " AND l.pendtl_item_code <= '$ito' ";
		$this->query = "$select $from $where $groupby $orderby";
//echo $this->query."<br>";
		return $this->getARRaw();
	}



}

?>
