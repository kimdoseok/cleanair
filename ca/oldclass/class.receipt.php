<?php
include_once("class.ar.php");

class Receipt extends AR {

	var $fieldlist;
	var $openpayment;

	function Receipt() {
		$openpayment="";
	}

	function insertReceipt($arr) {
		if ($lastid = $this->insertAR("rcpts", $arr)) return $lastid;
		return false;
	}

	function updateReceipt($code, $arr) {
		if ($this->updateAR("rcpts", "rcpt_id", $code, $arr)) return true;
		return false;
	}

	function deleteReceipt($code) {
		$query = "delete from rcpts where rcpt_id='$code'";
		if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function updateReceiptAmt($code, $newamt, $oldamt, $flg) {
		$amt = $newamt - $oldamt;
		if ($flg == "t") {
			$this->query = "UPDATE rcpts SET x = x + $amt WHERE rcpt_id='$code' ";
		} else {
			$this->query = "UPDATE rcpts SET x = x + $amt WHERE rcpt_id='$code' ";
		}
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function getReceipt($code) {
		$this->query = "SELECT * FROM rcpts WHERE rcpt_id = '$code' ";
		if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		$this->query .= "LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getReceiptSales($code) {
		$this->query = "SELECT h.* FROM rcpts h, rcptdtls d WHERE h.rcpt_id=d.rcptdtl_rcpt_id AND d.rcptdtl_sale_id='$code' ";
		if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		$this->query .= "GROUP BY h.rcpt_id ";

		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getReceiptLast($cust) {
		$this->query = "SELECT * FROM rcpts WHERE rcpt_cust_code = '$cust' ";
		if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		$this->query .= "ORDER BY rcpt_date DESC LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getReceiptCount($cust_code) {
		$this->query = "SELECT count(rcpt_id) AS rcpt_cnt FROM rcpts WHERE rcpt_cust_code = '$cust_code' ";
		if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		if ($arr = $this->getAR()) return $arr[0][rcpt_cnt];
		return false;
	}

	function getReceiptSumAged($code, $begin="", $end="", $beg_eq="t", $end_eq="t") {
		$this->query = "SELECT sum(rcpt_amt+rcpt_disc_amt) AS amt FROM rcpts ";
		$this->query .= "WHERE rcpt_cust_code = '$code' ";
		if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		if (!empty($begin)) {
			if ($beg_eq=="t") $this->query .= "AND rcpt_date >= '$begin' ";
			else $this->query .= "AND rcpt_date > '$begin' ";
		}
		if (!empty($end)) {
			if ($end_eq=="t") $this->query .= "AND rcpt_date <= '$end' ";
			else  $this->query .= "AND rcpt_date < '$end' ";
		}
//echo $this->query."<br>";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["amt"];
		return 0;
	}

	function getReceiptFields() {
		$this->query = "SELECT * FROM rcpts LIMIT 0 ";
		if ($arr = $this->getARFields($this->query)) return $arr;
		return false;
	}

	function getLastReceipt($filter="") {
		$this->query = "SELECT * FROM rcpts ";
		if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
		$this->query .= "ORDER BY rcpt_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstReceipt($filter="") {
		$this->query = "SELECT * FROM rcpts ORDER BY rcpt_id ";
		if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
		$this->query .= "LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextReceipt($code, $filter="") {
		$this->query = "SELECT * FROM rcpts WHERE rcpt_id > '$code' ";
		if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		$this->query .= "ORDER BY rcpt_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevReceipt($code, $filter="") {
		$this->query = "SELECT * FROM rcpts WHERE rcpt_id  < '$code' ";
		if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		$this->query .= "ORDER BY rcpt_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getReceiptFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getReceipt($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevReceipt($code, $filter);
					if (!$rec) $rec = $this->getFirstReceipt($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextReceipt($code, $filter);
					if (!$rec) $rec = $this->getLastReceipt($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstReceipt($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastReceipt($filter);
				} else {
					$rec = $this->getReceipt($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastReceipt($filter);
				} else {
					$rec = $this->getFirstReceipt($filter);
				}
			}
		}
		return $rec;
	}

	function getReceiptList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
				$this->query = "SELECT * FROM rcpts ";
				if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_id  ";
			} else if ($condition == "cust") {
				$this->query = "SELECT * FROM rcpts ";
				if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_cust_code ";
			} else if ($condition == "date") {
				$this->query = "SELECT * FROM rcpts ";
				if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_date  ";
			} else if ($condition == "desc") {
				$this->query = "SELECT * FROM rcpts ";
				if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_desc  ";
			} else if ($condition == "check") {
				$this->query = "SELECT * FROM rcpts ";
				if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_check_no ";
			} else if ($condition == "amt") {
				$this->query = "SELECT * FROM rcpts ";
				if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_amt  ";
			} else if ($condition == "type") {
				$this->query = "SELECT * FROM rcpts ";
				if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_type  ";
			} else {
				$this->query = "SELECT * FROM rcpts ";
				if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_id ";
			}
		} else {
			if ($condition == "code") {
				$this->query = "SELECT * FROM rcpts WHERE rcpt_id  LIKE '$filtertext%' ";
				if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_id ";
			} else if ($condition == "cust") {
				$this->query = "SELECT * FROM rcpts WHERE rcpt_cust_code LIKE '%$filtertext%' ";
				if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_cust_code ";
			} else if ($condition == "date") {
				$this->query = "SELECT * FROM rcpts WHERE rcpt_date = '$filtertext' ";
				if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_date ";
			} else if ($condition == "desc") {
				$this->query = "SELECT * FROM rcpts WHERE rcpt_desc LIKE '%$filtertext%' ";
				if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_desc  ";
			} else if ($condition == "check") {
				$this->query = "SELECT * FROM rcpts WHERE rcpt_check_no LIKE '$filtertext%' ";
				if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_check_no ";
			} else if ($condition == "amt") {
				$this->query = "SELECT * FROM rcpts WHERE rcpt_amt =$filtertext ";
				if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_amt ";
			} else if ($condition == "type") {
				$this->query = "SELECT * FROM rcpts WHERE rcpt_type LIKE '$filtertext' ";
				if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_type ";
			} else {
				$this->query = "SELECT * FROM rcpts ";
				if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
				$this->query .= "ORDER BY rcpt_id ";
			}
		}
		if ($reverse != "t") {
			if ($condition == "code") $this->query .= " DESC ";
			else if ($condition == "cust") $this->query .= "";
			else if ($condition == "date") $this->query .= " DESC ";
			else if ($condition == "desc") $this->query .= "";
			else if ($condition == "check") $this->query .= "";
			else if ($condition == "amt") $this->query .= " DESC ";
			else if ($condition == "type") $this->query .= " ";
			else $this->query .= " DESC ";
		} else {
			if ($condition == "code") $this->query .= "";
			else if ($condition == "cust") $this->query .= " DESC ";
			else if ($condition == "date") $this->query .= "";
			else if ($condition == "desc") $this->query .= " DESC ";
			else if ($condition == "check") $this->query .= " DESC ";
			else if ($condition == "amt") $this->query .= "";
			else if ($condition == "type") $this->query .= " DESC ";
			else $this->query .= "";
		}
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getReceiptRows($condition="", $filtertext="") {
		if ($condition == "code") {
			$this->query = "SELECT count(rcpt_id) AS numrows FROM rcpts WHERE rcpt_id  LIKE '$filtertext%' ";
			if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		} else if ($condition == "cust") {
			$this->query = "SELECT count(rcpt_id) AS numrows FROM rcpts WHERE rcpt_cust_code LIKE '%$filtertext%' ";
			if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		} else if ($condition == "date") {
			$this->query = "SELECT count(rcpt_id) AS numrows FROM rcpts WHERE rcpt_date = '$filtertext' ";
			if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		} else if ($condition == "desc") {
			$this->query = "SELECT count(rcpt_id) AS numrows FROM rcpts WHERE rcpt_desc  LIKE '%$filtertext%' ";
			if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		} else if ($condition == "check") {
			$this->query = "SELECT count(rcpt_id) AS numrows FROM rcpts WHERE rcpt_check_no LIKE '$filtertext%' ";
			if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		} else if ($condition == "amt") {
			$this->query = "SELECT count(rcpt_id) AS numrows FROM rcpts WHERE rcpt_amt = $filtertext ";
			if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		} else if ($condition == "type") {
			$this->query = "SELECT count(rcpt_id) AS numrows FROM rcpts WHERE rcpt_type LIKE '$filtertext' ";
			if ($this->openpayment=="t") $this->query .= "AND rcpt_type='op' ";
		} else {
			$this->query = "SELECT count(rcpt_id) AS numrows FROM rcpts ";
			if ($this->openpayment=="t") $this->query .= "WHERE rcpt_type='op' ";
		}
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getReceiptStmt($cust_code, $fr="", $to="") {
		$where  = " WHERE rcpt_cust_code='$cust_code' ";
		if (!empty($fr) && $fr != "0000-00-00") $where .= " AND rcpt_date >= '$fr' ";
		if (!empty($to) && $to != "0000-00-00") $where .= " AND rcpt_date <= '$to' ";
		if ($this->openpayment=="t") $where .= "AND rcpt_type='op' ";
		else $where .= "AND rcpt_type!='op' ";
		
		$this->query = "SELECT rcpt_id, rcpt_date, rcpt_amt, rcpt_disc_amt, rcpt_type, rcpt_check_no FROM rcpts $where ORDER BY rcpt_date ";
//echo $this->query;
//echo "<br><br>";
		if ($arr = $this->getARRaw()) return $arr;
		return NULL;
	}

	function getReceiptRange($fr="", $to="", $ord="", $rev="t", $key="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$from = "FROM rcpts ";
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND $ord >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $ord <= '$to' ";
		}
		if ($this->openpayment=="t") $where .= "AND rcpt_type='op' ";
		else $where .= "AND rcpt_type!='op' ";
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

	function getReceiptRangeState($fr="", $to="", $state="ALL", $ord="", $rev="t", $key="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT r.* ";
		$from = "FROM rcpts r, custs c ";
		$where = "WHERE r.rcpt_cust_code=c.cust_code ";
		if ($state != "ALL") $where .= "AND c.cust_state LIKE '$state' ";
		if (!empty($fr)) {
			$where .= " AND r.$ord >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND r.$ord <= '$to' ";
		}
		if ($this->openpayment=="t") $where .= "AND r.rcpt_type='op' ";
		else $where .= "AND r.rcpt_type!='op' ";
		$orderby = "";
		if (!empty($key)) $ord = $key;
		if (!empty($ord)) {
			$orderby = " ORDER BY r.$ord ";
			if ($rev != "t") $orderby .= " DESC ";
		}
		$this->query = "$select $from $where $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getReceiptRangeSum($fr="", $to="", $key="", $ord="", $rev="f") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.rcpt_amt) as rcpt_sum, sum(p.rcpt_disc_amt) as rcpt_disc_sum ";
		$from = "FROM rcpts p, custs c ";
		$where = "WHERE p.rcpt_cust_code=c.cust_code ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		if ($this->openpayment=="t") $where .= "AND rcpt_type='op' ";
		else $where .= "AND rcpt_type!='op' ";
		$groupby = " GROUP BY rcpt_cust_code";
		$orderby = "";
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev == "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getReceiptRangeSumState($fr="", $to="", $state="ALL", $key="", $ord="", $rev="f") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.rcpt_amt) as rcpt_sum, sum(p.rcpt_disc_amt) as rcpt_disc_sum ";
		$from = "FROM rcpts p, custs c ";
		$where = "WHERE p.rcpt_cust_code=c.cust_code ";
		if ($state!="ALL") $where .= "AND c.cust_state LIKE '$state' ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		if ($this->openpayment=="t") $where .= "AND rcpt_type='op' ";
		else $where .= "AND rcpt_type!='op' ";
		$groupby = " GROUP BY rcpt_cust_code";
		$orderby = "";
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev == "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getReceiptRangeSumTax($fr="", $to="", $key="", $ord="", $rev="f", $state="", $grp="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.rcpt_amt) as rcpt_sum, sum(p.rcpt_disc_amt) as rcpt_disc_sum ";
		$from = "FROM rcpts p, custs c, taxrates x ";
		$where = "WHERE p.rcpt_cust_code=c.cust_code ";
		$where .= "AND x.taxrate_code=c.cust_tax_code ";
		$where .= "AND p.rcpt_type != 'ca' ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		if (!empty($state)) {
			$where .= "AND c.cust_state='$state' ";
		}
		if ($this->openpayment=="t") $where .= "AND rcpt_type='op' ";
		else $where .= "AND rcpt_type!='op' ";
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
		return $this->getARRaw();
	}

	function getReceiptRangeSumTaxMonth($year="", $months="", $key="", $ord="", $rev="f", $state="", $grp="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.rcpt_amt) as rcpt_sum, sum(p.rcpt_disc_amt) as rcpt_disc_sum ";
		$from = "FROM rcpts p, custs c, taxrates x ";
		$where = "WHERE p.rcpt_cust_code=c.cust_code ";
		$where .= "AND x.taxrate_code=c.cust_tax_code ";
		$where .= "AND p.rcpt_type != 'ca' ";

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
			$where .= " ($key>='$fr' AND $key<='$to') ";
		}
		$where .= ") ";

		if (!empty($state)) {
			$where .= "AND c.cust_state='$state' ";
		}

		if ($this->openpayment=="t") $where .= "AND rcpt_type='op' ";
		else $where .= "AND rcpt_type!='op' ";
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
		return $this->getARRaw();
	}

	function getReceiptSumBest($fr="", $to="", $rev="f", $limit=50) {
		$select = "SELECT c.* ";
		$select .= ", sum(p.rcpt_amt) as total_rcpt, sum(p.rcpt_disc_amt) as total_disc ";
		$select .= ", sum(p.rcpt_amt+p.rcpt_disc_amt) as total ";
		$from = "FROM rcpts p, custs c ";
		$where = "WHERE p.rcpt_cust_code=c.cust_code ";
		if (!empty($fr)) {
			$where .= " AND rcpt_date >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND rcpt_date <= '$to' ";
		}
		if ($this->openpayment=="t") $where .= "AND rcpt_type='op' ";
		else $where .= "AND rcpt_type!='op' ";
		$groupby = " GROUP BY rcpt_cust_code";
		$orderby = " ORDER BY total ";
		if ($rev != "f") $orderby .= " DESC ";
		if ($limit>0) $orderby .= "LIMIT 0, $limit ";

		$this->query = "$select $from $where $groupby $orderby ";
		//echo $this->query."<br>";
		return $this->getARRaw();
	}


}

?>