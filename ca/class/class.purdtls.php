<?php
include_once("class.ap.php");

class PurDtls extends AP {

	function insertPurDtls($arr) {
		if ($lastid = $this->insertAP("purdtls", $arr)) return $lastid;
		return false;
	}

	function updatePurDtls($code, $arr) {
		if ($this->updateAP("purdtls", "purdtl_id", $code, $arr)) return true;
		return false;
	}

	function deletePurDtls($code) {
		$query = "DELETE FROM purdtls WHERE purdtl_id = '$code' ";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function deletePurDtlsHdr($code) {
		$query = "DELETE FROM purdtls WHERE purdtl_purch_id = '$code' ";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function getPurDtls($code) {
		$this->query = "SELECT * FROM purdtls WHERE purdtl_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getPurDtlsFields() {
		$this->query = "SELECT * FROM purdtls LIMIT 0 ";
		if ($arr = $this->getAPFields()) return $arr;
		return false;
	}

	function getLastPurDtls($filter) {
		$this->query = "SELECT * FROM purdtls WHERE purdtl_purch_id='$filter' ORDER BY purdtl_id DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPurDtls($filter) {
		$this->query = "SELECT * FROM purdtls WHERE purdtl_purch_id='$filter' ORDER BY purdtl_id LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPurDtls($code, $filter) {
		$this->query = "SELECT * FROM purdtls WHERE purdtl_id > '$code' AND purdtl_purch_id='$filter' ORDER BY purdtl_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPurDtls($code, $filter) {
		$this->query = "SELECT * FROM purdtls WHERE purdtl_id  < '$code' AND purdtl_purch_id='$filter' ORDER BY purdtl_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPurDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPurDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPurDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstPurDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPurDtls($code, $filter);
					if (!$rec) $rec = $this->getLastPurDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPurDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPurDtls($filter);
				} else {
					$rec = $this->getPurDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPurDtls($filter);
				} else {
					$rec = $this->getFirstPurDtls($filter);
				}
			}
		}
		return $rec;
	}

	function getPurDtlsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition != "all") $this->query = "SELECT * FROM purdtls WHERE purdtl_purch_id='$condition' ORDER BY purdtl_id  ";
			else $this->query = "SELECT * FROM purdtls ORDER BY purdtl_id ";
		} else {
			if ($condition != "all") $this->query = "SELECT * FROM purdtls WHERE purdtl_purch_id='$condition' ORDER BY purdtl_id  ";
			else $this->query = "SELECT * FROM purdtls ORDER BY purdtl_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAPRaw();
	}

	function getPurDtlsRows($purch_id="") {
		if (empty($purch_id)) $this->query = "SELECT count(purdtl_id) AS numrows FROM purdtls ";
		else $this->query = "SELECT count(purdtl_id) AS numrows FROM purdtls WHERE purdtl_purch_id='$purch_id'";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPurDtlsHistList($item, $vend, $page=1, $limit=20, $ex="f") {
		if ($page < 1) $page = 0;
		else $page--;
		$SELECT = " SELECT * ";
		$FROM = " FROM purchs h, purdtls d ";
		$WHERE = "WHERE d.purdtl_purch_id=h.purch_id ";
		$WHERE .= " AND h.purch_vend_code='$vend' ";
		if (!empty($item)) {
			if ($ex == "t") $WHERE .= " AND d.purdtl_item_code LIKE '$item%' ";
			else $WHERE .= " AND d.purdtl_item_code='$item' ";
		}
		$ORDERBY = " ORDER BY h.purch_date DESC, d.purdtl_id DESC ";
		$this->query = "$SELECT $FROM $WHERE $ORDERBY ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAP();
	}

	function getPurDtlsHistRows($item, $vend, $ex="f") {
		$SELECT = " SELECT count(d.purdtl_id) AS numrows ";
		$FROM = " FROM purchs h, purdtls d ";
		$WHERE = "WHERE d.purdtl_purch_id=h.purch_id ";
		$WHERE .= " AND h.purch_vend_code='$vend' ";
		if (!empty($item)) {
			if ($ex == "t") $WHERE .= " AND d.purdtl_item_code LIKE '$item%' ";
			else $WHERE .= " AND d.purdtl_item_code='$item' ";
		}
		$this->query = "$SELECT $FROM $WHERE ";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPurDtlsSlsHdrSum($code) {
		$this->query = "SELECT sum(p.pickdtl_qty) AS sum_qty FROM pickdtls p, slsdtls s WHERE p.pickdtl_slsdtl_id = s.slsdtl_id AND s.slsdtl_sale_id = $code ";
//echo $this->query."<br>";
		if ($arr = $this->getAP()) return $arr[0]["sum_qty"];
		return 0;
	}

	function getPurDtlsHdrs($code) {
		$select = "SELECT p.*, i.item_desc, i.item_tax ";
		$from = "FROM purdtls p, items i ";
		$where = "WHERE p.purdtl_item_code=i.item_code AND p.purdtl_purch_id = '$code' ";
		$orderby = "ORDER BY p.purdtl_item_code ";
		$limit = "";
		$this->query = "$select $from $where $limit $orderby";
		if ($arr = $this->getAPRaw()) return $arr;
		return false;
	}

	function getPurDtlHdrSum($code) {
		$select = "SELECT sum(purdtl_cost) as purdtl_sum, sum(if(purdtl_taxable='t', purdtl_cost,0)) as purdtl_tax_sum ";
		$from = "FROM purdtls ";
		$where = "WHERE purdtl_purch_id = '$code' ";
		$this->query = "$select $from $where";
		if ($arr = $this->getAP()) return $arr;
		return false;
	}

	function getPurDtlOpenList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition != "all") $this->query = "SELECT * FROM purdtls WHERE purdtl_purch_id='$condition' ORDER BY purdtl_id  ";
			else $this->query = "SELECT * FROM purdtls ORDER BY purdtl_id ";
		} else {
			if ($condition != "all") $this->query = "SELECT * FROM purdtls WHERE purdtl_purch_id='$condition' ORDER BY purdtl_id  ";
			else $this->query = "SELECT * FROM purdtls ORDER BY purdtl_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		$this->query = "SELECT p.purch_vend_code, p.purch_vend_name, d.purdtl_item_code, d.purdtl_item_desc, sum(d.purdtl_qty) as pur_qty, sum(d.purdtl_qty_picked) as rcv_qty, sum(d.purdtl_qty_cancel) as csl_qty, count(d.purdtl_item_code) as cnt FROM purchs p, purdtls d WHERE p.purch_id=d.purdtl_purch_id AND d.purdtl_qty <> d.purdtl_qty_picked+d.purdtl_qty_cancel GROUP BY d.purdtl_item_code, p.purch_vend_code ORDER BY d.purdtl_item_code, p.purch_vend_code ";
//echo $this->query."<br>";
		return $this->getAPRaw();
	}

	function getPurDtlStatusList($item, $vend) {
		$this->query = "SELECT * FROM purdtl";
		return $this->getAPRaw();
	}


}

?>