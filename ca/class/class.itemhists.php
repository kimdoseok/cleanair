<?php
include_once("class.ic.php");
include_once("class.dbutils.php");

class ItemHists extends IC {

	function insertItemHists($arr) {
		if ($lastid = $this->insertIC("itemhists", $arr)) return $lastid;
		return false;
	}

	function deleteItemHists($id) {
		$this->query = "DELETE FROM itemhists WHERE itemhist_id='$id' ";
		if ($this->updateICRaw()) return true;
		else return false;
	}

	function getItemHists($id) {
		$this->query = "SELECT * FROM itemhists WHERE itemhist_id = '$id' LIMIT 1 ";
//echo $this->query."<br>";
		if ($arr = $this->getIC($item)) return $arr[0];
		return false;
	}

	function getItemUnitsFields() {
		$this->query = "SELECT * FROM itemhists LIMIT 0 ";
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

	function getLastItemHists($item="") {
		if (!empty($item)) $where = "WHERE itemhist_item_code='$item' ";
		$this->query = "SELECT * FROM itemhists $where ORDER BY itemhist_id DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstItemHists($item="") {
		if (!empty($item)) $where = "WHERE itemhist_item_code='$item' ";
		$this->query = "SELECT * FROM itemhists ORDER BY itemhist_id LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextItemHists($id, $item="") {
		if (!empty($item)) $where = "AND itemhist_item_code='$item' ";
		$this->query = "SELECT * FROM itemhists WHERE itemhist_id > '$id' $where ORDER BY itemhist_id LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevItemHists($id, $item="") {
		if (!empty($item)) $where = "AND itemhist_item_code='$item' ";
		$this->query = "SELECT * FROM itemhists WHERE itemhist_id  < '$id' $where ORDER BY itemhist_id DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $id="", $item="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($item)) {
				$cols = $this->getitemhistsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getItemHists($id);
			}
		} else {
			if (!empty($item)) {
				if ($direction == -1) {
					$rec = $this->getPrevItemHists($id, $item);
					if (!$rec) $rec = $this->getFirstItemHists($item);
				} else if ($direction == 1) {
					$rec = $this->getNextItemHists($id, $item);
					if (!$rec) $rec = $this->getLastItemHists($item);
				} else if ($direction == -2) {
					$rec = $this->getFirstItemHists($item);
				} else if ($direction == 2) {
					$rec = $this->getLastItemHists($item);
				} else {
					$rec = $this->getItemHists($id);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastItemHists($item);
				} else {
					$rec = $this->getFirstItemHists($item);
				}
			}
		}
		return $rec;
	}

	function getItemHistsList($item, $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		$select = "SELECT * ";
		$from = "FROM itemhists ";
		$where = "WHERE itemhist_item_code = '$item' ";
		$orderby = "ORDER BY itemhist_ts ";
		if ($reverse == "t") $orderby .= " DESC ";
		
		$this->query = "$select $from $where $orderby ";
		$offset = $page * $limit;
		$this->query .= "LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getIC();
	}

	function getItemHistsRows($item) {
		$this->query = "SELECT count(itemhist_id) AS numrows FROM itemhists WHERE itemhist_item_code='$item' ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>