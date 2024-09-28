<?php
include_once("class.ar.php");

class Pends extends AR {

	function insertPends($arr) {
		if ($lastid = $this->insertAR("pends", $arr)) return $lastid;
		return false;
	}

	function updatePends($code, $arr) {
		if ($this->updateAR("pends", "pend_id", $code, $arr)) return true;
		return false;
	}

	function getPends($code) {
		$this->query = "SELECT * FROM pends WHERE pend_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function deletePends($code) {
		$query = "delete from pends where pend_id='$code'";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function increasePends($code, $fld_name, $qty) {
		$query = "UPDATE pends SET $fld_name = $fld_name + $qty WHERE pend_id=$code";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getPendsCount($cust_code) {
		$this->query = "SELECT count(pend_id) AS pend_cnt FROM pends WHERE pend_cust_code = '$cust_code' ";
		if ($arr = $this->getAR()) return $arr[0][pend_cnt];
		return false;
	}

	function getPendsFields() {
		$this->query = "SELECT * FROM pends LIMIT 0 ";
		if ($arr = $this->getARFields()) return $arr;
		return false;
	}

	function getLastPends($filter="") {
		$this->query = "SELECT * FROM pends ORDER BY pend_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstPends($filter="") {
		$this->query = "SELECT * FROM pends ORDER BY pend_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextPends($code, $filter="") {
		$this->query = "SELECT * FROM pends WHERE pend_id < '$code' ORDER BY pend_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevPends($code, $filter="") {
		$this->query = "SELECT * FROM pends WHERE pend_id  > '$code' ORDER BY pend_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getPendsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getPends($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevPends($code, $filter);
					if (!$rec) $rec = $this->getFirstPends($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextPends($code, $filter);
					if (!$rec) $rec = $this->getLastPends($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstPends($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastPends($filter);
				} else {
					$rec = $this->getPends($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastPends($filter);
				} else {
					$rec = $this->getFirstPends($filter);
				}
			}
		}
		return $rec;
	}

  function getPendsIds($sale_id) {
    $this->query = "SELECT pend_id FROM pends WHERE pend_parent=$sale_id OR pend_origin=$sale_id ORDER BY pend_id ";
    $arr = $this->getAR();
    $outarr =  array();
    if (!$arr) return false;
    for ($i=0;$i<count($arr);$i++) array_push($outarr, $pend_id);
    return $outarr;
  }
  
	function getPendsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20, $showall="f") {
		if ($page < 1) $page = 0;
		else $page--;

		if ($showall=="t") {
			$sawhere = "";
			$sawhand = "";
		} else {
			$sawhere = "WHERE pend_status > 0 ";
			$sawhand = "AND pend_status > 0 ";
		}
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM pends $sawhere ORDER BY pend_id ";
			else if ($condition == "cust") $this->query = "SELECT * FROM pends $sawhere ORDER BY pend_cust_code ";
			else if ($condition == "date") $this->query = "SELECT * FROM pends $sawhere ORDER BY pend_date ";
			else if ($condition == "tel") $this->query = "SELECT * FROM pends $sawhere ORDER BY pend_tel ";
			else $this->query = "SELECT * FROM pends $sawhere ORDER BY pend_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM pends WHERE pend_id LIKE '$filtertext%' $sawhand ORDER BY pend_id ";
			else if ($condition == "cust") $this->query = "SELECT * FROM pends WHERE pend_cust_code  LIKE '$filtertext%' $sawhand ORDER BY pend_cust_code ";
			else if ($condition == "date") $this->query = "SELECT * FROM pends WHERE pend_date = '$filtertext' $sawhand ORDER BY pend_date  ";
			else if ($condition == "tel") $this->query = "SELECT * FROM pends WHERE pend_tel  LIKE '$filtertext%' $sawhand ORDER BY pend_tel ";
			else $this->query = "SELECT * FROM pends WHERE pend_id LIKE '$filtertext%' $sawhand ORDER BY pend_id ";
		}
		
		if ($reverse == "t") {
			if ($condition == "code") $this->query .= " ";
			else if ($condition == "cust") $this->query .= " DESC, pend_id DESC ";
			else if ($condition == "date") $this->query .= " , pend_id DESC ";
			else if ($condition == "tel") $this->query .= " DESC, pend_id DESC ";
			else $this->query .= " ";
		} else {
			if ($condition == "code") $this->query .= " DESC ";
			else if ($condition == "cust") $this->query .= " , pend_id DESC ";
			else if ($condition == "date") $this->query .= " DESC, pend_id DESC";
			else if ($condition == "tel") $this->query .= " , pend_id DESC";
			else $this->query .= " DESC ";
		}
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getPendsRows($condition="", $filtertext="", $showall="f") {
		if ($showall=="t") {
			$sawhere = "";
			$sawhand = "";
		} else {
			$sawhere = "WHERE pend_status > 0 ";
			$sawhand = "AND pend_status > 0 ";
		}

		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(pend_id) AS numrows FROM pends $sawhere ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(pend_id) AS numrows FROM pends WHERE pend_id LIKE '$filtertext%' $sawhand ";
			else if ($condition == "cust") $this->query = "SELECT count(pend_id) AS numrows FROM pends WHERE pend_cust_code  LIKE '$filtertext%' $sawhand ";
			else if ($condition == "tel") $this->query = "SELECT count(pend_id) AS numrows FROM pends WHERE pend_tel  LIKE '$filtertext%' $sawhand ";
			else $this->query = "SELECT count(pend_id) AS numrows FROM pends WHERE pend_id LIKE '$filtertext%' $sawhand ";
		}
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getPendsRowsAvl($cust_code) {
		$this->query = "SELECT pend_id FROM pends s, pendlts d WHERE s.pend_id=d.pendtl_pend_id AND s.pend_cust_code='$cust_code' GROUP BY s.pend_id HAVING sum(d.pendtl_qty)>sum(d.pendtl_qty_picked)";
		return $this->getARRow();
	}

	function getPendsRange($fr="", $to="", $ord="", $rev="t") {
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
		$this->query = "SELECT * FROM pends $where $orderby ";
		return $this->getARRaw();
	}

}

?>
