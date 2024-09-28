<?php
include_once("class.ar.php");

class Custs extends AR {

	var $active="";

	function insertCusts($arr) {
		if ($lastid = $this->insertAR("custs", $arr)) return $lastid;
		return false;
	}

	function updateCusts($code, $arr) {
		if ($this->updateAR("custs", "cust_code", $code, $arr)) return true;
		return false;
	}

	function updateCustsAmt($code, $field, $amt) {
		$this->query = "UPDATE custs SET $field = $field + $amt WHERE cust_code='$code' ";
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function deleteCusts($code) {
		$this->query = "DELETE FROM custs WHERE cust_code='$code' ";
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function getCustsBalance($code) {
		$balance = 0;
		// got picks total
		$arr = $this->getCusts($code);
		if (!$arr) return 0;
		$this->query = "SELECT sum(pick_amt+pick_tax_amt+pick_freight_amt) as p_amt FROM picks WHERE pick_cust_code = '$code'";

		if ($parr = $this->getARRaw()) $balance += $parr[0]["p_amt"];
		// get cash receipt total
		$this->query = "SELECT sum(rcpt_amt+rcpt_disc_amt) as r_amt FROM rcpts WHERE rcpt_cust_code = '$code'";
		if ($rarr = $this->getARRaw()) $balance -= $rarr[0]["r_amt"];
		// get credit memo total
		$this->query = "SELECT sum(cmemo_amt+cmemo_tax_amt+cmemo_freight_amt) as c_amt FROM cmemos WHERE cmemo_cust_code = '$code'";
		if ($carr = $this->getARRaw()) $balance -= $carr[0]["c_amt"];
		return $balance;
	}

	function updateCustsBalance($code) {
		$balance = 0;
		// got picks total
		$arr = $this->getCusts($code);
		if (!$arr) return false;
//print_r($arr);
//echo "<br>";
		$this->query = "SELECT sum(pick_amt+pick_tax_amt+pick_freight_amt) as p_amt FROM picks WHERE pick_cust_code = '$code'";
//echo $this->query;
//echo "<br>";
		if ($arr = $this->getAR()) $balance += $arr[0]["p_amt"];
		// get cash receipt total
		$this->query = "SELECT sum(rcpt_amt+rcpt_disc_amt) as r_amt FROM rcpts WHERE rcpt_cust_code = '$code'";
//echo $this->query;
//echo "<br>";
		if ($arr = $this->getAR()) $balance -= $arr[0]["r_amt"];
		// get credit memo total
		$this->query = "SELECT sum(cmemo_amt+cmemo_tax_amt+cmemo_freight_amt) as c_amt FROM cmemos WHERE cmemo_cust_code = '$code'";
//echo $this->query;
//echo "<br>";
		if ($arr = $this->getAR()) $balance -= $arr[0]["c_amt"];
		$this->query = "UPDATE custs SET cust_balance = cust_init_bal + $balance WHERE cust_code='$code' ";
//echo $this->query;
//echo "<br>";
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function getCusts($code) {
		$aw = "";
		if ($this->active == "t") $aw = "AND cust_active = 't' ";
		$this->query = "SELECT * FROM custs WHERE cust_code = '$code' $aw LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getCustsEx($code) {
		if ($this->active == "t") $aw = "AND cust_active = 't' ";
		$this->query = "SELECT * FROM custs c, taxrates t WHERE c.cust_tax_code=t.taxrate_code AND c.cust_code = '$code' $aw LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getCustStatement($code, $sd="", $ed="", $zero="f") {
		if (!empty($sd) && $sd != "0000-00-00") {
			$where_pick .= " AND pick_date >= '$sd' ";
			$where_rcpt .= " AND rcpt_date >= '$sd' ";
			$where_cmemo .= " AND cmemo_date >= '$sd' ";
		}
		if (!empty($ed) && $ed != "0000-00-00") {
			$where_pick .= " AND pick_date <= '$sd' ";
			$where_rcpt .= " AND rcpt_date <= '$sd' ";
			$where_cmemo .= " AND cmemo_date <= '$sd' ";
		}
		if ($zero == "f") $where = " WHERE samt > 0 ";

		$temp_name = "stmt".substr(md5(uniqid(rand(),1)),0,16);

		$this->query = "CREATE TEMPORARY TABLE IF NOT EXISTS $temp_name (sid integer, sdate date, stype enum('p','r','c'), samt decimal(15,2), KEY id_$temp_name (sid), KEY da_$temp_name (sdate)); \n";
		$this->query .= "INSERT INTO $temp_name SELECT pick_id, pick_date, 'p', pick_amt+pick_tax_amt+pick_freight_amt FROM picks WHERE pick_cust_code = '$code' $where_pick; \n";
		$this->query .= "INSERT INTO $temp_name SELECT rcpt_id, rcpt_date, 'r', -1 * rcpt_amt FROM rcpts WHERE rcpt_cust_code = '$code' $where_rcpt; \n";
		$this->query .= "INSERT INTO $temp_name SELECT cmemo_id, cmemo_date, 'c', -1 * cmemo_amt FROM cmemos WHERE cmemo_cust_code = '$code' $where_cmemo; \n";
		$this->query .= " SELECT * FROM $temp_name $where ORDER BY sdate DESC ";
//echo $this->query;
//echo "<br>";
		return $this->getARRaw();
	}

	function getCustsFields() {
		$this->query = "SELECT * FROM custs LIMIT 0 ";
		if ($arr = $this->getARFields($this->query)) return $arr;
		return array();
	}

	function getLastCusts($filter="") {
		if ($this->active == "t") $aw = "WHERE cust_active = 't' ";
		$this->query = "SELECT * FROM custs $aw ORDER BY cust_code DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstCusts($filter="") {
		if ($this->active == "t") $aw = "WHERE cust_active = 't' ";
		$this->query = "SELECT * FROM custs $aw ORDER BY cust_code LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextCusts($code, $filter="") {
		if ($this->active == "t") $aw = "AND cust_active = 't' ";
		$this->query = "SELECT * FROM custs WHERE cust_code > '$code' $aw ORDER BY cust_code LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevCusts($code, $filter="") {
		if ($this->active == "t") $aw = "AND cust_active = 't' ";
		$this->query = "SELECT * FROM custs WHERE cust_code  < '$code' $aw ORDER BY cust_code DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getCustsFields();
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getCusts($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevCusts($code, $filter);
					if (!$rec) $rec = $this->getFirstCusts($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextCusts($code, $filter);
					if (!$rec) $rec = $this->getLastCusts($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstCusts($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastCusts($filter);
				} else {
					$rec = $this->getCusts($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastCusts($filter);
				} else {
					$rec = $this->getFirstCusts($filter);
				}
			}
		}
		return $rec;
	}

	function getCustsRange($fr="", $to="", $ord="", $rev="t") {
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND cust_code >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND cust_code <= '$to' ";
		}
		if ($this->active == "t") $where .= "AND cust_active = 't' ";

		if (!empty($ord)) $orderby = " ORDER BY $ord ";
		else $orderby = " ORDER BY cust_code ";
		if ($rev != "t") $orderby .= " DESC ";
		$this->query = "SELECT * FROM custs $where $orderby ";
		return $this->getARRaw();
	}

	function getNewCustsRange($fr="", $to="", $ord="",$begin="", $rev="t") {
		$where = "WHERE 1 ";
    $having = "";
    
		if (!empty($fr)) {
			$where .= " AND c.cust_code >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND c.cust_code <= '$to' ";
		}
		if (!empty($begin)) {
      $having = "HAVING min(s.sale_date)>= '$begin' ";
		}
		if ($this->active == "t") $where .= "AND cust_active = 't' ";

		if (!empty($ord)) $orderby = " ORDER BY $ord ";
		else $orderby = " ORDER BY cust_code ";
		if ($rev != "t") $orderby .= " DESC ";
		$this->query = "SELECT c.cust_code,c.cust_name,c.cust_addr1,c.cust_addr2,c.cust_addr3,c.cust_city,c.cust_state,c.cust_zip,min(s.sale_date) as begin_date FROM sales s JOIN custs c ON s.sale_cust_code=c.cust_code $where GROUP BY c.cust_code,c.cust_name,c.cust_addr1,c.cust_addr2,c.cust_addr3,c.cust_city,c.cust_state,c.cust_zip $having $orderby ";
    //echo $this->query;
		return $this->getARRaw();
	}
  
	function getCustsRangeLabel($fr="", $to="", $ord="") {
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND $ord >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND $ord <= '$to' ";
		}
		if ($this->active == "t") $where .= "AND cust_active = 't' ";

		if (!empty($ord)) $orderby = " ORDER BY $ord ";
		else $orderby = " ORDER BY $ord ";
   		$this->query = "SELECT * FROM custs $where $orderby ";
		return $this->getARRaw();
	}


	function getCustsRangeRep($fr="", $to="", $rev="f", $ord="rep") {
		// $ord IN ("rep", "cust") 
		$where = "WHERE 1 ";
		if (!empty($fr)) {
			$where .= " AND cust_slsrep >= '$fr' ";
		}
		if (!empty($to)) {
			$where .= " AND cust_slsrep <= '$to' ";
		}
		if ($this->active == "t") $where .= "AND cust_active = 't' ";
		if ($ord=="rep") {
			if ($rev == "t") $orderby = " ORDER BY cust_slsrep DESC, cust_code ";
			else $orderby = " ORDER BY cust_slsrep, cust_code ";
		} else {
			if ($rev == "t") $orderby = " ORDER BY cust_code DESC, cust_slsrep ";
			else $orderby = " ORDER BY cust_code, cust_slsrep ";
		}
		$this->query = "SELECT * FROM custs $where $orderby ";
		//print $this->query."<br>";
		return $this->getARRaw();
	}

	function getCustsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$wh = "";
		$aw = "";
		if ($this->active == "t") {
			$wh .= "WHERE cust_active = 't' ";
			$aw .= "AND cust_active = 't' ";
		}
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
        $this->query = "SELECT * FROM custs $wh ORDER BY cust_code  ";
			} else if ($condition == "name") {
        $this->query = "SELECT * FROM custs $wh ORDER BY cust_name  ";
			} else if ($condition == "addr") {
        $this->query = "SELECT * FROM custs $wh ORDER BY cust_addr1  ";
			} else if ($condition == "city") {
        $this->query = "SELECT * FROM custs $wh ORDER BY cust_city  ";
			} else if ($condition == "zip") {
        $this->query = "SELECT * FROM custs $wh ORDER BY cust_zip  ";
			} else if ($condition == "tel") {
        $this->query = "SELECT * FROM custs $wh ORDER BY cust_tel  ";
			} else if ($condition == "slsrep") {
        $this->query = "SELECT * FROM custs $wh ORDER BY cust_slsrep  ";
			} else if ($condition == "points") {
        $this->query = "SELECT * FROM custs $wh ORDER BY cust_points  ";
			} else {
        $this->query = "SELECT * FROM custs $wh ORDER BY cust_code ";
      }
		} else {
			if ($condition == "code") {
        $this->query = "SELECT * FROM custs WHERE cust_code LIKE '$filtertext%' $aw ORDER BY cust_code ";
			} else if ($condition == "name") {
        $this->query = "SELECT * FROM custs WHERE cust_name LIKE '$filtertext%' $aw ORDER BY cust_name  ";
			} else if ($condition == "addr") {
        $this->query = "SELECT * FROM custs WHERE cust_addr1 LIKE '$filtertext%' $aw ORDER BY cust_addr1 ";
			} else if ($condition == "city") {
        $this->query = "SELECT * FROM custs WHERE cust_city LIKE '$filtertext%' $aw ORDER BY cust_city ";
			} else if ($condition == "zip") {
        $this->query = "SELECT * FROM custs WHERE cust_zip LIKE '$filtertext%' $aw ORDER BY cust_zip ";
			} else if ($condition == "tel") {
        $this->query = "SELECT * FROM custs WHERE cust_tel LIKE '$filtertext%' $aw ORDER BY cust_tel ";
			} else if ($condition == "slsrep") {
        $this->query = "SELECT * FROM custs WHERE cust_slsrep LIKE '$filtertext%' $aw ORDER BY cust_slsrep ";
			} else if ($condition == "points") {
        $ptarr = explode('-',$filtertext,2);
        if (count($ptarr)==2) {
          $fr = intval($ptarr[0]);
          $to = intval($ptarr[1]);
          $this->query = "SELECT * FROM custs WHERE cust_points>= $fr and cust_points<= $to $aw ORDER BY cust_points ";
        } else if (count($ptarr)==1) {
          $fr = intval($ptarr[0]);
          $this->query = "SELECT * FROM custs WHERE cust_points= $fr $aw ORDER BY cust_points ";
        } else {
          $this->query = "SELECT * FROM custs $wh ORDER BY cust_points  ";
        }
			} else {
        $this->query = "SELECT * FROM custs WHERE cust_code  LIKE '$filtertext%' $aw ORDER BY cust_code ";
      }
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getCustsListEx($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if ($this->active == "t") {
			$wh .= "WHERE cust_active = 't' ";
			$aw .= "AND cust_active = 't' ";
		}

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM custs c, taxrates x WHERE c.cust_tax_code=x.taxrate_code $aw ORDER BY cust_code  ";
			else if ($condition == "name") $this->query = "SELECT * FROM custs c, taxrates x WHERE c.cust_tax_code=x.taxrate_code $aw ORDER BY cust_name  ";
			else $this->query = "SELECT * FROM custs c, taxrates x WHERE c.cust_tax_code=x.taxrate_code $aw ORDER BY cust_code ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM custs c, taxrates x WHERE c.cust_tax_code=x.taxrate_code AND cust_code  LIKE '$filtertext%' $aw ORDER BY cust_code ";
			else if ($condition == "name") $this->query = "SELECT * FROM custs c, taxrates x WHERE c.cust_tax_code=x.taxrate_code AND cust_name  LIKE '$filtertext%' $aw ORDER BY cust_name  ";
			else $this->query = "SELECT * FROM custs c, taxrates x WHERE c.cust_tax_code=x.taxrate_code AND cust_code  LIKE '$filtertext%' $aw ORDER BY cust_code ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getCustsRows($condition="", $filtertext="") {
		$wh = "";
		$aw = "";
		if ($this->active == "t") {
			$wh .= "WHERE cust_active = 't' ";
			$aw .= "AND cust_active = 't' ";
		}
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(cust_code) AS numrows FROM custs $wh ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(cust_code) AS numrows FROM custs WHERE cust_code LIKE '$filtertext%' $aw ";
			else if ($condition == "name") $this->query = "SELECT count(cust_code) AS numrows FROM custs WHERE cust_name  LIKE '$filtertext%' $aw ";
			else if ($condition == "addr") $this->query = "SELECT count(cust_code) AS numrows FROM custs WHERE cust_addr1 LIKE '$filtertext%' $aw ";
			else if ($condition == "city") $this->query = "SELECT count(cust_code) AS numrows FROM custs WHERE cust_city LIKE '$filtertext%' $aw ";
			else if ($condition == "tel") $this->query = "SELECT count(cust_code) AS numrows FROM custs WHERE cust_tel LIKE '$filtertext%' $aw ";
			else if ($condition == "slsrep") $this->query = "SELECT count(cust_code) AS numrows FROM custs WHERE cust_slsrep  LIKE '$filtertext%' $aw";
			else $this->query = "SELECT count(cust_code) AS numrows FROM custs WHERE cust_code LIKE '$filtertext%' $aw";
		}
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getCustsListCRM($deldate, $reverse="f", $page=1, $limit=100) {
		if ($page < 1) $page = 0;
		else $page--;

		$this->query = "SELECT * FROM custs WHERE cust_active='t' AND cust_marketing='t' AND cust_delv_week='$deldate' ORDER BY cust_code ";
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getCustsRowsCRM($deldate, $filtertext="") {
		$this->query = "SELECT count(cust_code) AS numrows FROM custs WHERE cust_active='t' AND cust_marketing='t' AND cust_delv_week='$deldate' ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getCustsReports($cuf="",$cut="",$czf="",$czt="",$ctf="",$ctt="",$cpf="",$cpt="",$ordby="cust_code" ) {

		$where = "WHERE 1=1 ";
		$groupby = "GROUP BY cust_code ";
		if (!empty($cuf)) $where .= "AND cust_code>= '$cuf' ";
		if (!empty($cut)) $where .= "AND cust_code<= '$cut' ";
		if (!empty($czf)) $where .= "AND cust_zip>= '$czf' ";
		if (!empty($czt)) $where .= "AND cust_zip<= '$czt' ";
		if (!empty($ctf)) $where .= "AND cust_tel>= '$ctf' ";
		if (!empty($ctt)) $where .= "AND cust_tel<= '$ctt' ";
		if (!empty($cpf)) $where .= "AND cust_points>= $cpf ";
		if (!empty($cpt)) $where .= "AND cust_points<= $cpt ";
		if ($ordby=='c') $orderby = "ORDER BY cust_code ";
		else if ($ordby=='n') $orderby = "ORDER BY cust_name ";
		else if ($ordby=='z') $orderby = "ORDER BY cust_zip ";
		else if ($ordby=='p') $orderby = "ORDER BY cust_tel ";
		else if ($ordby=='e') $orderby = "ORDER BY cust_email ";
		else if ($ordby=='b') $orderby = "ORDER BY cust_balance ";
		else if ($ordby=='a') $orderby = "ORDER BY datediff(current_date,pick_date) ";
    else $orderby = "ORDER BY cust_code ";		
		$this->query = "select c.*,datediff(current_date,pick_date) as aging,pick_amt,pick_date,max(pick_id) from custs c left outer join picks p on p.pick_cust_code=cust_code and pick_id in (select max(pick_id) from picks q where q.pick_cust_code=c.cust_code) $where $groupby $orderby ";
		#print $this->query;
		return $this->getARRaw();
	}

	function getActiveCustsYear() {
		if ($this->active == "t") $aw = "AND cust_active = 't' ";
		$this->query = "SELECT cust_code, cust_name,cust_addr1,cust_addr2,cust_addr3,cust_city,cust_state,cust_zip, cust_tel, cust_cell FROM custs WHERE cust_code IN (SELECT sale_cust_code FROM sales WHERE YEAR(sale_date)=YEAR(current_date)) ORDER BY cust_code ";
		if ($arr = $this->getARRaw()) return $arr;
		return false;
	}

}

?>
