<?php
include_once("class.ar.php");

class Invoices extends AR {

	function insertInvoices($arr) {
		if ($lastid = $this->insertAR("invoices", $arr)) return $lastid;
		return false;
	}

	function updateInvoices($code, $arr) {
		if ($this->updateAR("invoices", "invoice_id", $code, $arr)) return true;
		return false;
	}

	function getInvoices($code) {
		$select = "SELECT * ";
		$from = "FROM invoices i, picks p ";
		$where = "WHERE invoice_id = '$code' ";
		$where .= "AND i.invoice_pick_id = p.pick_id ";
		$limit = "LIMIT 1 ";
		$this->query = "$select $from $where $limit ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getInvoicesPick($code) {
		$select = "SELECT * ";
		$from = "FROM invoices i, picks p ";
		$where = "WHERE invoice_pick_id = '$code' ";
		$where .= "AND i.invoice_pick_id = p.pick_id ";
		$limit = "LIMIT 1 ";
		$this->query = "$select $from $where $limit ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function deleteInvoices($code) {
		$query = "delete from invoices where invoice_id='$code'";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getInvoicesFields() {
		$this->query = "SELECT * FROM invoices LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastInvoices($filter="") {
		$this->query = "SELECT * FROM invoices ORDER BY invoice_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstInvoices($filter="") {
		$this->query = "SELECT * FROM invoices ORDER BY invoice_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextInvoices($code, $filter="") {
		$this->query = "SELECT * FROM invoices WHERE invoice_id > '$code' ORDER BY invoice_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevInvoices($code, $filter="") {
		$this->query = "SELECT * FROM invoices WHERE invoice_id  < '$code' ORDER BY invoice_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getInvoicesFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getInvoices($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevInvoices($code, $filter);
					if (!$rec) $rec = $this->getFirstInvoices($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextInvoices($code, $filter);
					if (!$rec) $rec = $this->getLastInvoices($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstInvoices($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastInvoices($filter);
				} else {
					$rec = $this->getInvoices($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastInvoices($filter);
				} else {
					$rec = $this->getFirstInvoices($filter);
				}
			}
		}
		return $rec;
	}

	function getInvoicesList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		$select = "SELECT * ";
		$from = "FROM invoices i, picks p ";
		$where = "WHERE i.invoice_pick_id = p.pick_id ";
		$having = "";
		$orderby = "";

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
				$orderby .= "ORDER BY invoice_id ";
			} else if ($condition == "cust") {
				$orderby .= "ORDER BY pick_cust_code ";
			} else if ($condition == "date") {
				$orderby .= "ORDER BY pick_date ";
			} else if ($condition == "idate") {
				$orderby .= "ORDER BY invoice_date  ";
			} else if ($condition == "ddate") {
				$orderby .= "ORDER BY pick_delv_date  ";
			} else if ($condition == "tel") {
				$orderby .= "ORDER BY pick_tel  ";
			} else {
				$orderby .= "ORDER BY invoice_id ";
			}
		} else {
			if ($condition == "code") {
				$where .= "AND invoice_id LIKE '$filtertext%' ";
				$orderby .= "ORDER BY invoice_id ";
			} else if ($condition == "cust") {
				$where .= "AND pick_cust_code LIKE '$filtertext%' ";
				$orderby .= "ORDER BY pick_cust_code, pick_id ";
			} else if ($condition == "date") {
				$where .= "AND pick_date = '$filtertext' ";
				$orderby .= "ORDER BY pick_date, pick_id ";
			} else if ($condition == "idate") {
				$where .= "AND invoice_date = '$filtertext' ";
				$orderby .= "ORDER BY pick_prom_date ";
			} else if ($condition == "ddate") {
				$where .= "AND pick_delv_date = '$filtertext' ";
				$orderby .= "ORDER BY pick_delv_date, pick_id ";
			} else if ($condition == "tel") {
				$where .= "AND pick_tel LIKE '$filtertext%' ";
				$orderby .= "ORDER BY pick_tel, pick_id  ";
			} else {
				$where = "AND pick_id LIKE '$filtertext%' ";
				$orderby = "ORDER BY invoice_id ";
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

		return $this->getAR();
	}

	function getInvoicesRows() {
		$this->query = "SELECT count(invoice_id) AS numrows FROM invoices";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>