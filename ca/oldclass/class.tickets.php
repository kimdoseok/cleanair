<?php
include_once("class.sy.php");

class Tickets extends SY {

	function insertTickets($arr) {
		if ($lastid = $this->insertSY("tickets", $arr)) return $lastid;
		return false;
	}

	function updateTickets($code, $arr) {
		if ($this->updateSY("tickets", "tkt_id", $code, $arr)) return true;
		return false;
	}

	function deleteTickets($code) {
		$query = "delete from tickets where tkt_id='$code'";
		if ($this->updateSYRaw($query)) return true;
		return false;
	}

	function getTickets($code="") {
		$code = trim($code);
		if (empty($code)) return false;
		$this->query = "SELECT * FROM tickets WHERE tkt_id = '$code' LIMIT 1 ";
		if ($arr = $this->getSY($code)) return $arr[0];
		return false;
	}

	function getTicketsCust($cust="") {
		$this->query = "SELECT tkt_id FROM tickets WHERE tkt_status<10 AND tkt_parent=0 AND tkt_cust_code LIKE '$cust' ORDER BY tkt_id DESC LIMIT 1 ";
		if ($arr = $this->getSY($code)) return $arr;
		return false;
	}

	function getTicketsTwo($cust="",$ref="") {
		$this->query = "SELECT tkt_id FROM tickets WHERE tkt_status<10 AND tkt_parent=0 AND (tkt_cust_code LIKE '$cust' OR tkt_refnum LIKE '$ref') LIMIT 1 ";
		if ($arr = $this->getSY($code)) return $arr;
		return false;
	}

	function getTicketsFields() {
		$this->query = "SELECT * FROM tickets LIMIT 0 ";
		if ($arr = $this->getSYFields($this->query)) return $arr;
		return false;
	}

	function getLastTickets($filter="") {
		$this->query = "SELECT * FROM tickets WHERE tkt_parent=0 ORDER BY tkt_id DESC LIMIT 1 ";
		$arr = $this->getSY();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstTickets($filter="") {
		$this->query = "SELECT * FROM tickets WHERE tkt_parent=0 ORDER BY tkt_id LIMIT 1 ";
		$arr = $this->getSY();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextTickets($code, $filter="") {
		$this->query = "SELECT * FROM tickets WHERE tkt_id > '$code' AND tkt_parent=0 ORDER BY tkt_id LIMIT 1 ";
		$arr = $this->getSY($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevTickets($code, $filter="") {
		$this->query = "SELECT * FROM tickets WHERE tkt_id  < '$code' AND tkt_parent=0 ORDER BY tkt_id DESC LIMIT 1 ";
		$arr = $this->getSY($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getTicketsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getTickets($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevTickets($code, $filter);
					if (!$rec) $rec = $this->getFirstTickets($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextTickets($code, $filter);
					if (!$rec) $rec = $this->getLastTickets($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstTickets($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastTickets($filter);
				} else {
					$rec = $this->getTickets($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastTickets($filter);
				} else {
					$rec = $this->getFirstTickets($filter);
				}
			}
		}
		return $rec;
	}

	function getTicketResponses($tkt_id) {
		$this->query = "SELECT * FROM tickets WHERE tkt_parent=$tkt_id ORDER BY tkt_date, tkt_time ";
		return $this->getSY();
	}

	function getTicketRspRows($tkt_id) {
		$this->query = "SELECT count(tkt_id) as numrows FROM tickets WHERE tkt_parent=$tkt_id ORDER BY tkt_date, tkt_time  ";
		$arr = $this->getSY();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getTicketsList($fo="", $ft="", $oo=0, $page=1, $limit=200) {
		if ($page < 1) $page = 0;
		else $page--;
		$wh = "";
	    if ($oo>0) $wh = "AND tkt_status<10 ";
		if (empty($ft) || !isset($ft)) {
			if ($fo == "id") $this->query = "SELECT * FROM tickets WHERE tkt_parent=0 $wh ORDER BY tkt_id DESC ";
			else if ($fo == "ti") $this->query = "SELECT * FROM tickets WHERE tkt_parent=0 $wh ORDER BY tkt_title ";
			else if ($fo == "cu") $this->query = "SELECT * FROM tickets WHERE tkt_parent=0 $wh ORDER BY tkt_cust_code ";
			else if ($fo == "rf") $this->query = "SELECT * FROM tickets WHERE tkt_parent=0 $wh ORDER BY tkt_refnum DESC ";
			else if ($fo == "us") $this->query = "SELECT * FROM tickets WHERE tkt_parent=0 $wh ORDER BY tkt_user_code ";
			else $this->query = "SELECT * FROM tickets WHERE tkt_parent=0 $wh ORDER BY tkt_id DESC ";
		} else {
			if ($fo == "id") $this->query = "SELECT * FROM tickets WHERE tkt_id LIKE '$ft%' AND tkt_parent=0 $wh ORDER BY tkt_id DESC ";
			else if ($fo == "ti") $this->query = "SELECT * FROM tickets WHERE tkt_title LIKE '$ft%' AND tkt_parent=0 $wh ORDER BY tkt_title ";
			else if ($fo == "cu") $this->query = "SELECT * FROM tickets WHERE tkt_cust_code LIKE '$ft%' AND tkt_parent=0 $wh ORDER BY tkt_cust_code ";
			else if ($fo == "rf") $this->query = "SELECT * FROM tickets WHERE tkt_refnum LIKE '$ft%' AND tkt_parent=0 $wh ORDER BY tkt_refnum DESC ";
			else if ($fo == "us") $this->query = "SELECT * FROM tickets WHERE tkt_user_code LIKE '$ft%' AND tkt_parent=0 $wh ORDER BY tkt_user_code ";
			else $this->query = "SELECT * FROM tickets WHERE tkt_parent=0 $wh ORDER BY tkt_id DESC ";
		}

		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		//print $this->query;
		return $this->getSY();
	}

	function getTicketsRows($fo="", $ft="", $oo=0) {
		$wh = "";
	    if ($oo>0) {
			$wh = "AND tkt_status<10 ";
		}

		if (empty($ft) || !isset($ft)) {
			$this->query = "SELECT count(tkt_id) AS numrows FROM tickets WHERE tkt_parent=0 $wh ";
		} else {
			if ($fo == "id") $this->query = "SELECT count(tkt_id) AS numrows FROM tickets WHERE tkt_id LIKE '$ft%' AND tkt_parent=0 $wh ";
			else if ($fo == "ti") $this->query = "SELECT count(tkt_id) AS numrows FROM tickets WHERE tkt_title LIKE '$ft%' AND tkt_parent=0 $wh ";
			else if ($fo == "cu") $this->query = "SELECT count(tkt_id) AS numrows FROM tickets WHERE tkt_title LIKE '$ft%' AND tkt_parent=0 $wh ";
			else if ($fo == "rf") $this->query = "SELECT count(tkt_id) AS numrows FROM tickets WHERE tkt_refnum LIKE '$ft%' AND tkt_parent=0 $wh ";
			else if ($fo == "us") $this->query = "SELECT count(tkt_id) AS numrows FROM tickets WHERE tkt_user_code LIKE '$ft%' AND tkt_parent=0 $wh ";
			else $this->query = "SELECT count(tkt_id) AS numrows FROM tickets WHERE tkt_parent=0 $wh ";
		}
		$arr = $this->getSY();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

}
?>
