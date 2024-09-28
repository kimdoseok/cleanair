<?php
include_once("class/map.default.php");
include_once("class/class.accounts.php");
include_once("class/class.receipt.php");
include_once("class/class.rcptdtls.php");
include_once("class/class.customers.php");
include_once("class/class.requests.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.items.php");
include_once("class/register_globals.php");

$errno = 0;
//echo $cmd;

$_SESSION[rcptdtl_del] = NULL;

if ($cmd == "rcpt_sess_add") {
	if ($ht=="e") {
		$s = new Receipt();
		$sls = $_SESSION[receipt_edit];
		$sls["rcpt_id"] = $rcpt_id;
		$sls["rcpt_date"] = $rcpt_date ;
		$sls["rcpt_cust_code"] = $rcpt_cust_code;
		$sls[rcpt_po_no] = $rcpt_po_no;
		$sls[rcpt_ref_id] = $rcpt_ref_id;
		$sls["rcpt_check_no"] = $rcpt_check_no;
		$sls["rcpt_type"] = $rcpt_type;
		$sls["rcpt_amt"] = $rcpt_amt;
		$sls["rcpt_disc_amt"] = $rcpt_disc_amt;
		$sls[rcpt_user_code] = $rcpt_user_code;
		$sls[rcpt_desc] = $rcpt_desc;
		$sls[rcpt_comment] = $rcpt_comment;
		$_SESSION[receipt_edit] = $sls;
		$loc = "Location: receipt_details.php?ty=a&ht=e&rcpt_id=$rcpt_id";
	} else {
		$s = new Receipt();
		$sls = $_SESSION["receipt_add"];
		$sls["rcpt_id"] = $rcpt_id;
		$sls["rcpt_date"] = $rcpt_date ;
		$sls["rcpt_cust_code"] = $rcpt_cust_code;
		$sls[rcpt_po_no] = $rcpt_po_no;
		$sls[rcpt_ref_id] = $rcpt_ref_id;
		$sls["rcpt_check_no"] = $rcpt_check_no;
		$sls["rcpt_type"] = $rcpt_type;
		$sls["rcpt_amt"] = $rcpt_amt;
		$sls["rcpt_disc_amt"] = $rcpt_disc_amt;
		$sls[rcpt_user_code] = $rcpt_user_code;
		$sls[rcpt_desc] = $rcpt_desc;
		$sls[rcpt_comment] = $rcpt_comment;
		$_SESSION["receipt_add"] = $sls;
		$loc = "Location: receipt_details.php?ty=a&ht=a";
	}
	header($loc);

} else if ($cmd == "rcpt_detail_sess_add") {

	$_SESSION["last"] = $_POST;
	if ($ht == "e") {
		$rcpt = $_SESSION[receipt_edit];
		$rcptdtl = $_SESSION[rcptdtls_edit];
//		$applied = 0;
//		for ($i=0;$i<count($rcptdtl);$i++) $applied += $rcptdtl[$i]["rcptdtl_amt"];
		$applied = 0;
		$rcptdtl_disc_amt = 0;
		$rcptdtl_op_amt = 0;
		$rcptdtl_cm_amt = 0;
		$rcptdtl_aw_amt = 0;
		for ($i=0;$i<count($rcptdtl);$i++) {
			if ($rcptdtl[$i]["rcptdtl_type"]=="ar") $applied += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else $applied += $rcptdtl[$i]["rcptdtl_amt"];
		}

		if ($rcptdtl_type == "ar") {
//			if ($rcpt["rcpt_amt"] < $applied + $rcptdtl_amt ) {
//				$errno=5;
//				$recommand = sprintf("%0.2f", $rcpt["rcpt_amt"] - $applied);
//				$errmsg="Amount should be equal or less than $recommand!";
//				include("error.php");
//				exit;
//			}
		}
		$dtl = array();
		for ($i=0;$i<count($rcptdtl);$i++) if (!empty($rcptdtl[$i])) array_push($dtl, $rcptdtl[$i]);
		$sls = array();
		$sls[rcptdtl_rcpt_id]	= $rcpt_id;
		$sls["rcptdtl_pick_id"]	= $rcptdtl_pick_id;
		$sls[rcptdtl_acct_code]	= $rcptdtl_acct_code;
		$sls["rcptdtl_type"]		= $rcptdtl_type;
//		if ($rcptdtl_type != "d") $rcptdtl_amt = abs($rcptdtl_amt)*-1;
		$sls["rcptdtl_amt"]		= $rcptdtl_amt;
		$sls["rcptdtl_desc"]		= $rcptdtl_desc;
		array_push($dtl, $sls);
		$_SESSION[rcptdtls_edit] = $dtl;
		$loc = "Location: receipt_details.php?ty=a&ht=e&rcpt_id=$rcpt_id";
	} else {
		$a = new Accts();
		$arr = $a->getAccts($rcptdtl_acct_code);
		if (empty($rcptdtl_acct_code) && !$arr) {
			$errno=4;
			$errmsg="Account code is blank or not exist!";
			include("error.php");
			exit;
		}
		$rcpt = $_SESSION["receipt_add"];
		$rcptdtl = $_SESSION["rcptdtls_add"];
//		$applied = 0;
//		for ($i=0;$i<count($rcptdtl);$i++) $applied += $rcptdtl[$i]["rcptdtl_amt"];
		$applied = 0;
		$rcptdtl_disc_amt = 0;
		$rcptdtl_op_amt = 0;
		$rcptdtl_cm_amt = 0;
		$rcptdtl_aw_amt = 0;
		for ($i=0;$i<count($rcptdtl);$i++) {
			if ($rcptdtl[$i]["rcptdtl_type"]=="ar") $applied += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else $applied += $rcptdtl[$i]["rcptdtl_amt"];
		}
		if ($rcptdtl_type == "ar") {
//			if ($rcpt["rcpt_amt"] < $applied + $rcptdtl_amt) {
//				$errno=5;
//				$recommand = sprintf("%0.2f", $rcpt["rcpt_amt"] - $applied);
//				$errmsg="Amount should be equal or less than $recommand!";
//				include("error.php");
//				exit;
//			}
		}
		$dtl = array();
		for ($i=0;$i<count($rcptdtl);$i++) if (!empty($rcptdtl[$i])) array_push($dtl, $rcptdtl[$i]);
		$sls = array();
		$sls[rcptdtl_rcpt_id]	= $rcpt_id;
		$sls["rcptdtl_pick_id"]		= $rcptdtl_pick_id;
		$sls[rcptdtl_acct_code]	= $rcptdtl_acct_code;
		$sls["rcptdtl_type"]		= $rcptdtl_type;
//		if ($rcptdtl_type != "d") $rcptdtl_amt = abs($rcptdtl_amt)*-1;
		$sls["rcptdtl_amt"]			= $rcptdtl_amt;
		$sls["rcptdtl_desc"]		= $rcptdtl_desc;
		array_push($dtl, $sls);
		$_SESSION["rcptdtls_add"] = $dtl;
		$loc = "Location: receipt_details.php?ty=a&ht=a";
	}
	$_SESSION["last"]=NULL;
	
	header($loc);
	exit;

} else if ($cmd == "rcpt_add") {
	$rcptdtls_add = $_SESSION["rcptdtls_add"];
	$dtl_num = count($rcptdtls_add);
//	$appl_total = 0;
//	for ($i=0;$i<$dtl_num;$i++) $appl_total += $rcptdtls_add[$i]["rcptdtl_amt"];
	$applied = 0;
	$rcptdtl_disc_amt = 0;
	$rcptdtl_op_amt = 0;
	$rcptdtl_cm_amt = 0;
	$rcptdtl_aw_amt = 0;
	for ($i=0;$i<count($rcptdtls_add);$i++) {
		if ($rcptdtls_add[$i]["rcptdtl_type"]=="ar") $applied += $rcptdtls_add[$i]["rcptdtl_amt"];
		else if ($rcptdtls_add[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $rcptdtls_add[$i]["rcptdtl_amt"];
		else if ($rcptdtls_add[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $rcptdtls_add[$i]["rcptdtl_amt"];
		else if ($rcptdtls_add[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $rcptdtls_add[$i]["rcptdtl_amt"];
		else if ($rcptdtls_add[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $rcptdtls_add[$i]["rcptdtl_amt"];
		else $applied += $rcptdtls_add[$i]["rcptdtl_amt"];
	}
	
	if ($rcpt_amt != $applied) {
		$errno=6;
		$errmsg="Remaining should be zero";
		include("error.php");
		exit;
	}

	$s = new Receipt();
	$d = new RcptDtls();
	$parr = $s->getReceipt($rcpt_id);
	if (empty($parr["rcpt_id"])) {
		$oldrec = $s->getTextFields("", "");
		$r = new Requests();
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		/*
		print_r($oldrec);
		print_r($_POST);
		print_r($arr);
		*/
		if ($last_id = $s->insertReceipt($arr)) {
			if (empty($rcpt_id)) $rcpt_id = $last_id;
			$sdtls = $_SESSION["rcptdtls_add"];
			$j = new JrnlTrxs();
			$j->deleteJrnlTrxRefs($rcpt_id, "c");

			for ($i=0;$i<count($sdtls);$i++) {
				$sdtls[$i][rcptdtl_rcpt_id] = $rcpt_id;
				if (!$iddtl = $d->insertRcptDtls($sdtls[$i])) {
					$errno=6;
					$errmsg="Receiptment Detail Insertion Error";
					include("error.php");
					exit;
				}
				$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $sdtls[$i][rcptdtl_acct_code], "c", "c", $sdtls[$i]["rcptdtl_amt"], $rcpt_date);
			}
			unset($_SESSION["receipt_add"]);
			unset($_SESSION["rcptdtls_add"]);
		} else {
			$errno=5;
			$errmsg="Receipt Header Insertion Error";
			include("error.php");
			exit;
		}
	} else {
		$errno=4;
		$errmsg="Receipt code is already in database";
		include("error.php");
		exit;
	}

	// update customer balance

	$c = new Custs();
	$cust_arr = $c->getCusts($_POST["rcpt_cust_code"]);
	if ($cust_arr) {
		$c->updateCustsBalance($_POST["rcpt_cust_code"]);
	} else {
		$errno=7;
		$errmsg="Customer Not Found Error";
		include("error.php");
		exit;
	}

	$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $default[ascr_acct], "c", "d", $rcpt_amt, $rcpt_date);

	$_SESSION["receipt_add"]=NULL;
	$_SESSION["rcptdtls_add"]=NULL;

	$loc = "Location: receipt.php?ty=v&rcpt_id=$rcpt_id";
	header($loc);
	exit;

} else if ($cmd == "rcpt_edit") {
	$dtl_num = count($rcptdtls_edit);
//	$appl_total = 0;
//	for ($i=0;$i<$dtl_num;$i++) $appl_total += $rcptdtls_edit[$i]["rcptdtl_amt"];
	$applied = 0;
	$rcptdtl_disc_amt = 0;
	$rcptdtl_op_amt = 0;
	$rcptdtl_cm_amt = 0;
	$rcptdtl_aw_amt = 0;
	for ($i=0;$i<count($rcptdtls_edit);$i++) {
		if ($rcptdtls_edit[$i]["rcptdtl_type"]=="ar") $applied += $rcptdtls_edit[$i]["rcptdtl_amt"];
		else if ($rcptdtls_edit[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $rcptdtls_edit[$i]["rcptdtl_amt"];
		else if ($rcptdtls_edit[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $rcptdtls_edit[$i]["rcptdtl_amt"];
		else if ($rcptdtls_edit[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $rcptdtls_edit[$i]["rcptdtl_amt"];
		else if ($rcptdtls_edit[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $rcptdtls_edit[$i]["rcptdtl_amt"];
		else $applied += $rcptdtls_edit[$i]["rcptdtl_amt"];
	}

	$rcpt_amt = str_replace(",","",$rcpt_amt);
	if ($rcpt_amt != $applied) {
		$errno=6;
		$errmsg="Remaining should be zero";
		include("error.php");
		exit;
	}

	$s = new Receipt();
	$d = new RcptDtls();
	$sarr = $s->getReceipt($rcpt_id);
	$oldrec = $s->getTextFields("", "$rcpt_id");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$s->updateReceipt($rcpt_id, $arr);
	$_SESSION[receipt_edit]=NULL;
	$d->deleteRcptDtlsPI($rcpt_id);
	$sdtls = $_SESSION[rcptdtls_edit];

	$t = new Items();
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($rcpt_id, "c");

	for ($i=0;$i<count($sdtls);$i++) {
		$sdtls[$i][rcptdtl_rcpt_id] = $rcpt_id;
		$sdtls[$i]["rcptdtl_type"] = "c";
		unset($sdtls[$i][rcptdtl_id]);
		if (!$d->insertRcptDtls($sdtls[$i])) {
			$errno=6;
			$errmsg="Receiptment Detail Insertion Error";
			include("error.php");
			exit;
		}
		$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $sdtls[$i][rcptdtl_acct_code], "c", "c", $sdtls[$i]["rcptdtl_amt"],  $rcpt_date);
	}
	$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $default[ascr_acct], "c", "d", $rcpt_amt, $rcpt_date); // expense 

	// update customer balance
	$c = new Custs();
	$cust_arr = $c->getCusts($sarr["rcpt_cust_code"]);
	if ($cust_arr) {
		$c->updateCustsBalance($sarr["rcpt_cust_code"]);
	} else {
		$errno=7;
		$errmsg="Customer Not Found Error";
		include("error.php");
		exit;
	}

	$_SESSION[receipt_edit]=NULL;
	$_SESSION[rcptdtls_edit]=NULL;
	$_SESSION[rcptdtl_del]=NULL;

	$loc = "Location: receipt.php?ty=e&rcpt_id=$rcpt_id";
	header($loc);

} else if ($cmd == "rcpt_sess_del") {
	if ($ty=="e") {
		unset($_SESSION["rcpt_edit"]);
		unset($_SESSION["rcptdtls_edit"]);
		$loc = "Location: receipt.php?ty=e&rcpt_id=$rcpt_id";
	} else {
		$loc = "Location: receipt.php?ty=a";
	}
	$_SESSION[rcpt_del]=1;
	header($loc);
	exit;

} else if ($cmd == "rcpt_del") {
	$s = new Receipt();
	$d = new RcptDtls();
	$sarr = $s->getReceipt($rcpt_id);
	$s->deleteReceipt($rcpt_id);
	$d->deleteRcptDtlsPI($rcpt_id);
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($rcpt_id, "c");

	// update customer balance
	$c = new Custs();
	$cust_arr = $c->getCusts($sarr["rcpt_cust_code"]);
	if ($cust_arr) {
		$c->updateCustsBalance($sarr["rcpt_cust_code"]);
	} else {
		$errno=7;
		$errmsg="Customer Not Found Error";
		include("error.php");
		exit;
	}

	$_SESSION[receipt_edit]=NULL;
	$_SESSION[rcptdtls_edit]=NULL;
	$loc = "Location: receipt.php?ty=l";
	$_SESSION[rcptdtl_del] = 1;
	header($loc);
	exit;

} else if ($cmd == "rcpt_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$slsdtl = $_SESSION[rcptdtls_edit];
		for ($i=0;$i<count($slsdtl);$i++) if ($i != $did) array_push($arr, $slsdtl[$i]);
		$_SESSION[rcptdtls_edit] = $arr;
//		$_SESSION[rcptdtl_del] = 1;
		$loc = "Location: receipt.php?ty=e&rcpt_id=$rcpt_id";
	} else {
		$arr = array();
		$slsdtl = $_SESSION["rcptdtls_add"];
		for ($i=0;$i<count($slsdtl);$i++) if ($i != $did) array_push($arr, $slsdtl[$i]);
		$_SESSION["rcptdtls_add"] = $arr;
		$loc = "Location: receipt.php?ty=a";
	}
	$_SESSION[rcptdtl_del]=1;
	header($loc);
	exit;

} else if ($cmd == "rcpt_clear_sess_edit") {
	$_SESSION[receipt_edit]=NULL;;
	$_SESSION[rcptdtls_edit]=NULL;
	$loc = "Location: receipt.php?ty=e&rcpt_id=$rcpt_id";
	header($loc);
	exit;

} else if ($cmd == "rcpt_clear_sess_add") {
	$_SESSION["receipt_add"]=NULL;
	$_SESSION["rcptdtls_add"]=NULL;
	$loc = "Location: receipt.php?ty=a";
	header($loc);
	exit;

} else if ($cmd == "rcpt_update_sess_add") {
	if ($ht=="e") {
		$s = new Receipt();
		$sls = $_SESSION[receipt_edit];
		$sls["rcpt_id"] = $rcpt_id;
		$sls["rcpt_date"] = $rcpt_date ;
		$sls["rcpt_cust_code"] = $rcpt_cust_code;
		$sls[rcpt_po_no] = $rcpt_po_no;
		$sls[rcpt_ref_id] = $rcpt_ref_id;
		$sls["rcpt_check_no"] = $rcpt_check_no;
		$sls["rcpt_type"] = $rcpt_type;
		$sls["rcpt_amt"] = $rcpt_amt;
		$sls["rcpt_disc_amt"] = $rcpt_disc_amt;
		$sls[rcpt_user_code] = $rcpt_user_code;
		$sls[rcpt_desc] = $rcpt_desc;
		$sls[rcpt_comment] = $rcpt_comment;
		$_SESSION[receipt_edit] = $sls;
		$loc = "Location: receipt.php?ty=e&rcpt_id=$rcpt_id";
	} else {
		$s = new Receipt();
		if ($arr = $s->getReceipt($rcpt_id)) {
			$errno=3;
			$errmsg="Receipt code is already in database";
		} else {
			$sls = $_SESSION["receipt_add"];
			$sls["rcpt_id"] = $rcpt_id;
			$sls["rcpt_date"] = $rcpt_date ;
			$sls["rcpt_cust_code"] = $rcpt_cust_code;
			$sls[rcpt_po_no] = $rcpt_po_no;
			$sls[rcpt_ref_id] = $rcpt_ref_id;
			$sls["rcpt_check_no"] = $rcpt_check_no;
			$sls["rcpt_type"] = $rcpt_type;
			$sls["rcpt_amt"] = $rcpt_amt;
			$sls["rcpt_disc_amt"] = $rcpt_disc_amt;
			$sls[rcpt_user_code] = $rcpt_user_code;
			$sls[rcpt_desc] = $rcpt_desc;
			$sls[rcpt_comment] = $rcpt_comment;
			$_SESSION["receipt_add"] = $sls;
			$loc = "Location: receipt.php?ty=a";
		}
	}
	header($loc);

} else if ($cmd == "rcpt_detail_sess_edit") {
	if ($ht == "e") {
		$rcpt = $_SESSION[receipt_edit];
		$rcptdtl = $_SESSION[rcptdtls_edit];
//		$applied = 0;
//		for ($i=0;$i<count($rcptdtl);$i++) if ($i != $did) $applied += $rcptdtl[$i]["rcptdtl_amt"];
		$applied = 0;
		$rcptdtl_disc_amt = 0;
		$rcptdtl_op_amt = 0;
		$rcptdtl_cm_amt = 0;
		$rcptdtl_aw_amt = 0;
		for ($i=0;$i<count($rcptdtl);$i++) {
			if ($rcptdtl[$i]["rcptdtl_type"]=="ar") $applied += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else $applied += $rcptdtl[$i]["rcptdtl_amt"];
		}
		if ($rcptdtl_type == "ar") {
			if ($rcpt["rcpt_amt"] < $applied + $rcptdtl_amt ) {
				$errno=5;
				$recommand = sprintf("%0.2f", $rcpt["rcpt_amt"] - $applied);
				$errmsg="Amount should be equal or less than $recommand!";
				include("error.php");
				exit;
			}
		}
		$arr = array();
		for ($i=0;$i<count($rcptdtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls[rcptdtl_rcpt_id]	= $rcptdtl_rcpt_id;
				$sls["rcptdtl_pick_id"]		= $rcptdtl_pick_id;
				$sls[rcptdtl_acct_code]	= $rcptdtl_acct_code;
				$sls["rcptdtl_type"]		= $rcptdtl_type;
//				if ($rcptdtl_type != "d") $rcptdtl_amt = abs($rcptdtl_amt)*-1;
				$sls["rcptdtl_amt"]			= $rcptdtl_amt;
				$sls["rcptdtl_desc"]		= $rcptdtl_desc;
				array_push($arr, $sls);
			} else {
				array_push($arr, $rcptdtl[$i]);
			}
		}
		$_SESSION[rcptdtls_edit] = $arr;
		$loc = "Location: receipt_details.php?ty=e&ht=e&did=$did&rcpt_id=$rcpt_id";
	} else {
		$rcpt = $_SESSION["receipt_add"];
		$rcptdtl = $_SESSION["rcptdtls_add"];
//		$applied = 0;
//		for ($i=0;$i<count($rcptdtl);$i++) if ($i != $did) $applied += $rcptdtl[$i]["rcptdtl_amt"];
		$applied = 0;
		$rcptdtl_disc_amt = 0;
		$rcptdtl_op_amt = 0;
		$rcptdtl_cm_amt = 0;
		$rcptdtl_aw_amt = 0;
		for ($i=0;$i<count($rcptdtl);$i++) {
			if ($rcptdtl[$i]["rcptdtl_type"]=="ar") $applied += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else if ($rcptdtl[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $rcptdtl[$i]["rcptdtl_amt"];
			else $applied += $rcptdtl[$i]["rcptdtl_amt"];
		}
		if ($rcptdtl_type == "ar") {
			if ($rcpt["rcpt_amt"] < $applied + $rcptdtl_amt ) {
				$errno=5;
				$recommand = sprintf("%0.2f", $rcpt["rcpt_amt"] - $applied);
				$errmsg="Amount should be equal or less than $recommand!";
				include("error.php");
				exit;
			}
		}
		$arr = array();
		for ($i=0;$i<count($rcptdtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls[rcptdtl_rcpt_id]	= $rcptdtl_rcpt_id;
				$sls["rcptdtl_pick_id"]		= $rcptdtl_pick_id;
				$sls[rcptdtl_acct_code]	= $rcptdtl_acct_code;
				$sls["rcptdtl_type"]		= $rcptdtl_type;
//				if ($rcptdtl_type != "d") $rcptdtl_amt = abs($rcptdtl_amt)*-1;
				$sls["rcptdtl_amt"]			= $rcptdtl_amt;
				$sls["rcptdtl_desc"]		= $rcptdtl_desc;
				array_push($arr, $sls);
			} else {
				array_push($arr, $rcptdtl[$i]);
			}
		}
		$_SESSION["rcptdtls_add"] = $arr;
		$loc = "Location: receipt_details.php?ty=e&ht=a&did=$did";
	}
	header($loc);

} else if ($cmd == "rcpt_bounced_check") {
	$r = new Receipt();
	$rcpt_arr = $r->getReceipt($rcpt_id);
	if ($rcpt_arr) {
		unset($rcpt_arr["rcpt_id"]);
		$rcpt_arr["rcpt_amt"] *= -1;
		$rcpt_arr["rcpt_disc_amt"] *= -1;
		$rcpt_arr["rcpt_type"] = "bc";
		$rcpt_arr["rcpt_date"] = date("m/d/Y");
		$rcpt_arr[rcpt_user_code] = $_SERVER["PHP_AUTH_USER"];
		$rcpt_arr[rcpt_comment] = $rcpt_id;
		$_SESSION["receipt_add"] = $rcpt_arr;
		$d = new RcptDtls();
		$darr = $d->getRcptDtlsList($rcpt_id);
		$_SESSION["rcptdtls_add"] = array();
		for ($i=0;$i<count($darr);$i++) {
			unset($darr[$i][rcptdtl_id]);
			$darr[$i]["rcptdtl_amt"] *= -1;
			$_SESSION["rcptdtls_add"][$i] = $darr[$i];
		}
		$loc = "Location: receipt.php?ty=a";
	} else {
		$loc = "Location: receipt.php?ty=e";
	}
	header($loc);

}
?>