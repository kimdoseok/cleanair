<?php
include_once("class/map.default.php");
include_once("class/class.accounts.php");
include_once("class/class.gledger.php");
include_once("class/class.requests.php");
include_once("class/class.jrnltrxs.php");
include_once("class/register_globals.php");


$errno = 0;

$jrnltrxs_edit = $default["comp_code"]."_jrnltrxs_edit";
$jrnltrxs_add = $default["comp_code"]."_jrnltrxs_add";
$gledger_edit = $default["comp_code"]."_gledger_edit";
$gledger_add = $default["comp_code"]."_gledger_add";

//////////////////////////////////////////////////////////////////////////////////////////////////
if ($cmd == "gldgr_apply") {
	$s = new GLedger();
	$j = new JrnlTrxs();
	if ($ty == "e") { // update GLedger
		$sarr = $s->getGLedger($gldgr_id);
		$oldrec = $s->getTextFields("", "$gldgr_id");
		$r = new Requests();
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if ($s->updateGLedger($gldgr_id, $arr)) {
			$_SESSION[$gledger_edit]=NULL;
			$sdtls = $_SESSION[$jrnltrxs_edit];
			if ($j->deleteJrnlTrxRefs($gldgr_id, "g"));
			for ($i=0;$i<count($sdtls);$i++) {
				if (!empty($sdtls[$i])) {
					$sdtls[$i]["jrnltrx_ref_id"] = $gldgr_id;
					if (!$j->insertJrnlTrxExs($gldgr_id, $_SERVER["PHP_AUTH_USER"], $sdtls[$i]["jrnltrx_acct_code"], "g", $sdtls[$i]["jrnltrx_dc"], $sdtls[$i]["jrnltrx_amt"], $gldgr_date)) {
						$errno=6;
						$errmsg="GLedgerment Detail Insertion Error";
						include("error.php");
						exit;
					}
				}				
			}
			$gl_field = array("gldgr_amt"=>"");
			$gl_field[gldgr_amt] = $j->getJrnlTrxsAmt($gldgr_id, "g", "d"); 
			$s->updateGLedger($gldgr_id, $gl_field);
		}
		$_SESSION[$jrnltrx_edit]=NULL;
		$loc = "Location: gledgers.php?ty=e&gldgr_id=$gldgr_id";

	} else { // insert GLedger
		$parr = $s->getGLedger($gldgr_id);
		if (empty($parr[gldgr_id])) {
			$oldrec = $s->getTextFields("", "");
			$r = new Requests();
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			if ($last_id = $s->insertGLedger($arr)) {
				if (empty($gldgr_id)) $gldgr_id = $last_id;
				$sdtls = $_SESSION[$jrnltrxs_add];
				for ($i=0;$i<count($sdtls);$i++) {
					if (!empty($sdtls[$i])) {
						$sdtls[$i]["jrnltrx_ref_id"] = $gldgr_id;
						if (!$j->insertJrnlTrxExs($gldgr_id, $_SERVER["PHP_AUTH_USER"], $sdtls[$i]["jrnltrx_acct_code"], "g", $sdtls[$i]["jrnltrx_dc"], $sdtls[$i]["jrnltrx_amt"], $gldgr_date)) {
							$errno=6;
							$errmsg="GLedgerment Detail Insertion Error";
							include("error.php");
							exit;
						}
					}				
				}
				$gl_field = array("gldgr_amt"=>"");
				$gl_field[gldgr_amt] = $j->getJrnlTrxsAmt($gldgr_id, "g", "d"); 
				$s->updateGLedger($gldgr_id, $gl_field);
				$_SESSION[$gledger_add]=NULL;
				$_SESSION[$jrnltrxs_add]=NULL;
				$loc = "Location: gledgers.php?ty=a";
			} else {
				$errno=5;
				$errmsg="GLedger Header Insertion Error";
				include("error.php");
				exit;
			}
		} else {
			$errno=4;
			$errmsg="GLedger code is already in database";
			include("error.php");
			exit;
		}
	}
	header($loc);
	exit;

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "gldgr_del") {
	$s = new GLedger();
	$j = new JrnlTrxs();
	$s->deleteGLedger($gldgr_id);
	$j->deleteJrnlTrxRefs($gldgr_id, "g");
	
	$_SESSION[$gledger_edit]=NULL;
	$_SESSION[$jrnltrx_edit]=NULL;
	$loc = "Location: gledgers.php?ty=l";
	header($loc);
	exit;

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "gldgr_sess_clear") {
	if ($ty=="e") {
		$_SESSION[$gledger_edit]=NULL;
		$_SESSION[$jrnltrxs_edit]=NULL;
		$loc = "Location: gledgers.php?ty=e&gldgr_id=$gldgr_id";
	} else {
		$_SESSION[$gledger_add]=NULL;
		$_SESSION[$jrnltrxs_add]=NULL;
		$loc = "Location: gledgers.php?ty=a";
	}
	header($loc);
	exit;

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "gldgr_sess_apply") {
	$s = new GLedger();
	if ($ty=="e") {
		$gld = $_SESSION[$gledger_edit];
	} else {
		$gld = $_SESSION[$gledger_add];
	}
	$gld[gldgr_id] = $gldgr_id;
	$gld[gldgr_date] = $gldgr_date ;
	$gld[gldgr_amt] = $gldgr_amt;
	$gld[gldgr_user_code] = $gldgr_user_code;
	$gld[gldgr_cmnt] = $gldgr_cmnt;
	if ($ty=="e") {
		$_SESSION[$gledger_edit] = $gld;
		$loc = "Location: gledgers.php?ty=e&gldgr_id=$gldgr_id";
	} else {
		$_SESSION[$gledger_add] = $gld;
		$loc = "Location: gledgers.php?ty=a";
	}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "gldgr_detail_sess_apply") {
	if ($ty == "e") {
		$gld = $_SESSION[$gledger_edit];
		$jrnltrxs = $_SESSION[$jrnltrxs_edit];
	} else {
		$gld = $_SESSION[$gledger_add];
		$jrnltrxs = $_SESSION[$jrnltrxs_add];
	}
	$gld[gldgr_id] = $gldgr_id;
	$gld[gldgr_date] = $gldgr_date ;
	$gld[gldgr_amt] = $gldgr_amt;
	$gld[gldgr_user_code] = $gldgr_user_code;
	$gld[gldgr_cmnt] = $gldgr_cmnt;
	$a = new Accts();
	if (!$aarr = $a->getAccts($jrnltrx_acct_code)) {
		$errno=4;
		$errmsg="Account code($jrnltrx_acct_code) is blank or not exist in database!";
		include("error.php");
		exit;
	}
	$arr = array();
	if ($did == "0") $did = 0; // because php treat 0 as empty
	else if ($did > 0) $did = $did;
	else $did = -1;

	if ($did >= 0) { // update
		for ($i=0;$i<count($jrnltrxs);$i++) {
			if ($i == $did) {
				$jrnl = array();
				$jrnl[jrnltrx_id]		= $jrnltrx_id;
				$jrnl["jrnltrx_ref_id"]	= $jrnltrx_ref_id;
				$jrnl["jrnltrx_acct_code"]	= $jrnltrx_acct_code;
				$jrnl["jrnltrx_dc"]		= $jrnltrx_dc;
				$jrnl["jrnltrx_amt"]		= $jrnltrx_amt;
				$jrnl[jrnltrx_desc]		= $jrnltrx_desc;
				array_push($arr, $jrnl);
			} else {
				array_push($arr, $jrnltrxs[$i]);
			}
		}
	} else { // insert
		for ($i=0;$i<count($jrnltrxs);$i++) if (!empty($jrnltrxs[$i])) array_push($arr, $jrnltrxs[$i]);
		$jrnl = array();
		$jrnl[jrnltrx_id]		= $jrnltrx_id;
		$jrnl["jrnltrx_ref_id"]	= $jrnltrx_ref_id;
		$jrnl["jrnltrx_acct_code"]	= $jrnltrx_acct_code;
		$jrnl["jrnltrx_dc"]		= $jrnltrx_dc;
		$jrnl["jrnltrx_amt"]		= $jrnltrx_amt;
		$jrnl[jrnltrx_desc]		= $jrnltrx_desc;
		array_push($arr, $jrnl);
	}
	if ($ty == "e") {
		$_SESSION[$jrnltrxs_edit] = $arr;
		$_SESSION[$gledger_edit] = $gld;
		$loc = "Location: gledgers.php?ty=e&did=$did&gldgr_id=$gldgr_id";
	} else {
		$_SESSION[$jrnltrxs_add] = $arr;
		$_SESSION[$gledger_add] = $gld;
		$loc = "Location: gledgers.php?ty=a&did=$did";
	}
	header($loc);
	exit;

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "gldgr_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$jrnldtl = $_SESSION[$jrnltrxs_edit];
		for ($i=0;$i<count($jrnldtl);$i++) if ($i != $did) array_push($arr, $jrnldtl[$i]);
		$_SESSION[$jrnltrxs_edit] = $arr;
		$loc = "Location: gledgers.php?ty=e&gldgr_id=$gldgr_id";
	} else {
		$arr = array();
		$jrnldtl = $_SESSION[$jrnltrxs_add];
		for ($i=0;$i<count($jrnldtl);$i++) if ($i != $did) array_push($arr, $jrnldtl[$i]);
		$_SESSION[$jrnltrxs_add] = $arr;
		$loc = "Location: gledgers.php?ty=a";
	}
	header($loc);
	exit;

