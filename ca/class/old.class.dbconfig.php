<?php

class DbConfig {

	var $hostname;
	var $dbname;
	var $username;
	var $password;
	var $cpool;

	function DbConfig() {
		$this->hostname = "192.168.1.7"; 
		$this->dbname = "ca" ; 
		$this->username = "doseok" ; 
		$this->password = "kim7795004";  
		$this->setConn("default", $this->hostname, $this->dbname, $this->username, $this->password);
		$this->setConn("select", $this->hostname, $this->dbname, $this->username, $this->password);
		$this->setConn("update", $this->hostname, $this->dbname, $this->username, $this->password);
		$this->setConn("delete", $this->hostname, $this->dbname, $this->username, $this->password);
		$this->setConn("insert", $this->hostname, $this->dbname, $this->username, $this->password);
	}

	function setConn($ctype, $host, $db, $user, $pwd) {
		$this->cpool[$ctype]["hostname"] = $host;
		$this->cpool[$ctype]["dbname"] = $db;
		$this->cpool[$ctype]["username"] = $user;
		$this->cpool[$ctype]["password"] = $pwd;
	}

	function getConn($ctype) {
		$this->hostname = $this->cpool[$ctype]["hostname"];
		$this->dbname = $this->cpool[$ctype]["dbname"];
		$this->username = $this->cpool[$ctype]["username"];
		$this->password = $this->cpool[$ctype]["password"];
		return $this->cpool[$ctype];
	}

}





?>
