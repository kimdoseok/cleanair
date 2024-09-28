<?php
include_once("class.ar.php");

class Terms extends AR {

	var $ar_type = "";

	function insertTerms($arr) {
		if ($lastid = $this->insertAR("terms", $arr)) return $lastid;
		return false;
	}

	function updateTerms($code, $arr) {
		if ($this->updateAR("terms", "term_code", $code, $arr)) return true;
		return false;
	}

	function deleteTerms($code) {
		$query = "delete from terms where term_code='$code'";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getTerms($code="") {
		$code = trim($code);
		if (empty($code)) return false;
		$this->query = "SELECT * FROM terms WHERE term_code = '$code' ";
		if ($this->ar_type == 't') $this->query .= "AND (term_type = 'r' OR term_type = 'b') ";
		else if ($this->ar_type == 'f') $this->query .= "AND (term_type = 'p' OR term_type = 'b') ";
		$this->query .= "LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getTermsFields() {
		$this->query = "SELECT * FROM terms LIMIT 0 ";
		if ($arr = $this->getARFields($this->query)) return $arr;
		return false;
	}

	function getLastTerms($filter="") {
		$this->query = "SELECT * FROM terms ";
		if ($this->ar_type == 't') $this->query .= "WHERE (term_type = 'r' OR term_type = 'b') ";
		else if ($this->ar_type == 'f') $this->query .= "WHERE (term_type = 'p' OR term_type = 'b') ";
		$this->query .= "ORDER BY term_code DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstTerms($filter="") {
		$this->query = "SELECT * FROM terms ";
		if ($this->ar_type == 't') $this->query .= "WHERE (term_type = 'r' OR term_type = 'b') ";
		else if ($this->ar_type == 'f') $this->query .= "WHERE (term_type = 'p' OR term_type = 'b') ";
		$this->query .= "ORDER BY term_code LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextTerms($code, $filter="") {
		$this->query = "SELECT * FROM terms WHERE term_code > '$code' ";
		if ($this->ar_type == 't') $this->query .= "AND (term_type = 'r' OR term_type = 'b') ";
		else if ($this->ar_type == 'f') $this->query .= "AND (term_type = 'p' OR term_type = 'b') ";
		$this->query .= "ORDER BY term_code LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevTerms($code, $filter="") {
		$this->query = "SELECT * FROM terms WHERE term_code  < '$code' ";
		if ($this->ar_type == 't') $this->query .= "AND (term_type = 'r' OR term_type = 'b') ";
		else if ($this->ar_type == 'f') $this->query .= "AND (term_type = 'p' OR term_type = 'b') ";
		$this->query .= "ORDER BY term_code DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getTermsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getTerms($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevTerms($code, $filter);
					if (!$rec) $rec = $this->getFirstTerms($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextTerms($code, $filter);
					if (!$rec) $rec = $this->getLastTerms($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstTerms($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastTerms($filter);
				} else {
					$rec = $this->getTerms($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastTerms($filter);
				} else {
					$rec = $this->getFirstTerms($filter);
				}
			}
		}
		return $rec;
	}

	function getTermsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$this->query = "SELECT * FROM terms ";
		if (empty($filtertext) || !isset($filtertext)) {
			if ($this->ar_type == 't') $this->query .= "WHERE (term_type = 'r' OR term_type = 'b') ";
			else if ($this->ar_type == 'f') $this->query .= "WHERE (term_type = 'p' OR term_type = 'b') ";
			if ($condition == "code") $this->query .= "ORDER BY term_code  ";
			else if ($condition == "desc") $this->query .= "ORDER BY term_desc  ";
			else $this->query .= "ORDER BY term_code ";
		} else {
			if ($condition == "code") {
				$this->query .= "WHERE term_code LIKE '$filtertext%' ";
				if ($this->ar_type == 't') $this->query .= "AND (term_type = 'r' OR term_type = 'b') ";
				else if ($this->ar_type == 'f') $this->query .= "AND (term_type = 'p' OR term_type = 'b') ";
				$this->query .= "ORDER BY term_code ";
			} else if ($condition == "desc") {
				$this->query .= "WHERE term_desc  LIKE '$filtertext%' ";
				if ($this->ar_type == 't') $this->query .= "AND (term_type = 'r' OR term_type = 'b') ";
				else if ($this->ar_type == 'f') $this->query .= "AND (term_type = 'p' OR term_type = 'b') ";
				$this->query .= "ORDER BY term_desc  ";
			} else {
				if ($this->ar_type == 't') $this->query .= "WHERE (term_type = 'r' OR term_type = 'b') ";
				else if ($this->ar_type == 'f') $this->query .= "WHERE (term_type = 'p' OR term_type = 'b') ";
				$this->query .= "ORDER BY term_code ";
			}
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getTermsRows() {
		$this->query = "SELECT count(term_code) AS numrows FROM terms ";
		if ($this->ar_type == 't') $this->query .= "WHERE (term_type = 'r' OR term_type = 'b') ";
		else if ($this->ar_type == 'f') $this->query .= "WHERE (term_type = 'p' OR term_type = 'b') ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getTermsBox() {
		$num = $this->getTermsRows();
		$arr = $this->getTermsList("","","",1,$num);
		$out_arr = array();
		for ($i=0;$i<$num;$i++) {
			$tmp = array();
			$tmp["value"] = $arr[$i]["term_code"];
			$tmp["Name"] = $arr[$i]["term_code"];
			array_push($out_arr, $tmp);
		}
		return $out_arr;
	}
}
?>