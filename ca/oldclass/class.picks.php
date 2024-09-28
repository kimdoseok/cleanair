<?php
include_once("class.ar.php");

class Picks extends AR {

	var $fieldlist;

	function insertPicks($arr) {
		if ($lastid = $this->insertAR("picks", $arr)) return $lastid;
		return false;
	}

	function updatePicks($code, $arr) {
		if ($this->updateAR("picks", "pick_id", $code, $arr)) return true;
		return false;
	}

	function getPicks($code) {
		$this->query = "SELECT * FROM picks WHERE pick_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getPicksSumAged($code, $begin="", $end="", $beg_eq="t", $end_eq="t") {
		$this->query = "SELECT sum(pick_amt+pick_tax_amt+pick_freight_amt) AS amt FROM picks ";
		$this->query .= "WHERE pick_cust_code = '$code' ";
		if (!empty($begin)) {
			if ($beg_eq=="t") $this->query .= "AND pick_date >= '$begin' ";
			else $this->query .= "AND pick_date > '$begin' ";
		}
		if (!empty($end)) {
			if ($end_eq=="t") $this->query .= "AND pick_date <= '$end' ";
			else  $this->query .= "AND pick_date < '$end' ";
		}
//echo $this->query."<br>";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["amt"];
		return 0;
	}

	function getPicksDepositSum($sale_id) {
		$this->query = "SELECT pickdtl_pick_id FROM pickdtls p, slsdtls s ";
		$this->query .= "WHERE p.pickdtl_slsdtl_id = s.slsdtl_id AND s.slsdtl_sale_id = $sale_id ";
		$arr = $this->getAR();
		if (!$arr) return 0;
		$instr = "";
		for ($i=0;$i<count($arr);$i++) {
			if ($i!=0) $instr .= ",";
			$instr .= $arr[$i]["pickdtl_pick_id"];
		}
		$this->query = "SELECT sum(pick_deposit_amt) as amt FROM picks WHERE pick_id IN $instr ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["amt"];
		return 0;
	}

	function getPicksTaxRatio($cust="", $taxcode="", $fr="", $to="", $state="") {
		$lower = 0;
		$upper = 0;
		$select = "SELECT sum(d.pickdtl_cost * d.pickdtl_qty) as total ";
		$from = "FROM picks p, pickdtls d, slsdtls s, custs c ";
		$where = "WHERE p.pick_id=d.pickdtl_pick_id ";
		$where .= "AND d.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= "AND p.pick_cust_code=c.cust_code ";
		if (!empty($cust)) $where .= "AND p.pick_cust_code='$cust' ";
		if (!empty($taxcode)) $where .= "AND c.cust_tax_code='$taxcode' ";
		if (!empty($fr)) $where .= "AND p.pick_date>='$fr' ";
		if (!empty($to)) $where .= "AND p.pick_date<='$to' ";
		if (!empty($state)) $where .= "AND p.pick_state='$state' ";
		$this->query = "$select $from $where ";
		$arr = $this->getAR();
		if ($arr) $lower = $arr[0]["Total"];
		if ($lower<=0) return 0;
		$this->query .= "AND s.slsdtl_taxable='t' ";
//echo $this->query."<br>";
		$arr = $this->getAR();
		if ($arr) $upper = $arr[0]["Total"];
		if ($upper <= 0) $ratio = 0;
		else $ratio = $upper/$lower;
//echo "$ratio = $upper/$lower <br>";
		return $ratio;
	}

