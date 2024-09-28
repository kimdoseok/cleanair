<?php
include_once("class.ic.php");

class ItmBuilds extends IC {

	function insertItmBuilds($arr) {
		if ($lastid = $this->insertIC("itmbuilds", $arr)) return $lastid;
		return false;
	}

	function updateItmBuilds($code, $arr) {
		if ($this->updateIC("itmbuilds", "itmbuild_id", $code, $arr)) return true;
		return false;
	}

	function getItmBuildsPO($code) {
		$this->query = "SELECT * FROM itmbuilds WHERE styl_po_no = '$code' ";
		if ($arr = $this->getIC($code)) return $arr;
		return false;

	}


	function getItmBuilds($code) {
		$this->query = "SELECT * FROM itmbuilds WHERE itmbuild_id = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function deleteItmBuilds($code) {

		$this->query = "DELETE FROM itmbuilds WHERE itmbuild_id = '$code' ";
		if ($arr = $this->updateICRaw($this->query)) return true;
		return false;

	}



	function getItmBuildsFields() {
		$this->query = "SELECT * FROM itmbuilds LIMIT 0 ";
		if ($arr = $this->getICFields()) return $arr;
		return false;
	}

	function getLastItmBuilds($filter="") {
		$this->query = "SELECT * FROM itmbuilds ORDER BY itmbuild_id DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstItmBuilds($filter="") {
		$this->query = "SELECT * FROM itmbuilds ORDER BY itmbuild_id LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextItmBuilds($code, $filter="") {
		$this->query = "SELECT * FROM itmbuilds WHERE itmbuild_id > '$code' ORDER BY itmbuild_id LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevItmBuilds($code, $filter="") {
		$this->query = "SELECT * FROM itmbuilds WHERE itmbuild_id  < '$code' ORDER BY itmbuild_id DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getItmBuildsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getItmBuilds($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevItmBuilds($code, $filter);
					if (!$rec) $rec = $this->getFirstItmBuilds($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextItmBuilds($code, $filter);
					if (!$rec) $rec = $this->getLastItmBuilds($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstItmBuilds($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastItmBuilds($filter);
				} else {
					$rec = $this->getItmBuilds($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastItmBuilds($filter);
				} else {
					$rec = $this->getFirstItmBuilds($filter);
				}
			}
		}
		return $rec;
	}

	function getItmBuildsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($condition)) $this->query = "SELECT * FROM itmbuilds ORDER BY itmbuild_id ";
		else $this->query = "SELECT * FROM itmbuilds WHERE styl_po_no ='$condition' ORDER BY itmbuild_id ";
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getIC();
	}

	function getItmBuildsRows($condition="", $filtertext="") {
		$this->query = "SELECT count(itmbuild_id) AS numrows FROM itmbuilds ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

}

?>