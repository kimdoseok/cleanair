<?php
include_once("class.ar.php");

class Cmemo extends AR {

	var $fieldlist;

	function insertCmemo($arr) {
		if ($lastid = $this->insertAR("cmemos", $arr)) return $lastid;
		return false;
	}

	function updateCmemo($code, $arr) {
		if ($this->updateAR("cmemos", "cmemo_id", $code, $arr)) return true;
		return false;
	}

	function getCmemo($code) {
		$this->query = "SELECT * FROM cmemos WHERE cmemo_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function increaseCmemo($code, $fld_name, $qty) {
		$this->query = "UPDATE cmemos SET $fld_name = $fld_name + $qty WHERE cmemo_id=$code";
//echo $this->query;
//echo "<br>";
		if ($this->updateARRaw($this->query)) return true;
		return false;
	}

	function getCmemoCount($cust_code) {
		$this->query = "SELECT count(cmemo_id) AS cmemo_cnt FROM cmemos WHERE cmemo_cust_code = '$cust_code' ";
		if ($arr = $this->getAR()) return $arr[0][cmemo_cnt];
		return false;
	}

	function deleteCmemo($code) {
		$query = "delete from cmemos where cmemo_id='$code'";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getCmemoSumAged($code, $begin="", $end="", $beg_eq="t", $end_eq="t") {
		$this->query = "SELECT sum(cmemo_amt+cmemo_tax_amt+cmemo_freight_amt) AS amt FROM cmemos ";
		$this->query .= "WHERE cmemo_cust_code = '$code' ";
		if (!empty($begin)) {
			if ($beg_eq=="t") $this->query .= "AND cmemo_date >= '$begin' ";
			else $this->query .= "AND cmemo_date > '$begin' ";
		}
		if (!empty($end)) {
			if ($end_eq=="t") $this->query .= "AND cmemo_date <= '$end' ";
			else  $this->query .= "AND cmemo_date < '$end' ";
		}
//echo $this->query."<br>";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["amt"];
		return 0;
	}


	function getCmemoFields() {
		$this->query = "SELECT * FROM cmemos LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastCmemo($filter="") {
		$this->query = "SELECT * FROM cmemos ORDER BY cmemo_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstCmemo($filter="") {
		$this->query = "SELECT * FROM cmemos ORDER BY cmemo_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextCmemo($code, $filter="") {
		$this->query = "SELECT * FROM cmemos WHERE cmemo_id < '$code' ORDER BY cmemo_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevCmemo($code, $filter="") {
		$this->query = "SELECT * FROM cmemos WHERE cmemo_id  > '$code' ORDER BY cmemo_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getCmemoFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getCmemo($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevCmemo($code, $filter);
					if (!$rec) $rec = $this->getFirstCmemo($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextCmemo($code, $filter);
					if (!$rec) $rec = $this->getLastCmemo($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstCmemo($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastCmemo($filter);
				} else {
					$rec = $this->getCmemo($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastCmemo($filter);
				} else {
					$rec = $this->getFirstCmemo($filter);
				}
			}
		}
		return $rec;
	}

	function getCmemoList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM cmemos ORDER BY cmemo_id  ";
			else if ($condition == "cust") $this->query = "SELECT * FROM cmemos ORDER BY cmemo_cust_code  ";
			else if ($condition == "tel") $this->query = "SELECT * FROM cmemos ORDER BY cmemo_tel  ";
			else $this->query = "SELECT * FROM cmemos ORDER BY cmemo_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM cmemos WHERE cmemo_id LIKE '$filtertext%' ORDER BY cmemo_id ";
			else if ($condition == "cust") $this->query = "SELECT * FROM cmemos WHERE cmemo_cust_code  LIKE '$filtertext%' ORDER BY cmemo_cust_code, cmemo_id  ";
			else if ($condition == "tel") $this->query = "SELECT * FROM cmemos WHERE cmemo_tel  LIKE '$filtertext%' ORDER BY cmemo_tel, cmemo_id  ";
			else $this->query = "SELECT * FROM cmemos WHERE cmemo_id LIKE '$filtertext%' ORDER BY cmemo_id ";
		}
		if ($reverse == "t") {
			if ($condition == "code") $this->query .= " ";
			else if ($condition == "cust") $this->query .= " DESC ";
			else if ($condition == "tel") $this->query .= " DESC ";
			else $this->query .= " ";
		} else {
			if ($condition == "code") $this->query .= " DESC ";
			else if ($condition == "cust") $this->query .= " ";
			else if ($condition == "tel") $this->query .= " ";
			else $this->query .= " DESC ";
		}
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getCmemoListEx($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		
		$select = "SELECT s.*, sum(k.pickdtl_qty*k.pickdtl_cost ) AS sum_pickdtl ";
		$from = "FROM cmemos s, slsdtls d LEFT OUTER JOIN pickdtls k ON d.slsdtl_id=k.pickdtl_slsdtl_id ";
		$where = " WHERE s.cmemo_id=d.slsdtl_cmemo_id ";
		$orderby = "";
		$groupby = "GROUP BY cmemo_id ";
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
				$orderby .= "ORDER BY cmemo_id ";
			} else if ($condition == "cust") {
				$orderby .= "ORDER BY cmemo_cust_code ";
			} else if ($condition == "tel") {
				$orderby .= "ORDER BY cmemo_tel  ";
			} else {
				$orderby .= "ORDER BY cmemo_id ";
			}
		} else {
			if ($condition == "code") {
				$where .= "AND cmemo_id LIKE '$filtertext%' ";
				$orderby .= "ORDER BY cmemo_id ";
			} else if ($condition == "cust") {
				$where .= "AND cmemo_cust_code LIKE '$filtertext%' ";
				$orderby .= "ORDER BY cmemo_cust_code, cmemo_id  ";
			} else if ($condition == "tel") {
				$where .= "AND cmemo_tel LIKE '$filtertext%' ";
				$orderby .= "ORDER BY cmemo_tel, cmemo_id  ";
			} else {
				$where = "AND cmemo_id LIKE '$filtertext%' ";
				$orderby = "ORDER BY cmemo_id ";
			}
		}
		if ($reverse == "t") {
			if ($condition == "code") $orderby .= " ";
			else if ($condition == "cust") $orderby .= " DESC ";
			else if ($condition == "tel") $orderby .= " DESC ";
			else $orderby .= " ";
		} else {
			if ($condition == "code") $orderby .= " DESC ";
			else if ($condition == "cust") $orderby .= " ";
			else if ($condition == "tel") $orderby .= " ";
			else $orderby .= " DESC ";
		}
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $groupby $orderby $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getCmemoListAvl($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$this->query = "SELECT s.*, sum(p.pickdtl_qty) as sum_pickdtl_qty  FROM cmemos s, slsdtls d LEFT OUTER JOIN pickdtls p ON d.slsdtl_id = p.pickdtl_slsdtl_id WHERE s.cmemo_id = d.slsdtl_cmemo_id AND s.cmemo_cust_code = '$condition' GROUP BY s.cmemo_id ";
		$this->query = "SELECT s.* FROM cmemos s, slsdtls d WHERE s.cmemo_id=d.slsdtl_cmemo_id AND s.cmemo_cust_code='$condition' GROUP BY s.cmemo_id HAVING sum(d.slsdtl_qty)>sum(d.slsdtl_qty_picked)";
		if ($reverse != "t") $this->query .= " ORDER BY s.cmemo_date DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getCmemoRows($condition="", $filtertext="") {

		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(cmemo_id) AS numrows FROM cmemos ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(cmemo_id) AS numrows FROM cmemos WHERE cmemo_id LIKE '$filtertext%' ";
			else if ($condition == "cust") $this->query = "SELECT count(cmemo_id) AS numrows FROM cmemos WHERE cmemo_cust_code  LIKE '$filtertext%' ";
			else if ($condition == "tel") $this->query = "SELECT count(cmemo_id) AS numrows FROM cmemos WHERE cmemo_tel  LIKE '$filtertext%' ";
			else $this->query = "SELECT count(cmemo_id) AS numrows FROM cmemos WHERE cmemo_id LIKE '$filtertext%' ";
		}

		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getCmemoRowsAvl($cust_code) {
		$this->query = "SELECT cmemo_id FROM cmemos s, slsdtls d WHERE s.cmemo_id=d.slsdtl_cmemo_id AND s.cmemo_cust_code='$cust_code' GROUP BY s.cmemo_id HAVING sum(d.slsdtl_qty)>sum(d.slsdtl_qty_picked)";
		return $this->getARRow();
	}

