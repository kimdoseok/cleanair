<?php
include_once("class.ic.php");

class Category extends IC {

	function insertCategory($arr) {
		if ($lastid = $this->insertIC("cates", $arr)) return $lastid;
		return false;
	}

	function updateCategory($code, $arr) {
		if ($this->updateIC("cates", "cate_code", $code, $arr)) return true;
		return false;
	}

	function deleteCategory($code) {
		$query = "delete from cates where cate_code='$code'";
		if ($this->updateICRaw($query)) return true;
		return false;
	}

	function getCategory($code="") {
		$code = trim($code);
		if (empty($code)) return false;
		$this->query = "SELECT * FROM cates WHERE cate_code = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function getCategoryFields() {
		$this->query = "SELECT * FROM cates LIMIT 0 ";
		if ($arr = $this->getICFields($this->query)) return $arr;
		return false;
	}

	function getLastCategory($filter="") {
		$this->query = "SELECT * FROM cates ORDER BY cate_code DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstCategory($filter="") {
		$this->query = "SELECT * FROM cates ORDER BY cate_code LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextCategory($code, $filter="") {
		$this->query = "SELECT * FROM cates WHERE cate_code > '$code' ORDER BY cate_code LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevCategory($code, $filter="") {
		$this->query = "SELECT * FROM cates WHERE cate_code  < '$code' ORDER BY cate_code DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getCategoryFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getCategory($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevCategory($code, $filter);
					if (!$rec) $rec = $this->getFirstCategory($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextCategory($code, $filter);
					if (!$rec) $rec = $this->getLastCategory($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstCategory($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastCategory($filter);
				} else {
					$rec = $this->getCategory($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastCategory($filter);
				} else {
					$rec = $this->getFirstCategory($filter);
				}
			}
		}
		return $rec;
	}

	function getCategoryList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM cates ORDER BY cate_code, cate_name1, cate_name2, cate_name3, cate_name4 ";
			else if ($condition == "name") $this->query = "SELECT * FROM cates ORDER BY  cate_name1, cate_name2, cate_name3, cate_name4 ";
			else if ($condition == "desc") $this->query = "SELECT * FROM cates ORDER BY cate_desc, cate_name1, cate_name2, cate_name3, cate_name4 ";
			else $this->query = "SELECT * FROM cates ORDER BY cate_code, cate_name1, cate_name2, cate_name3, cate_name4 ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM cates WHERE cate_code LIKE '$filtertext%' ORDER BY cate_code, cate_name1, cate_name2, cate_name3, cate_name4";
			else if ($condition == "name") $this->query = "SELECT * FROM cates WHERE cate_name1 LIKE '$filtertext%' OR cate_name2 LIKE '$filtertext%' OR cate_name3 LIKE '$filtertext%' OR cate_name4 LIKE '$filtertext%' ORDER BY  cate_name1, cate_name2, cate_name3, cate_name4 ";
			else if ($condition == "desc") $this->query = "SELECT * FROM cates WHERE cate_desc  LIKE '$filtertext%' ORDER BY cate_desc, cate_name1, cate_name2, cate_name3, cate_name4 ";
			else $this->query = "SELECT * FROM cates ORDER BY cate_code, cate_name1, cate_name2, cate_name3, cate_name4 ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getIC();
	}

	function getCategoryRows() {
		$this->query = "SELECT count(cate_code) AS numrows FROM cates ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

}
?>