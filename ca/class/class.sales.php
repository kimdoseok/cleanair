<?php
include_once("class.ar.php");

class Sales extends AR {

	function insertSales($arr) {
		if ($lastid = $this->insertAR("sales", $arr)) {
			//print_r("lastid: $lastid");
			return $lastid;
		} else {
			return 0;
		}
	}

	function updateSales($code, $arr) {
		if ($this->updateAR("sales", "sale_id", $code, $arr)) return true;
		return false;
	}

	function getSales($code) {
		$this->query = "SELECT * FROM sales WHERE sale_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return array();
	}

	function getSalesSumCounted($code, $begin="", $end="", $beg_eq="t", $end_eq="t") {
		$this->query = "SELECT sum(sale_amt+sale_tax_amt+sale_freight_amt) AS amt,count(sale_id) AS cnt,max(sale_date) as lst FROM sales ";
		$this->query .= "WHERE sale_cust_code = '$code' ";
		if (!empty($begin)) {
			if ($beg_eq=="t") $this->query .= "AND sale_date >= '$begin' ";
			else $this->query .= "AND sale_date > '$begin' ";
		}
		if (!empty($end)) {
			if ($end_eq=="t") $this->query .= "AND sale_date <= '$end' ";
			else  $this->query .= "AND sale_date < '$end' ";
		}
//echo $this->query."<br>";
		$arr = $this->getAR();
		//if ($arr) return $arr[0]["amt"];
		return $arr;
	}
  