	function getPicksTaxRatioMonth($cust="", $taxcode="", $year="", $months="", $state="") {
		$lower = 0;
		$upper = 0;
		$select = "SELECT sum(d.pickdtl_cost * d.pickdtl_qty) as total ";
		$from = "FROM picks p, pickdtls d, slsdtls s, custs c ";
		$where = "WHERE p.pick_id=d.pickdtl_pick_id ";
		$where .= "AND d.pickdtl_slsdtl_id=s.slsdtl_id ";
		$where .= "AND p.pick_cust_code=c.cust_code ";
		if (!empty($cust)) $where .= "AND p.pick_cust_code='$cust' ";
		if (!empty($taxcode)) $where .= "AND c.cust_tax_code='$taxcode' ";
		$where .= "AND ( ";
		$first = 1;
		foreach ($months as $i) {
			$month = $i+1;
			$fr = $year."-".$month."-1";
			$to = $year."-".$month."-".date("t",strtotime($fr));
			if ($first>0) {
				$first =0;
			} else {
				$where .= "OR ";
			}
			$where .= " (p.pick_date>='$fr' AND p.pick_date<='$to') ";
		}
		$where .= ") ";
		if (!empty($state)) $where .= "AND p.pick_state='$state' ";
		$this->query = "$select $from $where ";
		$arr = $this->getAR();
		if ($arr) $lower = $arr[0]["Total"];
		if ($lower<=0) return 0;
		$where .= "AND s.slsdtl_taxable='t' ";
		$this->query = "$select $from $where ";
		$arr = $this->getAR();
		if ($arr) $upper = $arr[0]["Total"];
		if ($upper <= 0) $ratio = 0;
		else $ratio = $upper/$lower;
//echo "$ratio = $upper/$lower <br>";
		return $ratio;
	}


	function getReceiptSumAged($code, $begin="", $end="", $beg_eq="t", $end_eq="t") {
		$this->query = "SELECT sum(rcpt_amt) AS amt FROM rcpts ";
		$this->query .= "WHERE rcpt_cust_code = '$code' ";
		if (!empty($begin)) {
			if ($beg_eq=="t") $this->query .= "AND rcpt_date >= '$begin' ";
			else $this->query .= "AND rcpt_date > '$begin' ";
		}
		if (!empty($end)) {
			if ($end_eq=="t") $this->query .= "AND rcpt_date <= '$end' ";
			else  $this->query .= "AND rcpt_date < '$end' ";
		}
//echo $this->query;
		$arr = $this->getAR();
		if ($arr) return $arr[0]["amt"];
		return 0;
	}

	function getPicksCount($cust_code) {
		$this->query = "SELECT count(pick_id) AS pick_cnt FROM picks WHERE pick_cust_code = '$cust_code' ";
		if ($arr = $this->getAR()) return $arr[0][pick_cnt];
		return false;
	}

	function getPickMaxId() {
		$this->query = "SELECT max(pick_id) AS maxid FROM picks ";
		if ($arr = $this->getAR($code)) return $arr[0][maxid];
		return false;
	}

