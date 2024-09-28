<?php
include_once("class.ap.php");

class PoRcptDtls extends AP {

	function insertPoRcptDtls($arr) {
		if ($lastid = $this->insertAP("porcptdtls", $arr)) return $lastid;
		return false;
	}

	function updatePoRcptDtls($code, $arr) {
		if ($this->updateAP("porcptdtls", "porcptdtl_id", $code, $arr)) return true;
		return false;
	}

	function deletePoRcptDtls($code) {
		$query = "DELETE FROM porcptdtls WHERE porcptdtl_id = '$code' ";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function deletePoRcptDtlsHdr($code) {
		$query = "DELETE FROM porcptdtls WHERE porcptdtl_porcpt_id = '$code' ";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function getPoRcptDtls($code) {
		$this->query = "SELECT * FROM porcptdtls WHERE porcptdtl_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getPoRcptDtlsFields() {
		$this->query = "SELECT * FROM porcptdtls LIMIT 0 ";
		if ($arr = $this->getAPFields()) return $arr;
		return false;
	}

	function getLastPoRcptDtls($filter) {
		$this->query = "SELECT * FROM porcptdtls WHERE porcptdtl_porcpt_id='$filter' ORDER BY porcptdtl_id DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPoRcptDtls($filter) {
		$this->query = "SELECT * FROM porcptdtls WHERE porcptdtl_porcpt_id='$filter' ORDER BY porcptdtl_id LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPoRcptDtls($code, $filter) {
		$this->query = "SELECT * FROM porcptdtls WHERE porcptdtl_id > '$code' AND porcptdtl_porcpt_id='$filter' ORDER BY porcptdtl_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPoRcptDtls($code, $filter) {
		$this->query = "SELECT * FROM porcptdtls WHERE porcptdtl_id  < '$code' AND porcptdtl_porcpt_id='$filter' ORDER BY porcptdtl_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPoRcptDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPoRcptDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPoRcptDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstPoRcptDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPoRcptDtls($code, $filter);
					if (!$rec) $rec = $this->getLastPoRcptDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPoRcptDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPoRcptDtls($filter);
				} else {
					$rec = $this->getPoRcptDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPoRcptDtls($filter);
				} else {
					$rec = $this->getFirstPoRcptDtls($filter);
				}
			}
		}
		return $rec;
	}

	function getPoRcptDtlsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition != "all") $this->query = "SELECT * FROM porcptdtls WHERE porcptdtl_porcpt_id='$condition' ORDER BY porcptdtl_id  ";
			else $this->query = "SELECT * FROM porcptdtls ORDER BY porcptdtl_id ";
		} else {
			if ($condition != "all") $this->query = "SELECT * FROM porcptdtls WHERE porcptdtl_porcpt_id='$condition' ORDER BY porcptdtl_id  ";
			else $this->query = "SELECT * FROM porcptdtls ORDER BY porcptdtl_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAPRaw();
	}

	function getPoRcptDtlsRows($porcpt_id="") {
		if (empty($porcpt_id)) $this->query = "SELECT count(porcptdtl_id) AS numrows FROM porcptdtls ";
		else $this->query = "SELECT count(porcptdtl_id) AS numrows FROM porcptdtls WHERE porcptdtl_porcpt_id='$porcpt_id'";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPoRcptDtlsHistList($item, $vend, $page=1, $limit=20, $ex="f") {
		if ($page < 1) $page = 0;
		else $page--;
		$SELECT = " SELECT * ";
		$FROM = " FROM purchs h, porcptdtls d ";
		$WHERE = "WHERE d.porcptdtl_porcpt_id=h.porcpt_id ";
		$WHERE .= " AND h.porcpt_vend_code='$vend' ";
		if (!empty($item)) {
			if ($ex == "t") $WHERE .= " AND d.porcptdtl_item_code LIKE '$item%' ";
			else $WHERE .= " AND d.porcptdtl_item_code='$item' ";
		}
		$ORDERBY = " ORDER BY h.porcpt_date DESC, d.porcptdtl_id DESC ";
		$this->query = "$SELECT $FROM $WHERE $ORDERBY ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAP();
	}

	function getPoRcptDtlsHistRows($item, $vend, $ex="f") {
		$SELECT = " SELECT count(d.porcptdtl_id) AS numrows ";
		$FROM = " FROM purchs h, porcptdtls d ";
		$WHERE = "WHERE d.porcptdtl_porcpt_id=h.porcpt_id ";
		$WHERE .= " AND h.porcpt_vend_code='$vend' ";
		if (!empty($item)) {
			if ($ex == "t") $WHERE .= " AND d.porcptdtl_item_code LIKE '$item%' ";
			else $WHERE .= " AND d.porcptdtl_item_code='$item' ";
		}
		$this->query = "$SELECT $FROM $WHERE ";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPoRcptDtlsSlsHdrSum($code) {
		$this->query = "SELECT sum(p.pickdtl_qty) AS sum_qty FROM pickdtls p, slsdtls s WHERE p.pickdtl_slsdtl_id = s.slsdtl_id AND s.slsdtl_sale_id = $code ";
//echo $this->query."<br>";
		if ($arr = $this->getAP()) return $arr[0]["sum_qty"];
		return 0;
	}

	function getPoRcptDtlsHdrs($code) {
		$select = "SELECT p.*, i.item_desc, i.item_tax ";
		$from = "FROM porcptdtls p, items i ";
		$where = "WHERE p.porcptdtl_item_code=i.item_code AND p.porcptdtl_porcpt_id = '$code' ";
		$orderby = "ORDER BY p.porcptdtl_item_code ";
		$limit = "";
		$this->query = "$select $from $where $limit $orderby";
		if ($arr = $this->getAPRaw()) return $arr;
		return false;
	}

	function getPoRcptDtlHdrSum($code) {
		$select = "SELECT sum(porcptdtl_cost) as porcptdtl_sum, sum(if(porcptdtl_taxable='t', porcptdtl_cost,0)) as porcptdtl_tax_sum ";
		$from = "FROM porcptdtls ";
		$where = "WHERE porcptdtl_porcpt_id = '$code' ";
		$this->query = "$select $from $where";
		if ($arr = $this->getAP()) return $arr;
		return false;
	}

	function getPoRcptDtlOpenList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition != "all") $this->query = "SELECT * FROM porcptdtls WHERE porcptdtl_porcpt_id='$condition' ORDER BY porcptdtl_id  ";
			else $this->query = "SELECT * FROM porcptdtls ORDER BY porcptdtl_id ";
		} else {
			if ($condition != "all") $this->query = "SELECT * FROM porcptdtls WHERE porcptdtl_porcpt_id='$condition' ORDER BY porcptdtl_id  ";
			else $this->query = "SELECT * FROM porcptdtls ORDER BY porcptdtl_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		$this->query = "SELECT p.porcpt_vend_code, p.porcpt_vend_name, d.porcptdtl_item_code, d.porcptdtl_item_desc, sum(d.porcptdtl_qty) as pur_qty, sum(d.porcptdtl_qty_picked) as rcv_qty, sum(d.porcptdtl_qty_cancel) as csl_qty, count(d.porcptdtl_item_code) as cnt FROM purchs p, porcptdtls d WHERE p.porcpt_id=d.porcptdtl_porcpt_id AND d.porcptdtl_qty <> d.porcptdtl_qty_picked+d.porcptdtl_qty_cancel GROUP BY d.porcptdtl_item_code, p.porcpt_vend_code ORDER BY d.porcptdtl_item_code, p.porcpt_vend_code ";
//echo $this->query."<br>";
		return $this->getAPRaw();
	}

	function getPoRcptDtlStatusList($item, $vend) {
		$this->query = "SELECT * FROM purdtl";
		return $this->getAPRaw();
	}


}

?>