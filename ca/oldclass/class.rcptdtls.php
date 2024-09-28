<?php
include_once("class.ar.php");

class RcptDtls extends AR {

	function insertRcptDtls($arr) {
		if ($lastid = $this->insertAR("rcptdtls", $arr)) return $lastid;
		return false;
	}

	function updateRcptDtls($code, $arr) {
		if ($this->updateAR("rcptdtls", "rcptdtl_id", $code, $arr)) return true;
		return false;
	}

	function deleteRcptDtlsPI($code) {
		$query = "DELETE FROM rcptdtls WHERE rcptdtl_rcpt_id = '$code' ";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getRcptDtls($code) {
		$this->query = "SELECT * FROM rcptdtls WHERE rcptdtl_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getRcptDtlsFields() {
		$this->query = "SELECT * FROM rcptdtls LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastRcptDtls($filter) {
		$this->query = "SELECT * FROM rcptdtls WHERE rcptdtl_rcpt_id='$filter' ORDER BY rcptdtl_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstRcptDtls($filter) {
		$this->query = "SELECT * FROM rcptdtls WHERE rcptdtl_rcpt_id='$filter' ORDER BY rcptdtl_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextRcptDtls($code, $filter) {
		$this->query = "SELECT * FROM rcptdtls WHERE rcptdtl_id > '$code' AND rcptdtl_rcpt_id='$filter' ORDER BY rcptdtl_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevRcptDtls($code, $filter) {
		$this->query = "SELECT * FROM rcptdtls WHERE rcptdtl_id  < '$code' AND rcptdtl_rcpt_id='$filter' ORDER BY rcptdtl_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getRcptDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getRcptDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevRcptDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstRcptDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextRcptDtls($code, $filter);
					if (!$rec) $rec = $this->getLastRcptDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstRcptDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastRcptDtls($filter);
				} else {
					$rec = $this->getRcptDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastRcptDtls($filter);
				} else {
					$rec = $this->getFirstRcptDtls($filter);
				}
			}
		}
		return $rec;
	}

	function getRcptDtlsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition != "all") $this->query = "SELECT * FROM rcptdtls WHERE rcptdtl_rcpt_id='$condition' ORDER BY rcptdtl_id  ";
			else $this->query = "SELECT * FROM rcptdtls ORDER BY rcptdtl_id ";
		} else {
			if ($condition != "all") $this->query = "SELECT * FROM rcptdtls WHERE rcptdtl_rcpt_id='$condition' ORDER BY rcptdtl_id  ";
			else $this->query = "SELECT * FROM rcptdtls ORDER BY rcptdtl_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getRcptDtlsRows($rcpt_id="") {
		if (empty($rcpt_id)) $this->query = "SELECT count(rcptdtl_id) AS numrows FROM rcptdtls ";
		else $this->query = "SELECT count(rcptdtl_id) AS numrows FROM rcptdtls WHERE rcptdtl_rcpt_id='$rcpt_id'";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>