	function getCmemoStmt($cust_code, $fr="", $to="") {
		$where  = " WHERE cmemo_cust_code='$cust_code' ";
		if (!empty($fr) && $fr != "0000-00-00") $where .= " AND cmemo_date >= '$fr' ";
		if (!empty($to) && $to != "0000-00-00") $where .= " AND cmemo_date <= '$to' ";
		
		$this->query = "SELECT cmemo_id, cmemo_date, cmemo_tax_amt, cmemo_freight_amt, cmemo_amt+cmemo_tax_amt+cmemo_freight_amt AS cmemo_total, cmemo_taxrate FROM cmemos $where ORDER BY cmemo_date ";
//echo $this->query;
//echo "<br><br>";
		if ($arr = $this->getARRaw()) return $arr;
		return array();
	}

	function getCmemoRange($fr="", $to="", $ord="", $rev="t", $key="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$from = "FROM cmemos ";
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND $ord >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $ord <= '$to' ";
		}
		$orderby = "";
		if (!empty($key)) $ord = $key;
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev != "t") $orderby .= " DESC ";
		}
		$this->query = "$select $from $where $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getCmemoRangeState($fr="", $to="", $state="ALL", $ord="", $rev="t", $key="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT m.* ";
		$from = "FROM cmemos m, custs c ";
		$where = "WHERE m.cmemo_cust_code=c.cust_code ";
		if ($state!="ALL") $where .= "AND c.cust_state LIKE '$state' ";
		if (!empty($fr)) {
			$where .= " AND m.$ord >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND m.$ord <= '$to' ";
		}
		$orderby = "";
		if (!empty($key)) $ord = $key;
		if (!empty($ord)) {
			$orderby = " ORDER BY m.$ord ";
			if ($rev != "t") $orderby .= " DESC ";
		}
		$this->query = "$select $from $where $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getCmemoRangeSum($fr="", $to="", $key="", $ord="", $rev="f") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.cmemo_amt) as cmemo_sum, sum(p.cmemo_freight_amt) as cmemo_freight_sum, sum(p.cmemo_tax_amt) as cmemo_tax_sum ";
		$from = "FROM cmemos p, custs c ";
		$where = "WHERE p.cmemo_cust_code=c.cust_code ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		$groupby = " GROUP BY p.cmemo_cust_code";
		$orderby = "";
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev == "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getCmemoRangeSumState($fr="", $to="", $state="ALL", $key="", $ord="", $rev="f") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.cmemo_amt) as cmemo_sum, sum(p.cmemo_freight_amt) as cmemo_freight_sum, sum(p.cmemo_tax_amt) as cmemo_tax_sum ";
		$from = "FROM cmemos p, custs c ";
		$where = "WHERE p.cmemo_cust_code=c.cust_code ";
		if ($state!="ALL") $where .= "AND c.cust_state LIKE '$state' ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		$groupby = " GROUP BY p.cmemo_cust_code";
		$orderby = "";
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev == "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getCmemoSumBest($fr="", $to="", $rev="f", $limit=50) {
		$select = "SELECT c.* ";
		$select .= ", sum(p.cmemo_amt) as total_cmemo, sum(p.cmemo_freight_amt) as total_freight, ";
		$select .= "sum(p.cmemo_tax_amt) as total_tax, ";
		$select .= "sum(p.cmemo_amt+p.cmemo_freight_amt+p.cmemo_tax_amt) as total ";
		$from = "FROM cmemos p, custs c ";
		$where = "WHERE p.cmemo_cust_code=c.cust_code ";
		if (!empty($fr)) {
			$where .= " AND cmemo_date >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND cmemo <= '$to' ";
		}
		$groupby = " GROUP BY p.cmemo_cust_code";
		$orderby = " ORDER BY total ";
		if ($rev == "f") $orderby .= " DESC ";
		if ($limit>0) $orderby .= " LIMIT 0, $limit ";

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

}
?>
