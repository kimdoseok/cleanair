<?php
include_once("class.sy.php");

class Company extends SY {

	function insertCompany($arr) {
		if ($lastid = $this->insertSY("company", $arr)) return $lastid;
		return false;
	}

	function updateCompany($code, $arr) {
		if ($this->updateSY("company", "name", $code, $arr)) return true;
		return false;
	}

	function deleteCompany($code) {
		$this->query = "DELETE FROM company WHERE name='$code' ";
		if ($this->updateSYRaw()) return true;
		else return false;
	}

	function getCompany() {
		$this->query = "SELECT * FROM company ORDER BY name ";
//echo $this->query."<br>";
		$arr = $this->getSY();
		if($arr) $arr_num = count($arr);
		else $arr_num = 0;
		$out_arr = array();
		for ($i=0;$i<$arr_num;$i++) {
			$k = $arr[$i]["Name"];
			$v = $arr[$i]["value"];
			$out_arr[$k] = $v;
		}
		return $out_arr;
	}

	function getCompanyRows() {
		$this->query = "SELECT count(name) AS numrows FROM company ";
		$arr = $this->getSY();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>