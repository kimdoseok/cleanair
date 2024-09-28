<?php
include_once("class.ar.php");

class OpenPay extends AR {

	var $fieldlist;
	var $openpayment;

	function OpenPay() {
		$openpayment="";
	}

	function insertOpenPay($arr) {
		if ($lastid = $this->insertAR("openpays", $arr)) return $lastid;
		return false;
	}

	function updateOpenPay($code, $arr) {
		if ($this->updateAR("openpays", "openpay_id", $code, $arr)) return true;
		return false;
	}

	function deleteOpenPay($code) {
		$query = "delete from openpays where openpay_id='$code'";
		if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function updateOpenPayAmt($code, $newamt, $oldamt, $flg) {
		$amt = $newamt - $oldamt;
		if ($flg == "t") {
			$this->query = "UPDATE openpays SET x = x + $amt WHERE openpay_id='$code' ";
		} else {
			$this->query = "UPDATE openpays SET x = x + $amt WHERE openpay_id='$code' ";
		}
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function getOpenPay($code) {
		$this->query = "SELECT * FROM openpays WHERE openpay_id = '$code' ";
		if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		$this->query .= "LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getOpenPaySales($code) {
		$this->query = "SELECT h.* FROM openpays h, rcptdtls d WHERE h.openpay_id=d.rcptdtl_openpay_id AND d.rcptdtl_sale_id='$code' ";
		if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		$this->query .= "GROUP BY h.openpay_id ";

		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getOpenPayLast($cust) {
		$this->query = "SELECT * FROM openpays WHERE openpay_cust_code = '$cust' ";
		if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		$this->query .= "ORDER BY openpay_date DESC LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getOpenPayCount($cust_code) {
		$this->query = "SELECT count(openpay_id) AS openpay_cnt FROM openpays WHERE openpay_cust_code = '$cust_code' ";
		if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		if ($arr = $this->getAR()) return $arr[0][openpay_cnt];
		return false;
	}

	function getOpenPaySumAged($code, $begin="", $end="", $beg_eq="t", $end_eq="t") {
		$this->query = "SELECT sum(openpay_amt+openpay_disc_amt) AS amt FROM openpays ";
		$this->query .= "WHERE openpay_cust_code = '$code' ";
		if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		if (!empty($begin)) {
			if ($beg_eq=="t") $this->query .= "AND openpay_date >= '$begin' ";
			else $this->query .= "AND openpay_date > '$begin' ";
		}
		if (!empty($end)) {
			if ($end_eq=="t") $this->query .= "AND openpay_date <= '$end' ";
			else  $this->query .= "AND openpay_date < '$end' ";
		}
//echo $this->query."<br>";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["amt"];
		return 0;
	}

	function getOpenPayFields() {
		$this->query = "SELECT * FROM openpays LIMIT 0 ";
		if ($arr = $this->getARFields($this->query)) return $arr;
		return false;
	}

	function getLastOpenPay($filter="") {
		$this->query = "SELECT * FROM openpays ";
		if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
		$this->query .= "ORDER BY openpay_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstOpenPay($filter="") {
		$this->query = "SELECT * FROM openpays ORDER BY openpay_id ";
		if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
		$this->query .= "LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextOpenPay($code, $filter="") {
		$this->query = "SELECT * FROM openpays WHERE openpay_id > '$code' ";
		if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		$this->query .= "ORDER BY openpay_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevOpenPay($code, $filter="") {
		$this->query = "SELECT * FROM openpays WHERE openpay_id  < '$code' ";
		if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		$this->query .= "ORDER BY openpay_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getOpenPayFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getOpenPay($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevOpenPay($code, $filter);
					if (!$rec) $rec = $this->getFirstOpenPay($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextOpenPay($code, $filter);
					if (!$rec) $rec = $this->getLastOpenPay($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstOpenPay($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastOpenPay($filter);
				} else {
					$rec = $this->getOpenPay($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastOpenPay($filter);
				} else {
					$rec = $this->getFirstOpenPay($filter);
				}
			}
		}
		return $rec;
	}

	function getOpenPayList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
				$this->query = "SELECT * FROM openpays ";
				if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
				$this->query .= "ORDER BY openpay_id  ";
			} else if ($condition == "cust") {
				$this->query = "SELECT * FROM openpays ";
				if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
				$this->query .= "ORDER BY openpay_cust_code ";
			} else if ($condition == "date") {
				$this->query = "SELECT * FROM openpays ";
				if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
				$this->query .= "ORDER BY openpay_date  ";
			} else if ($condition == "desc") {
				$this->query = "SELECT * FROM openpays ";
				if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
				$this->query .= "ORDER BY openpay_desc  ";
			} else if ($condition == "check") {
				$this->query = "SELECT * FROM openpays ";
				if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
				$this->query .= "ORDER BY openpay_check_no ";
			} else if ($condition == "amt") {
				$this->query = "SELECT * FROM openpays ";
				if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
				$this->query .= "ORDER BY openpay_amt  ";
			} else if ($condition == "type") {
				$this->query = "SELECT * FROM openpays ";
				if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
				$this->query .= "ORDER BY openpay_type  ";
			} else {
				$this->query = "SELECT * FROM openpays ";
				if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
				$this->query .= "ORDER BY openpay_id ";
			}
		} else {
			if ($condition == "code") {
				$this->query = "SELECT * FROM openpays WHERE openpay_id  LIKE '$filtertext%' ";
				if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
				$this->query .= "ORDER BY openpay_id ";
			} else if ($condition == "cust") {
				$this->query = "SELECT * FROM openpays WHERE openpay_cust_code LIKE '%$filtertext%' ";
				if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
				$this->query .= "ORDER BY openpay_cust_code ";
			} else if ($condition == "date") {
				$this->query = "SELECT * FROM openpays WHERE openpay_date = '$filtertext' ";
				if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
				$this->query .= "ORDER BY openpay_date ";
			} else if ($condition == "desc") {
				$this->query = "SELECT * FROM openpays WHERE openpay_desc LIKE '%$filtertext%' ";
				if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
				$this->query .= "ORDER BY openpay_desc  ";
			} else if ($condition == "check") {
				$this->query = "SELECT * FROM openpays WHERE openpay_check_no LIKE '$filtertext%' ";
				if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
				$this->query .= "ORDER BY openpay_check_no ";
			} else if ($condition == "amt") {
				$this->query = "SELECT * FROM openpays WHERE openpay_amt =$filtertext ";
				if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
				$this->query .= "ORDER BY openpay_amt ";
			} else if ($condition == "type") {
				$this->query = "SELECT * FROM openpays WHERE openpay_type LIKE '$filtertext' ";
				if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
				$this->query .= "ORDER BY openpay_type ";
			} else {
				$this->query = "SELECT * FROM openpays ";
				if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
				$this->query .= "ORDER BY openpay_id ";
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

	function getOpenPayRows($condition="", $filtertext="") {
		if ($condition == "code") {
			$this->query = "SELECT count(openpay_id) AS numrows FROM openpays WHERE openpay_id  LIKE '$filtertext%' ";
			if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		} else if ($condition == "cust") {
			$this->query = "SELECT count(openpay_id) AS numrows FROM openpays WHERE openpay_cust_code LIKE '%$filtertext%' ";
			if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		} else if ($condition == "date") {
			$this->query = "SELECT count(openpay_id) AS numrows FROM openpays WHERE openpay_date = '$filtertext' ";
			if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		} else if ($condition == "desc") {
			$this->query = "SELECT count(openpay_id) AS numrows FROM openpays WHERE openpay_desc  LIKE '%$filtertext%' ";
			if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		} else if ($condition == "check") {
			$this->query = "SELECT count(openpay_id) AS numrows FROM openpays WHERE openpay_check_no LIKE '$filtertext%' ";
			if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		} else if ($condition == "amt") {
			$this->query = "SELECT count(openpay_id) AS numrows FROM openpays WHERE openpay_amt = $filtertext ";
			if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		} else if ($condition == "type") {
			$this->query = "SELECT count(openpay_id) AS numrows FROM openpays WHERE openpay_type LIKE '$filtertext' ";
			if ($this->openpayment=="t") $this->query .= "AND openpay_type='op' ";
		} else {
			$this->query = "SELECT count(openpay_id) AS numrows FROM openpays ";
			if ($this->openpayment=="t") $this->query .= "WHERE openpay_type='op' ";
		}
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getOpenPayStmt($cust_code, $fr="", $to="") {
		$where  = " WHERE openpay_cust_code='$cust_code' ";
		if (!empty($fr) && $fr != "0000-00-00") $where .= " AND openpay_date >= '$fr' ";
		if (!empty($to) && $to != "0000-00-00") $where .= " AND openpay_date <= '$to' ";
		if ($this->openpayment=="t") $where .= "AND openpay_type='op' ";
		else $where .= "AND openpay_type!='op' ";
		
		$this->query = "SELECT openpay_id, openpay_date, openpay_amt, openpay_disc_amt, openpay_type, openpay_check_no FROM openpays $where ORDER BY openpay_date ";
//echo $this->query;
//echo "<br><br>";
		if ($arr = $this->getARRaw()) return $arr;
		return NULL;
	}

	function getOpenPayRange($fr="", $to="", $ord="", $rev="t", $key="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$from = "FROM openpays ";
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND $ord >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $ord <= '$to' ";
		}
		if ($this->openpayment=="t") $where .= "AND openpay_type='op' ";
		else $where .= "AND openpay_type!='op' ";
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

	function getOpenPayRangeSum($fr="", $to="", $key="", $ord="", $rev="f") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.openpay_amt) as openpay_sum, sum(p.openpay_disc_amt) as openpay_disc_sum ";
		$from = "FROM openpays p, custs c ";
		$where = "WHERE p.openpay_cust_code=c.cust_code ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		if ($this->openpayment=="t") $where .= "AND openpay_type='op' ";
		else $where .= "AND openpay_type!='op' ";
		$groupby = " GROUP BY openpay_cust_code";
		$orderby = "";
		if (!empty($ord)) {
			$orderby = " ORDER BY $ord ";
			if ($rev == "t") $orderby .= " DESC ";
		}

		$this->query = "$select $from $where $groupby $orderby ";
//echo $this->query."<br>";
		return $this->getARRaw();
	}

	function getOpenPayRangeSumTax($fr="", $to="", $key="", $ord="", $rev="f", $state="", $grp="") {
		if (!empty($this->fieldlist)) $select = "SELECT ".$this->fieldlist;
		else $select = "SELECT * ";
		$select .= ", sum(p.openpay_amt) as openpay_sum, sum(p.openpay_disc_amt) as openpay_disc_sum ";
		$from = "FROM openpays p, custs c, taxrates x ";
		$where = "WHERE p.openpay_cust_code=c.cust_code ";
		$where .= "AND x.taxrate_code=c.cust_tax_code ";
		$where .= "AND p.openpay_type != 'ca' ";
		if (!empty($fr)) {
			$where .= " AND $key >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $key <= '$to' ";
		}
		if (!empty($state)) {
			$where .= "AND c.cust_state='$state' ";
		}
		if ($this->openpayment=="t") $where .= "AND openpay_type='op' ";
		else $where .= "AND openpay_type!='op' ";
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


}

?>