<?php
include_once("class.ic.php");
include_once("class.dbutils.php");

class ItemUnits extends IC {

	var $active;

	function insertItemUnits($arr) {
		if ($lastid = $this->insertIC("itemunits", $arr)) return $lastid;
		return false;
	}

	function updateItemUnits($item,$unit, $arr) {
		$dbu = new Dbutils();
		$pair = $dbu->getPairStr($arr);
		$this->query = "UPDATE itemunits SET $pair WHERE itemunit_item = '$item' AND  itemunit_unit = '$unit' ";

		if ($dbu->updateQry($this->query)) return true;
		return false;
	}

	function updateItemUnitsAmt($item, $unit, $field, $amt) {
		$this->query = "UPDATE itemunits SET $field = $field + $amt WHERE itemunit_item='$item' AND itemunit_unit='$unit' ";
//echo $this->query."<br>";
		if ($this->updateICRaw()) return true;
		else return false;
	}

	function deleteItemUnits($item, $unit) {
		$this->query = "DELETE FROM itemunits WHERE itemunit_item='$item' AND itemunit_unit='$unit' ";
		if ($this->updateICRaw()) return true;
		else return false;
	}

	function deleteItemUnitsByItem($item) {
		$this->query = "DELETE FROM itemunits WHERE itemunit_item='$item' ";
		if ($this->updateICRaw()) return true;
		else return false;
	}

	function getItemUnits($item, $unit) {
		if ($this->active == "t") $aw = "AND itemunit_active = 't' ";
		$this->query = "SELECT * FROM itemunits WHERE itemunit_item = '$item' AND itemunit_unit = '$unit'  $aw LIMIT 1 ";
//echo $this->query."<br>";
		if ($arr = $this->getIC($item)) return $arr[0];
		return false;
	}

	function getItemUnitsFields() {
		$this->query = "SELECT * FROM itemunits LIMIT 0 ";
		if ($arr = $this->getICFields($this->query)) {
			if ($arr) {
				$num = count($arr);
				$karr = array();
				for ($i=0;$i<$num;$i++) {
					$x = $arr[$i];
					$karr[$x] = "";
				}
				return $karr;
			}
			return false;
		} else {
			return false;
		}
	}

	function getLastItemUnits($filter="") {
		if ($this->active == "t") $aw = "WHERE itemunit_active = 't' ";
		$this->query = "SELECT * FROM itemunits $aw ORDER BY concat(itemunit_item, itemunit_unit) DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstItemUnits($filter="") {
		if ($this->active == "t") $aw = "WHERE itemunit_active = 't' ";
		$this->query = "SELECT * FROM itemunits $aw ORDER BY concat(itemunit_item, itemunit_unit) LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextItemUnits($item, $unit, $filter="") {
		$itemnuit = $item.$unit;
		if ($this->active == "t") $aw = "AND itemunit_active = 't' ";
		$this->query = "SELECT * FROM itemunits WHERE concat(itemunit_item, itemunit_unit) > '$itemunit' $aw ORDER BY concat(itemunit_item, itemunit_unit) LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevItemUnits($item, $unit, $filter="") {
		$itemunit = $item.$unit;
		if ($this->active == "t") $aw = "AND itemunit_active = 't' ";
		$this->query = "SELECT * FROM itemunits WHERE concat(itemunit_item, itemunit_unit)  < '$itemunit' $aw ORDER BY concat(itemunit_item, itemunit_unit) DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $item="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($item)) {
				$cols = $this->getitemunitsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getItemUnits($item);
			}
		} else {
			if (!empty($item)) {
				if ($direction == -1) {
					$rec = $this->getPrevItemUnits($item, $filter);
					if (!$rec) $rec = $this->getFirstItemUnits($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextItemUnits($item, $filter);
					if (!$rec) $rec = $this->getLastItemUnits($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstItemUnits($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastItemUnits($filter);
				} else {
					$rec = $this->getItemUnits($item, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastItemUnits($filter);
				} else {
					$rec = $this->getFirstItemUnits($filter);
				}
			}
		}
		return $rec;
	}

	function getItemUnitsListByItem($item, $filtertext="", $reverse="f") {
		if ($page < 1) $page = 0;
		else $page--;

		$select = "SELECT * ";
		$from = "FROM itemunits i, units u ";
		$where = "WHERE i.itemunit_item = '$item' ";
		$where .= "AND i.itemunit_unit = u.unit_code ";

		if ($this->active == "t") $where .= "AND i.itemunit_active = 't' ";

		if (empty($filtertext) || !isset($filtertext)) {
			$orderby = "ORDER BY i.itemunit_unit ";
		} else {
			$where .= "AND i.itemunit_unit LIKE '$filtertext%' ";
			$orderby = "ORDER BY i.itemunit_unit ";
		}
		if ($reverse == "t") $orderby .= " DESC ";
		
		$this->query = "$select $from $where $orderby ";
//echo $this->query."<br>";
		return $this->getIC();
	}

	function getItemUnitsListByItemSingle($item, $filtertext="", $reverse="f") {
		if ($page < 1) $page = 0;
		else $page--;

		$select = "SELECT * ";
		$from = "FROM itemunits ";
		$where = "WHERE itemunit_item = '$item' ";

		if ($this->active == "t") $where .= "AND itemunit_active = 't' ";

		if (empty($filtertext) || !isset($filtertext)) {
			$orderby = "ORDER BY itemunit_unit ";
		} else {
			$where .= "AND itemunit_unit LIKE '$filtertext%' ";
			$orderby = "ORDER BY itemunit_unit ";
		}
		if ($reverse == "t") $orderby .= " DESC ";
		
		$this->query = "$select $from $where $orderby ";
//echo $this->query."<br>";
		return $this->getIC();
	}

	function getItemUnitsListByUnit($unit, $filtertext="", $reverse="f") {
		if ($page < 1) $page = 0;
		else $page--;

		$select = "SELECT * ";
		$from = "FROM itemunits ";
		$where = "WHERE itemunit_unit = '$unit' ";

		if ($this->active == "t") $where .= "AND itemunit_active = 't' ";

		if (empty($filtertext) || !isset($filtertext)) {
			$orderby = "ORDER BY itemunit_item ";
		} else {
			$where .= "AND itemunit_item LIKE '$filtertext%' ";
			$orderby = "ORDER BY itemunit_item ";
		}
		if ($reverse == "t") $orderby .= " DESC ";
		
		$this->query = "$select $from $where $orderby ";
		return $this->getIC();
	}


	function getItemUnitsRowsByItem($item, $filtertext="") {
		$where = "WHERE itemunit_item='$item' ";
		if ($this->active == "t") $where .= "AND itemunit_active = 't' ";
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(itemunit_item) AS numrows FROM itemunits $where ";
		} else {
			$this->query = "SELECT count(itemunit_item) AS numrows FROM itemunits $where AND itemunit_unit LIKE '$filtertext%' ";
		}
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getItemUnitsRowsByUnit($unit, $filtertext="") {
		$where = "WHERE itemunit_unit='$unit' ";
		if ($this->active == "t") $where .= "AND itemunit_active = 't' ";
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(itemunit_item) AS numrows FROM itemunits $where ";
		} else {
			$this->query = "SELECT count(itemunit_item) AS numrows FROM itemunits $where AND itemunit_item LIKE '$filtertext%' ";
		}
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>
