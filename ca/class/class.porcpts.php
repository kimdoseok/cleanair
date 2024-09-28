<?php
include_once("class.ap.php");

class PoRcpts extends AP {

	var $fieldlist;
	var $openpaymentflag = "";

	function insertPoRcpts($arr) {
		if ($lastid = $this->insertAP("porcpts", $arr)) return $lastid;
		return false;
	}

	function updatePoRcpts($code, $arr) {
		if ($this->updateAP("porcpts", "porcpt_id", $code, $arr)) return true;
		return false;
	}

	function deletePoRcpts($code) {
		$query = "delete from porcpts where porcpt_id='$code'";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function updatePoRcptsAmt($code, $newamt, $oldamt, $flg) {
		$amt = $newamt - $oldamt;
		if ($flg == "t") {
			$this->query = "UPDATE porcpts SET x = x + $amt WHERE porcpt_id='$code' ";
		} else {
			$this->query = "UPDATE porcpts SET x = x + $amt WHERE porcpt_id='$code' ";
		}
		if ($this->updateAPRaw()) return true;
		else return false;
	}

	function getPoRcpts($code) {
		$this->query = "SELECT * FROM porcpts WHERE porcpt_id = '$code' ";
		$this->query .= "LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getPoRcptsSales($code) {
		$this->query = "SELECT h.* FROM porcpts h, rcptdtls d ";
		$this->query .= "WHERE h.porcpt_id=d.rcptdtl_porcpt_id AND d.rcptdtl_sale_id='$code' ";
		$this->query .= "GROUP BY h.porcpt_id ";

		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getPoRcptsLast($cust) {
		$this->query = "SELECT * FROM porcpts WHERE porcpt_cust_code = '$cust' ";
		$this->query .= "ORDER BY porcpt_date DESC LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getPoRcptsCount($cust_code) {
		$this->query = "SELECT count(porcpt_id) AS porcpt_cnt ";
		$this->query .= "FROM porcpts WHERE porcpt_cust_code = '$cust_code' ";
		if ($arr = $this->getAP()) return $arr[0][porcpt_cnt];
		return false;
	}

	function getPoRcptsSumAged($code, $begin="", $end="", $beg_eq="t", $end_eq="t") {
		$this->query = "SELECT sum(porcpt_amt+porcpt_disc_amt) AS amt FROM porcpts ";
		$this->query .= "WHERE porcpt_cust_code = '$code' ";
		if (!empty($begin)) {
			if ($beg_eq=="t") $this->query .= "AND porcpt_date >= '$begin' ";
			else $this->query .= "AND porcpt_date > '$begin' ";
		}
		if (!empty($end)) {
			if ($end_eq=="t") $this->query .= "AND porcpt_date <= '$end' ";
			else  $this->query .= "AND porcpt_date < '$end' ";
		}
//echo $this->query."<br>";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["amt"];
		return 0;
	}

	function getPoRcptsFields() {
		$this->query = "SELECT * FROM porcpts LIMIT 0 ";
		if ($arr = $this->getAPFields($this->query)) return $arr;
		return false;
	}

	function getLastPoRcpts($filter="") {
		$this->query = "SELECT * FROM porcpts ";
		$this->query .= "ORDER BY porcpt_id DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPoRcpts($filter="") {
		$this->query = "SELECT * FROM porcpts ORDER BY porcpt_id ";
		$this->query .= "LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPoRcpts($code, $filter="") {
		$this->query = "SELECT * FROM porcpts WHERE porcpt_id > '$code' ";
		$this->query .= "ORDER BY porcpt_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPoRcpts($code, $filter="") {
		$this->query = "SELECT * FROM porcpts WHERE porcpt_id  < '$code' ";
		$this->query .= "ORDER BY porcpt_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPoRcptsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPoRcpts($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPoRcpts($code, $filter);
					if (!$rec) $rec = $this->getFirstPoRcpts($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPoRcpts($code, $filter);
					if (!$rec) $rec = $this->getLastPoRcpts($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPoRcpts($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPoRcpts($filter);
				} else {
					$rec = $this->getPoRcpts($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPoRcpts($filter);
				} else {
					$rec = $this->getFirstPoRcpts($filter);
				}
			}
		}
		return $rec;
	}

	function getPoRcptsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
				$this->query = "SELECT * FROM porcpts ";
				$this->query .= "ORDER BY porcpt_id  ";
			} else if ($condition == "vend") {
				$this->query = "SELECT * FROM porcpts ";
				$this->query .= "ORDER BY porcpt_vend_code ";
			} else if ($condition == "date") {
				$this->query = "SELECT * FROM porcpts ";
				$this->query .= "ORDER BY porcpt_date  ";
			} else {
				$this->query = "SELECT * FROM porcpts ";
				$this->query .= "ORDER BY porcpt_id ";
			}
		} else {
			if ($condition == "code") {
				$this->query = "SELECT * FROM porcpts WHERE porcpt_id  LIKE '$filtertext%' ";
				$this->query .= "ORDER BY porcpt_id ";
			} else if ($condition == "vend") {
				$this->query = "SELECT * FROM porcpts WHERE porcpt_cust_code LIKE '%$filtertext%' ";
				$this->query .= "ORDER BY porcpt_cust_code ";
			} else if ($condition == "date") {
				$this->query = "SELECT * FROM porcpts WHERE porcpt_date = '$filtertext' ";
				$this->query .= "ORDER BY porcpt_date ";
			} else {
				$this->query = "SELECT * FROM porcpts ";
				if ($this->openpaymentflag=="t") $this->query .= "WHERE porcpt_type='op' ";
				$this->query .= "ORDER BY porcpt_id ";
			}
		}
		if ($reverse != "t") {
			if ($condition == "code") $this->query .= " DESC ";
			else if ($condition == "vend") $this->query .= "";
			else if ($condition == "date") $this->query .= " DESC ";
			else $this->query .= " DESC ";
		} else {
			if ($condition == "code") $this->query .= "";
			else if ($condition == "vend") $this->query .= " DESC ";
			else if ($condition == "date") $this->query .= "";
			else $this->query .= "";
		}
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAP();
	}

