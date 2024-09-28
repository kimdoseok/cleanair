<?php
include_once("class.ic.php");

class Material extends IC {

	function insertMaterial($arr) {
		if ($lastid = $this->insertIC("materials", $arr)) return $lastid;
		return false;
	}

	function updateMaterial($code, $arr) {
		if ($this->updateIC("materials", "material_code", $code, $arr)) return true;
		return false;
	}

	function deleteMaterial($code) {
		$query = "delete from materials where material_code='$code'";
		if ($this->updateICRaw($query)) return true;
		return false;
	}

	function getMaterial($code="") {
		$code = trim($code);
		if (empty($code)) return false;
		$this->query = "SELECT * FROM materials WHERE material_code = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function getMaterialFields() {
		$this->query = "SELECT * FROM materials LIMIT 0 ";
		if ($arr = $this->getICFields($this->query)) return $arr;
		return false;
	}

	function getLastMaterial($filter="") {
		$this->query = "SELECT * FROM materials ORDER BY material_code DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstMaterial($filter="") {
		$this->query = "SELECT * FROM materials ORDER BY material_code LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextMaterial($code, $filter="") {
		$this->query = "SELECT * FROM materials WHERE material_code > '$code' ORDER BY material_code LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevMaterial($code, $filter="") {
		$this->query = "SELECT * FROM materials WHERE material_code  < '$code' ORDER BY material_code DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getMaterialFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getMaterial($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevMaterial($code, $filter);
					if (!$rec) $rec = $this->getFirstMaterial($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextMaterial($code, $filter);
					if (!$rec) $rec = $this->getLastMaterial($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstMaterial($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastMaterial($filter);
				} else {
					$rec = $this->getMaterial($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastMaterial($filter);
				} else {
					$rec = $this->getFirstMaterial($filter);
				}
			}
		}
		return $rec;
	}

	function getMaterialList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM materials ORDER BY material_code, material_name ";
			else if ($condition == "name") $this->query = "SELECT * FROM materials ORDER BY  material_name ";
			else if ($condition == "desc") $this->query = "SELECT * FROM materials ORDER BY material_desc, material_name ";
			else $this->query = "SELECT * FROM materials ORDER BY material_code, material_name ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM materials WHERE material_code LIKE '$filtertext%' ORDER BY material_code, material_name";
			else if ($condition == "name") $this->query = "SELECT * FROM materials WHERE material_name LIKE '$filtertext%' ORDER BY  material_name";
			else if ($condition == "desc") $this->query = "SELECT * FROM materials WHERE material_desc  LIKE '$filtertext%' ORDER BY material_desc, material_name ";
			else $this->query = "SELECT * FROM materials ORDER BY material_code, material_name";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getIC();
	}

	function getMaterialRows() {
		$this->query = "SELECT count(material_code) AS numrows FROM materials ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

}
?>