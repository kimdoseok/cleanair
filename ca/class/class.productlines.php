<?php
include_once("class.ic.php");

class ProductLine extends IC {

	function insertProductLine($arr) {
		if ($lastid = $this->insertIC("productlines", $arr)) return $lastid;
		return false;
	}

	function updateProductLine($code, $arr) {
		if ($this->updateIC("productlines", "productline_code", $code, $arr)) return true;
		return false;
	}

	function deleteProductLine($code) {
		$query = "delete from productlines where productline_code='$code'";
		if ($this->updateICRaw($query)) return true;
		return false;
	}

	function getProductLine($code="") {
		$code = trim($code);
		if (empty($code)) return false;
		$this->query = "SELECT * FROM productlines WHERE productline_code = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function getProductLineFields() {
		$this->query = "SELECT * FROM productlines LIMIT 0 ";
		if ($arr = $this->getICFields($this->query)) return $arr;
		return false;
	}

	function getLastProductLine($filter="") {
		$this->query = "SELECT * FROM productlines ORDER BY productline_code DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstProductLine($filter="") {
		$this->query = "SELECT * FROM productlines ORDER BY productline_code LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextProductLine($code, $filter="") {
		$this->query = "SELECT * FROM productlines WHERE productline_code > '$code' ORDER BY productline_code LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevProductLine($code, $filter="") {
		$this->query = "SELECT * FROM productlines WHERE productline_code  < '$code' ORDER BY productline_code DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getProductLineFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getProductLine($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevProductLine($code, $filter);
					if (!$rec) $rec = $this->getFirstProductLine($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextProductLine($code, $filter);
					if (!$rec) $rec = $this->getLastProductLine($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstProductLine($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastProductLine($filter);
				} else {
					$rec = $this->getProductLine($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastProductLine($filter);
				} else {
					$rec = $this->getFirstProductLine($filter);
				}
			}
		}
		return $rec;
	}

	function getProductLineList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM productlines ORDER BY productline_code, productline_name ";
			else if ($condition == "name") $this->query = "SELECT * FROM productlines ORDER BY  productline_name ";
			else if ($condition == "desc") $this->query = "SELECT * FROM productlines ORDER BY productline_desc, productline_name ";
			else $this->query = "SELECT * FROM productlines ORDER BY productline_code, productline_name ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM productlines WHERE productline_code LIKE '$filtertext%' ORDER BY productline_code, productline_name";
			else if ($condition == "name") $this->query = "SELECT * FROM productlines WHERE productline_name LIKE '$filtertext%' ORDER BY  productline_name";
			else if ($condition == "desc") $this->query = "SELECT * FROM productlines WHERE productline_desc  LIKE '$filtertext%' ORDER BY productline_desc, productline_name ";
			else $this->query = "SELECT * FROM productlines ORDER BY productline_code, productline_name";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getIC();
	}

	function getProductLineRows() {
		$this->query = "SELECT count(productline_code) AS numrows FROM productlines ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

}
?>