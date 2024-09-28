<?php
include_once("class.ic.php");

class ItemTrxs extends IC {

	function insertItemTrxs($arr) {
		if ($lastid = $this->insertIC("invtrxs", $arr)) return $lastid;
		return false;
	}

	function updateItemTrxs($code, $arr) {
		if ($this->updateIC("invtrxs", "invtrx_id", $code, $arr)) return true;
		return false;
	}

	function updateItemTrxsQty($code, $newqty, $oldqty, $flg) {
		$qty = $newqty - $oldqty;
		if ($flg == "t") {
			$this->query = "UPDATE invtrxs SET invtrx_qty = invtrx_qty + $qty WHERE invtrx_id='$code' ";
		} else {
			$this->query = "UPDATE invtrxs SET invtrx_qty = invtrx_qty + $qty WHERE invtrx_id='$code' ";
		}
		if ($this->updateICRaw()) return true;
		else return false;
	}

	function deleteItemTrxs($code) {
		$this->query = "DELETE FROM invtrxs WHERE invtrx_id='$code' ";
		if ($this->updateICRaw()) return true;
		else return false;
	}

	function getItemTrxs($code="") {
		$code = trim($code);
		if (empty($code)) return false;
		$this->query = "SELECT * FROM invtrxs WHERE invtrx_id = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function getItemTrxsFields() {
		$this->query = "SELECT * FROM invtrxs LIMIT 0 ";
		if ($arr = $this->getICFields($this->query)) return $arr;
		return false;
	}

