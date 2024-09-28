<?php
include_once("class.gl.php");

class GLedger extends GL {

	function insertGLedger($arr) {
		if ($lastid = $this->insertGL("gldgrs", $arr)) return $lastid;
		return false;
	}

	function updateGLedger($code, $arr) {
		if ($this->updateGL("gldgrs", "gldgr_id", $code, $arr)) return true;
		return false;
	}

	function deleteGLedger($code) {
		$query = "delete from gldgrs where gldgr_id='$code'";
		if ($this->updateGLRaw($query)) return true;
		return false;
	}

	function updateGLedgerAmt($code, $newamt, $oldamt, $flg) {
		$amt = $newamt - $oldamt;
		if ($flg == "t") {
			$this->query = "UPDATE gldgrs SET x = x + $amt WHERE gldgr_id='$code' ";
		} else {
			$this->query = "UPDATE gldgrs SET x = x + $amt WHERE gldgr_id='$code' ";
		}
		if ($this->updateGLRaw()) return true;
		else return false;
	}

	function getGLedger($code) {
		$this->query = "SELECT * FROM gldgrs WHERE gldgr_id = '$code' LIMIT 1 ";
		if ($arr = $this->getGL($code)) return $arr[0];
		return false;
	}

	function getGLedgerFields() {
		$this->query = "SELECT * FROM gldgrs LIMIT 0 ";
		if ($arr = $this->getGLFields($this->query)) return $arr;
		return false;
	}

	function getLastGLedger($filter="") {
		$this->query = "SELECT * FROM gldgrs ORDER BY gldgr_id DESC LIMIT 1 ";
		$arr = $this->getGL();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstGLedger($filter="") {
		$this->query = "SELECT * FROM gldgrs ORDER BY gldgr_id LIMIT 1 ";
		$arr = $this->getGL();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextGLedger($code, $filter="") {
		$this->query = "SELECT * FROM gldgrs WHERE gldgr_id > '$code' ORDER BY gldgr_id LIMIT 1 ";
		$arr = $this->getGL($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevGLedger($code, $filter="") {
		$this->query = "SELECT * FROM gldgrs WHERE gldgr_id  < '$code' ORDER BY gldgr_id DESC LIMIT 1 ";
		$arr = $this->getGL($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getGLedgerFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getGLedger($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevGLedger($code, $filter);
					if (!$rec) $rec = $this->getFirstGLedger($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextGLedger($code, $filter);
					if (!$rec) $rec = $this->getLastGLedger($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstGLedger($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastGLedger($filter);
				} else {
					$rec = $this->getGLedger($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastGLedger($filter);
				} else {
					$rec = $this->getFirstGLedger($filter);
				}
			}
		}
		return $rec;
	}

	function getGLedgerList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM gldgrs ORDER BY gldgr_id  ";
			else if ($condition == "desc") $this->query = "SELECT * FROM gldgrs ORDER BY gldgr_desc  ";
			else $this->query = "SELECT * FROM gldgrs ORDER BY gldgr_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM gldgrs WHERE gldgr_id  LIKE '$filtertext%' ORDER BY gldgr_id ";
			else if ($condition == "desc") $this->query = "SELECT * FROM gldgrs WHERE gldgr_desc  LIKE '$filtertext%' ORDER BY gldgr_desc  ";
			else $this->query = "SELECT * FROM gldgrs ORDER BY gldgr_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getGL();
	}

	function getGLedgerListEx($condition="", $filtertext, $ft_arr=array(), $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
// format of filtertext array
// $ft_arr = array(array("l"=>"fieldname","r"=>"value","o"=>"operator"),...);
		$ft_num = count($ft_arr);
		$filter = " ";
		for ($i=0;$i<$ft_num;$i++) {
			if (!empty($ft_arr[$i][r])) {
				if ($ft_arr[$i][o]=="gt") $filter .= " AND ".$ft_arr[$i][l].">'".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="ge") $filter .= " AND ".$ft_arr[$i][l].">='".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="lt") $filter .= " AND ".$ft_arr[$i][l]."<'".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="le") $filter .= " AND ".$ft_arr[$i][l]."<='".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="eq") $filter .= " AND ".$ft_arr[$i][l]."='".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="ls") $filter .= " AND ".$ft_arr[$i][l]." LIKE '".$ft_arr[$i][r]."%' ";
				else if ($ft_arr[$i][o]=="lm") $filter .= " AND ".$ft_arr[$i][l]." LIKE '%".$ft_arr[$i][r]."%' ";
				else if ($ft_arr[$i][o]=="ll") $filter .= " AND ".$ft_arr[$i][l]." LIKE '%".$ft_arr[$i][r]."' ";
			}
		}
		if ($ft_num > 0) {
			if ($condition == "code") $this->query = "SELECT * FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' AND gldgr_id  LIKE '$filtertext%' $filter ORDER BY gldgr_id ";
			else if ($condition == "desc") $this->query = "SELECT * FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' AND gldgr_desc  LIKE '$filtertext%' $filter ORDER BY gldgr_desc  ";
			else $this->query = "SELECT * FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' $filter ORDER BY gldgr_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' ORDER BY gldgr_id  ";
			else if ($condition == "desc") $this->query = "SELECT * FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' ORDER BY gldgr_desc  ";
			else $this->query = "SELECT * FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' ORDER BY gldgr_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getGL();
	}

	function getGLedgerRows() {
		$this->query = "SELECT count(gldgr_id) AS numrows FROM gldgrs ";
		$arr = $this->getGL();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getGLedgerRowsEx($condition="", $filtertext, $ft_arr=array()) {
// format of filtertext array
// $ft_arr = array(array("l"=>"fieldname","r"=>"value","o"=>"operator"),...);
		$ft_num = count($ft_arr);
		$filter = " ";
		for ($i=0;$i<$ft_num;$i++) {
			if (!empty($ft_arr[$i][r])) {
				if ($ft_arr[$i][o]=="gt") $filter .= " AND ".$ft_arr[$i][l].">'".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="ge") $filter .= " AND ".$ft_arr[$i][l].">='".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="lt") $filter .= " AND ".$ft_arr[$i][l]."<'".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="le") $filter .= " AND ".$ft_arr[$i][l]."<='".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="eq") $filter .= " AND ".$ft_arr[$i][l]."='".$ft_arr[$i][r]."'";
				else if ($ft_arr[$i][o]=="ls") $filter .= " AND ".$ft_arr[$i][l]." LIKE '".$ft_arr[$i][r]."%' ";
				else if ($ft_arr[$i][o]=="lm") $filter .= " AND ".$ft_arr[$i][l]." LIKE '%".$ft_arr[$i][r]."%' ";
				else if ($ft_arr[$i][o]=="ll") $filter .= " AND ".$ft_arr[$i][l]." LIKE '%".$ft_arr[$i][r]."' ";
			}
		}
		if ($ft_num > 0) {
			if ($condition == "code") $this->query = "SELECT count(gldgr_id) AS numrows FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' AND gldgr_id  LIKE '$filtertext%' $filter";
			else if ($condition == "desc") $this->query = "SELECT count(gldgr_id) AS numrows FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' AND gldgr_desc  LIKE '$filtertext%' $filter ORDER BY gldgr_desc  ";
			else $this->query = "SELECT count(gldgr_id) AS numrows FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' $filter ORDER BY gldgr_id ";
		} else {
			$this->query = "SELECT count(gldgr_id) AS numrows FROM gldgrs g, jrnltrxs j WHERE g.gldgr_id=j.jrnltrx_ref_id AND j.jrnltrx_type='g' ";
		}
	
		$arr = $this->getGL();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>