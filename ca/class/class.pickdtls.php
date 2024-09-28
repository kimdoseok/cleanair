<?php
include_once("class.ar.php");

class PickDtls extends AR {

	function insertPickDtls($arr) {
		if ($lastid = $this->insertAR("pickdtls", $arr)) return $lastid;
		return false;
	}

	function updatePickDtls($code, $arr) {
		if ($this->updateAR("pickdtls", "pickdtl_id", $code, $arr)) return true;
		return false;
	}

	function deletePickDtls($code) {
		$query = "DELETE FROM pickdtls WHERE pickdtl_id = $code ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function deletePickDtlsHdr($code) {
		$this->query = "DELETE FROM pickdtls WHERE pickdtl_pick_id = $code ";
//echo $this->query."<br>";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getPickDtls($code) {
		$this->query = "SELECT * FROM pickdtls WHERE pickdtl_id = $code LIMIT 1 ";
//echo $this->query."<br>";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function isPickDtled($code) {
		$this->query = "SELECT * FROM pickdtls WHERE pickdtl_slsdtl_id = $code LIMIT 1 ";
		if ($arr = $this->getAR($code)) return true;
		return false;
	}

	function getPickDtlsEx($code) {
		$this->query = "SELECT * FROM pickdtls p, slsdtls s WHERE s.slsdtl_id=p.pickdtl_slsdtl_id AND p.pickdtl_id = $code LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getPickDtlsSlsSum($code) {
		$this->query = "SELECT COALESCE(sum(pickdtl_qty), 0) AS sum_qty FROM pickdtls WHERE pickdtl_slsdtl_id = '$code' ";
//echo $this->query."<br>";
		if ($arr = $this->getAR($code)) return $arr[0]["sum_qty"];
		return 0;
	}

	function getPickDtlsSlsHdrSum($code) {
		$this->query = "SELECT sum(p.pickdtl_qty) AS sum_qty FROM pickdtls p, slsdtls s WHERE p.pickdtl_slsdtl_id = s.slsdtl_id AND s.slsdtl_sale_id = $code ";
//echo $this->query."<br>";
		if ($arr = $this->getAR()) return $arr[0]["sum_qty"];
		return 0;
	}

	function getPickDtlsHdr($code) {
		$this->query = "SELECT * FROM pickdtls WHERE pickdtl_pick_id = $code ORDER BY pickdtl_id DESC ";
//echo $this->query;
		return $this->getARRaw($this->query);
	}

	function getPickDtlsHdrEx($code) {
		$this->query = "SELECT * FROM picks h, pickdtls p, slsdtls s WHERE h.pick_id=p.pickdtl_pick_id AND s.slsdtl_id=p.pickdtl_slsdtl_id AND pickdtl_pick_id = $code ORDER BY pickdtl_id DESC  ";
//echo $this->query;
		return $this->getARRaw($this->query);
	}

