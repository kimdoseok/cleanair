<?php
include_once("defaults.php");
include_once("class/class.porcpts.php");
include_once("class/class.porcptdtls.php");
include_once("class/class.items.php");
include_once("class/class.vendors.php");
include_once("class/class.cmemo.php");
include_once("class/class.datex.php");
include_once("class/class.requests.php");
include_once("class/class.porhists.php");
include_once("class/map.default.php");
include_once("class/register_globals.php");


$porcptdtl_del = $default["comp_code"]."_porcptdtl_del";
$porcptdtl_edit = $default["comp_code"]."_porcptdtl_edit";
$porcptdtl_add = $default["comp_code"]."_porcptdtl_add";
$porcpt_edit = $default["comp_code"]."_porcpt_edit";
$porcpt_add = $default["comp_code"]."_porcpt_add";
$_SESSION[$porcptdtl_del]=NULL;

if ($cmd == "porcpt_sess_add") {
	if (empty($porcpt_vend_code)) {
		$errno=5;
		$errmsg="Vendor code should not be blank!";
		include("error.php");
		exit;
	}
	if ($ht=="e") {
		$s = new PoRcpts();
		$sls = $_SESSION[$porcpt_edit];
		$sls[porcpt_user_code] = $porcpt_user_code;
		$sls["porcpt_vend_code"] = $porcpt_vend_code;
		$sls[porcpt_vend_inv] = $porcpt_vend_inv;
		$sls[porcpt_ponum] = $porcpt_ponum;
		$sls["porcpt_amt"] = $porcpt_amt;
		$sls["porcpt_date"] = $porcpt_date;
		$sls["porcpt_tax_amt"] = $porcpt_tax_amt;
		$sls["porcpt_freight_amt"] = $porcpt_freight_amt;
		$sls[porcpt_shipvia] = $porcpt_shipvia;
		$sls[porcpt_fin] = $porcpt_fin;
		$sls[porcpt_comnt] = $porcpt_comnt;
		$_SESSION[$porcpt_edit] = $sls;
		$loc = "Location: poreceipt_details.php?ty=a&ht=e&porcpt_id=$porcpt_id";
	} else {
		$sls = $_SESSION[$porcpt_add];
		$sls[porcpt_user_code] = $porcpt_user_code;
		$sls["porcpt_vend_code"] = $porcpt_vend_code;
		$sls[porcpt_vend_inv] = $porcpt_vend_inv;
		$sls[porcpt_ponum] = $porcpt_ponum;
		$sls["porcpt_amt"] = $porcpt_amt;
		$sls["porcpt_date"] = $porcpt_date;
		$sls["porcpt_tax_amt"] = $porcpt_tax_amt;
		$sls["porcpt_freight_amt"] = $porcpt_freight_amt;
		$sls[porcpt_shipvia] = $porcpt_shipvia;
		$sls[porcpt_fin] = $porcpt_fin;
		$sls[porcpt_comnt] = $porcpt_comnt;
		$_SESSION[$porcpt_add] = $sls;
		$loc = "Location: poreceipt_details.php?ty=a&ht=a";
	}
	header($loc);

} else if ($cmd == "porcpt_detail_sess_add") {
	if (empty($porcptdtl_item_code)) {
		$errno=4;
		$errmsg="Item code should not be blank!";
		include("error.php");
		exit;
	}
	if ($ht == "e") {
		$pur = $_SESSION[$porcpt_edit];
		$purdtl = $_SESSION[$porcptdtl_edit];

		$dtl = array();
		for ($i=0;$i<count($purdtl);$i++) if (!empty($purdtl[$i])) array_push($dtl, $purdtl[$i]);
		$sls = array();
		$sls[porcptdtl_porcpt_id]	= $porcpt_id;
		$sls["porcptdtl_item_code"]	= $porcptdtl_item_code;
		$sls["porcptdtl_item_desc"]	= $porcptdtl_item_desc;
		$sls["porcptdtl_qty"]			= $porcptdtl_qty;
		$sls[porcptdtl_unit]		= $porcptdtl_unit;
		$sls["porcptdtl_cost"]		= $porcptdtl_cost;
		array_push($dtl, $sls);
		$_SESSION[$porcptdtl_edit] = $dtl;
		$loc = "Location: poreceipt_details.php?ty=a&ht=e&porcpt_id=$porcpt_id";
	} else {
		if (empty($porcptdtl_item_code)) {
			$errno=4;
			$errmsg="Item code should not be blank!";
			include("error.php");
			exit;
		} else {
			$pur = $_SESSION[$porcpt_add];
			$purdtl = $_SESSION[$porcptdtl_add];
			$dtl = array();
			for ($i=0;$i<count($purdtl);$i++) if (!empty($purdtl[$i])) array_push($dtl, $purdtl[$i]);
			$sls = array();
			$sls[porcptdtl_porcpt_id]	= $porcpt_id;
			$sls["porcptdtl_item_code"]	= $porcptdtl_item_code;
			$sls["porcptdtl_item_desc"]	= $porcptdtl_item_desc;
			$sls["porcptdtl_qty"]			= $porcptdtl_qty;
			$sls[porcptdtl_unit]		= $porcptdtl_unit;
			$sls["porcptdtl_cost"]		= $porcptdtl_cost;
			$sls[porcptdtl_comnt]		= $porcptdtl_comnt;

			array_push($dtl, $sls);
			$_SESSION[$porcptdtl_add] = $dtl;
			$loc = "Location: poreceipt_details.php?ty=a&ht=a";
		}
	}
	header($loc);
	exit;

} else if ($cmd == "porcpt_add") {
	if (empty($porcpt_vend_code)) {
		$errno=5;
		$errmsg="Vendor code should not be blank!";
		include("error.php");
		exit;
	}
	$s = new PoRcpts();
	$d = new PoRcptDtls();
	$t = new Items();
	$v = new Vends();

	$parr = $s->getPoRcpts($porcpt_id);

	$oldrec = $s->getTextFields("", "");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$sid = $s->insertPoRcpts($arr);

	if (empty($porcpt_id)) $porcpt_id = $sid;
	$sdtls = $_SESSION[$porcptdtl_add];
	if ($sdtls) $sdtl_num = count($sdtls);
	else $sdtl_num = 0;
	$taxtotal = 0;
	$subtotal = 0;
	for ($i=0;$i<$sdtl_num;$i++) {
		$sdtls[$i][porcptdtl_porcpt_id] = $porcpt_id;
		$lastid = $d->insertPoRcptDtls($sdtls[$i]);
		if ($lastid <= 0) {
			$errno=6;
			$errmsg="PO Receipt Detail Insertion Error";
			include("error.php");
			exit;
		}
		if ($sdtls[$i]["porcptdtl_cost"]>0) {
			if (!$t->updateItemsQtyCost($sdtls[$i]["porcptdtl_item_code"], $sdtls[$i]["porcptdtl_cost"], 0, $sdtls[$i]["porcptdtl_qty"])) {
				$errno=7;
				$errmsg="Item Cost Update Error";
				include("error.php");
				exit;
			}
		}
	}

	$h = new PorHists();
	$hist_arr = array();
	$hist_arr[porhist_porcpt_id]=$porcpt_id;
	$hist_arr[porhist_type]="i";
	$hist_arr[porhist_user_code]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr[porhist_modified]=date("YmdHis");
	$h->insertPorHists($hist_arr);
	$_SESSION[$porcpt_add]=NULL;
	$_SESSION[$porcptdtl_add]=NULL;
	$loc = "Location: poreceipt.php?ty=e&porcpt_id=$porcpt_id";
	header($loc);
	exit;

} else if ($cmd == "porcpt_edit") {
	if (empty($porcpt_vend_code)) {
		$errno=5;
		$errmsg="Vendor code should not be blank!";
		include("error.php");
		exit;
	}
	$s = new PoRcpts();
	$d = new PoRcptDtls();
	$t = new Items();
	$sarr = $s->getPoRcpts($porcpt_id);
	if (!$sarr) {
		$errno=4;
		$errmsg="PO Receipt id is not in database";
		include("error.php");
		exit;
	}
	$oldrec = $s->getTextFields("", "$porcpt_id");
	$r = new Requests();
	if ($_POST[porcpt_custom_po]!="t") $_POST[porcpt_custom_po] = "f";
	if ($_POST[porcpt_need_confirm]!="t") $_POST[porcpt_need_confirm] = "f";
	if ($_POST[porcpt_sample_included]!="t") $_POST[porcpt_sample_included] = "f";
	if ($_POST["porcpt_completed"]!="t") $_POST["porcpt_completed"] = "f";
	$arr = $r->getAlteredArray($oldrec, $_POST); 

	if (count($arr)>0) $s->updatePoRcpts($porcpt_id, $arr);
	//$d->deletePoRcptDtlsSI($porcpt_id);
	$sdtls = $_SESSION[$porcptdtl_edit];
	$sdtl_num = count($sdtls);
	$amt = 0;
	$sd_arr = $d->getPoRcptDtlsHdrs($porcpt_id);

	if (!empty($sd_arr)) $sd_num = count($sd_arr);
	else $sd_num = 0;

	for ($i=0;$i<$sdtl_num;$i++) {

		$sdtls[$i][porcptdtl_porcpt_id] = $porcpt_id;
		$amt += $sdtls["porcptdtl_cost"];
		$found = 0;
		for ($j=0;$j<$sd_num;$j++) {
			if ($sd_arr[$j][porcptdtl_id] == $sdtls[$i][porcptdtl_id]) {
				$oldqty = $sd_arr[$j]["porcptdtl_qty"];
				$found = 1;
				break;
			}
		}

		if ($found>0) {
			if ($d->updatePoRcptDtls($sdtls[$i][porcptdtl_id], $sdtls[$i])) {
				if (!$t->updateItemsQtyCost($sdtls[$i]["porcptdtl_item_code"], $sdtls[$i]["porcptdtl_cost"], $oldqty, $sdtls[$i]["porcptdtl_qty"])) {
					$errno=7;
					$errmsg="Item Cost Update Error";
					include("error.php");
					exit;
				}
			} else {
				$errno=6;
				$errmsg="PO Receipt Detail Updating Error";
				include("error.php");
				exit;
			}
		} else {
			unset($sdtls[$i][porcptdtl_id]);
			if (!empty($sdtls[$i]["porcptdtl_item_code"]) && $d->insertPoRcptDtls($sdtls[$i])) {
				if (!$t->updateItemsQtyCost($sdtls[$i]["porcptdtl_item_code"], $sdtls[$i]["porcptdtl_cost"], 0, $sdtls[$i]["porcptdtl_qty"])) {
					$errno=7;
					$errmsg="Item Cost Update Error";
					include("error.php");
					exit;
				}
			} else {
				$errno=7;
				$errmsg="PO Receipt Detail Inserting Error";
				include("error.php");
				exit;
			}
		}
	}

	for ($i=0;$i<$sd_num;$i++) {
		$found = 0;
		for ($j=0;$j<$sdtl_num;$j++) {
			if ($sd_arr[$i][porcptdtl_id] == $sdtls[$j][porcptdtl_id]) {
				$found = 1;
				break;
			}
		}
		if ($found == 0) {
			if ($d->deletePoRcptDtls($sd_arr[$i][porcptdtl_id])) {
				if (!$t->updateItemsQtyCost($sd_arr[$i]["porcptdtl_item_code"], $sd_arr[$i]["porcptdtl_cost"], $sd_arr[$i]["porcptdtl_qty"], 0)) {
					$errno=7;
					$errmsg="Item Cost Update Error";
					include("error.php");
					exit;
				}
			} else {
				$errno=8;
				$errmsg="PO Receipt Detail deleting Error";
				include("error.php");
				exit;
			}
		} else if (!$sd_arr[$i]["porcptdtl_item_code"]) {
			if ($d->deletePoRcptDtls($sd_arr[$i][porcptdtl_id])) {
				if (!$t->updateItemsQtyCost($sd_arr[$i]["porcptdtl_item_code"], $sd_arr[$i]["porcptdtl_cost"], $sd_arr[$i]["porcptdtl_qty"], 0)) {
					$errno=7;
					$errmsg="Item Cost Update Error";
					include("error.php");
					exit;
				}
			} else {
				$errno=8;
				$errmsg="PO Receipt Detail deleting Error";
				include("error.php");
				exit;
			}
		}
	}

	$h = new PorHists();
	$hist_arr = array();
	$hist_arr[porhist_porcpt_id]=$porcpt_id;
	$hist_arr[porhist_type]="u";
	$hist_arr[porhist_user_code]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr[porhist_modified]=date("YmdHis");
	$h->insertPorHists($hist_arr);

	$_SESSION[$porcpt_edit]=NULL;
	$_SESSION[$porcptdtl_edit]=NULL;
	$_SESSION[$porcptdtl_del] = 0;

	$loc = "Location: poreceipt.php?ty=e&porcpt_id=$porcpt_id";
	header($loc);

} else if ($cmd == "porcpt_del") {
	$s = new PoRcpts();
	$d = new PoRcptDtls();
	$t = new Items();
	$s->deletePoRcpts($porcpt_id);
//	$d->deletePoRcptDtlsHdr($porcpt_id);
	$sd_arr = $d->getPoRcptDtlsHdrs($porcpt_id);
	if ($sd_arr) $sd_num = count($sd_arr);
	else $sd_num = 0;
	for ($i=0;$i<$sd_num;$i++) {
		if ($d->deletePoRcptDtls($sd_arr[$i][porcptdtl_id])) {
			if (!$t->updateItemsQtyCost($sd_arr[$i]["porcptdtl_item_code"], $sd_arr[$i]["porcptdtl_cost"], $sd_arr[$i]["porcptdtl_qty"], 0)) {
				$errno=7;
				$errmsg="Item Cost Update Error";
				include("error.php");
				exit;
			}
		} else {
			$errno=8;
			$errmsg="PO Receipt Detail deleting Error";
			include("error.php");
			exit;
		}
	}
//	$j = new JrnlTrxs();
//	$j->deleteJrnlTrxRefs($porcpt_id, "r");

	$h = new PorHists();
	$hist_arr = array();
	$hist_arr[porhist_porcpt_id]=$porcpt_id;
	$hist_arr[porhist_type]="d";
	$hist_arr[porhist_user_code]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr[porhist_modified]=date("YmdHis");
	$h->insertPorHists($hist_arr);

	$_SESSION[$porcpt_edit]=NULL;
	$_SESSION[$porcptdtl_edit]=NULL;
	$_SESSION[$porcptdtl_del] = 1;
	$loc = "Location: poreceipt.php?ty=l";
	header($loc);
	exit;

} else if ($cmd == "porcpt_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$purdtl = $_SESSION[$porcptdtl_edit];
		for ($i=0;$i<count($purdtl);$i++) if ($i != $did) array_push($arr, $purdtl[$i]);
		$_SESSION[$porcptdtl_edit] = $arr;
		$_SESSION[$porcptdtl_del] = 1;
		$loc = "Location: poreceipt.php?ty=e&porcpt_id=$porcpt_id";
	} else {
		$arr = array();
		$purdtl = $_SESSION[$porcptdtl_add];
		for ($i=0;$i<count($purdtl);$i++) if ($i != $did) array_push($arr, $purdtl[$i]);
		$_SESSION[$porcptdtl_add] = $arr;
		$loc = "Location: poreceipt.php?ty=a";
	}
	header($loc);

} else if ($cmd == "porcpt_clear_sess_edit") {
	$_SESSION[$porcpt_edit]=NULL;
	$_SESSION[$porcptdtl_edit]=NULL;
	$loc = "Location: poreceipt.php?ty=e&porcpt_id=$porcpt_id";
	header($loc);

} else if ($cmd == "porcpt_clear_sess_add") {
	$_SESSION[$porcpt_add]=NULL;
	$_SESSION[$porcptdtl_add]=NULL;
	$loc = "Location: poreceipt.php?ty=a";
	header($loc);

} else if ($cmd == "porcpt_update_sess_add") {
	if ($ty=="e") {
		$s = new PoRcpts();
		$sls = $_SESSION[$porcpt_edit];
		$sls[porcpt_user_code] = $porcpt_user_code;
		$sls["porcpt_vend_code"] = $porcpt_vend_code;
		$sls[porcpt_vend_inv] = $porcpt_vend_inv;
		$sls[porcpt_ponum] = $porcpt_ponum;
		$sls["porcpt_amt"] = $porcpt_amt;
		$sls["porcpt_date"] = $porcpt_date;
		$sls["porcpt_tax_amt"] = $porcpt_tax_amt;
		$sls["porcpt_freight_amt"] = $porcpt_freight_amt;
		$sls[porcpt_shipvia] = $porcpt_shipvia;
		$sls[porcpt_fin] = $porcpt_fin;
		$sls[porcpt_comnt] = $porcpt_comnt;
		$_SESSION[$porcpt_edit] = $sls;

		$loc = "Location: poreceipt.php?ty=e&porcpt_id=$porcpt_id";
	} else {
		$s = new PoRcpts();
		if ($arr = $s->getPoRcpts($porcpt_id)) {
			$errno=3;
			$errmsg="PoRcpt number is already in database";
			include("error.php");
			exit;
		} else {
			$sls = $_SESSION[$porcpt_add];
			$sls[porcpt_user_code] = $porcpt_user_code;
			$sls["porcpt_vend_code"] = $porcpt_vend_code;
			$sls[porcpt_vend_inv] = $porcpt_vend_inv;
			$sls[porcpt_ponum] = $porcpt_ponum;
			$sls["porcpt_amt"] = $porcpt_amt;
			$sls["porcpt_date"] = $porcpt_date;
			$sls["porcpt_tax_amt"] = $porcpt_tax_amt;
			$sls["porcpt_freight_amt"] = $porcpt_freight_amt;
			$sls[porcpt_shipvia] = $porcpt_shipvia;
			$sls[porcpt_fin] = $porcpt_fin;
			$sls[porcpt_comnt] = $porcpt_comnt;
			$_SESSION[$porcpt_add] = $sls;
			$loc = "Location: poreceipt.php?ty=a";
		}
	}
	header($loc);

} else if ($cmd == "porcpt_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$purdtl = $_SESSION[$porcptdtl_edit];
		for ($i=0;$i<count($purdtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls[porcptdtl_porcpt_id]	= $porcpt_id;
				$sls["porcptdtl_item_code"]	= $porcptdtl_item_code;
				$sls["porcptdtl_qty"]			= $porcptdtl_qty;
				$sls[porcptdtl_unit]		= $porcptdtl_unit;
				$sls["porcptdtl_cost"]		= $porcptdtl_cost;
				$sls[porcptdtl_comnt]		= $porcptdtl_comnt;
				array_push($arr, $sls);
			} else {
				array_push($arr, $purdtl[$i]);
			}
		}
		$_SESSION[$porcptdtl_edit] = $arr;
		$loc = "Location: poreceipt_details.php?ty=e&ht=e&porcpt_id=$porcpt_id&did=$did";

	} else {
		$arr = array();
		$purdtl = $_SESSION[$porcptdtl_add];
		for ($i=0;$i<count($purdtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls[porcptdtl_porcpt_id]	= $porcpt_id;
				$sls["porcptdtl_item_code"]	= $porcptdtl_item_code;
				$sls["porcptdtl_qty"]			= $porcptdtl_qty;
				$sls[porcptdtl_unit]		= $porcptdtl_unit;
				$sls["porcptdtl_cost"]		= $porcptdtl_cost;
				$sls[porcptdtl_comnt]		= $porcptdtl_comnt;
				array_push($arr, $sls);
			} else {
				array_push($arr, $purdtl[$i]);
			}
		}
		$_SESSION[$porcptdtl_add] = $arr;
		$loc = "Location: poreceipt_details.php?ty=e&ht=a&did=$did";
	}
	header($loc);
}

?>