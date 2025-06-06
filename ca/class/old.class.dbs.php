<?php

include_once("class/class.datex.php");

class Dbs {
	var $conn;
	var $dsn;
	var $host;
	var $dbname;
	var $username;
	var $password;
	var $dbtype;
	var $rset;
	var $query;
	var $record;
	var $fields;
	var $numfields;
	var $numrows;
	var $errno;
	var $errmsg;

	function Dbs($dbtype = "mysql") {
		$this->dbtype = $dbtype;
		$this->numrows = 0;
	}

	function setDbType($dbtype) {
		$this->dbtype = $dbtype;
	}

	function getDbType() {
		return $this->dbtype;
	}

	function setHost($host="localhost") {
		$this->host = $host;
	}

	function setDbname($dbname) {
		$this->dsn = $dbname;
	}

	function setDsn($dsn) {
		$this->dsn = $dsn;
	}

	function setUsername($username) {
		$this->username = $username;
	}

	function setPassword($password) {
		$this->password = $password;
	}

	function setQuery($query) {
		$this->query = $query;
	}

	function pconnect($host, $dbname, $username="", $password="") {
		if ($this->dbtype == "odbc") {
			$this->dsn = $dbname;
			$this->username = $username;
			$this->password = $password;
			$this->conn = odbc_pconnect($this->dsn, $this->username, $this->password);
			if (!$this->conn) return false;
		} else if ($this->dbtype == "mysql") {
			$this->dbname = $dbname;
			$this->host = $host;
			$this->username = $username;
			$this->password = $password;
			$this->conn = mysql_pconnect($this->host, $this->username, $this->password);
			if (!$this->conn) return false;
			if (!mysql_select_db($this->dbname, $this->conn)) return false;
		} else {
			return false;
		}
		return true;
	}

	function dbSelect($query="") {
		$afield = array();
		if (!empty($query)) $this->query = $query;
		if ($this->dbtype == "odbc") {
			$this->result = odbc_exec($this->conn, $this->query);
			$this->numrows = odbc_num_rows($this->result);
		} else if ($this->dbtype == "mysql") {
			if (mysql_select_db($this->dbname, $this->conn)) {
				$this->result = mysql_query($this->query, $this->conn);
				$this->numrows = mysql_num_rows($this->result);
			} else {
				$this->result = false;
			}
		} else {
			 $this->result = false;
		}

		if ($this->result) {
			$f = $this->num_fields();
			for ($i=0;$i<$f;$i++) {
				$fname = $this->field_name($i);
				$afield[$i] = $fname;
			}
			$this->fields = $afield;
		}
		return $this->result;
	}

	function dbexec($query="") {
		$ok = $this->dbSelect($query);
		return $ok;
	}

	function dbUpdate($query="") {
		$afield = array();
		$this->result = false;
		if ($query != "") $this->query = $query;
		if ($this->dbtype == "odbc") {
			$this->result = @odbc_exec($this->conn, $this->query);
		} else if ($this->dbtype == "mysql") {
			mysql_select_db($this->dbname, $this->conn) ;
			$this->result = @mysql_query($this->query, $this->conn);
		}
		return $this->result;
	}

	function getLastId() {
		if ($this->dbtype == "odbc") {
			$query = "SELECT LAST_INSERT_ID() AS LAST_ID";
			$rs1 = odbc_exec($this->conn, $query);
			if (odbc_fetch_row($rs1)) return odbc_result($rs1, "LAST_ID");
		} else if ($this->dbtype == "mysql") {
			return mysql_insert_id($this->conn);
		}
	}

	function num_fields() {
		if (!$this->result) return false;
		if ($this->dbtype == "odbc") {
			$this->numfields = odbc_num_fields($this->result);
		} else if ($this->dbtype == "mysql") {
			$this->numfields = mysql_num_fields($this->result);
		} else {
			$this->numfields = 0;
		}
		return $this->numfields;
	}

	function num_rows() {
		if (!$this->result) return false;
		if ($this->dbtype == "odbc") {
			$this->numrows = odbc_num_rows($this->result);
		} else if ($this->dbtype == "mysql") {
			$this->numrows = mysql_num_rows($this->result);
		} else {
			$this->numrows = 0;
		}
		return $this->numrows;
	}

	function field_name($fieldidx) {
		if (!$this->result) return false;
		if ($this->dbtype == "odbc") {
			$fieldidx++;
			$fieldname = odbc_field_name($this->result, $fieldidx);
		} else if ($this->dbtype == "mysql") {
			$fieldname = mysql_field_name($this->result, $fieldidx);
		} else {
			$fieldnames = "";
		}
		return $fieldname;
	}

	function fetch_row() {
		if (!$this->result) return false;
		$d = new Datex();
		$this->record = false;
		if ($this->dbtype == "odbc") {
			$out = odbc_fetch_row($this->result);
			$rec = array();
			if ($out>0) {
				$f = $this->num_fields();
				for($i=1;$i<=$f;$i++) {
					$fieldname = odbc_field_name($this->result, $i);
					$rec[$fieldname] = stripslashes(odbc_result($this->result, $i));
					if (odbc_field_type($this->result, $i)=="date") $rec[$fieldname] = $d->usaDate($rec[$fieldname]);
				}
			}
			$this->record = $rec;
		} else if ($this->dbtype == "mysql") {
			$this->record = mysql_fetch_array($this->result);
		}
		return $this->record;
	}

	function dbresult($fieldname) {
		if (!$this->result) return false;
		if ($this->dbtype == "odbc") {
			$value = odbc_result($this->rset, $fieldname);
		} else if ($this->dbtype == "mysql") {
			$value = $this->record[$fieldname];
		} else {
			$value = "";
		}
		return $value;
	}
}
?>