	function deleteSales($code) {
		$query = "delete from sales where sale_id='$code'";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function increaseSales($code, $fld_name, $qty) {
		$query = "UPDATE sales SET $fld_name = $fld_name + $qty WHERE sale_id=$code";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getSalesCount($cust_code) {
		$this->query = "SELECT count(sale_id) AS sale_cnt FROM sales WHERE sale_cust_code = '$cust_code' ";
		if ($arr = $this->getAR()) return $arr[0][sale_cnt];
		return false;
	}

	function getSalesFields() {
		$this->query = "SELECT * FROM sales LIMIT 0 ";
		if ($arr = $this->getARFields()) {
			//echo "getSalesFields:";
			//print_r($arr);
			return $arr;
		} else {
			return false;
		}				
		

	}

	function getLastSales($filter="") {
		$this->query = "SELECT * FROM sales ORDER BY sale_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstSales($filter="") {
		$this->query = "SELECT * FROM sales ORDER BY sale_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextSales($code, $filter="") {
		$this->query = "SELECT * FROM sales WHERE sale_id < '$code' ORDER BY sale_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevSales($code, $filter="") {
		$this->query = "SELECT * FROM sales WHERE sale_id  > '$code' ORDER BY sale_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getSalesFields();	
				//echo "getTextFields:";

				for ($i=0;$i<count($cols);$i++) {
					//$name = $cols[$i]->name;
					$rec[$cols[$i]] = "";
				}
				//print_r($rec);
			} else {
				$rec = $this->getSales($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevSales($code, $filter);
					if (!$rec) $rec = $this->getFirstSales($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextSales($code, $filter);
					if (!$rec) $rec = $this->getLastSales($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstSales($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastSales($filter);
				} else {
					$rec = $this->getSales($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastSales($filter);
				} else {
					$rec = $this->getFirstSales($filter);
				}
			}
		}
		return $rec;
	}

	function getSalesList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM sales ORDER BY sale_id  ";
			else if ($condition == "cust") $this->query = "SELECT * FROM sales ORDER BY sale_cust_code  ";
			else if ($condition == "date") $this->query = "SELECT * FROM sales ORDER BY sale_date  ";
			else if ($condition == "tel") $this->query = "SELECT * FROM sales ORDER BY sale_tel  ";
			else $this->query = "SELECT * FROM sales ORDER BY sale_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM sales WHERE sale_id LIKE '$filtertext%' ORDER BY sale_id ";
			else if ($condition == "cust") $this->query = "SELECT * FROM sales WHERE sale_cust_code  LIKE '$filtertext%' ORDER BY sale_cust_code ";
			else if ($condition == "date") $this->query = "SELECT * FROM sales WHERE sale_date = '$filtertext' ORDER BY sale_date  ";
			else if ($condition == "tel") $this->query = "SELECT * FROM sales WHERE sale_tel  LIKE '$filtertext%' ORDER BY sale_tel ";
			else $this->query = "SELECT * FROM sales WHERE sale_id LIKE '$filtertext%' ORDER BY sale_id ";
		}
		if ($reverse == "t") {
			if ($condition == "code") $this->query .= " ";
			else if ($condition == "cust") $this->query .= " DESC, sale_id DESC ";
			else if ($condition == "date") $this->query .= " , sale_id DESC ";
			else if ($condition == "tel") $this->query .= " DESC, sale_id DESC ";
			else $this->query .= " ";
		} else {
			if ($condition == "code") $this->query .= " DESC ";
			else if ($condition == "cust") $this->query .= " , sale_id DESC ";
			else if ($condition == "date") $this->query .= " DESC, sale_id DESC";
			else if ($condition == "tel") $this->query .= " , sale_id DESC";
			else $this->query .= " DESC ";
		}
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getSalesListEx($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		
		$select = "SELECT s.*, sum(k.pickdtl_qty*k.pickdtl_cost ) AS sum_pickdtl ";
		$from = "FROM sales s, slsdtls d LEFT OUTER JOIN pickdtls k ON d.slsdtl_id=k.pickdtl_slsdtl_id ";
		$where = " WHERE s.sale_id=d.slsdtl_sale_id ";
		$orderby = "";
		$groupby = "GROUP BY sale_id ";
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
				$orderby .= "ORDER BY sale_id ";
			} else if ($condition == "cust") {
				$orderby .= "ORDER BY sale_cust_code ";
			} else if ($condition == "date") {
				$orderby .= "ORDER BY sale_date  ";
			} else if ($condition == "tel") {
				$orderby .= "ORDER BY sale_tel  ";
			} else {
				$orderby .= "ORDER BY sale_id ";
			}
		} else {
			if ($condition == "code") {
				$where .= "AND sale_id LIKE '$filtertext%' ";
				$orderby .= "ORDER BY sale_id ";
			} else if ($condition == "cust") {
				$where .= "AND sale_cust_code LIKE '$filtertext%' ";
				$orderby .= "ORDER BY sale_cust_code, sale_id  ";
			} else if ($condition == "date") {
				$where .= "AND sale_date = '$filtertext' ";
				$orderby .= "ORDER BY sale_date ";
			} else if ($condition == "tel") {
				$where .= "AND sale_tel LIKE '$filtertext%' ";
				$orderby .= "ORDER BY sale_tel, sale_id  ";
			} else {
				$where = "AND sale_id LIKE '$filtertext%' ";
				$orderby = "ORDER BY sale_id ";
			}
		}
		if ($reverse == "t") {
			if ($condition == "code") $orderby .= " ";
			else if ($condition == "cust") $orderby .= " DESC ";
			else if ($condition == "date") $orderby .= "";
			else if ($condition == "tel") $orderby .= " DESC ";
			else $orderby .= " ";
		} else {
			if ($condition == "code") $orderby .= " DESC ";
			else if ($condition == "cust") $orderby .= " ";
			else if ($condition == "date") $orderby .= " DESC ";
			else if ($condition == "tel") $orderby .= " ";
			else $orderby .= " DESC ";
		}
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $groupby $orderby $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getSalesListAvl($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$this->query = "SELECT s.*, sum(p.pickdtl_qty) as sum_pickdtl_qty  FROM sales s, slsdtls d LEFT OUTER JOIN pickdtls p ON d.slsdtl_id = p.pickdtl_slsdtl_id WHERE s.sale_id = d.slsdtl_sale_id AND s.sale_cust_code = '$condition' GROUP BY s.sale_id ";
		$this->query = "SELECT s.* FROM sales s, slsdtls d WHERE s.sale_id=d.slsdtl_sale_id AND s.sale_cust_code='$condition' GROUP BY s.sale_id HAVING sum(d.slsdtl_qty)>sum(d.slsdtl_qty_picked)";
		if ($reverse != "t") $this->query .= " ORDER BY s.sale_date DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getSalesRows($condition="", $filtertext="") {

		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(sale_id) AS numrows FROM sales ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(sale_id) AS numrows FROM sales WHERE sale_id LIKE '$filtertext%' ";
			else if ($condition == "cust") $this->query = "SELECT count(sale_id) AS numrows FROM sales WHERE sale_cust_code  LIKE '$filtertext%' ";
			else if ($condition == "tel") $this->query = "SELECT count(sale_id) AS numrows FROM sales WHERE sale_tel  LIKE '$filtertext%' ";
			else $this->query = "SELECT count(sale_id) AS numrows FROM sales WHERE sale_id LIKE '$filtertext%' ";
		}

		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getSalesRowsAvl($cust_code) {
		$this->query = "SELECT sale_id FROM sales s, slsdtls d WHERE s.sale_id=d.slsdtl_sale_id AND s.sale_cust_code='$cust_code' GROUP BY s.sale_id HAVING sum(d.slsdtl_qty)>sum(d.slsdtl_qty_picked)";
		return $this->getARRow();
	}

	function getSalesRange($fr="", $to="", $ord="", $rev="t") {
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND $ord >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $ord <= '$to' ";
		}
		if (!empty($ord)) $orderby = " ORDER BY $ord ";
		else $orderby = " ORDER BY $ord ";
		if ($rev != "t") $orderby .= " DESC ";
		$this->query = "SELECT * FROM sales $where $orderby ";
		return $this->getARRaw();
	}

}

?>