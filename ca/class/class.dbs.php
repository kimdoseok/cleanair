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
	var $result;
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
			$this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
			//$this->conn = mysqli_pconnect($this->host, $this->username, $this->password);
			if (!$this->conn) return false;
			//if (!mysqli_select_db($this->dbname, $this->conn)) return false;
			//mysqli_select_db($this->conn, $this->dbname)
		} else {
			return false;
		}
		return true;
	}

	function dbSelect($query="") {
		$this->fields = array();
		if (!empty($query)) $this->query = $query;
		if ($this->dbtype == "odbc") {
			$this->result = odbc_exec($this->conn, $this->query);
			$this->numrows = odbc_num_rows($this->result);
		} else if ($this->dbtype == "mysql") {
			$this->result = mysqli_query($this->conn, $this->query);
			$this->numrows = mysqli_num_rows($this->result);
		/*
			if (mysqli_select_db($this->dbname, $this->conn)) {
				$this->result = mysqli_query($this->conn, $this->query);
				$this->numrows = mysqli_num_rows($this->result);
			} else {
				$this->result = false;
			}
			*/
			$this->fields = mysqli_fetch_fields($this->result);

		} else {
			 $this->result = false;
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
			mysqli_select_db($this->conn, $this->dbname) ;
			//echo $this->query;
			$this->result = mysqli_query($this->conn, $this->query);
		}
		return $this->result;
	}

	function getLastId() {
		if ($this->dbtype == "odbc") {
			$query = "SELECT LAST_INSERT_ID() AS LAST_ID";
			$rs1 = odbc_exec($this->conn, $query);
			if (odbc_fetch_row($rs1)) return odbc_result($rs1, "LAST_ID");
		} else if ($this->dbtype == "mysql") {
			return mysqli_insert_id($this->conn);
		}
	}

	function num_fields() {
		if (!$this->result) return false;
		if ($this->dbtype == "odbc") {
			$this->numfields = odbc_num_fields($this->result);
		} else if ($this->dbtype == "mysql") {
			//$this->numfields = count($this->fields);
			$this->numfields = mysqli_field_count($this->conn);
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
			$this->numrows = mysqli_num_rows($this->result);
			//$this->numrows = mysqli_num_rows($this->result);
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
			$fieldname = mysqli_field_name($this->result, $fieldidx);
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
			$this->record = mysqli_fetch_assoc($this->result);
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
