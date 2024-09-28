<?php
include_once("class/map.default.php");
include_once("class/class.accounts.php");
include_once("class/class.disburse.php");
include_once("class/class.disburdtls.php");
include_once("class/class.vendors.php");
include_once("class/class.requests.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.items.php");
include_once("class/map.default.php");
include_once("class/register_globals.php");

$disburdtls_del = $default["comp_code"]."_disburdtls_del";
$disburdtls_edit = $default["comp_code"]."_disburdtls_edit";
$disburdtls_add = $default["comp_code"]."_disburdtls_add";
$disburse_edit = $default["comp_code"]."_disburse_edit";
$disburse_add = $default["comp_code"]."_disburse_add";
$last = $default["comp_code"]."_last";

$errno = 0;
//echo $cmd;
//////////////////////////////////////////////////////////////////////////////////////////////////
if ($cmd == "disbur_sess_add") {
	if ($ht=="e") {
		$s = new Disburse();
		$pur = $_SESSION[$disburse_edit]));
		$pur[disbur_id] = $disbur_id;
		$pur[disbur_date] = $disbur_date ;
		$pur[disbur_vend_code] = $disbur_vend_code;
		$pur[disbur_vend_inv] = $disbur_vend_inv;
		$pur[disbur_po_no] = $disbur_po_no;
		$pur[disbur_ref_id] = $disbur_ref_id;
		$pur[disbur_check_no] = $disbur_check_no;
		$pur[disbur_amt] = $disbur_amt;
		$pur[disbur_user_code] = $disbur_user_code;
		$pur[disbur_desc] = $disbur_desc;
		$_SESSION[$disburse_edit]=$pur;
		$loc = "Location: disburse_details.php?ty=a&ht=e&disbur_id=$disbur_id";
	} else {
		$s = new Disburse();
		$pur = $_SESSION[$disburse_add];
		$pur[disbur_id] = $disbur_id;
		$pur[disbur_date] = $disbur_date ;
		$pur[disbur_vend_code] = $disbur_vend_code;
		$pur[disbur_vend_inv] = $disbur_vend_inv;
		$pur[disbur_po_no] = $disbur_po_no;
		$pur[disbur_ref_id] = $disbur_ref_id;
		$pur[disbur_check_no] = $disbur_check_no;
		$pur[disbur_amt] = $disbur_amt;
		$pur[disbur_user_code] = $disbur_user_code;
		$pur[disbur_desc] = $disbur_desc;
		$_SESSION[$disburse_add]=$pur;
		$loc = "Location: disburse_details.php?ty=a&ht=a";
	}
	header($loc);
	exit;