	function deletePicks($code) {
		$query = "delete from picks where pick_id='$code'";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function increasePicks($code, $fld_name, $qty) {
		$query = "UPDATE picks SET $fld_name = $fld_name + $qty WHERE pick_id=$code";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getPicksFields() {
		$this->query = "SELECT * FROM picks LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastPicks($filter="") {
		$this->query = "SELECT * FROM picks ORDER BY pick_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPicks($filter="") {
		$this->query = "SELECT * FROM picks ORDER BY pick_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPicks($code, $filter="") {
		$this->query = "SELECT * FROM picks WHERE pick_id > '$code' ORDER BY pick_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPicks($code, $filter="") {
		$this->query = "SELECT * FROM picks WHERE pick_id  < '$code' ORDER BY pick_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPicksFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPicks($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPicks($code, $filter);
					if (!$rec) $rec = $this->getFirstPicks($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPicks($code, $filter);
					if (!$rec) $rec = $this->getLastPicks($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPicks($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPicks($filter);
				} else {
					$rec = $this->getPicks($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPicks($filter);
				} else {
					$rec = $this->getFirstPicks($filter);
				}
			}
		}
		return $rec;
	}

	function getPickShipViaList() {
		$this->query = "SELECT pick_shipvia FROM picks GROUP BY pick_shipvia ORDER BY pick_shipvia";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr;
		return false;
	}

	function getPicksList($condition="", $filtertext="", $reverse="f", $page=1, $limit=100) {
		if ($page < 1) $page = 0;
		else $page--;

		$select = "SELECT * ";
		$from = "FROM picks p ";
		$where = "WHERE 1 ";
		$having = "";
		$orderby = "";
		$groupby = "GROUP BY pick_id ";

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
				$orderby .= "ORDER BY pick_id ";
			} else if ($condition == "cust") {
				$orderby .= "ORDER BY pick_cust_code ";
			} else if ($condition == "date") {
				$orderby .= "ORDER BY pick_date  ";
			} else if ($condition == "pdate") {
				$orderby .= "ORDER BY pick_prom_date  ";
			} else if ($condition == "ddate") {
				$orderby .= "ORDER BY pick_delv_date  ";
			} else if ($condition == "tel") {
				$orderby .= "ORDER BY pick_tel  ";
			} else {
				$orderby .= "ORDER BY pick_id ";
			}
		} else {
			if ($condition == "code") {
				$where .= "AND pick_id LIKE '$filtertext%' ";
				$orderby .= "ORDER BY pick_id ";
			} else if ($condition == "cust") {
				$where .= "AND pick_cust_code LIKE '$filtertext%' ";
				$orderby .= "ORDER BY pick_cust_code, pick_id ";
			} else if ($condition == "date") {
				$where .= "AND pick_date = '$filtertext' ";
				$orderby .= "ORDER BY pick_date, pick_id ";
			} else if ($condition == "pdate") {
				$where .= "AND pick_prom_date = '$filtertext' ";
				$orderby .= "ORDER BY pick_prom_date ";
			} else if ($condition == "ddate") {
				$where .= "AND pick_delv_date = '$filtertext' ";
				$orderby .= "ORDER BY pick_delv_date, pick_id ";
			} else if ($condition == "tel") {
				$where .= "AND pick_tel LIKE '$filtertext%' ";
				$orderby .= "ORDER BY pick_tel, pick_id  ";
			} else {
				$where .= "AND pick_id LIKE '$filtertext%' ";
				$orderby = "ORDER BY pick_id ";
			}
		}
		if ($reverse == "t") {
			if ($condition == "code") $orderby .= " ";
			else if ($condition == "cust") $orderby .= " DESC ";
			else if ($condition == "date") $orderby .= "";
			else if ($condition == "pdate") $orderby .= "";
			else if ($condition == "ddate") $orderby .= "";
			else if ($condition == "tel") $orderby .= " DESC ";
			else $orderby .= " ";
		} else {
			if ($condition == "code") $orderby .= " DESC ";
			else if ($condition == "cust") $orderby .= " ";
			else if ($condition == "date") $orderby .= " DESC ";
			else if ($condition == "pdate") $orderby .= " DESC ";
			else if ($condition == "ddate") $orderby .= " DESC ";
			else if ($condition == "tel") $orderby .= " ";
			else $orderby .= " DESC ";
		}
	
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $having $groupby $orderby $limit ";
		
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getPicksListEx($condition="", $filtertext="", $reverse="f", $page=1, $limit=100) {
		if ($page < 1) $page = 0;
		else $page--;

		$select = "SELECT p.*, sum(r.rcptdtl_amt) AS sum_rcptdtl ";
		$from = "FROM picks p LEFT OUTER JOIN rcptdtls r ON p.pick_id=r.rcptdtl_pick_id ";
		$where = "WHERE 1 ";
		$having = "";
		$orderby = "";
		$groupby = "GROUP BY pick_id ";

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
				$orderby .= "ORDER BY pick_id ";
			} else if ($condition == "cust") {
				$orderby .= "ORDER BY pick_cust_code ";
			} else if ($condition == "date") {
				$orderby .= "ORDER BY pick_date  ";
			} else if ($condition == "pdate") {
				$orderby .= "ORDER BY pick_prom_date  ";
			} else if ($condition == "ddate") {
				$orderby .= "ORDER BY pick_delv_date  ";
			} else if ($condition == "tel") {
				$orderby .= "ORDER BY pick_tel  ";
			} else {
				$orderby .= "ORDER BY pick_id ";
			}
		} else {
			if ($condition == "code") {
				$where .= "AND pick_id LIKE '$filtertext%' ";
				$orderby .= "ORDER BY pick_id ";
			} else if ($condition == "cust") {
				$where .= "AND pick_cust_code LIKE '$filtertext%' ";
				$orderby .= "ORDER BY pick_cust_code, pick_id ";
			} else if ($condition == "date") {
				$where .= "AND pick_date = '$filtertext' ";
				$orderby .= "ORDER BY pick_date, pick_id ";
			} else if ($condition == "pdate") {
				$where .= "AND pick_prom_date = '$filtertext' ";
				$orderby .= "ORDER BY pick_prom_date ";
			} else if ($condition == "ddate") {
				$where .= "AND pick_delv_date = '$filtertext' ";
				$orderby .= "ORDER BY pick_delv_date, pick_id ";
			} else if ($condition == "tel") {
				$where .= "AND pick_tel LIKE '$filtertext%' ";
				$orderby .= "ORDER BY pick_tel, pick_id  ";
			} else {
				$where = "AND pick_id LIKE '$filtertext%' ";
				$orderby = "ORDER BY pick_id ";
			}
		}
		if ($reverse == "t") {
			if ($condition == "code") $orderby .= " ";
			else if ($condition == "cust") $orderby .= " DESC ";
			else if ($condition == "date") $orderby .= "";
			else if ($condition == "pdate") $orderby .= "";
			else if ($condition == "ddate") $orderby .= "";
			else if ($condition == "tel") $orderby .= " DESC ";
			else $orderby .= " ";
		} else {
			if ($condition == "code") $orderby .= " DESC ";
			else if ($condition == "cust") $orderby .= " ";
			else if ($condition == "date") $orderby .= " DESC ";
			else if ($condition == "pdate") $orderby .= " DESC ";
			else if ($condition == "ddate") $orderby .= " DESC ";
			else if ($condition == "tel") $orderby .= " ";
			else $orderby .= " DESC ";
		}
	
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $having $groupby $orderby $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getPicksListUnpaid($pick_cust_code, $page=1, $limit=100) {
		if ($page < 1) $page = 0;
		else $page--;
//		$select = "SELECT *, sum(r.rcptdtl_amt) AS pick_paid ";
//		$from = "FROM picks p LEFT OUTER JOIN rcptdtls r ON p.pick_id=r.rcptdtl_pick_id ";
//		$groupby = "GROUP BY p.pick_id ";
//		$having = "HAVING p.pick_amt > sum(r.rcptdtl_amt) AND p.pick_cust_code = '$pick_cust_code' ";
//		$orderby = "ORDER BY p.pick_date DESC";
		$select = "SELECT * ";
		$from = "FROM picks p ";
		$having = "HAVING p.pick_amt > p.pick_paid_amt AND p.pick_cust_code = '$pick_cust_code' ";
		$orderby = "ORDER BY p.pick_date DESC";
		$this->query = "$select $from $where $groupby $having $orderby";
//echo $this->query;

		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getPicksRows() {
		$this->query = "SELECT count(pick_id) AS numrows FROM picks";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPicksRowsUnpaid($pick_cust_code) {
//		$select = "SELECT p.pick_amt, p.pick_cust_code, count(p.pick_id) AS numrows ";
//		$from = "FROM picks p LEFT OUTER JOIN rcptdtls r ON p.pick_id=r.rcptdtl_ref_code ";
//		$groupby = "GROUP BY p.pick_id ";
//		$having = "HAVING p.pick_amt > sum(r.rcptdtl_amt) AND p.pick_cust_code = '$pick_cust_code' ";
//		$orderby = "ORDER BY p.pick_date DESC";
		$select = "SELECT count(p.pick_id) AS numrows ";
		$from = "FROM picks p ";
		$having = "WHERE p.pick_amt > p.pick_paid_amt AND p.pick_cust_code = '$pick_cust_code' ";
		$orderby = "ORDER BY p.pick_date DESC";
		$this->query = "$select $from $where $groupby $having $orderby";
//echo $this->query."<br>";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPicksStmt($cust_code, $fr="", $to="") {
		$where  = " WHERE pick_cust_code='$cust_code' ";
		if (!empty($fr) && $fr != "0000-00-00") $where .= " AND pick_date >= '$fr' ";
		if (!empty($to) && $to != "0000-00-00") $where .= " AND pick_date <= '$to' ";
		
		$this->query = "SELECT pick_id, pick_date, pick_code, pick_tax_amt, pick_freight_amt, pick_amt+pick_tax_amt+pick_freight_amt AS pick_total, pick_taxrate FROM picks $where ORDER BY pick_date ";
//echo $this->query;
//echo "<br><br>";
		if ($arr = $this->getARRaw()) return $arr;
		return NULL;
	}

	function getPicksRange($fr="", $to="", $ord="", $rev="t", $key="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$from = "FROM picks ";
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

	function getPicksRangeState($fr="", $to="", $state="ALL", $ord="", $rev="t", $key="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT p.* ";
		$from = "FROM picks p, custs c ";
		$where = "WHERE p.pick_cust_code=c.cust_code ";
		if ($state!="ALL") $where .= "AND c.cust_state LIKE '$state' ";
		if (!empty($fr)) {
			$where .= " AND p.$ord >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND p.$ord <= '$to' ";
		}
		$orderby = "";
		if (!empty($key)) $ord = $key;
		if (!empty($ord)) {
			$orderby = " ORDER BY p.$ord ";
			if ($rev != "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getPicksRangeSum($fr="", $to="", $key="", $ord="", $rev="f") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.pick_amt) as pick_sum, sum(p.pick_freight_amt) as pick_freight_sum, sum(p.pick_tax_amt) as pick_tax_sum ";
		$from = "FROM picks p, custs c ";
		$where = "WHERE p.pick_cust_code=c.cust_code ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		$groupby = " GROUP BY pick_cust_code";
		$orderby = "";
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev == "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getPicksRangeReps($fdate="", $tdate="", $frep="", $trep="", $summary="t") {
		$from = "FROM picks p, custs c LEFT OUTER JOIN slsreps r ON c.cust_slsrep=r.slsrep_code ";
		$where = "WHERE p.pick_cust_code=c.cust_code ";
		if (!empty($fdate)) $where .= " AND p.pick_date >= '$fdate' ";
		if (!empty($tdate)) $where .= " AND p.pick_date <= '$tdate' ";
		if (!empty($frep)) $where .= " AND c.cust_slsrep >= '$frep' ";
		if (!empty($trep)) $where .= " AND c.cust_slsrep <= '$trep' ";
		if ($summary=="t") {
			$select = "SELECT r.slsrep_code, r.slsrep_name, sum(p.pick_amt) as amount, sum(p.pick_tax_amt) as tax, sum(p.pick_freight_amt) as freight  ";
			$orderby = "ORDER BY c.cust_slsrep ";
			$groupby = "GROUP BY c.cust_slsrep ";
		} else { // detail
			$select = "SELECT r.slsrep_code, r.slsrep_name, p.pick_id, p.pick_date, c.cust_code, c.cust_name, p.pick_amt, p.pick_tax_amt, p.pick_freight_amt  ";
			$orderby = "ORDER BY c.cust_slsrep, p.pick_date ";
		}
		$this->query = "$select $from $where $groupby $orderby";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getPicksRangeSumState($fr="", $to="", $state="ALL", $key="", $ord="", $rev="f") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.pick_amt) as pick_sum, sum(p.pick_freight_amt) as pick_freight_sum, sum(p.pick_tax_amt) as pick_tax_sum ";
		$from = "FROM picks p, custs c ";
		$where = "WHERE p.pick_cust_code=c.cust_code ";
		if ($state!="ALL") $where .= "AND c.cust_state LIKE '$state' ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		$groupby = " GROUP BY pick_cust_code";
		$orderby = "";
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev == "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getPicksSumBest($fr="", $to="", $rev="f", $limit=50) {
		$select = "SELECT c.* ";
		$select .= ", sum(p.pick_amt+p.pick_freight_amt+p.pick_tax_amt) as total ";
		$select .= ", sum(p.pick_amt) as total_pick ";
		$select .= ", sum(p.pick_freight_amt) as total_freight ";
		$select .= ", sum(p.pick_tax_amt) as total_tax ";
		$from = "FROM picks p, custs c ";
		$where = "WHERE p.pick_cust_code=c.cust_code ";
		if (!empty($fr)) {
			$where .= " AND pick_date >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND pick_date <= '$to' ";
		}
		$groupby = " GROUP BY pick_cust_code";
		$orderby = " ORDER BY total ";
		if ($rev == "t") $orderby .= " DESC ";
		if ($limit>0) $orderby .= " LIMIT 0, $limit ";

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}


}

?>
