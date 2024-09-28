<?php
include_once("class.ar.php");

class OpenPayDtls extends AR {

	function insertOpenPayDtls($arr) {
		if ($lastid = $this->insertAR("openpaydtls", $arr)) return $lastid;
		return false;
	}

	function updateOpenPayDtls($code, $arr) {
		if ($this->updateAR("openpaydtls", "openpaydtl_id", $code, $arr)) return true;
		return false;
	}

	function deleteOpenPayDtlsPI($code) {
		$query = "DELETE FROM openpaydtls WHERE openpaydtl_openpay_id = '$code' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getOpenPayDtls($code) {
		$this->query = "SELECT * FROM openpaydtls WHERE openpaydtl_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getOpenPayDtlsFields() {
		$this->query = "SELECT * FROM openpaydtls LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastOpenPayDtls($filter) {
		$this->query = "SELECT * FROM openpaydtls WHERE openpaydtl_openpay_id='$filter' ORDER BY openpaydtl_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstOpenPayDtls($filter) {
		$this->query = "SELECT * FROM openpaydtls WHERE openpaydtl_openpay_id='$filter' ORDER BY openpaydtl_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextOpenPayDtls($code, $filter) {
		$this->query = "SELECT * FROM openpaydtls WHERE openpaydtl_id > '$code' AND openpaydtl_openpay_id='$filter' ORDER BY openpaydtl_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevOpenPayDtls($code, $filter) {
		$this->query = "SELECT * FROM openpaydtls WHERE openpaydtl_id  < '$code' AND openpaydtl_openpay_id='$filter' ORDER BY openpaydtl_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getOpenPayDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getOpenPayDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevOpenPayDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstOpenPayDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextOpenPayDtls($code, $filter);
					if (!$rec) $rec = $this->getLastOpenPayDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstOpenPayDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastOpenPayDtls($filter);
				} else {
					$rec = $this->getOpenPayDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastOpenPayDtls($filter);
				} else {
					$rec = $this->getFirstOpenPayDtls($filter);
				}
			}
		}
		return $rec;
	}

	function getOpenPayDtlsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition != "all") $this->query = "SELECT * FROM openpaydtls WHERE openpaydtl_openpay_id='$condition' ORDER BY openpaydtl_id  ";
			else $this->query = "SELECT * FROM openpaydtls ORDER BY openpaydtl_id ";
		} else {
			if ($condition != "all") $this->query = "SELECT * FROM openpaydtls WHERE openpaydtl_openpay_id='$condition' ORDER BY openpaydtl_id  ";
			else $this->query = "SELECT * FROM openpaydtls ORDER BY openpaydtl_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getOpenPayDtlsRows($rcpt_id="") {
		if (empty($rcpt_id)) $this->query = "SELECT count(openpaydtl_id) AS numrows FROM openpaydtls ";
		else $this->query = "SELECT count(openpaydtl_id) AS numrows FROM openpaydtls WHERE openpaydtl_openpay_id='$rcpt_id'";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>