//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "disbur_detail_sess_add") {
	$_SESSION[$last]=$_POST;
	if ($ht == "e") {
		$disbur = $_SESSION[$disburse_edit];
		$disburdtl = $_SESSION[$disburdtls_edit];
		$applied = 0;
		for ($i=0;$i<count($disburdtl);$i++) $applied += $disburdtl[$i][disburdtl_amt];
		if ($disbur[disbur_amt] < $applied + $disburdtl_amt ) {
			$errno=5;
			$recommend = sprintf("%0.2f", $disbur[disbur_amt] - $applied);
			$errmsg="Amount should be equal or less than $recommend!";
			include("error.php");
			exit;
		}
		$dtl = array();
		for ($i=0;$i<count($disburdtl);$i++) if (!empty($disburdtl[$i])) array_push($dtl, $disburdtl[$i]);
		$pur = array();
		$pur[disburdtl_disbur_id]	= $disbur_id;
		$pur[disburdtl_ref_id]		= $disburdtl_ref_id;
		$pur[disburdtl_vend_inv]	= $disburdtl_vend_inv;
		$pur[disburdtl_acct_code]	= $disburdtl_acct_code;
		$pur[disburdtl_amt]			= $disburdtl_amt;
		$pur[disburdtl_desc]		= $disburdtl_desc;
		array_push($dtl, $pur);
		$_SESSION[$disburdtls_edit]=$dtl;
		$loc = "Location: disburse_details.php?ty=a&ht=e&disbur_id=$disbur_id";
	} else {
		$a = new Accts();
		$arr = $a->getAccts($disburdtl_acct_code);
		if (empty($disburdtl_acct_code) && !$arr) {
			$errno=4;
			$errmsg="Account code is blank or not exist!";
			include("error.php");
			exit;
		}
		$disbur = $_SESSION[$disburse_add];
		$disburdtl = $_SESSION[$disburdtls_add];
		$applied = 0;
		for ($i=0;$i<count($disburdtl);$i++) $applied += $disburdtl[$i][disburdtl_amt];
		if ($disbur[disbur_amt] < $applied + $disburdtl_amt ) {
			$errno=5;
			$recommend = sprintf("%0.2f", $disbur[disbur_amt] - $applied);
			$errmsg="Amount should be equal or less than $recommend!";
			include("error.php");
			exit;
		}
		$dtl = array();
		for ($i=0;$i<count($disburdtl);$i++) if (!empty($disburdtl[$i])) array_push($dtl, $disburdtl[$i]);
		$pur = array();
		$pur[disburdtl_disbur_id]	= $disbur_id;
		$pur[disburdtl_ref_id]		= $disburdtl_ref_id;
		$pur[disburdtl_vend_inv]	= $disburdtl_vend_inv;
		$pur[disburdtl_acct_code]	= $disburdtl_acct_code;
		$pur[disburdtl_amt]			= $disburdtl_amt;
		$pur[disburdtl_desc]		= $disburdtl_desc;
		array_push($dtl, $pur);
		$disburdtls_add = base64_encode(serialize($dtl));
		$_SESSION["disburdtls_add"]=$disburdtls_add;

		$loc = "Location: disburse_details.php?ty=a&ht=a";
	}
	unset($last);
	session_unregister("last");
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "disbur_add") {
	$s = new Disburse();
	$d = new DisburDtls();
	$parr = $s->getDisburse($disbur_id);
	if (empty($parr[disbur_id])) {
		$oldrec = $s->getTextFields("", "");
		$r = new Requests();
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if ($last_id = $s->insertDisburse($arr)) {
			if (empty($disbur_id)) $disbur_id = $last_id;
			$sdtls = unserialize(base64_decode($_SESSION[disburdtls_add]));
			$j = new JrnlTrxs();
			$j->deleteJrnlTrxRefs($disbur_id, "p");

			for ($i=0;$i<count($sdtls);$i++) {
				$sdtls[$i][disburdtl_disbur_id] = $disbur_id;
				if (!$iddtl = $d->insertDisburDtls($sdtls[$i])) {
					$errno=6;
					$errmsg="Disbursement Detail Insertion Error";
					include("error.php");
					exit;
				}
				$j->insertJrnlTrxExs($disbur_id, $_SERVER["PHP_AUTH_USER"], $sdtls[$i][disburdtl_acct_code], "d", "d", $sdtls[$i][disburdtl_amt], $disbur_date);
			}
			session_unregister("disburse_add");
			session_unregister("disburdtls_add");
			$loc = "Location: disburse.php?ty=a";
		} else {
			$errno=5;
			$errmsg="Disbursement Header Insertion Error";
			include("error.php");
			exit;
		}
	} else {
		$errno=4;
		$errmsg="Disbursement code is already in database";
		include("error.php");
		exit;
	}

	$j->insertJrnlTrxExs($disbur_id, $_SERVER["PHP_AUTH_USER"], $default[ascr_acct], "d", "c", $disbur_amt, $disbur_date);

	header($loc);
//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "disbur_edit") {
	$s = new Disburse();
	$d = new DisburDtls();
	$sarr = $s->getDisburse($disbur_id);
	$oldrec = $s->getTextFields("", "$disbur_id");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$s->updateDisburse($disbur_id, $arr);
	session_unregister("disburse_edit");
	$d->deleteDisburDtlsPI($disbur_id);
	$sdtls = unserialize(base64_decode($_SESSION[disburdtls_edit]));

	$t = new Items();
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($disbur_id, "d");

	for ($i=0;$i<count($sdtls);$i++) {
		$sdtls[$i][disburdtl_disbur_id] = $disbur_id;
		$itm = $t->getItems($sdtls[$i][disburdtl_item_code]);
		if (!$d->insertDisburDtls($sdtls[$i])) {
			$errno=6;
			$errmsg="Disbursement Detail Insertion Error";
			include("error.php");
			exit;
		}
		$j->insertJrnlTrxExs($disbur_id, $_SERVER["PHP_AUTH_USER"], $sdtls[$i][disburdtl_acct_code], "d", "d", $sdtls[$i][disburdtl_amt], $disbur_date);
	}
	$j->insertJrnlTrxExs($disbur_id, $_SERVER["PHP_AUTH_USER"], $default[ascr_acct], "d", "c", $disbur_amt, $disbur_date);

	session_unregister("disburdtl_edit");
	$loc = "Location: disburse.php?ty=e&disbur_id=$disbur_id";
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "disbur_del") {
	include_once("class/class.disburse.php");
	$s = new Disburse();
	include_once("class/class.purdtls.php");
	$d = new DisburDtls();
	$s->deleteDisburse($disbur_id);
	$d->deleteDisburDtlsPI($disbur_id);
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($disbur_id, "p");
	
	unset($disburse_edit);
	session_unregister("disburse_edit");
	unset($disburdtl_edit);
	session_unregister("disburdtl_edit");
	$loc = "Location: disburse.php?ty=l";
	header($loc);
//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "disbur_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$purdtl = unserialize(base64_decode($_SESSION[disburdtls_edit]));
		for ($i=0;$i<count($purdtl);$i++) if ($i != $did) array_push($arr, $purdtl[$i]);
		$disburdtls_edit = base64_encode(serialize($arr));
		$_SESSION["disburdtls_edit"]=$disburdtls_edit;

		$loc = "Location: disburse.php?ty=e&disbur_id=$disbur_id";
	} else {
		$arr = array();
		$purdtl = unserialize(base64_decode($_SESSION[disburdtls_add]));
		for ($i=0;$i<count($purdtl);$i++) if ($i != $did) array_push($arr, $purdtl[$i]);
		$disburdtls_add = base64_encode(serialize($arr));
		$_SESSION["disburdtls_add"]=$disburdtls_add;

		$loc = "Location: disburse.php?ty=a";
	}
	header($loc);

} else if ($cmd == "disbur_clear_sess_edit") {
	unset($_SESSION["disburse_edit"]);
	unset($_SESSION["disburdtls_edit"]);
	$loc = "Location: disburse.php?ty=e&disbur_id=$disbur_id";
	header($loc);

} else if ($cmd == "disbur_clear_sess_add") {
	unset($_SESSION["disburse_add"]);
	unset($_SESSION["disburdtls_add"]);
	$loc = "Location: disburse.php?ty=a";
	header($loc);

} else if ($cmd == "disbur_update_sess_add") {
	if ($ht=="e") {
		$s = new Disburse();
		$pur = unserialize(base64_decode($_SESSION[disburse_edit]));
		$pur[disbur_id] = $disbur_id;
		$pur[disbur_date] = $disbur_date ;
		$pur[disbur_vend_code] = $disbur_vend_code;
		$pur[disbur_vend_inv] = $disbur_vend_inv;
		$pur[disbur_po_no] = $disbur_po_no;
		$pur[disbur_ref_id] = $disbur_ref_id;
		$pur[disbur_check_no] = $disbur_check_no;
		$pur[disbur_amt] = $disbur_amt;
		$pur[disbur_user_code] = $disbur_user_code;
		$pur[disbur_desc] = $disbur_desc;
		$disburse_edit = base64_encode(serialize($pur));
		$_SESSION["disburse_edit"]=disburse_edit;

		$loc = "Location: disburse.php?ty=e&disbur_id=$disbur_id";
	} else {
		$s = new Disburse();
		if ($arr = $s->getDisburse($disbur_id)) {
			$errno=3;
			$errmsg="Disbursement code is already in database";
		} else {
			$pur = unserialize(base64_decode($_SESSION[disburse_add]));
			$pur[disbur_id] = $disbur_id;
			$pur[disbur_date] = $disbur_date ;
			$pur[disbur_vend_code] = $disbur_vend_code;
			$pur[disbur_vend_inv] = $disbur_vend_inv;
			$pur[disbur_po_no] = $disbur_po_no;
			$pur[disbur_ref_id] = $disbur_ref_id;
			$pur[disbur_check_no] = $disbur_check_no;
			$pur[disbur_amt] = $disbur_amt;
			$pur[disbur_user_code] = $disbur_user_code;
			$pur[disbur_desc] = $disbur_desc;
			$disburse_add = base64_encode(serialize($pur));
			$_SESSION["disburse_add"]=disburse_add;

			$loc = "Location: disburse.php?ty=a";
		}
	}
	header($loc);

} else if ($cmd == "disbur_detail_sess_edit") {
	if ($ht == "e") {
		$disbur = unserialize(base64_decode($_SESSION[disburse_edit]));
		$disburdtl = unserialize(base64_decode($_SESSION[disburdtls_edit]));
		$applied = 0;
		for ($i=0;$i<count($disburdtl);$i++) if ($i != $did) $applied += $disburdtl[$i][disburdtl_amt];
		if ($disbur[disbur_amt] < $applied + $disburdtl_amt ) {
			$errno=5;
			$recommend = sprintf("%0.2f", $disbur[disbur_amt] - $applied);
			$errmsg="Amount should be equal or less than $recommend!";
			include("error.php");
			exit;
		}
		$arr = array();
		for ($i=0;$i<count($disburdtl);$i++) {
			if ($i == $did) {
				$pur = array();
				$pur[disburdtl_disbur_id]	= $disburdtl_disbur_id;
				$pur[disburdtl_ref_id]		= $disburdtl_ref_id;
				$pur[disburdtl_vend_inv]	= $disburdtl_vend_inv;
				$pur[disburdtl_acct_code]	= $disburdtl_acct_code;
				$pur[disburdtl_amt]			= $disburdtl_amt;
				$pur[disburdtl_desc]		= $disburdtl_desc;
				array_push($arr, $pur);
			} else {
				array_push($arr, $disburdtl[$i]);
			}
		}
		$disburdtls_edit = base64_encode(serialize($arr));
		$_SESSION["disburdtls_edit"]=$disburdtls_edit;

		$loc = "Location: disburse_details.php?ty=e&ht=e&did=$did&disbur_id=$disbur_id";
	} else {
		$disbur = unserialize(base64_decode($_SESSION[disburse_add]));
		$disburdtl = unserialize(base64_decode($_SESSION[disburdtls_add]));
		$applied = 0;
		for ($i=0;$i<count($disburdtl);$i++) if ($i != $did) $applied += $disburdtl[$i][disburdtl_amt];
		if ($disbur[disbur_amt] < $applied + $disburdtl_amt ) {
			$errno=5;
			$recommend = sprintf("%0.2f", $disbur[disbur_amt] - $applied);
			$errmsg="Amount should be equal or less than $recommend!";
			include("error.php");
			exit;
		}
		$arr = array();
		for ($i=0;$i<count($disburdtl);$i++) {
			if ($i == $did) {
				$pur = array();
				$pur[disburdtl_disbur_id]	= $disburdtl_disbur_id;
				$pur[disburdtl_ref_id]		= $disburdtl_ref_id;
				$pur[disburdtl_vend_inv]	= $disburdtl_vend_inv;
				$pur[disburdtl_acct_code]	= $disburdtl_acct_code;
				$pur[disburdtl_amt]			= $disburdtl_amt;
				$pur[disburdtl_desc]		= $disburdtl_desc;
				array_push($arr, $pur);
			} else {
				array_push($arr, $disburdtl[$i]);
			}
		}
		$disburdtls_add = base64_encode(serialize($arr));
		$_SESSION["disburdtls_add"] = $disburdtls_add;
		$loc = "Location: disburse_details.php?ty=e&ht=a&did=$did";
	}
	header($loc);
}
?>