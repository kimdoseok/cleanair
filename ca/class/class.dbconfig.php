<?php

class DbConfig {

	public $hostname;
	public $dbname;
	public $username;
	public $password;
	public $cpool = array("default"=>array());

	function __construct() {
		//$this->hostname = "192.168.1.7"; 
		$this->hostname = "mysql"; 
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
		$this->cpool[$ctype] = array();
		$this->cpool[$ctype]["hostname"] = $host;
		$this->cpool[$ctype]["dbname"] = $db;
		$this->cpool[$ctype]["username"] = $user;
		$this->cpool[$ctype]["password"] = $pwd;
	}

	function getConn($ctype="default") {
		/*
		$this->hostname = $this->cpool[$ctype]["hostname"];
		$this->dbname = $this->cpool[$ctype]["dbname"];
		$this->username = $this->cpool[$ctype]["username"];
		$this->password = $this->cpool[$ctype]["password"];
		*/
		$conntype = "default";
		if (!is_null($ctype)) {
			$conntype = $ctype;
		}
		if (isset($this->cpool[$ctype])) {
			return $this->cpool[$conntype];
		} else {
			return $this->cpool["default"];
		}
	}

}





?>
