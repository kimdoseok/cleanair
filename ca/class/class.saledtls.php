<?php
include_once("class.ar.php");

class SaleDtls extends AR {

	function insertSaleDtls($arr) {
		$lastid = $this->insertAR("slsdtls", $arr);
//echo "$lastid";
		if ($lastid>0) return $lastid;
		else return false;
	}

	function updateSaleDtls($code, $arr) {
		if ($this->updateAR("slsdtls", "slsdtl_id", $code, $arr)) return true;
		return false;
	}

	function updateSlsDtlsQtyPicked($code, $newqty, $oldqty) {
		$qty = $newqty - $oldqty;
		$this->query = "UPDATE slsdtls SET slsdtl_qty_picked = slsdtl_qty_picked + $qty WHERE slsdtl_id='$code' ";
		if ($this->updateARRaw($this->query)) return true;
		else return false;
	}

	function deleteSaleDtlsSI($code) {
		$query = "DELETE FROM slsdtls WHERE slsdtl_sale_id = '$code' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function deleteSaleDtls($code) {
		$query = "DELETE FROM slsdtls WHERE slsdtl_id = '$code' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getSaleDtls($code) {
		$select = "SELECT s.*, i.item_desc, i.item_tax ";
		$from = "FROM slsdtls s, items i ";
		$where = "WHERE s.slsdtl_item_code=i.item_code AND s.slsdtl_id = '$code' ";
		$limit = "LIMIT 1 ";
		$this->query = "$select $from $where $limit";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getSaleDtlBoQty($code=-1) {
		$this->query = "SELECT sum(slsdtl_qty_bo) as boqty FROM slsdtls WHERE slsdtl_sale_id = $code ";
		//echo $this->query;
		if ($arr = $this->getAR($code)) return $arr[0]['boqty'];
		return 0;
	}

	function getSaleDtlHdrs($code) {
		$select = "SELECT s.*, i.item_desc, i.item_tax ";
		$from = "FROM slsdtls s, items i ";
		$where = "WHERE s.slsdtl_item_code=i.item_code AND s.slsdtl_sale_id = '$code' ";
		$orderby = "ORDER BY s.slsdtl_item_code ";
		$limit = "";
		$this->query = "$select $from $where $limit $orderby";
		if ($arr = $this->getARRaw()) return $arr;
		return false;
	}

	function getSaleDtlHdrSum($code) {
		$select = "SELECT sum(slsdtl_cost) as slsdtl_sum, sum(if(slsdtl_taxable='t', slsdtl_cost,0)) as slsdtl_tax_sum ";
		$from = "FROM slsdtls ";
		$where = "WHERE slsdtl_sale_id = '$code' ";
		$this->query = "$select $from $where";
		if ($arr = $this->getAR()) return $arr;
		return false;
	}

	function getSaleDtlsFields() {
		$this->query = "SELECT * FROM slsdtls LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastSaleDtls($filter) {
		$this->query = "SELECT * FROM slsdtls WHERE slsdtl_sale_id='$filter' ORDER BY slsdtl_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstSaleDtls($filter) {
		$this->query = "SELECT * FROM slsdtls WHERE slsdtl_sale_id='$filter' ORDER BY slsdtl_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextSaleDtls($code, $filter) {
		$this->query = "SELECT * FROM slsdtls WHERE slsdtl_id > '$code' AND slsdtl_sale_id='$filter' ORDER BY slsdtl_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevSaleDtls($code, $filter) {
		$this->query = "SELECT * FROM slsdtls WHERE slsdtl_id  < '$code' AND slsdtl_sale_id='$filter' ORDER BY slsdtl_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getSaleDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getSaleDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevSaleDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstSaleDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextSaleDtls($code, $filter);
					if (!$rec) $rec = $this->getLastSaleDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstSaleDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastSaleDtls($filter);
				} else {
					$rec = $this->getSaleDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastSaleDtls($filter);
				} else {
					$rec = $this->getFirstSaleDtls($filter);
				}
			}
		}
		return $rec;
	}

	function getSaleDtlsRowsAvl($cust_code) {
		$SELECT = " SELECT count(d.slsdtl_id) AS numrows ";
		$FROM = " FROM sales s, slsdtls d ";
		$WHERE = "WHERE d.slsdtl_sale_id=s.sale_id ";
		$WHERE .= " AND d.slsdtl_qty > d.slsdtl_qty_picked ";
		$WHERE .= " AND s.sale_cust_code='$cust_code' ";
		$this->query = "$SELECT $FROM $WHERE ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getSaleDtlsListAvl($cust_code, $filtertext="", $reverse="f", $page=1, $limit=2000) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM slsdtls d, sales s ";
		$where = " WHERE d.slsdtl_sale_id = s.sale_id " ;
		$where .= " AND d.slsdtl_qty > d.slsdtl_qty_picked ";
		$where .= " AND s.sale_cust_code = '$cust_code' ";
		$orderby = " ORDER BY d.slsdtl_id ";
		if ($reverse == "t") $orderby .= " DESC ";
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit";
		return $this->getAR();
	}

	function getSaleDtlsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=2000) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM slsdtls ";
		$where = " WHERE slsdtl_sale_id='$condition' " ;
		$orderby = " ORDER BY slsdtl_sort, slsdtl_id ";
		if ($reverse == "t") $orderby .= " DESC ";
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getSaleDtlsListEx($condition="", $filtertext="", $reverse="f", $page=1, $limit=2000) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM slsdtls s, items i ";
		$where = " WHERE s.slsdtl_item_code=i.item_code AND slsdtl_sale_id='$condition' " ;
		$orderby = " ORDER BY  s.slsdtl_id, i.item_type, s.slsdtl_item_code ";
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit";
//echo $this->query;
//echo "<br>";
		return $this->getAR();
	}

	function getSaleDtlsHistList($item, $cust, $page=1, $limit=2000, $ex="f") {
		if ($page < 1) $page = 0;
		else $page--;
		$SELECT = " SELECT d.slsdtl_item_desc, d.slsdtl_item_code, d.slsdtl_qty, d.slsdtl_cost, h.sale_date, h.sale_id ";
		$FROM = " FROM sales h, slsdtls d ";
		$WHERE = "WHERE d.slsdtl_sale_id=h.sale_id ";
		$WHERE .= " AND h.sale_cust_code='$cust' ";
		if (!empty($item)) {
			if ($ex == "t") $WHERE .= " AND d.slsdtl_item_code LIKE '$item%' ";
			else $WHERE .= " AND d.slsdtl_item_code='$item' ";
		}
		$ORDERBY = " ORDER BY h.sale_date DESC, d.slsdtl_id DESC ";
		$this->query = "$SELECT $FROM $WHERE $ORDERBY ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getSaleDtlsHistRows($item, $cust, $ex="f") {
		$SELECT = " SELECT count(d.slsdtl_id) AS numrows ";
		$FROM = " FROM sales h, slsdtls d ";
		$WHERE = "WHERE d.slsdtl_sale_id=h.sale_id ";
		$WHERE .= " AND h.sale_cust_code='$cust' ";
		if (!empty($item)) {
			if ($ex == "t") $WHERE .= " AND d.slsdtl_item_code LIKE '$item%' ";
			else $WHERE .= " AND d.slsdtl_item_code='$item' ";
		}
		$this->query = "$SELECT $FROM $WHERE ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


	function getSaleDtlsRows($sale_id="") {
		$this->query = "SELECT count(slsdtl_id) AS numrows FROM slsdtls WHERE slsdtl_sale_id='$sale_id'";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getSaleDtlsRowsItem($item) {
		$this->query = "SELECT count(slsdtl_id) AS numrows FROM slsdtls WHERE slsdtl_item_code='$item'";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getSaleDtlsHdrSum($code) {
		$this->query = "SELECT sum(slsdtl_qty-slsdtl_qty_cancel) AS sum_qty FROM slsdtls WHERE slsdtl_sale_id='$code'";
		if ($arr = $this->getAR()) return $arr[0]["sum_qty"];
		else return false;
	}

	function getSaleDtlsHdrPendSum($code) {
		$this->query = "SELECT sum(slsdtl_qty_pend) AS sum_qty FROM slsdtls WHERE slsdtl_sale_id='$code'";
		if ($arr = $this->getAR()) return $arr[0]["sum_qty"];
		else return false;
	}

	function getSaleDtlsListPicks($code) {
		$select = " SELECT p.pickdtl_pick_id ";
		$from = " FROM pickdtls p, slsdtls s ";
		$where = "WHERE p.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= " AND s.slsdtl_sale_id='$code'";
		$groupby = " GROUP BY p.pickdtl_pick_id ";
		$orderby = "ORDER BY p.pickdtl_pick_id ";
		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query;
		return $this->getARRaw($this->query);
	}

	function getSaleDtlsListPicksEx($code) {
		$select = " SELECT k.* ";
		$from = " FROM pickdtls p, slsdtls s, picks k ";
		$where = "WHERE p.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= "AND p.pickdtl_pick_id=k.pick_id ";
		$where .= " AND s.slsdtl_sale_id='$code'";
		$groupby = " GROUP BY p.pickdtl_pick_id ";
		$orderby = "ORDER BY k.pick_date ";
		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query;
		return $this->getARRaw($this->query);
	}

	function getSaleDtlsRange($dfr="", $dto="", $ifr="", $ito="", $ty="s") { // s for summary, d for detail
		//if ($page < 1) $page = 0;
		//else $page--;
		$groupby = "";
		$orderby = "";
		$from = "";
		$where = "";
		
		$where = "WHERE l.slsdtl_sale_id=h.sale_id ";
		if ($ty=="s") {
			$select = " SELECT l.slsdtl_item_code, l.slsdtl_item_desc, sum(l.slsdtl_qty) as qty, avg(l.slsdtl_cost) as prc, sum(l.slsdtl_qty*l.slsdtl_cost) as amt ";
			$groupby = " GROUP BY l.slsdtl_item_code,slsdtl_item_desc ";
			$orderby = " ORDER BY l.slsdtl_item_code ";
			$from = " FROM slsdtls l, sales h ";
		} else {
			$select = " SELECT h.sale_id, h.sale_date, c.cust_code, c.cust_name, c.cust_addr1, c.cust_city, c.cust_state, c.cust_zip, l.slsdtl_item_code, l.slsdtl_item_desc, l.slsdtl_qty, l.slsdtl_cost ";
			$orderby = " ORDER BY l.slsdtl_item_code, h.sale_date ";
			$from = " FROM sales h, slsdtls l, custs c ";
			$where .= " AND h.sale_cust_code=c.cust_code ";
		}
		if (!empty($dfr)) $where .= " AND h.sale_date >= '$dfr' ";
		if (!empty($dto)) $where .= " AND h.sale_date <= '$dto' ";
		if (!empty($ifr)) $where .= " AND l.slsdtl_item_code >= '$ifr' ";
		if (!empty($ito)) $where .= " AND l.slsdtl_item_code <= '$ito' ";
		$this->query = "$select $from $where $groupby $orderby";
//echo $this->query."<br>";
		return $this->getARRaw();
	}



}

?>