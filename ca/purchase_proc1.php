<?php
include_once("class/map.default.php");
include_once("class/class.disbursements.php");
include_once("class/class.vendors.php");
include_once("class/class.requests.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.items.php");

$errno = 0;

if ($cmd == "purch_sess_add") {
	include_once("class/class.purchases.php");
	if ($ht=="e") {
		$s = new Purchases();
		$pur = unserialize(base64_decode($_SESSION[purchs_edit]));
		$pur["purch_id"] = $purch_id;
		$pur[purch_vend_inv] = $purch_vend_inv;
		$pur["purch_user_code"] = $purch_user_code;
		$pur["purch_vend_code"] = $purch_vend_code;
		$pur["purch_amt"] = $purch_amt;
		$pur["purch_date"] = $purch_date ;
		$pur["purch_tax_amt"] = $purch_tax_amt;
		$pur["purch_freight_amt"] = $purch_freight_amt;
		$pur["purch_shipvia"] = $purch_shipvia;
		$pur["purch_comnt"] = $purch_comnt;
		$purchs_edit = base64_encode(serialize($pur));
		$_SESSION["purchs_edit"] = $purchs_edit;

		$loc = "Location: purch_details.php?ty=a&ht=e&purch_id=$purch_id";
	} else {
		$s = new Purchases();
		$pur = unserialize(base64_decode($_SESSION[purchs_add]));
		$pur["purch_id"] = $purch_id;
		$pur[purch_vend_inv] = $purch_vend_inv;
		$pur["purch_user_code"] = $purch_user_code;
		$pur["purch_vend_code"] = $purch_vend_code;
		$pur["purch_amt"] = $purch_amt;
		$pur["purch_date"] = $purch_date ;
		$pur["purch_tax_amt"] = $purch_tax_amt;
		$pur["purch_freight_amt"] = $purch_freight_amt;
		$pur["purch_shipvia"] = $purch_shipvia;
		$pur["purch_comnt"] = $purch_comnt;
		$purchs_add = base64_encode(serialize($pur));
		$_SESSION["purchs_add"] = $purchs_add;
		$loc = "Location: purch_details.php?ty=a&ht=a";
	}
	header($loc);

} else if ($cmd == "purch_detail_sess_add") {
	include_once("class/class.purdtls.php");
	if ($ht == "e") {
		$purdtl = unserialize(base64_decode($_SESSION["purdtls_edit"]));
		$dtl = array();
		for ($i=0;$i<count($purdtl);$i++) if (!empty($purdtl[$i])) array_push($dtl, $purdtl[$i]);
		$pur = array();
		$pur["purdtl_purch_id"]		= $purch_id;
		$pur[purdtl_po_no]			= $purdtl_po_no;
		$pur["purdtl_item_code"]		= $purdtl_item_code;
		$pur[purdtl_acct_code]		= $purdtl_acct_code;
		$pur["purdtl_qty"]			= $purdtl_qty;
		$pur["purdtl_cost"]			= $purdtl_cost;
		$pur["purdtl_unit"]			= $purdtl_unit;
		array_push($dtl, $pur);
		$purdtls_edit = base64_encode(serialize($dtl));
		$_SESSION["purdtls_edit"] = $purdtls_edit;

		$loc = "Location: purch_details.php?ty=a&ht=e&purch_id=$purch_id";
	} else {
		include_once("class/class.items.php");
		$s = new Items();
		if (empty($purdtl_item_code)) {
			$errno=4;
			$errmsg="Item code should not be blank!";
			include("error.php");
			exit;
		} else {
			$purdtl = unserialize(base64_decode($_SESSION["purdtls_add"]));
			$dtl = array();
			for ($i=0;$i<count($purdtl);$i++) if (!empty($purdtl[$i])) array_push($dtl, $purdtl[$i]);
			$pur = array();
			$pur[purdtl_po_no]			= $purdtl_po_no;
			$pur["purdtl_item_code"]		= $purdtl_item_code;
			$pur[purdtl_acct_code]		= $purdtl_acct_code;
			$pur["purdtl_qty"]			= $purdtl_qty;
			$pur["purdtl_cost"]			= $purdtl_cost;
			$pur["purdtl_unit"]			= $purdtl_unit;
			array_push($dtl, $pur);
			$purdtls_add = base64_encode(serialize($dtl));
			$_SESSION["purdtls_add"] = $purdtls_add;

			$loc = "Location: purch_details.php?ty=a&ht=a&purdtl_po_no=$purdtl_po_no";
		}
	}
	header($loc);

} else if ($cmd == "purch_add") {
	include_once("class/class.purchases.php");
	$s = new Purchases();
	include_once("class/class.purdtls.php");
	$d = new PurDtls();
	$parr = $s->getPurchases($purch_id);
	if (empty($parr["purch_id"])) {
		$oldrec = $s->getTextFields("", "");
		$r = new Requests();
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if ($last_id = $s->insertPurchases($arr)) {
			if (empty($purch_id)) $purch_id = $last_id;
			$sdtls = unserialize(base64_decode($_SESSION["purdtls_add"]));
			$j = new JrnlTrxs();
			$j->deleteJrnlTrxRefs($purch_id, "p");

			for ($i=0;$i<count($sdtls);$i++) {
				$sdtls[$i]["purdtl_purch_id"] = $purch_id;
				if (!$d->insertPurDtls($sdtls[$i])) {
					$errno=6;
					$errmsg="Purchase Detail Insertion Error";
					include("error.php");
					exit;
				}
				$j->insertJrnlTrxExs($purch_id, $_SERVER["PHP_AUTH_USER"], $sdtls[$i][purdtl_acct_code], "p", "d", $sdtls[$i]["purdtl_cost"]*$sdtls[$i]["purdtl_qty"], $purch_date); 
			}
			session_unregister("purchs_add");
			session_unregister("purdtls_add");
			$loc = "Location: purchases.php?ty=a";
		} else {
			$errno=5;
			$errmsg="Purchase Header Insertion Error";
			include("error.php");
			exit;
		}
	} else {
		$errno=4;
		$errmsg="Purchase code is already in database";
		include("error.php");
		exit;
	}

	$j->insertJrnlTrxExs($purch_id, $_SERVER["PHP_AUTH_USER"], $default["litp_acct"], "p", "c", $purch_amt, $purch_date);

	header($loc);

} else if ($cmd == "purch_edit") {
	include_once("class/class.purchases.php");
	$s = new Purchases();
	include_once("class/class.purdtls.php");
	$d = new PurDtls();
	$sarr = $s->getPurchases($purch_id);
	$oldrec = $s->getTextFields("", "$purch_id");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$s->updatePurchases($purch_id, $arr);
	session_unregister("purchs_edit");
	$d->deletePurDtlsPI($purch_id);
	$sdtls = unserialize(base64_decode($_SESSION["purdtls_edit"]));

	$t = new Items();
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($purch_id, "p");

	for ($i=0;$i<count($sdtls);$i++) {
		$sdtls[$i]["purdtl_purch_id"] = $purch_id;
		$itm = $t->getItems($sdtls[$i]["purdtl_item_code"]);
		$j->insertJrnlTrxExs($purch_id, $_SERVER["PHP_AUTH_USER"], $sdtls[$i][purdtl_acct_code], "p", "d", $sdtls[$i]["purdtl_cost"]*$sdtls[$i]["purdtl_qty"], $purch_date); // expense for purchase
		if (!$d->insertPurDtls($sdtls[$i])) {
			$errno=6;
			$errmsg="Purchase Detail Insertion Error";
			include("error.php");
			exit;
		}

	}
	$j->insertJrnlTrxExs($purch_id, $_SERVER["PHP_AUTH_USER"], $default["litp_acct"], "p", "c", $purch_amt, $purch_date); // ap for purchase

	session_unregister("purdtls_edit");
	$loc = "Location: purchases.php?ty=e&purch_id=$purch_id";
	header($loc);

} else if ($cmd == "purch_del") {
	include_once("class/class.purchases.php");
	$s = new Purchases();
	include_once("class/class.purdtls.php");
	$d = new PurDtls();
	$s->deletePurchases($purch_id);
	$d->deletePurDtlsPI($purch_id);
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($purch_id, "p");
	
	unset($purchs_edit);
	session_unregister("purchs_edit");
	unset($purdtls_edit);
	session_unregister("purdtls_edit");
	$loc = "Location: purchases.php?ty=l";
	header($loc);

} else if ($cmd == "purch_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$purdtl = unserialize(base64_decode($_SESSION["purdtls_edit"]));
		for ($i=0;$i<count($purdtl);$i++) if ($i != $did) array_push($arr, $purdtl[$i]);
		$purdtls_edit = base64_encode(serialize($arr));
		$_SESSION["purdtls_edit"] = $purdtls_edit;

		$loc = "Location: purchases.php?ty=e&purch_id=$purch_id";
	} else {
		$arr = array();
		$purdtl = unserialize(base64_decode($_SESSION["purdtls_add"]));
		for ($i=0;$i<count($purdtl);$i++) if ($i != $did) array_push($arr, $purdtl[$i]);
		$purdtls_add = base64_encode(serialize($arr));
		$_SESSION["purdtls_add"] = $purdtls_add;
		$loc = "Location: purchases.php?ty=a";
	}
	header($loc);

} else if ($cmd == "purch_clear_sess_edit") {
	session_unregister("purchs_edit");
	session_unregister("purdtls_edit");
	$loc = "Location: purchases.php?ty=e&purch_id=$purch_id";
	header($loc);

} else if ($cmd == "purch_clear_sess_add") {
	session_unregister("purchs_add");
	session_unregister("purdtls_add");
	$loc = "Location: purchases.php?ty=a";
	header($loc);

} else if ($cmd == "purch_update_sess_add") {
	include_once("class/class.purchases.php");
	if ($ty=="e") {
		$s = new Purchases();
		$pur = unserialize(base64_decode($_SESSION[purchs_edit]));
		$pur["purch_id"] = $purch_id;
		$pur[purch_vend_inv] = $purch_vend_inv;
		$pur["purch_user_code"] = $purch_user_code;
		$pur["purch_vend_code"] = $purch_vend_code;
		$pur["purch_amt"] = $purch_amt;
		$pur["purch_date"] = $purch_date ;
		$pur["purch_tax_amt"] = $purch_tax_amt;
		$pur["purch_freight_amt"] = $purch_freight_amt;
		$pur["purch_shipvia"] = $purch_shipvia;
		$pur["purch_comnt"] = $purch_comnt;
		$purchs_edit = base64_encode(serialize($pur));
		$_SESSION["purchs_edit"] = $purchs_edit;
		
		$loc = "Location: purchases.php?ty=e&purch_id=$purch_id";
	} else {
		$s = new Purchases();
		if ($arr = $s->getPurchases($purch_id)) {
			$errno=3;
			$errmsg="Purchase code is already in database";
		} else {
			$pur = unserialize(base64_decode($_SESSION[purchs_add]));
			$pur["purch_id"] = $purch_id;
			$pur[purch_vend_inv] = $purch_vend_inv;
			$pur["purch_user_code"] = $purch_user_code;
			$pur["purch_vend_code"] = $purch_vend_code;
			$pur["purch_amt"] = $purch_amt;
			$pur["purch_date"] = $purch_date ;
			$pur["purch_tax_amt"] = $purch_tax_amt;
			$pur["purch_freight_amt"] = $purch_freight_amt;
			$pur["purch_shipvia"] = $purch_shipvia;
			$pur["purch_comnt"] = $purch_comnt;
			$purchs_add = base64_encode(serialize($pur));
			$_SESSION["purchs_add"] = $purchs_add;

			$loc = "Location: purchases.php?ty=a";
		}
	}
	header($loc);

} else if ($cmd == "purch_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$purdtl = unserialize(base64_decode($_SESSION["purdtls_edit"]));
		for ($i=0;$i<count($purdtl);$i++) {
			if ($i == $did) {
				$pur = array();
				$pur["purdtl_purch_id"]		= $purch_id;
				$pur[purdtl_po_no]			= $purdtl_po_no;
				$pur["purdtl_item_code"]		= $purdtl_item_code;
				$pur[purdtl_acct_code]		= $purdtl_acct_code;
				$pur["purdtl_qty"]			= $purdtl_qty;
				$pur["purdtl_cost"]			= $purdtl_cost;
				$pur["purdtl_unit"]			= $purdtl_unit;
				array_push($arr, $pur);
			} else {
				array_push($arr, $purdtl[$i]);
			}
		}
		$purdtls_edit = base64_encode(serialize($arr));
		$_SESSION["purdtls_edit"] = $purdtls_edit;

		$loc = "Location: purch_details.php?ty=e&ht=e&did=$did&purch_id=$purch_id";
	} else {
		$arr = array();
		$purdtl = unserialize(base64_decode($_SESSION["purdtls_add"]));
		for ($i=0;$i<count($purdtl);$i++) {
			if ($i == $did) {
				$pur = array();
				$pur[purdtl_po_no]			= $purdtl_po_no;
				$pur["purdtl_item_code"]		= $purdtl_item_code;
				$pur[purdtl_acct_code]		= $purdtl_acct_code;
				$pur["purdtl_qty"]			= $purdtl_qty;
				$pur["purdtl_cost"]			= $purdtl_cost;
				$pur["purdtl_unit"]			= $purdtl_unit;
				array_push($arr, $pur);
			} else {
				array_push($arr, $purdtl[$i]);
			}
		}
		$purdtls_add = base64_encode(serialize($arr));
		$_SESSION["purdtls_add"] = $purdtls_add;
		$loc = "Location: purch_details.php?ty=e&ht=a&did=$did";
	}
	header($loc);
}
?>