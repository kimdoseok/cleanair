<?php
include_once("class.gl.php");

class JrnlTrxs extends GL {

	function insertJrnlTrxs($arr) {
		if ($lastid = $this->insertGL("jrnltrxs", $arr)) return $lastid;
		return false;
	}

	function insertJrnlTrxExs($lastid, $user, $acct, $type, $dc, $amt, $dat) {
		if ($amt == 0) return false;
		$j_arr = array();
		$j_arr["jrnltrx_ref_id"] = $lastid;
		$j_arr["jrnltrx_user_code"] = $user;
		$j_arr["jrnltrx_acct_code"] = $acct;
		$j_arr["jrnltrx_type"] = $type;
		$j_arr["jrnltrx_dc"] = $dc;
		$j_arr["jrnltrx_amt"] = $amt;
		$j_arr["jrnltrx_date"] = $dat;
		if ($id = $this->insertJrnlTrxs($j_arr)) return $id;
		return false;
	}

	function updateJrnlTrxs($code, $arr) {
		if ($this->updateGL("jrnltrxs", "jrnltrx_id", $code, $arr)) return true;
		return false;
	}

	function deleteJrnlTrxRefs($code, $type) {
		$code = addslashes(trim($code));
		$type = addslashes(trim($type));
		$query = "DELETE FROM jrnltrxs WHERE jrnltrx_ref_id = '$code' AND jrnltrx_type='$type' ";
		if ($arr = $this->updateGLRaw($query)) return $arr;
		return false;
	}

	function getJrnlTrxs($code) {
		$this->query = "SELECT * FROM jrnltrxs WHERE jrnltrx_id = '$code' LIMIT 1 ";
		if ($arr = $this->getGL($code)) return $arr[0];
		return false;
	}

	function getJrnlTrxRefs($code) {
		$this->query = "SELECT * FROM jrnltrxs WHERE jrnltrx_ref_id = '$code' LIMIT 1 ";
		if ($arr = $this->getGL($code)) return $arr;
		return false;
	}

	function getJrnlTrxsAmt($code, $type, $dc) {
		$this->query = "SELECT sum(jrnltrx_amt) AS amt FROM jrnltrxs WHERE jrnltrx_ref_id = '$code' AND jrnltrx_dc='$dc' AND jrnltrx_type='$type' ";
		if ($arr = $this->getGL($code)) return $arr[0]["amt"];
		return false;
	}


	function getJrnlTrxTrial($code, $begin, $end) {
		$jarr = array();
		$this->query = "SELECT sum(jrnltrx_amt) AS amt FROM jrnltrxs ";
		$this->query .= " WHERE jrnltrx_acct_code = '$code' AND jrnltrx_dc='d' ";
		if (!empty($begin)) $this->query .= " AND jrnltrx_date < '$begin' ";
		$arr = $this->getGL();
		$jarr[begin_debit] = $arr[0]["amt"];
		$this->query = "SELECT sum(jrnltrx_amt) AS amt FROM jrnltrxs ";
		$this->query .= "WHERE jrnltrx_acct_code = '$code' AND jrnltrx_dc='c' ";
		if (!empty($begin)) $this->query .= "AND jrnltrx_date < '$begin' ";
		$arr = $this->getGL();
		$jarr[begin_credit] = $arr[0]["amt"];
		$this->query = "SELECT sum(jrnltrx_amt) AS amt FROM jrnltrxs ";
		$this->query .= "WHERE jrnltrx_acct_code = '$code' AND jrnltrx_dc='d' ";
		if (!empty($begin)) $this->query .= "AND jrnltrx_date >= '$begin' ";
		if (!empty($end)) $this->query .= "AND jrnltrx_date <= '$end' ";
		$arr = $this->getGL();
		$jarr[period_debit] = $arr[0]["amt"];
		$this->query = "SELECT sum(jrnltrx_amt) AS amt FROM jrnltrxs ";
		$this->query .= "WHERE jrnltrx_acct_code = '$code' AND jrnltrx_dc='c' ";
		if (!empty($begin)) $this->query .= "AND jrnltrx_date >= '$begin' ";
		if (!empty($end)) $this->query .= "AND jrnltrx_date <= '$end' ";
		$arr = $this->getGL();
		$jarr[period_credit] = $arr[0]["amt"];
		if ($jarr) return $jarr;
		return false;
	}

