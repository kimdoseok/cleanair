<?php
include_once("class.ar.php");

class Ships extends AR {

	var $active;

	function insertShips($arr) {
		if ($lastid = $this->insertAR("ships", $arr)) return $lastid;
		return false;
	}

	function updateShips($code, $arr) {
		if ($this->updateAR("ships", "ship_id", $code, $arr)) return true;
		return false;
	}

	function updateShipsAmt($code, $field, $amt) {
		$this->query = "UPDATE ships SET $field = $field + $amt WHERE ship_id='$code' ";
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function deleteShips($code) {
		$this->query = "DELETE FROM ships WHERE ship_id='$code' ";
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function getShips($code) {
		if ($this->active == "t") $aw = "AND ship_active = 't' ";
		$this->query = "SELECT * FROM ships WHERE ship_id = '$code' $aw LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getShipsFields() {
		$this->query = "SELECT * FROM ships LIMIT 0 ";
		if ($arr = $this->getARFields($this->query)) return $arr;
		return false;
	}

	function getLastShips($filter="") {
		if ($this->active == "t") $aw = "WHERE ship_active = 't' ";
		$this->query = "SELECT * FROM ships $aw ORDER BY ship_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstShips($filter="") {
		if ($this->active == "t") $aw = "WHERE ship_active = 't' ";
		$this->query = "SELECT * FROM ships $aw ORDER BY ship_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextShips($code, $filter="") {
		if ($this->active == "t") $aw = "AND ship_active = 't' ";
		$this->query = "SELECT * FROM ships WHERE ship_id > '$code' $aw ORDER BY ship_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevShips($code, $filter="") {
		if ($this->active == "t") $aw = "AND ship_active = 't' ";
		$this->query = "SELECT * FROM ships WHERE ship_id  < '$code' $aw ORDER BY ship_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getshipsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getShips($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevShips($code, $filter);
					if (!$rec) $rec = $this->getFirstShips($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextShips($code, $filter);
					if (!$rec) $rec = $this->getLastShips($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstShips($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastShips($filter);
				} else {
					$rec = $this->getShips($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastShips($filter);
				} else {
					$rec = $this->getFirstShips($filter);
				}
			}
		}
		return $rec;
	}

	function getShipsList($code, $condition="", $filtertext="", $reverse="f") {
		if ($page < 1) $page = 0;
		else $page--;

		$select = "SELECT * ";
		$from = "FROM ships ";
		$where = "WHERE ship_cust_code = '$code' ";

		if ($this->active == "t") $where .= "AND ship_active = 't' ";

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "name") {
				$orderby = "ORDER BY ship_name ";
			} else if ($condition == "addr") {
				$orderby = "ORDER BY ship_addr1  ";
			} else if ($condition == "city") {
				$orderby = "ORDER BY ship_city  ";
			} else if ($condition == "tel") {
				$orderby = "ORDER BY ship_tel  ";
			} else {
				$orderby = "ORDER BY ship_id ";
			}
		} else {
			if ($condition == "name") {
				$where .= "AND ship_name LIKE '$filtertext%' ";
				$orderby = "ORDER BY ship_name ";
			} else if ($condition == "addr") {
				$where .= "AND ship_addr1 LIKE '$filtertext%' ";
				$orderby = "ORDER BY ship_addr1 ";
			} else if ($condition == "city") {
				$where .= "AND ship_city LIKE '$filtertext%' ";
				$orderby = "ORDER BY ship_city ";
			} else if ($condition == "tel") {
				$where .= "AND ship_tel LIKE '$filtertext%' ";
				$orderby = "ORDER BY ship_tel ";
			} else {
				$where .= "AND ship_name LIKE '$filtertext%' ";
				$orderby = "ORDER BY ship_name ";
			}
		}
		if ($reverse == "t") $orderby .= " DESC ";
		
		$this->query .= "$select $from $where $orderby ";
//echo $this->query."<br>";
		return $this->getAR();
	}


	function getShipsRows($condition="", $filtertext="") {
		if ($this->active == "t") {
			$wh .= "WHERE ship_active = 't' ";
			$aw .= "AND ship_active = 't' ";
		}
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(ship_id) AS numrows FROM ships $wh ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(ship_id) AS numrows FROM ships WHERE ship_id LIKE '$filtertext%' $aw";
			else if ($condition == "name") $this->query = "SELECT count(ship_id) AS numrows FROM ships WHERE ship_name  LIKE '$filtertext%' $aw";
			else $this->query = "SELECT count(ship_id) AS numrows FROM ships WHERE ship_id LIKE '$filtertext%' $aw";
		}
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>