	function getPoRcptsRows($condition="", $filtertext="") {
		if ($condition == "code") {
			$this->query = "SELECT count(porcpt_id) AS numrows FROM porcpts WHERE porcpt_id  LIKE '$filtertext%' ";
			if ($this->openpaymentflag=="t") $this->query .= "AND porcpt_type='op' ";
		} else if ($condition == "cust") {
			$this->query = "SELECT count(porcpt_id) AS numrows FROM porcpts WHERE porcpt_cust_code LIKE '%$filtertext%' ";
			if ($this->openpaymentflag=="t") $this->query .= "AND porcpt_type='op' ";
		} else if ($condition == "date") {
			$this->query = "SELECT count(porcpt_id) AS numrows FROM porcpts WHERE porcpt_date = '$filtertext' ";
			if ($this->openpaymentflag=="t") $this->query .= "AND porcpt_type='op' ";
		} else if ($condition == "desc") {
			$this->query = "SELECT count(porcpt_id) AS numrows FROM porcpts WHERE porcpt_desc  LIKE '%$filtertext%' ";
			if ($this->openpaymentflag=="t") $this->query .= "AND porcpt_type='op' ";
		} else if ($condition == "check") {
			$this->query = "SELECT count(porcpt_id) AS numrows FROM porcpts WHERE porcpt_check_no LIKE '$filtertext%' ";
			if ($this->openpaymentflag=="t") $this->query .= "AND porcpt_type='op' ";
		} else if ($condition == "amt") {
			$this->query = "SELECT count(porcpt_id) AS numrows FROM porcpts WHERE porcpt_amt = $filtertext ";
			if ($this->openpaymentflag=="t") $this->query .= "AND porcpt_type='op' ";
		} else if ($condition == "type") {
			$this->query = "SELECT count(porcpt_id) AS numrows FROM porcpts WHERE porcpt_type LIKE '$filtertext' ";
			if ($this->openpaymentflag=="t") $this->query .= "AND porcpt_type='op' ";
		} else {
			$this->query = "SELECT count(porcpt_id) AS numrows FROM porcpts ";
			if ($this->openpaymentflag=="t") $this->query .= "WHERE porcpt_type='op' ";
		}
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPoRcptsStmt($cust_code, $fr="", $to="") {
		$where  = " WHERE porcpt_cust_code='$cust_code' ";
		if (!empty($fr) && $fr != "0000-00-00") $where .= " AND porcpt_date >= '$fr' ";
		if (!empty($to) && $to != "0000-00-00") $where .= " AND porcpt_date <= '$to' ";
		if ($this->openpaymentflag=="t") $where .= "AND porcpt_type='op' ";
		else $where .= "AND porcpt_type!='op' ";
		
		$this->query = "SELECT porcpt_id, porcpt_date, porcpt_amt, porcpt_disc_amt, porcpt_type, porcpt_check_no FROM porcpts $where ORDER BY porcpt_date ";
//echo $this->query;
//echo "<br><br>";
		if ($arr = $this->getAPRaw()) return $arr;
		return NULL;
	}

	function getPoRcptsRange($fr="", $to="", $ord="", $rev="t", $key="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$from = "FROM porcpts ";
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND $ord >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $ord <= '$to' ";
		}
		if ($this->openpaymentflag=="t") $where .= "AND porcpt_type='op' ";
		else $where .= "AND porcpt_type!='op' ";
		$orderby = "";
		if (!empty($key)) $ord = $key;
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev != "t") $orderby .= " DESC ";
		}
		$this->query = "$select $from $where $orderby ";
//echo $this->query."<br>";
		return $this->getAPRaw();
	}

	function getPoRcptsRangeSum($fr="", $to="", $key="", $ord="", $rev="f") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.porcpt_amt) as porcpt_sum, sum(p.porcpt_disc_amt) as porcpt_disc_sum ";
		$from = "FROM porcpts p, custs c ";
		$where = "WHERE p.porcpt_cust_code=c.cust_code ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		if ($this->openpaymentflag=="t") $where .= "AND porcpt_type='op' ";
		else $where .= "AND porcpt_type!='op' ";
		$groupby = " GROUP BY porcpt_cust_code";
		$orderby = "";
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev == "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getAPRaw();
	}

	function getPoRcptsRangeSumTax($fr="", $to="", $key="", $ord="", $rev="f", $state="", $grp="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.porcpt_amt) as porcpt_sum, sum(p.porcpt_disc_amt) as porcpt_disc_sum ";
		$from = "FROM porcpts p, custs c, taxrates x ";
		$where = "WHERE p.porcpt_cust_code=c.cust_code ";
		$where .= "AND x.taxrate_code=c.cust_tax_code ";
		$where .= "AND p.porcpt_type != 'ca' ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		if (!empty($state)) {
			$where .= "AND c.cust_state='$state' ";
		}
		if ($this->openpaymentflag=="t") $where .= "AND porcpt_type='op' ";
		else $where .= "AND porcpt_type!='op' ";
		$groupby = "";
		if (!empty($grp)) {
			$groupby = " GROUP BY $grp";
		}
		$orderby = "";
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev == "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getAPRaw();
	}


}

?>