	function getLastItemTrxs($filter="") {
		$this->query = "SELECT * FROM invtrxs ORDER BY invtrx_id DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstItemTrxs($filter="") {
		$this->query = "SELECT * FROM invtrxs ORDER BY invtrx_id LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextItemTrxs($code, $filter="") {
		$this->query = "SELECT * FROM invtrxs WHERE invtrx_id > '$code' ORDER BY invtrx_id LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevItemTrxs($code, $filter="") {
		$this->query = "SELECT * FROM invtrxs WHERE invtrx_id  < '$code' ORDER BY invtrx_id DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getItemTrxsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getItemTrxs($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevItemTrxs($code, $filter);
					if (!$rec) $rec = $this->getFirstItemTrxs($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextItemTrxs($code, $filter);
					if (!$rec) $rec = $this->getLastItemTrxs($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstItemTrxs($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastItems($filter);
				} else {
					$rec = $this->getItemTrxs($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastItemTrxs($filter);
				} else {
					$rec = $this->getFirstItemTrxs($filter);
				}
			}
		}
		return $rec;
	}

	function getItemTrxsList($condition="", $condition1="", $filtertext="", $reverse="f", $page=1, $limit=20) {

		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "dat") {
				if ($condition1 == "rec") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='r' ORDER BY invtrx_date ";
				else if ($condition1 == "sal") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='s' ORDER BY invtrx_date ";
				else if ($condition1 == "adj") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='a' ORDER BY invtrx_date ";

				else $this->query = "SELECT * FROM invtrxs ORDER BY invtrx_date ";
			} else if ($condition == "itm") {
				if ($condition1 == "rec") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='r' ORDER BY invtrx_item_code ";
				else if ($condition1 == "sal") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='s' ORDER BY invtrx_item_code ";
				else if ($condition1 == "adj") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='a' ORDER BY invtrx_item_code ";
				else $this->query = "SELECT * FROM invtrxs ORDER BY invtrx_item_code  ";
			} else if ($condition == "doc") {
				if ($condition1 == "rec") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='r' ORDER BY invtrx_item_code ";
				else if ($condition1 == "sal") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='s' ORDER BY invtrx_item_code ";
				else if ($condition1 == "adj") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='a' ORDER BY invtrx_ref_code ";
				else $this->query = "SELECT * FROM invtrxs ORDER BY invtrx_ref_code  ";
			} else {
				if ($condition1 == "rec") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='r' ORDER BY invtrx_date ";
				else if ($condition1 == "sal") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='s' ORDER BY invtrx_date ";
				else if ($condition1 == "adj") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='a' ORDER BY invtrx_date ";
				else $this->query = "SELECT * FROM invtrxs ORDER BY invtrx_date  ";
			}
		} else {
			if ($condition == "dat") {
				$num = substr_count("-", $filtertext);
				if (!empty($filtertext)) $num = 1;
				if ($num == 1) {
					list($from,$to) = explode("-",$filtertext);
					$from = rtrim(ltrim($from));
					$to = rtrim(ltrim($to));
					$df = explode("/", $from);
					$dt = explode("/", $to);
					if (empty($df[2])) {
						if (empty($df[1])) {
							$df[2] = $df[0];
							$df[1] = 1;
							$df[0] = 1;
						} else {
							$df[2] = $df[1];
							$df[1] = 1;
						}
					}
					if (empty($dt[2])) {
						if (empty($dt[1])) {
							$dt[2] = $dt[0];
							$dt[0] = 12;
							$dt[1] = 31;
						} else {
							$dt[2] = $dt[1];
							$tmpdate=getdate(mktime(0,0,0,$dt[0]+1,0,$dt[2]));
							$dt[1] = $tmpdate["mday"];
						}
					}
					$sf = $df[2]."-".$df[0]."-".$df[1];
					$st = $dt[2]."-".$dt[0]."-".$dt[1];
					if (!empty($from) && !empty($to)) $wstr = " WHERE invtrx_date >= '$sf' AND invtrx_date <= '$st' ";
					else if (!empty($from) && empty($to)) $wstr = " WHERE invtrx_date >= '$sf' ";
					else if (empty($from) && !empty($to)) $wstr = " WHERE invtrx_date <= '$st' ";
					else $wstr = " WHERE 1 ";
				} else {
					$wstr = " WHERE 1 ";
				}
				if ($condition1 == "rec") $this->query = "SELECT * FROM invtrxs $wstr AND invtrx_type='r' AND ORDER BY invtrx_date ";
				else if ($condition1 == "sal") $this->query = "SELECT * FROM invtrxs $wstr AND invtrx_type='s' ORDER BY invtrx_date ";
				else if ($condition1 == "adj") $this->query = "SELECT * FROM invtrxs $wstr AND invtrx_type='a' ORDER BY invtrx_date ";
				else $this->query = "SELECT * FROM invtrxs $wstr ORDER BY invtrx_date ";
			} else if ($condition == "itm") {
				if ($condition1 == "rec") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='r' AND invtrx_item_code LIKE '%$filtertext' ORDER BY invtrx_item_code ";
				else if ($condition1 == "sal") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='s' AND invtrx_item_code LIKE '%$filtertext' ORDER BY invtrx_item_code ";
				else if ($condition1 == "adj") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='a' AND invtrx_item_code LIKE '%$filtertext' ORDER BY invtrx_item_code ";
				else $this->query = "SELECT * FROM invtrxs WHERE invtrx_item_code LIKE '%$filtertext' ORDER BY invtrx_item_code  ";
			} else if ($condition == "doc") {
				if ($condition1 == "rec") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='r' AND invtrx_ref_code LIKE '%$filtertext' ORDER BY invtrx_item_code ";
				else if ($condition1 == "sal") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='s' AND invtrx_ref_code LIKE '%$filtertext' ORDER BY invtrx_item_code ";
				else if ($condition1 == "adj") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='a' AND invtrx_ref_code LIKE '%$filtertext' ORDER BY invtrx_ref_code ";
				else $this->query = "SELECT * FROM invtrxs WHERE invtrx_ref_code LIKE '%$filtertext' ORDER BY invtrx_ref_code  ";
			} else {
				if ($condition1 == "rec") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='r' AND invtrx_date LIKE '%$filtertext' ORDER BY invtrx_date ";
				else if ($condition1 == "sal") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='s' AND invtrx_date LIKE '%$filtertext' ORDER BY invtrx_date ";
				else if ($condition1 == "adj") $this->query = "SELECT * FROM invtrxs WHERE invtrx_type='a' AND invtrx_date LIKE '%$filtertext' ORDER BY invtrx_date ";
				else $this->query = "SELECT * FROM invtrxs ORDER BY invtrx_date  ";
			}
		}
		if ($reverse != "t") {
			if ($condition=="dat") $this->query .= " DESC, invtrx_id DESC ";
			else if ($condition=="itm") $this->query .= " ";
			else if ($condition=="doc") $this->query .= " DESC ";
			else $this->query .= "DESC, invtrx_id DESC ";
		} else {
			if ($condition=="dat") $this->query .= ", invtrx_id DESC ";
			else if ($condition=="itm") $this->query .= " DESC ";
			else if ($condition=="doc") $this->query .= " ";
			else $this->query .= ", invtrx_id ";
		}
	
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getIC();
	}

	function getItemTrxsRows() {
		$this->query = "SELECT count(invtrx_id) AS numrows FROM invtrxs ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>