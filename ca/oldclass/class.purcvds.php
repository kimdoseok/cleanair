<?php
include_once("class.ap.php");

class Purcvds extends AP {

	function insertPurcvds($arr) {
		if ($lastid = $this->insertAP("purcvs", $arr)) return $lastid;
		return false;
	}

	function updatePurcvds($code, $arr) {
		if ($this->updateAP("purcvs", "purcv_id", $code, $arr)) return true;
		return false;
	}

	function deletePurcvds($code) {
		$query = "DELETE FROM purcvs WHERE purcv_id = '$code' ";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function deletePurcvdsHdr($code) {
		$query = "DELETE FROM purcvs WHERE purcv_purdtl_id = '$code' ";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function getPurcvds($code) {
		$this->query = "SELECT * FROM purcvs r, purdtls p WHERE r.purcv_purdtl_id=p.purdtl_id AND r.purcv_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getPurcvdsFields() {
		$this->query = "SELECT * FROM purcvs LIMIT 0 ";
		if ($arr = $this->getAPFields()) return $arr;
		return false;
	}

	function getLastPurcvds($filter) {
		$this->query = "SELECT * FROM purcvs r, purdtls p WHERE r.purcv_purdtl_id=p.purdtl_id AND purcv_purdtl_id='$filter' ORDER BY purcv_id DESC LIMIT 1 ";
//echo $this->query."<br>";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPurcvds($filter) {
		$this->query = "SELECT * FROM purcvs r, purdtls p WHERE r.purcv_purdtl_id=p.purdtl_id AND purcv_purdtl_id='$filter' ORDER BY purcv_id LIMIT 1 ";
//echo $this->query."<br>";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPurcvds($code, $filter) {
		$this->query = "SELECT * FROM purcvs r, purdtls p WHERE r.purcv_purdtl_id=p.purdtl_id AND purcv_id > '$code' AND purcv_purdtl_id='$filter' ORDER BY purcv_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPurcvds($code, $filter) {
		$this->query = "SELECT * FROM purcvs r, purdtls p WHERE r.purcv_purdtl_id=p.purdtl_id AND purcv_id  < '$code' AND purcv_purdtl_id='$filter' ORDER BY purcv_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPurcvdsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPurcvds($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPurcvds($code, $filter);
					if (!$rec) $rec = $this->getFirstPurcvds($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPurcvds($code, $filter);
					if (!$rec) $rec = $this->getLastPurcvds($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPurcvds($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPurcvds($filter);
				} else {
					$rec = $this->getPurcvds($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPurcvds($filter);
				} else {
					$rec = $this->getFirstPurcvds($filter);
				}
			}
		}
		return $rec;
	}

	function getPurcvdsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition != "all") $this->query = "SELECT * FROM purcvs r, purdtls p WHERE r.purcv_purdtl_id=p.purdtl_id AND purcv_purdtl_id='$condition' ORDER BY purcv_id  ";
			else $this->query = "SELECT * FROM purcvs r, purdtls p WHERE r.purcv_purdtl_id=p.purdtl_id ORDER BY purcv_id ";
		} else {
			if ($condition != "all") $this->query = "SELECT * FROM purcvs r, purdtls p WHERE r.purcv_purdtl_id=p.purdtl_id AND purcv_purdtl_id='$condition' ORDER BY purcv_id  ";
			else $this->query = "SELECT * FROM purcvs r, purdtls p WHERE r.purcv_purdtl_id=p.purdtl_id ORDER BY purcv_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAPRaw();
	}

	function getPurcvdsRows($purch_id="") {
		if (empty($purch_id)) $this->query = "SELECT count(purcv_id) AS numrows FROM purcvs ";
		else $this->query = "SELECT count(purcv_id) AS numrows FROM purcvs WHERE purcv_purdtl_id='$purch_id'";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>