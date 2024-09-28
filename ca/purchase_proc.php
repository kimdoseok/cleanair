<?php
include_once("defaults.php");
include_once("class/class.purchase.php");
include_once("class/class.purdtls.php");
include_once("class/class.picks.php");
include_once("class/class.pickdtls.php");
include_once("class/class.items.php");
include_once("class/class.vendors.php");
include_once("class/class.receipt.php");
include_once("class/class.cmemo.php");
include_once("class/class.datex.php");
include_once("class/class.purhists.php");
include_once("class/map.default.php");

$vars = array("purch_vend_contact","purch_ship_contact","purch_cust_contact",
			  "purch_cust_po","purch_shipvia","purch_slsrep","purch_fin","purch_status",
			  "purch_comnt","purch_closed","ht","purch_completed");
foreach ($vars as $var) {
	$$var = "";
} 
$vars = array("purch_amt","purch_tax_amt","purch_freight_amt");
foreach ($vars as $var) {
	$$var = 0;
} 

include_once("class/register_globals.php");


$_SESSION["purdtl_del"]=NULL;

if ($cmd == "purch_sess_add") {
	if (empty($purch_vend_code)) {
		$errno=5;
		$errmsg="Vendor code should not be blank!";
		include("error.php");
		exit;
	}
	if (empty($purch_ship_code)) {
		$errno=5;
		$errmsg="Ship code should not be blank!";
		include("error.php");
		exit;
	}
	if ($ht=="e") {
		$s = new Purchases();
		$sls = $_SESSION["purchases_edit"];
		$sls["purch_user_code"] = $purch_user_code;
		$sls["purch_vend_code"] = $purch_vend_code;
		$sls["purch_vend_name"] = $purch_vend_name;
		$sls["purch_vend_addr1"] = $purch_vend_addr1;
		$sls["purch_vend_addr2"] = $purch_vend_addr2;
		$sls["purch_vend_addr3"] = $purch_vend_addr3;
		$sls["purch_vend_city"] = $purch_vend_city;
		$sls["purch_vend_state"] = $purch_vend_state;
		$sls["purch_vend_country"] = $purch_vend_country;
		$sls["purch_vend_zip"] = $purch_vend_zip;
		$sls["purch_vend_tel"] = $purch_vend_tel;
		$sls["purch_vend_contact"] = $purch_vend_contact;
		$sls["purch_ship_code"] = $purch_ship_code;
		$sls["purch_ship_name"] = $purch_ship_name;
		$sls["purch_ship_addr1"] = $purch_ship_addr1;
		$sls["purch_ship_addr2"] = $purch_ship_addr2;
		$sls["purch_ship_addr3"] = $purch_ship_addr3;
		$sls["purch_ship_city"] = $purch_ship_city;
		$sls["purch_ship_state"] = $purch_ship_state;
		$sls["purch_ship_country"] = $purch_ship_country;
		$sls["purch_ship_zip"] = $purch_ship_zip;
		$sls["purch_ship_tel"] = $purch_ship_tel;
		$sls["purch_ship_contact"] = $purch_ship_contact;
		$sls["purch_cust_code"] = $purch_cust_code;
		$sls["purch_cust_name"] = $purch_cust_name;
		$sls["purch_cust_addr1"] = $purch_cust_addr1;
		$sls["purch_cust_addr2"] = $purch_cust_addr2;
		$sls["purch_cust_addr3"] = $purch_cust_addr3;
		$sls["purch_cust_city"] = $purch_cust_city;
		$sls["purch_cust_state"] = $purch_cust_state;
		$sls["purch_cust_country"] = $purch_cust_country;
		$sls["purch_cust_zip"] = $purch_cust_zip;
		$sls["purch_cust_tel"] = $purch_cust_tel;
		$sls["purch_cust_contact"] = $purch_cust_contact;
		$sls["purch_cust_po"] = $purch_cust_po;
		$sls["purch_amt"] = $purch_amt;
		$sls["purch_tax_amt"] = $purch_tax_amt;
		$sls["purch_freight_amt"] = $purch_freight_amt;
		$sls["purch_date"] = $purch_date;
		$sls["purch_prom_date"] = $purch_prom_date;
		$sls["purch_shipvia"] = $purch_shipvia;
		$sls["purch_slsrep"] = $purch_slsrep;
		$sls["purch_fin"] = $purch_fin;
		$sls["purch_status"] = $purch_status;
		$sls["purch_comnt"] = $purch_comnt;
		$sls["purch_closed"] = $purch_closed;
		if ($purch_custom_po!="t") $purch_custom_po = "f";
		$sls["purch_custom_po"] = $purch_custom_po;
		if ($purch_need_confirm!="t") $purch_need_confirm = "f";
		$sls["purch_need_confirm"] = $purch_need_confirm;
		if ($purch_sample_included!="t") $purch_sample_included = "f";
		$sls["purch_sample_included"] = $purch_sample_included;
		if ($purch_completed!="t") $purch_completed = "f";
		$sls["purch_completed"] = $purch_completed;
		$sls["purch_completed_date"] = $purch_completed_date;
		$_SESSION["purchases_edit"] = $sls;
		$loc = "Location: purchase_details.php?ty=a&ht=e&purch_id=$purch_id";
	} else {
		$sls = $_SESSION["purchases_add"];
		$sls["purch_user_code"] = $purch_user_code;
		$sls["purch_vend_code"] = $purch_vend_code;
		$sls["purch_vend_name"] = $purch_vend_name;
		$sls["purch_vend_addr1"] = $purch_vend_addr1;
		$sls["purch_vend_addr2"] = $purch_vend_addr2;
		$sls["purch_vend_addr3"] = $purch_vend_addr3;
		$sls["purch_vend_city"] = $purch_vend_city;
		$sls["purch_vend_state"] = $purch_vend_state;
		$sls["purch_vend_country"] = $purch_vend_country;
		$sls["purch_vend_zip"] = $purch_vend_zip;
		$sls["purch_vend_tel"] = $purch_vend_tel;
		$sls["purch_vend_contact"] = $purch_vend_contact;
		$sls["purch_ship_code"] = $purch_ship_code;
		$sls["purch_ship_name"] = $purch_ship_name;
		$sls["purch_ship_addr1"] = $purch_ship_addr1;
		$sls["purch_ship_addr2"] = $purch_ship_addr2;
		$sls["purch_ship_addr3"] = $purch_ship_addr3;
		$sls["purch_ship_city"] = $purch_ship_city;
		$sls["purch_ship_state"] = $purch_ship_state;
		$sls["purch_ship_country"] = $purch_ship_country;
		$sls["purch_ship_zip"] = $purch_ship_zip;
		$sls["purch_ship_tel"] = $purch_ship_tel;
		$sls["purch_ship_contact"] = $purch_ship_contact;
		$sls["purch_cust_code"] = $purch_cust_code;
		$sls["purch_cust_name"] = $purch_cust_name;
		$sls["purch_cust_addr1"] = $purch_cust_addr1;
		$sls["purch_cust_addr2"] = $purch_cust_addr2;
		$sls["purch_cust_addr3"] = $purch_cust_addr3;
		$sls["purch_cust_city"] = $purch_cust_city;
		$sls["purch_cust_state"] = $purch_cust_state;
		$sls["purch_cust_country"] = $purch_cust_country;
		$sls["purch_cust_zip"] = $purch_cust_zip;
		$sls["purch_cust_tel"] = $purch_cust_tel;
		$sls["purch_cust_contact"] = $purch_cust_contact;
		$sls["purch_cust_po"] = $purch_cust_po;
		$sls["purch_amt"] = $purch_amt;
		$sls["purch_tax_amt"] = $purch_tax_amt;
		$sls["purch_freight_amt"] = $purch_freight_amt;
		$sls["purch_date"] = $purch_date;
		$sls["purch_prom_date"] = $purch_prom_date;
		$sls["purch_shipvia"] = $purch_shipvia;
		$sls["purch_slsrep"] = $purch_slsrep;
		$sls["purch_fin"] = $purch_fin;
		$sls["purch_status"] = $purch_status;
		$sls["purch_comnt"] = $purch_comnt;
		$sls["purch_closed"] = $purch_closed;
		if ($purch_custom_po!="t") $purch_custom_po = "f";
		$sls["purch_custom_po"] = $purch_custom_po;
		if ($purch_need_confirm!="t") $purch_need_confirm = "f";
		$sls["purch_need_confirm"] = $purch_need_confirm;
		if ($purch_sample_included!="t") $purch_sample_included = "f";
		$sls["purch_sample_included"] = $purch_sample_included;
		if ($purch_completed!="t") $purch_completed = "f";
		$sls["purch_completed"] = $purch_completed;
		$sls["purch_completed_date"] = $purch_completed_date;
		$_SESSION["purchases_add"] = $sls;
		$loc = "Location: purchase_details.php?ty=a&ht=a";
	}
	header($loc);

} else if ($cmd == "purch_detail_sess_add") {
	if (empty($purdtl_item_code)) {
		$errno=4;
		$errmsg="Item code should not be blank!";
		include("error.php");
		exit;
	}
	if ($ht == "e") {
		$pur = $_SESSION["purchases_edit"];
		$purdtl = $_SESSION["purdtls_edit"];

		$dtl = array();
		for ($i=0;$i<count($purdtl);$i++) if (!empty($purdtl[$i])) array_push($dtl, $purdtl[$i]);
		$sls = array();
		$sls["purdtl_purch_id"]	= $purch_id;
		$sls["purdtl_item_code"]	= $purdtl_item_code;
		$sls["purdtl_item_desc"]	= $purdtl_item_desc;
		$sls["purdtl_qty"]		= $purdtl_qty;
		$sls["purdtl_qty_picked"]	= $purdtl_qty_picked;
		$sls["purdtl_qty_cancel"]	= $purdtl_qty_cancel;
		$sls["purdtl_unit"]		= $purdtl_unit;
		$sls["purdtl_cost"]		= $purdtl_cost;
		$sls["purdtl_comnt"]		= $purdtl_comnt;
		if ($_FILES["picture"]["size"]>0) {
			$filename = $_FILES["picture"]["Name"];
			$filetype = $_FILES["picture"]["Type"];
			$filesize = $_FILES["picture"]["size"];
			$tmpname = $_FILES["picture"]["tmp_name"];
			$error = $_FILES["picture"]["error"];
			move_uploaded_file($tmpname, $dirname.$filename);
		}
		$sls["purdtl_filename"]		= $filename;

		array_push($dtl, $sls);
		$_SESSION["purdtls_edit"] = $dtl;
		$loc = "Location: purchase_details.php?ty=a&ht=e&purch_id=$purch_id";
	} else {
		if (empty($purdtl_item_code)) {
			$errno=4;
			$errmsg="Item code should not be blank!";
			include("error.php");
			exit;
		} else {
			$pur = $_SESSION["purchases_add"];
			$purdtl = $_SESSION["purdtls_add"];
			$dtl = array();
			for ($i=0;$i<count($purdtl);$i++) if (!empty($purdtl[$i])) array_push($dtl, $purdtl[$i]);
			$sls = array();
			$sls["purdtl_purch_id"]	= $purch_id;
			$sls["purdtl_item_code"]	= $purdtl_item_code;
			$sls["purdtl_item_desc"]	= $purdtl_item_desc;
			$sls["purdtl_qty"]		= $purdtl_qty;
			$sls["purdtl_qty_picked"]	= $purdtl_qty_picked;
			$sls["purdtl_qty_cancel"]	= $purdtl_qty_cancel;
			$sls["purdtl_unit"]		= $purdtl_unit;
			$sls["purdtl_cost"]		= $purdtl_cost;
			$sls["purdtl_comnt"]		= $purdtl_comnt;
			if ($_FILES["picture"]["size"]>0) {
				$filename = $_FILES["picture"]["Name"];
				$filetype = $_FILES["picture"]["Type"];
				$filesize = $_FILES["picture"]["size"];
				$tmpname = $_FILES["picture"]["tmp_name"];
				$error = $_FILES["picture"]["error"];
				move_uploaded_file($tmpname, $dirname.$filename);
			}
			$sls["purdtl_filename"]		= $filename;

			array_push($dtl, $sls);
			$_SESSION["purdtls_add"] = $dtl;
			$loc = "Location: purchase_details.php?ty=a&ht=a";
		}
	}
	header($loc);

} else if ($cmd == "purch_add") {
	if (empty($purch_vend_code)) {
		$errno=5;
		$errmsg="Vendor code should not be blank!";
		include("error.php");
		exit;
	}
	if (empty($purch_ship_code)) {
		$errno=5;
		$errmsg="Ship code should not be blank!";
		include("error.php");
		exit;
	}
	$s = new Purchases();
	$d = new PurDtls();
	$t = new Items();
	$v = new Vends();

	$parr = $s->getPurchase($purch_id);

	$oldrec = $s->getTextFields("", "");
	$r = new Requests();
	if ($_POST["purch_custom_po"]!="t") $_POST["purch_custom_po"] = "f";
	if ($_POST["purch_need_confirm"]!="t") $_POST["purch_need_confirm"] = "f";
	if ($_POST["purch_sample_included"]!="t") $_POST["purch_sample_included"] = "f";
	if ($_POST["purch_completed"]!="t") $_POST["purch_completed"] = "f";
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	if ($purch_vend_serial = $v->incVendNo($purch_vend_code)) $arr["purch_vend_serial"] = $purch_vend_serial;
	$sid = $s->insertPurchase($arr);

	if (empty($purch_id)) $purch_id = $sid;
	$sdtls = $_SESSION["purdtls_add"];
	if ($sdtls) $sdtl_num = count($sdtls);
	else $sdtl_num = 0;
	$taxtotal = 0;
	$subtotal = 0;
	for ($i=0;$i<$sdtl_num;$i++) {
		$sdtls[$i]["purdtl_purch_id"] = $purch_id;
		$lastid = $d->insertPurDtls($sdtls[$i]);
		if ($lastid <= 0) {
			$errno=6;
			$errmsg="Purchase Detail Insertion Error";
			include("error.php");
			exit;
		}
		if ($sdtls[$i]["purdtl_cost"]>0) {
			$item_upd = array();
			$item_upd["item_last_cost"] = $sdtls[$i]["purdtl_cost"];
			if (!$t->updateItems($sdtls[$i]["purdtl_item_code"], $item_upd)) {
				$errno=7;
				$errmsg="Item Cost Update Error";
				include("error.php");
				exit;
			}
			$t->updateItemsAvgCost($sdtls[$i]["purdtl_item_code"]);
		}
	}

	$h = new PurHists();
	$hist_arr = array();
	$hist_arr["purhist_purch_id"]=$purch_id;
	$hist_arr["purhist_type"]="i";
	$hist_arr["purhist_user_code"]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr["purhist_modified"]=date("YmdHis");
	$h->insertPurHists($hist_arr);

	$_SESSION["purchases_add"]=NULL;
	$_SESSION["purdtls_add"]=NULL;
	$loc = "Location: purchase.php?ty=e&purch_id=$purch_id";
	header($loc);

} else if ($cmd == "purch_edit") {
	if (empty($purch_vend_code)) {
		$errno=5;
		$errmsg="Vendor code should not be blank!";
		include("error.php");
		exit;
	}
	if (empty($purch_ship_code)) {
		$errno=5;
		$errmsg="Ship code should not be blank!";
		include("error.php");
		exit;
	}
	$s = new Purchases();
	$d = new PurDtls();
	$sarr = $s->getPurchase($purch_id);
	if (!$sarr) {
		$errno=4;
		$errmsg="Purchase id is not in database";
		include("error.php");
		exit;
	}
	$oldrec = $s->getTextFields("", "$purch_id");
	$r = new Requests();
	if ($_POST["purch_custom_po"]!="t") $_POST["purch_custom_po"] = "f";
	if ($_POST["purch_need_confirm"]!="t") $_POST["purch_need_confirm"] = "f";
	if ($_POST["purch_sample_included"]!="t") $_POST["purch_sample_included"] = "f";
	if ($_POST["purch_completed"]!="t") $_POST["purch_completed"] = "f";
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	if (count($arr)>0) $s->updatePurchase($purch_id, $arr);
	//$d->deletePurDtlsSI($purch_id);
	$sdtls = $_SESSION["purdtls_edit"];
	$sdtl_num = count($sdtls);
	$amt = 0;
	$sd_arr = $d->getPurDtlsHdrs($purch_id);

	if (!empty($sd_arr)) $sd_num = count($sd_arr);
	else $sd_num = 0;

	for ($i=0;$i<$sdtl_num;$i++) {

		$sdtls[$i]["purdtl_purch_id"] = $purch_id;
		$amt += $sdtls["purdtl_cost"];
		$found = 0;
		for ($j=0;$j<$sd_num;$j++) {
			if ($sd_arr[$j]["purdtl_id"] == $sdtls[$i]["purdtl_id"]) {
				$found = 1;
				break;
			}
		}

		if ($found>0) {
			$udt_res = $d->updatePurDtls($sdtls[$i]["purdtl_id"], $sdtls[$i]);
			if ($udt_res== false) {
				$errno=6;
				$errmsg="Purchase Detail Updating Error";
				include("error.php");
				exit;
			}
		} else {
			unset($sdtls[$i]["purdtl_id"]);
			if (!empty($sdtls[$i]["purdtl_item_code"]) && !$d->insertPurDtls($sdtls[$i])) {
				$errno=7;
				$errmsg="Purchase Detail Inserting Error";
				include("error.php");
				exit;
			}
		}
	}

	for ($i=0;$i<$sd_num;$i++) {
		$found = 0;
		for ($j=0;$j<$sdtl_num;$j++) {
			if ($sd_arr[$i]["purdtl_id"] == $sdtls[$j]["purdtl_id"]) {
				$found = 1;
				break;
			}
		}
		if ($found == 0) {
			if (!$d->deletePurDtls($sd_arr[$i]["purdtl_id"])) {
				$errno=8;
				$errmsg="Purchase Detail deleting Error";
				include("error.php");
				exit;
			}
		} else if (!$sd_arr[$i]["purdtl_item_code"]) {
			if (!$d->deletePurDtls($sd_arr[$i]["purdtl_id"])) {
				$errno=8;
				$errmsg="Purchase Detail deleting Error";
				include("error.php");
				exit;
			}
		}
	}

	$h = new PurHists();
	$hist_arr = array();
	$hist_arr["purhist_purch_id"]=$purch_id;
	$hist_arr["purhist_type"]="u";
	$hist_arr["purhist_user_code"]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr["purhist_modified"]=date("YmdHis");
	$h->insertPurHists($hist_arr);

	$_SESSION["purchases_edit"]=NULL;
	$_SESSION["purdtls_edit"]=NULL;
	$_SESSION["purdtl_del"] = 0;

	$loc = "Location: purchase.php?ty=e&purch_id=$purch_id";
	header($loc);

} else if ($cmd == "purch_del") {
	$s = new Purchases();
	$d = new PurDtls();
	$s->deletePurchase($purch_id);
	$d->deletePurDtlsHdr($purch_id);
//	$j = new JrnlTrxs();
//	$j->deleteJrnlTrxRefs($purch_id, "r");

	$h = new PurHists();
	$hist_arr = array();
	$hist_arr["purhist_purch_id"]=$purch_id;
	$hist_arr["purhist_type"]="d";
	$hist_arr["purhist_user_code"]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr["purhist_modified"]=date("YmdHis");
	$h->insertPurHists($hist_arr);

	$_SESSION["purchases_edit"]=NULL;
	$_SESSION["purdtls_edit"]=NULL;
	$_SESSION["purdtl_del"] = 1;
	$loc = "Location: purchase.php?ty=l";
	header($loc);

} else if ($cmd == "purch_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$purdtl = $_SESSION["purdtls_edit"];
		for ($i=0;$i<count($purdtl);$i++) if ($i != $did) array_push($arr, $purdtl[$i]);
		$_SESSION["purdtls_edit"] = $arr;
		$_SESSION["purdtl_del"] = 1;
		$loc = "Location: purchase.php?ty=e&purch_id=$purch_id";
	} else {
		$arr = array();
		$purdtl = $_SESSION["purdtls_add"];
		for ($i=0;$i<count($purdtl);$i++) if ($i != $did) array_push($arr, $purdtl[$i]);
		$_SESSION["purdtls_add"] = $arr;
		$loc = "Location: purchase.php?ty=a";
	}
	header($loc);

} else if ($cmd == "purch_clear_sess_edit") {
	$_SESSION["purchases_edit"]=NULL;
	$_SESSION["purdtls_edit"]=NULL;
	$loc = "Location: purchase.php?ty=e&purch_id=$purch_id";
	header($loc);

} else if ($cmd == "purch_clear_sess_add") {
	$_SESSION["purchases_add"]=NULL;
	$_SESSION["purdtls_add"]=NULL;
	$loc = "Location: purchase.php?ty=a";
	header($loc);

} else if ($cmd == "purch_update_sess_add") {
	if ($ty=="e") {
		$s = new Purchases();
		$sls = $_SESSION["purchases_edit"];
		$sls["purch_user_code"] = $purch_user_code;
		$sls["purch_vend_code"] = $purch_vend_code;
		$sls["purch_vend_name"] = $purch_vend_name;
		$sls["purch_vend_addr1"] = $purch_vend_addr1;
		$sls["purch_vend_addr2"] = $purch_vend_addr2;
		$sls["purch_vend_addr3"] = $purch_vend_addr3;
		$sls["purch_vend_city"] = $purch_vend_city;
		$sls["purch_vend_state"] = $purch_vend_state;
		$sls["purch_vend_country"] = $purch_vend_country;
		$sls["purch_vend_zip"] = $purch_vend_zip;
		$sls["purch_vend_tel"] = $purch_vend_tel;
		$sls["purch_vend_contact"] = $purch_vend_contact;
		$sls["purch_ship_code"] = $purch_ship_code;
		$sls["purch_ship_name"] = $purch_ship_name;
		$sls["purch_ship_addr1"] = $purch_ship_addr1;
		$sls["purch_ship_addr2"] = $purch_ship_addr2;
		$sls["purch_ship_addr3"] = $purch_ship_addr3;
		$sls["purch_ship_city"] = $purch_ship_city;
		$sls["purch_ship_state"] = $purch_ship_state;
		$sls["purch_ship_country"] = $purch_ship_country;
		$sls["purch_ship_zip"] = $purch_ship_zip;
		$sls["purch_ship_tel"] = $purch_ship_tel;
		$sls["purch_ship_contact"] = $purch_ship_contact;
		$sls["purch_cust_code"] = $purch_cust_code;
		$sls["purch_cust_name"] = $purch_cust_name;
		$sls["purch_cust_addr1"] = $purch_cust_addr1;
		$sls["purch_cust_addr2"] = $purch_cust_addr2;
		$sls["purch_cust_addr3"] = $purch_cust_addr3;
		$sls["purch_cust_city"] = $purch_cust_city;
		$sls["purch_cust_state"] = $purch_cust_state;
		$sls["purch_cust_country"] = $purch_cust_country;
		$sls["purch_cust_zip"] = $purch_cust_zip;
		$sls["purch_cust_tel"] = $purch_cust_tel;
		$sls["purch_cust_contact"] = $purch_cust_contact;
		$sls["purch_cust_po"] = $purch_cust_po;
		$sls["purch_amt"] = $purch_amt;
		$sls["purch_tax_amt"] = $purch_tax_amt;
		$sls["purch_freight_amt"] = $purch_freight_amt;
		$sls["purch_date"] = $purch_date;
		$sls["purch_prom_date"] = $purch_prom_date;
		$sls["purch_shipvia"] = $purch_shipvia;
		$sls["purch_slsrep"] = $purch_slsrep;
		$sls["purch_fin"] = $purch_fin;
		$sls["purch_status"] = $purch_status;
		$sls["purch_comnt"] = $purch_comnt;
		$sls["purch_closed"] = $purch_closed;
		if ($purch_custom_po!="t") $purch_custom_po = "f";
		$sls["purch_custom_po"] = $purch_custom_po;
		if ($purch_need_confirm!="t") $purch_need_confirm = "f";
		$sls["purch_need_confirm"] = $purch_need_confirm;
		if ($purch_sample_included!="t") $purch_sample_included = "f";
		$sls["purch_sample_included"] = $purch_sample_included;
		if ($purch_completed!="t") $purch_completed = "f";
		$sls["purch_completed"] = $purch_completed;
		$sls["purch_completed_date"] = $purch_completed_date;
		$_SESSION["purchases_edit"] = $sls;

		$loc = "Location: purchase.php?ty=e&purch_id=$purch_id";
	} else {
		$s = new Purchases();
		if ($arr = $s->getPurchase($purch_id)) {
			$errno=3;
			$errmsg="Purchase number is already in database";
			include("error.php");
			exit;
		} else {
			$sls = $_SESSION["purchases_add"];
			$sls["purch_user_code"] = $purch_user_code;
			$sls["purch_vend_code"] = $purch_vend_code;
			$sls["purch_vend_name"] = $purch_vend_name;
			$sls["purch_vend_addr1"] = $purch_vend_addr1;
			$sls["purch_vend_addr2"] = $purch_vend_addr2;
			$sls["purch_vend_addr3"] = $purch_vend_addr3;
			$sls["purch_vend_city"] = $purch_vend_city;
			$sls["purch_vend_state"] = $purch_vend_state;
			$sls["purch_vend_country"] = $purch_vend_country;
			$sls["purch_vend_zip"] = $purch_vend_zip;
			$sls["purch_vend_tel"] = $purch_vend_tel;
			$sls["purch_vend_contact"] = $purch_vend_contact;
			$sls["purch_ship_code"] = $purch_ship_code;
			$sls["purch_ship_name"] = $purch_ship_name;
			$sls["purch_ship_addr1"] = $purch_ship_addr1;
			$sls["purch_ship_addr2"] = $purch_ship_addr2;
			$sls["purch_ship_addr3"] = $purch_ship_addr3;
			$sls["purch_ship_city"] = $purch_ship_city;
			$sls["purch_ship_state"] = $purch_ship_state;
			$sls["purch_ship_country"] = $purch_ship_country;
			$sls["purch_ship_zip"] = $purch_ship_zip;
			$sls["purch_ship_tel"] = $purch_ship_tel;
			$sls["purch_ship_contact"] = $purch_ship_contact;
			$sls["purch_cust_code"] = $purch_cust_code;
			$sls["purch_cust_name"] = $purch_cust_name;
			$sls["purch_cust_addr1"] = $purch_cust_addr1;
			$sls["purch_cust_addr2"] = $purch_cust_addr2;
			$sls["purch_cust_addr3"] = $purch_cust_addr3;
			$sls["purch_cust_city"] = $purch_cust_city;
			$sls["purch_cust_state"] = $purch_cust_state;
			$sls["purch_cust_country"] = $purch_cust_country;
			$sls["purch_cust_zip"] = $purch_cust_zip;
			$sls["purch_cust_tel"] = $purch_cust_tel;
			$sls["purch_cust_contact"] = $purch_cust_contact;
			$sls["purch_cust_po"] = $purch_cust_po;
			$sls["purch_amt"] = $purch_amt;
			$sls["purch_tax_amt"] = $purch_tax_amt;
			$sls["purch_freight_amt"] = $purch_freight_amt;
			$sls["purch_date"] = $purch_date;
			$sls["purch_prom_date"] = $purch_prom_date;
			$sls["purch_shipvia"] = $purch_shipvia;
			$sls["purch_slsrep"] = $purch_slsrep;
			$sls["purch_fin"] = $purch_fin;
			$sls["purch_status"] = $purch_status;
			$sls["purch_comnt"] = $purch_comnt;
			$sls["purch_closed"] = $purch_closed;
			if ($purch_custom_po!="t") $purch_custom_po = "f";
			$sls["purch_custom_po"] = $purch_custom_po;
			if ($purch_need_confirm!="t") $purch_need_confirm = "f";
			$sls["purch_need_confirm"] = $purch_need_confirm;
			if ($purch_sample_included!="t") $purch_sample_included = "f";
			$sls["purch_sample_included"] = $purch_sample_included;
			if ($purch_completed!="t") $purch_completed = "f";
			$sls["purch_completed"] = $purch_completed;
			$sls["purch_completed_date"] = $purch_completed_date;
			$_SESSION["purchases_add"] = $sls;
			$loc = "Location: purchase.php?ty=a";
		}
	}
	header($loc);

} else if ($cmd == "purch_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$purdtl = $_SESSION["purdtls_edit"];
		for ($i=0;$i<count($purdtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls["purdtl_purch_id"]	= $purch_id;
				$sls["purdtl_item_code"]	= $purdtl_item_code;
				$sls["purdtl_item_desc"]	= $purdtl_item_desc;
				$sls["purdtl_qty"]		= $purdtl_qty;
				$sls["purdtl_qty_picked"]	= $purdtl_qty_picked;
				$sls["purdtl_qty_cancel"]	= $purdtl_qty_cancel;
				$sls["purdtl_unit"]		= $purdtl_unit;
				$sls["purdtl_cost"]		= $purdtl_cost;
				$sls["purdtl_comnt"]		= $purdtl_comnt;
				$sls["purdtl_filename"]		= $purdtl[$i]["purdtl_filename"];
				if ($keepic != "t") {
					if ($_FILES["picture"]["size"]>0) {
						$filename = $_FILES["picture"]["Name"];
						$filetype = $_FILES["picture"]["Type"];
						$filesize = $_FILES["picture"]["size"];
						$tmpname = $_FILES["picture"]["tmp_name"];
						$error = $_FILES["picture"]["error"];
						move_uploaded_file($tmpname, $dirname.$filename);
						$sls["purdtl_filename"]		= $filename;
					}
				}
				array_push($arr, $sls);
			} else {
				array_push($arr, $purdtl[$i]);
			}
		}
		$_SESSION["purdtls_edit"] = $arr;
		$loc = "Location: purchase_details.php?ty=e&ht=e&purch_id=$purch_id&did=$did";

	} else {
		$arr = array();
		$purdtl = $_SESSION["purdtls_add"];
		for ($i=0;$i<count($purdtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls["purdtl_purch_id"]	= $purch_id;
				$sls["purdtl_item_code"]	= $purdtl_item_code;
				$sls["purdtl_item_desc"]	= $purdtl_item_desc;
				$sls["purdtl_qty"]		= $purdtl_qty;
				$sls["purdtl_qty_picked"]	= $purdtl_qty_picked;
				$sls["purdtl_qty_cancel"]	= $purdtl_qty_cancel;
				$sls["purdtl_unit"]		= $purdtl_unit;
				$sls["purdtl_cost"]		= $purdtl_cost;
				$sls["purdtl_comnt"]		= $purdtl_comnt;
				$sls["purdtl_filename"]		= $purdtl[$i]["purdtl_filename"];
				if ($keepic != "t") {
					if ($_FILES["picture"]["size"]>0) {
						$filename = $_FILES["picture"]["Name"];
						$filetype = $_FILES["picture"]["Type"];
						$filesize = $_FILES["picture"]["size"];
						$tmpname = $_FILES["picture"]["tmp_name"];
						$error = $_FILES["picture"]["error"];
						move_uploaded_file($tmpname, $dirname.$filename);
					}
					$sls["purdtl_filename"]		= $filename;
				}

				array_push($arr, $sls);
			} else {
				array_push($arr, $purdtl[$i]);
			}
		}
		$_SESSION["purdtls_add"] = $arr;
		$loc = "Location: purchase_details.php?ty=e&ht=a&did=$did";
	}
	header($loc);
}

?>