	function getPickDtlsFields() {
		$this->query = "SELECT * FROM pickdtls LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastPickDtls($filter) {
		$this->query = "SELECT * FROM pickdtls WHERE pickdtl_pick_id='$filter' ORDER BY pickdtl_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPickDtls($filter) {
		$this->query = "SELECT * FROM pickdtls WHERE pickdtl_pick_id='$filter' ORDER BY pickdtl_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPickDtls($code, $filter) {
		$this->query = "SELECT * FROM pickdtls WHERE pickdtl_id > '$code' AND pickdtl_pick_id='$filter' ORDER BY pickdtl_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPickDtls($code, $filter) {
		$this->query = "SELECT * FROM pickdtls WHERE pickdtl_id  < '$code' AND pickdtl_pick_id='$filter' ORDER BY pickdtl_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPickDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPickDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPickDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstPickDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPickDtls($code, $filter);
					if (!$rec) $rec = $this->getLastPickDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPickDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPickDtls($filter);
				} else {
					$rec = $this->getPickDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPickDtls($filter);
				} else {
					$rec = $this->getFirstPickDtls($filter);
				}
			}
		}
		return $rec;
	}

	function getPickDtlsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=1000) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM pickdtls p, slsdtls s, items i ";
		$where = "WHERE p.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= " AND s.slsdtl_item_code=i.item_code";
		$where .= " AND p.pickdtl_pick_id='$condition' ";
		$orderby = " ORDER BY p.pickdtl_id ";
		if ($reverse == "t") $orderby .= " DESC ";
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit";
		return $this->getAR();
	}

	function getPicksListDate($bl_date, $shipvia="") {
		$select = " SELECT s.slsdtl_item_code, i.item_desc, sum(p.pickdtl_qty) as pickdtl_qty_sum ";
		$from = " FROM picks k, pickdtls p, slsdtls s, items i ";
		$where = "WHERE p.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= " AND s.slsdtl_item_code=i.item_code";
		$where .= " AND k.pick_id=p.pickdtl_pick_id ";
		$where .= " AND k.pick_prom_date='$bl_date' ";
		if (!empty($shipvia) && $shipvia != "all") $where .= " AND k.pick_shipvia='$shipvia' ";
		$groupby = " GROUP BY s.slsdtl_item_code ";
		$orderby = " ORDER BY s.slsdtl_item_code, s.slsdtl_id DESC ";
		//$offset = $page * $limit;
		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query;
		return $this->getARRaw($this->query);
	}

	function getPicksListCustDate($bl_date, $shipvia="") {
		$select = " SELECT * ";
		$from = " FROM picks k, pickdtls p, slsdtls s, items i, custs c ";
		$where = "WHERE p.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= " AND s.slsdtl_item_code=i.item_code";
		$where .= " AND k.pick_id=p.pickdtl_pick_id ";
		$where .= " AND k.pick_cust_code=c.cust_code ";
		$where .= " AND k.pick_prom_date='$bl_date' ";
		if (!empty($shipvia) && $shipvia != "all") $where .= " AND k.pick_shipvia='$shipvia' ";
		$orderby = " ORDER BY k.pick_cust_code, s.slsdtl_item_code, s.slsdtl_id DESC  ";
		//$offset = $page * $limit;
		$this->query = "$select $from $where $orderby ";
//echo $this->query;
		return $this->getARRaw($this->query);
	}

	function getPickDtlsRows($code="") {
		if (empty($code)) $this->query = "SELECT count(pickdtl_id) AS numrows FROM pickdtls";
		else $this->query = "SELECT count(pickdtl_id) AS numrows FROM pickdtls WHERE pickdtl_pick_id='$code'";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPickDtlsHistList($item, $cust, $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$SELECT = " SELECT s.slsdtl_item_code, d.pickdtl_qty, d.pickdtl_cost, h.pick_date, h.pick_id, i.item_msrp ";
		$FROM = " FROM picks h, pickdtls d, slsdtls s, items i ";
		$WHERE = "WHERE d.pickdtl_pick_id=h.pick_id AND d.pickdtl_slsdtl_id=s.slsdtl_id AND s.slsdtl_item_code=i.item_code AND s.slsdtl_item_code='$item' AND h.pick_cust_code='$cust' ";
		$ORDERBY = " ORDER BY h.pick_date DESC, d.pickdtl_id DESC ";
		$this->query = "$SELECT $FROM $WHERE $ORDERBY ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getPickDtlsHistRows($item, $cust) {
		$SELECT = " SELECT count(s.slsdtl_id) AS numrows ";
		$FROM = " FROM picks h, pickdtls d, slsdtls s ";
		$WHERE = "WHERE d.pickdtl_slsdtl_id=s.slsdtl_id AND d.pickdtl_pick_id=h.pick_id AND s.slsdtl_item_code='$item' AND h.pick_cust_code='$code' ";
		$this->query = "$SELECT $FROM $WHERE ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPickDtlsRange($dfr="", $dto="", $ifr="", $ito="", $ty="s") { // s for summary, d for detail
		//if ($page < 1) $page = 0;
		//else $page--;
		$select = "";
		$groupby = "";
		$orderby = "";
		$from = "";
		$where = "WHERE d.pickdtl_pick_id=p.pick_id ";
		$where .= " AND d.pickdtl_slsdtl_id=s.slsdtl_id ";
		if ($ty=="s") {
			$select = " SELECT s.slsdtl_item_code, s.slsdtl_item_desc, sum(d.pickdtl_qty) as qty, avg(d.pickdtl_cost) as prc, sum(d.pickdtl_qty*s.slsdtl_cost) as amt ";
			$groupby = " GROUP BY s.slsdtl_item_code, s.slsdtl_item_desc ";
			$orderby = " ORDER BY s.slsdtl_item_code ";
			$from = " FROM pickdtls d, picks p, slsdtls s ";
		} else {
			$select = " SELECT p.pick_id, p.pick_date, c.cust_code, c.cust_name, c.cust_city, c.cust_state, s.slsdtl_item_code, s.slsdtl_item_desc, d.pickdtl_qty, s.slsdtl_cost ";
			$orderby = " ORDER BY s.slsdtl_item_code, p.pick_date ";
			$from = " FROM pickdtls d, picks p, slsdtls s, custs c ";
			$where .= " AND p.pick_cust_code=c.cust_code ";
		}
		if (!empty($dfr)) $where .= " AND p.pick_date >= '$dfr' ";
		if (!empty($dto)) $where .= " AND p.pick_date <= '$dto' ";
		if (!empty($ifr)) $where .= " AND s.slsdtl_item_code >= '$ifr' ";
		if (!empty($ito)) $where .= " AND s.slsdtl_item_code <= '$ito' ";
		$this->query = "$select $from $where $groupby $orderby";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getPickDtlsRangeCust($dfr="", $dto="", $cfr="", $cto="", $ty="s", $sby="") { // s for summary, d for detail
		//if ($page < 1) $page = 0;
		//else $page--;
		$where = "WHERE d.pickdtl_pick_id=p.pick_id ";
		$where .= " AND d.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= " AND p.pick_cust_code=c.cust_code ";
		$from = " FROM pickdtls d, picks p, slsdtls s, custs c ";
		if ($ty=="s") {
			$select = " SELECT p.pick_cust_code, c.cust_name, sum(d.pickdtl_qty) as qty, avg(d.pickdtl_cost) as prc, sum(d.pickdtl_qty*s.slsdtl_cost) as amt ";
			$groupby = " GROUP BY p.pick_cust_code ";
      
      if ($sby=="c") $orderby = " ORDER BY p.pick_cust_code, p.pick_date ";
      else if ($sby=="n") $orderby = " ORDER BY c.cust_name,p.pick_cust_code ";
      else if ($sby=="q") $orderby = " ORDER BY sum(d.pickdtl_qty) DESC ";
      else if ($sby=="a") $orderby = " ORDER BY sum(d.pickdtl_qty*s.slsdtl_cost) DESC ";
      else if ($sby=="v") $orderby = " ORDER BY avg(d.pickdtl_cost) DESC ";
      else $orderby = " ORDER BY p.pick_cust_code, p.pick_date ";
      
		} else {
			$select = " SELECT p.pick_id, p.pick_date, c.cust_code, c.cust_name, c.cust_city, c.cust_state, s.slsdtl_item_code, s.slsdtl_item_desc, d.pickdtl_qty, s.slsdtl_cost ";
			$groupby = "";

			if ($sby=="c") $orderby = " ORDER BY p.pick_cust_code, p.pick_date ";
			else if ($sby=="n") $orderby = " ORDER BY c.cust_name, p.pick_date ";
			else if ($sby=="q") $orderby = " ORDER BY d.pickdtl_qty DESC ";
			else if ($sby=="a") $orderby = " ORDER BY d.pickdtl_qty*s.slsdtl_cost DESC ";
			else if ($sby=="v") $orderby = " ORDER BY s.slsdtl_cost DESC ";
			else $orderby = " ORDER BY p.pick_cust_code, p.pick_date ";      
		}
		if (!empty($dfr)) $where .= " AND p.pick_date >= '$dfr' ";
		if (!empty($dto)) $where .= " AND p.pick_date <= '$dto' ";
		if (!empty($cfr)) $where .= " AND p.pick_cust_code >= '$cfr' ";
		if (!empty($cto)) $where .= " AND p.pick_cust_code <= '$cto' ";
		$this->query = "$select $from $where $groupby $orderby";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getPickDtlsRangeReps($dfr="", $dto="", $rfr="", $rto="", $ty="s") { // s for summary, d for detail
		//if ($page < 1) $page = 0;
		//else $page--;
		$where = "WHERE d.pickdtl_pick_id=p.pick_id ";
		$where .= " AND d.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= " AND p.pick_cust_code=c.cust_code ";
		$from = " FROM pickdtls d, picks p, slsdtls s, sales a, custs c LEFT OUTER JOIN slsreps r ON r.slsrep_code=a.sale_slsrep ";
		$orderby = " ORDER BY a.sale_slsrep, p.pick_date ";
		if ($ty=="s") {
			$select = " SELECT p.pick_cust_code, c.cust_name, sum(d.pickdtl_qty) as qty, avg(d.pickdtl_cost) as prc, sum(d.pickdtl_qty*s.slsdtl_cost) as amt ";
			$groupby = " GROUP BY a.sale_slsrep ";
		} else {
			$select = " SELECT p.pick_id, p.pick_date, c.cust_code, c.cust_name, c.cust_city, c.cust_state, s.slsdtl_item_code, s.slsdtl_item_desc, d.pickdtl_qty, s.slsdtl_cost ";
			$groupby = " GROUP BY p.pick_id ";
		}
		if (!empty($dfr)) $where .= " AND p.pick_date >= '$dfr' ";
		if (!empty($dto)) $where .= " AND p.pick_date <= '$dto' ";
		if (!empty($cfr)) $where .= " AND p.pick_cust_code >= '$cfr' ";
		if (!empty($cto)) $where .= " AND p.pick_cust_code <= '$cto' ";
		$this->query = "$select $from $where $groupby $orderby";
//echo $this->query."<br>";
		return $this->getARRaw();
	}


	function getPickDtlRangeVendor($dfr="", $dto="", $ifr="", $ito="", $vfr="", $vto="", $ty="s") { // s for summary, d for detail
		$where = "WHERE d.pickdtl_pick_id=p.pick_id ";
		$where .= " AND d.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= " AND s.slsdtl_item_code=i.item_code ";
		$from = " FROM pickdtls d, picks p, slsdtls s, items i ";
		$orderby = " ORDER BY p.pick_cust_code, p.pick_date ";
		$select = " SELECT p.*, i.*, s.slsdtl_item_code, s.slsdtl_item_desc, d.pickdtl_qty, s.slsdtl_cost ";
		$groupby = "";
		if (!empty($dfr)) $where .= " AND p.pick_date >= '$dfr' ";
		if (!empty($dto)) $where .= " AND p.pick_date <= '$dto' ";
		if (!empty($ifr)) $where .= " AND s.slsdtl_item_code >= '$ifr' ";
		if (!empty($ito)) $where .= " AND s.slsdtl_item_code <= '$ito' ";
		if (!empty($vfr)) $where .= " AND i.item_vend_code >= '$vfr' ";
		if (!empty($vto)) $where .= " AND i.item_vend_code <= '$vto' ";
		$this->query = "$select $from $where $groupby $orderby";
//echo $this->query."<br>";
		return $this->getARRaw();

	}

	function getPickDtlsListSales($code) {
		$select = " SELECT s.slsdtl_sale_id ";
		$from = " FROM pickdtls p, slsdtls s ";
		$where = "WHERE p.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= " AND p.pickdtl_pick_id='$code'";
		$groupby = " GROUP BY s.slsdtl_sale_id ";
		$this->query = "$select $from $where $groupby ";
//echo $this->query;
		return $this->getARRaw($this->query);
	}


}

?>