/*
//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "gldgr_update_sess_add") {
	$s = new GLedger();
	if ($ty=="e") {
		$gld = $_SESSION[gledger_edit];
	} else {
		$gld = $_SESSION[gledger_add];
	}
	$gld[gldgr_id]		= $gldgr_id;
	$gld[gldgr_date]	= $gldgr_date;
	$gld[gldgr_user_code]	= $gldgr_user_code;
	$gld[gldgr_cmnt]		= $gldgr_cmnt;
	if ($ty=="e") {
		$gledger_edit = $gld;
		session_register("gledger_edit");
		$loc = "Location: gledgers.php?ty=e&gldgr_id=$gldgr_id";
	} else {
		$gledger_add = $gld;
		session_register("gledger_add");
		$loc = "Location: gledgers.php?ty=a";
	}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "gldgr_detail_sess_add") {
	if ($ty == "e") {
		$gledger = $_SESSION[gledger_edit];
		$jrnltrxs = $_SESSION[jrnltrxs_edit];
		$balance = 0;
		for ($i=0;$i<count($jrnltrxs);$i++) $applied += $jrnltrxs[$i]["jrnltrx_amt"];
	} else {
		$a = new Accts();
		$arr = $a->getAccts($jrnltrx_acct_code);
		if (empty($jrnltrx_acct_code) && !$arr) {
			$errno=4;
			$errmsg="Account code is blank or not exist!";
			include("error.php");
			exit;
		}
		$gledger = $_SESSION[gledger_add];
		$jrnltrxs = $_SESSION[jrnltrxs_add];
	}
	$dtl = array();
	for ($i=0;$i<count($jrnltrxs);$i++) if (!empty($jrnltrxs[$i])) array_push($dtl, $jrnltrxs[$i]);
	$jrnl = array();
	$jrnl["jrnltrx_acct_code"]	= $jrnltrx_acct_code;
	$jrnl["jrnltrx_date"]		= $gldgr_date;
	$jrnl["jrnltrx_dc"]		= $jrnltrx_dc;
	$jrnl["jrnltrx_amt"]		= $jrnltrx_amt;
	$jrnl[jrnltrx_desc]		= $jrnltrx_desc;
	array_push($dtl, $jrnl);
	if ($ty == "e") {
		$jrnltrxs_edit = $dtl;
		session_register("jrnltrxs_edit");
		$loc = "Location: gledgers.php?ty=a&gldgr_id=$gldgr_id";
	} else {
		$jrnltrxs_add = $dtl;
		session_register("jrnltrxs_add");
		$loc = "Location: gledgers.php?ty=a";
	}
	unset($last);
	session_unregister("last");
	header($loc);


*/
}
?>