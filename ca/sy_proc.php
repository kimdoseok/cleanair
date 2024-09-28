<?php
include_once("class/map.default.php");
include_once("class/class.datex.php");
include_once("class/class.users.php");
include_once("class/class.auths.php");
include_once("class/class.userauths.php");
include_once("class/class.requests.php");
include_once("class/class.tickets.php");

include_once("class/register_globals.php");

$errno = 0;
$comp = $default["comp_code"];

include_once("common_proc.php");

if ($cmd =="userauth_save") {
	$ua = new UserAuths();
	$d = new Datex();
	$a = new Auths();
	$u = new Users();
	$rq = new Requests();

	$au_arr = $rq->getTagedArray($_POST, "au_");
	if ($au_arr) {
		$ua->deleteUserAuthsAll();
		foreach ($au_arr as $k=>$v) {
			if (preg_match("/(\d+)_(\d+)/", $k, $match)) {
				$updarr = array();
				$updarr["userauth_auth_id"] = $match[1];
				$updarr["userauth_user_id"] = $match[2];
				$updarr["userauth_allow"] = "t";
				$ua->insertUserAuths($updarr);
			}
		}
	}

	$loc = "Location: userauths.php";
	header($loc);

} else if ($cmd =="userauth_allc") {
	if (!empty($id)) {
		$ua = new UserAuths();
		$a = new Auths();
		$arows = $a->getAuthsRows();
		$arecs = $a->getAuthsList("","","",$page,$arows);
		$unums = count($arecs);
		for ($i=0;$i<$unums;$i++) {
			if (!$ua_arr = $ua->getUserAuthsTwoID($id, $arecs[$i]["auth_id"])) {
				$updarr = array();
				$updarr["userauth_auth_id"] = $arecs[$i]["auth_id"];
				$updarr["userauth_user_id"] = $id;
				$updarr["userauth_allow"] = "t";
				$ua->insertUserAuths($updarr);
			}
		}
	}

	$loc = "Location: userauths.php";
	header($loc);

} else if ($cmd =="userauth_allr") {
	if (!empty($id)) {
		$ua = new UserAuths();
		$u = new Users();
		$urows = $u->getUsersRows($cn, $ft);
		$urecs = $u->getUsersList($cn, $ft, $rv, $page, $urows);
		$unums = count($urecs);
		for ($i=0;$i<$unums;$i++) {
			if (!$ua_arr = $ua->getUserAuthsTwoID($urecs[$i]["user_id"], $id)) {
				$updarr = array();
				$updarr["userauth_auth_id"] = $id;
				$updarr["userauth_user_id"] = $urecs[$i]["user_id"];
				$updarr["userauth_allow"] = "t";
				$ua->insertUserAuths($updarr);
			}
		}
	}

	$loc = "Location: userauths.php";
	header($loc);


} else if ($cmd =="auth_edit") {
	$c = new Auths();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getAuths($auth_code)) {
		$arr = $r->getAlteredArray($check, $_POST); 
		$c->updateAuths($auth_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find auth code entered.";
		include("error.php");
	}
	$loc = "Location: auths.php?ty=e&auth_code=$auth_code";
	if ($errno == 0) header($loc);

} else if ($cmd =="auth_del") {
	$c = new Auths();
	$ua = new UserAuths();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getAuths($auth_code)) {
		$arr = $r->getAlteredArray($check, $_POST); 
		$c->deleteAuths($auth_code, $arr); 
		$ua->deleteUserAuthsByAuth($check["auth_id"]);

	} else {
		$errno = 2;
		$errmsg = "Couldn't find auth code entered.";
		include("error.php");
	}
	$loc = "Location: auths.php?ty=l";
	if ($errno == 0) header($loc);

} else if ($cmd =="auth_add") {
	$c = new Auths();
	$r = new Requests();
	$arr = array();

	if ($check = $c->getAuths($auth_code)) {
		$errno = 1;
		$errmsg = "Auth code should be unique";
		include("error.php");
		exit;
	} else {
		$f_arr = $c->getAuthsFields();
		$asso = $r->getConvertArray($f_arr);
		$arr = $r->getAlteredArray($asso, $_POST); 
		$c->insertAuths($arr); 
	}
	$loc = "Location: auths.php?ty=e&auth_code=$auth_code";
	if ($errno == 0) header($loc);

} else if ($cmd =="user_edit") {
	$c = new Users();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getUsers($user_code)) {
		$arr = $r->getAlteredArray($check, $_POST); 
		$c->updateUsers($user_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find user code entered.";
		include("error.php");
	}
	$loc = "Location: users.php?ty=e&user_code=$user_code";
	if ($errno == 0) header($loc);

} else if ($cmd =="user_add") {
	$c = new Users();
	$r = new Requests();
	$arr = array();

	if ($check = $c->getUsers($user_code)) {
		$errno = 1;
		$errmsg = "User code should be unique";
		include("error.php");
		exit;
	} else {
		$f_arr = $c->getUsersFields();
		$asso = $r->getConvertArray($f_arr);
		$arr = $r->getAlteredArray($asso, $_POST); 
		$c->insertUsers($arr); 
	}
	$loc = "Location: users.php?ty=e&user_code=$user_code";
	if ($errno == 0) header($loc);

} else if ($cmd =="user_del") {
	$c = new Users();
	$r = new Requests();
	$ua = new UserAuths();
	$arr = array();
	$check = $c->getUsers($_GET["user_code"]);
	if ($check) {
		$c->deleteUsers($_GET["user_code"]); 
		$ua->deleteUserAuthsByUser($check["user_code"]);

	} else {
		$errno = 2;
		$errmsg = "Couldn't find user code entered.";
		include("error.php");
	}
	$loc = "Location: users.php?ty=l";
	if ($errno == 0) header($loc);

} else if ($cmd =="ticket_add") {
	$t = new Tickets();
	$r = new Requests();
	$arr = array();
	$tkt_id = 0;
	if ($check = $t->getTickets($tkt_id)) {
		$errno = 1;
		$errmsg = "Ticket ID should be unique";
		include("error.php");
		exit;
	} else {
		$f_arr = $t->getTicketsFields();
		$asso = $r->getConvertArray($f_arr);
		$arr = $r->getAlteredArray($asso, $_POST);
		$arr["tkt_date"]=date("Y-m-d");
		$arr["tkt_time"]=date("H:i:s");
		$tkt_id = $t->insertTickets($arr); 
	}
	$loc = "Location: cust_tickets.php?ty=e&tkt_id=$tkt_id";
	if ($errno == 0) header($loc);

} else if ($cmd =="ticket_edit") {
	$t = new Tickets();
	$r = new Requests();
	$arr = array();
	if ($check = $t->getTickets($tkt_id)) {
		$arr = $r->getAlteredArray($check, $_POST); 
		$t->updateTickets($tkt_id, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find ticket id entered.";
		include("error.php");
	}
	$loc = "Location: cust_tickets.php?ty=e&tkt_id=$tkt_id";
	if ($errno == 0) header($loc);

} else if ($cmd =="ticket_del") {
	$t = new Tickets();
	$arr = array();
	if ($check = $t->getTickets($tkt_id)) {
		$t->deleteTickets($tkt_id); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find ticket id entered.";
		include("error.php");
	}
	if ($_GET["parent_id"]>0) $loc = "Location: cust_tickets.php?ty=e&tkt_id=".$_GET["parent_id"];
	else $loc = "Location: cust_tickets.php";
	if ($errno == 0) header($loc);

} else if ($cmd =="ticket_response_add") {
	$t = new Tickets();
	$r = new Requests();
	$arr = array();
	if ($check = $t->getTickets($tkt_parent)) {
		$f_arr = $t->getTicketsFields();		
		$asso = $r->getConvertArray($f_arr);
		$arr = $r->getAlteredArray($asso, $_POST); 
		$arr["tkt_status"]=5;
		$arr["tkt_date"]=date("Y-m-d");
		$arr["tkt_time"]=date("H:i:s");
		if ($t->insertTickets($arr)) {
			$close_ticket = 0;
			if (array_key_exists("close_ticket", $_POST)) {
				$close_ticket = $_POST["close_ticket"];
			}
			if ($close_ticket>0) $updarr = array("tkt_status"=>10);
			else $updarr = array("tkt_status"=>5);
			$t->updateTickets($tkt_parent, $updarr);
		}
		$tkt_id=$arr["tkt_parent"];
	} else {
		$errno = 2;
		$errmsg = "Couldn't find ticket id entered.";
		include("error.php");
	}
	$loc = "Location: cust_tickets.php?ty=v&tkt_id=$tkt_parent";
	if ($errno == 0) header($loc);


} else {
	header("Location: $HTTP_REFERER");
}
?>