	function getJrnlTrxsFields() {
		$this->query = "SELECT * FROM jrnltrxs LIMIT 0 ";
		if ($arr = $this->getGLFields()) return $arr;
		return false;
	}

	function getLastJrnlTrxs($filter="") {
		$this->query = "SELECT * FROM jrnltrxs ORDER BY jrnltrx_id DESC LIMIT 1 ";
		$arr = $this->getGL();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstJrnlTrxs($filter="") {
		$this->query = "SELECT * FROM jrnltrxs ORDER BY jrnltrx_id LIMIT 1 ";
		$arr = $this->getGL();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextJrnlTrxs($code, $filter="") {
		$this->query = "SELECT * FROM jrnltrxs WHERE jrnltrx_id > '$code' ORDER BY jrnltrx_id LIMIT 1 ";
		$arr = $this->getGL($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevJrnlTrxs($code, $filter="") {
		$this->query = "SELECT * FROM jrnltrxs WHERE jrnltrx_id  < '$code' ORDER BY jrnltrx_id DESC LIMIT 1 ";
		$arr = $this->getGL($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getJrnlTrxsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getJrnlTrxs($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevJrnlTrxs($code, $filter);
					if (!$rec) $rec = $this->getFirstJrnlTrxs($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextJrnlTrxs($code, $filter);
					if (!$rec) $rec = $this->getLastJrnlTrxs($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstJrnlTrxs($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastJrnlTrxs($filter);
				} else {
					$rec = $this->getJrnlTrxs($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastJrnlTrxs($filter);
				} else {
					$rec = $this->getFirstJrnlTrxs($filter);
				}
			}
		}
		return $rec;
	}

	function getJrnlTrxsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = " SELECT * ";
		$from = " FROM jrnltrxs ";
		$where = " WHERE 1=1 ";
		$orderby = " ORDER BY jrnltrx_id ";
		if ($condition == "r") $where .= " AND jrnltrx_type='r' ";
		else if ($condition == "p") $where .= " AND jrnltrx_type='p' ";
		else if ($condition == "i") $where .= " AND jrnltrx_type='i' ";
		else if ($condition == "g") $where .= " AND jrnltrx_type='g' ";
		else if ($condition == "c") $where .= " AND jrnltrx_type='c' ";
		else if ($condition == "d") $where .= " AND jrnltrx_type='d' ";
		else $where .= "";
		if (!empty($filtertext) && isset($filtertext)) $where .= " AND jrnltrx_ref_id = '$filtertext' ";
		if ($reverse != "t") $orderby .= " DESC ";
		$this->query = "$select $from $where $orderby ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getGL();
	}

	function getJrnlTrxsRows($condition="", $filtertext="") {
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "r") $where = "WHERE jrnltrx_type='r'";
			else if ($condition == "p") $where = "WHERE jrnltrx_type='p'";
			else if ($condition == "i") $where = "WHERE jrnltrx_type='i'";
			else if ($condition == "g") $where = "WHERE jrnltrx_type='g'";
			else if ($condition == "c") $where = "WHERE jrnltrx_type='c'";
			else if ($condition == "d") $where = "WHERE jrnltrx_type='d'";
			else $where = "";
		} else {
			if ($condition == "r") $where = "WHERE jrnltrx_type='r' AND jrnltrx_ref_id LIKE '$filtertext%'";
			else if ($condition == "p") $where = "WHERE jrnltrx_type='p' AND jrnltrx_ref_id LIKE '$filtertext%'";
			else if ($condition == "i") $where = "WHERE jrnltrx_type='i' AND jrnltrx_ref_id LIKE '$filtertext%'";
			else if ($condition == "g") $where = "WHERE jrnltrx_type='g' AND jrnltrx_ref_id LIKE '$filtertext%'";
			else if ($condition == "c") $where = "WHERE jrnltrx_type='c' AND jrnltrx_ref_id LIKE '$filtertext%'";
			else if ($condition == "d") $where = "WHERE jrnltrx_type='d' AND jrnltrx_ref_id LIKE '$filtertext%'";
			else $where = "WHERE jrnltrx_ref_id LIKE '$filtertext%'";
		}
		$this->query = "SELECT count(jrnltrx_id) AS numrows FROM jrnltrxs $where";
		$arr = $this->getGL();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

}

?>