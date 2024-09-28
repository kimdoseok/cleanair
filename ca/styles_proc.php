<?php
include_once("class/map.default.php");
include_once("class/class.items.php");
include_once("class/class.itemtrxs.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.requests.php");
include_once("class/class.styles.php");
include_once("class/class.styldtls.php");
include_once("class/register_globals.php");

if ($cmd == "style_sess_add") {
	if ($ht=="e") {
		$s = new Styles();
		$styl = unserialize(base64_decode($_SESSION[styles_edit]));
		$styl[styl_cost_usd] = $styl_cost_usd;
		$styl[styl_cost_rmb] = $styl_cost_rmb;
		$styl[styl_unit] = $styl_unit;
		$styl[styl_date] = $styl_date ;
		$styl[styl_desc] = $styl_desc;
		$styl[styl_onbrd_date] = $styl_onbrd_date;
		$styl[styl_status] = $styl_status;
		$styl[styl_qty_work] = $styl_qty_work;
		$styl[styl_cust_code] = $styl_cust_code;
		$styl[styl_qty_board] = $styl_qty_board;
		$styl[styl_po_no] = $styl_po_no;
		$styles_edit = base64_encode(serialize($styl));
		$_SESSION["styles_edit"] = $styles_edit;

		$loc = "Location: style_details.php?ty=a&ht=$ht&styl_code=$styl_code";
	} else {
		include_once("class/class.styles.php");
		$s = new Styles();
		$styl = unserialize(base64_decode($_SESSION[styles_add]));
		$styl[styl_code] = $styl_code;
		$styl[styl_cost_usd] = $styl_cost_usd;
		$styl[styl_cost_rmb] = $styl_cost_rmb;
		$styl[styl_unit] = $styl_unit;
		$styl[styl_date] = $styl_date ;
		$styl[styl_desc] = $styl_desc;
		$styl[styl_onbrd_date] = $styl_onbrd_date;
		$styl[styl_status] = $styl_status;
		$styl[styl_qty_work] = $styl_qty_work;
		$styl[styl_cust_code] = $styl_cust_code;
		$styl[styl_qty_board] = $styl_qty_board;
		$styl[styl_po_no] = $styl_po_no;
		$styles_add = base64_encode(serialize($styl));
		$_SESSION["styles_add"] = $styles_add;

		$loc = "Location: style_details.php?ty=a&ht=$ht";
	}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "style_del") {
	$s = new Styles();
	$d = new StylDtls();
	$s->deleteStyle($styl_id);
	$d->deleteStylDtlsSC($styl_id);
	
	unset($styles_edit);
	session_unregister("styles_edit");
	unset($styldtls_edit);
	session_unregister("styldtls_edit");
	$loc = "Location: styles.php?ty=l";
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "style_detail_sess_add") {
		if ($ht == "e") {
			$styldtl = unserialize(base64_decode($_SESSION[styldtls_edit]));
			$dtl = array();
			for ($i=0;$i<count($styldtl);$i++) if (!empty($styldtl[$i])) array_push($dtl, $styldtl[$i]);
			$sdtl = array();
			$sdtl[styldtl_styl_code] = $styl_code;
			$sdtl[styldtl_item_code] = $styldtl_item_code;
			$sdtl[styldtl_item_desc] = $styldtl_item_desc;
			$sdtl[styldtl_meter_per_pair] = $styldtl_meter_per_pair;
			$sdtl[styldtl_rmb_per_meter] = $styldtl_rmb_per_meter;
			$sdtl[styldtl_rmb_per_pair] = $styldtl_rmb_per_pair ;
			$sdtl[styldtl_unit] = $styldtl_unit ;
			array_push($dtl, $sdtl);
			$styldtls_edit = base64_encode(serialize($dtl));
			$_SESSION["styldtls_edit"] = $styldtls_edit;

			if ($ht=="e") $loc = "Location: style_details.php?ty=a&styl_code=$styl_code&ht=$ht";
			else $loc = "Location: style_details.php?ty=a&ht=$ht";
		} else {
			include_once("class/class.items.php");
			$s = new Items();
			if (empty($styldtl_item_code)) {
				$errno=4;
				$errmsg="Item code should not be blank!";
				include("error.php");
				exit;
			} else {
				$styldtl = unserialize(base64_decode($_SESSION[styldtls_add]));
				$dtl = array();
				for ($i=0;$i<count($styldtl);$i++) if (!empty($styldtl[$i])) array_push($dtl, $styldtl[$i]);
				$sdtl = array();
				$sdtl[styldtl_styl_code] = $styl_code;
				$sdtl[styldtl_item_code] = $styldtl_item_code;
				$sdtl[styldtl_item_desc] = $styldtl_item_desc;
				$sdtl[styldtl_meter_per_pair] = $styldtl_meter_per_pair;
				$sdtl[styldtl_rmb_per_meter] = $styldtl_rmb_per_meter;
				$sdtl[styldtl_rmb_per_pair] = $styldtl_rmb_per_pair ;
				$sdtl[styldtl_unit] = $styldtl_unit ;
				array_push($dtl, $sdtl);
				$styldtls_add = base64_encode(serialize($dtl));
				$_SESSION["styldtls_add"] = $styldtls_add;

				if ($ht=="e") $loc = "Location: style_details.php?ty=a&ht=$ht&styl_code=$styl_code";
				else $loc = "Location: style_details.php?ty=a&ht=$ht";
			}
		}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "style_fixed_sess_add") {
	include_once("class/map.fixedcomponents.php");
	if ($ht=="e") {
		$s = new Styles();
		$styl = unserialize(base64_decode($_SESSION[styles_edit]));
		$styl[styl_cost_usd] = $styl_cost_usd;
		$styl[styl_cost_rmb] = $styl_cost_rmb;
		$styl[styl_unit] = $styl_unit;
		$styl[styl_date] = $styl_date ;
		$styl[styl_desc] = $styl_desc;
		$styl[styl_onbrd_date] = $styl_onbrd_date;
		$styl[styl_status] = $styl_status;
		$styl[styl_qty_work] = $styl_qty_work;
		$styl[styl_cust_code] = $styl_cust_code;
		$styl[styl_qty_board] = $styl_qty_board;
		$styl[styl_po_no] = $styl_po_no;
		$styles_edit = base64_encode(serialize($styl));
		$_SESSION["styles_edit"] = $styles_edit;

	} else {
		$s = new Styles();
		$styl = unserialize(base64_decode($_SESSION[styles_add]));
		$styl[styl_code] = $styl_code;
		$styl[styl_cost_usd] = $styl_cost_usd;
		$styl[styl_cost_rmb] = $styl_cost_rmb;
		$styl[styl_unit] = $styl_unit;
		$styl[styl_date] = $styl_date ;
		$styl[styl_desc] = $styl_desc;
		$styl[styl_onbrd_date] = $styl_onbrd_date;
		$styl[styl_status] = $styl_status;
		$styl[styl_qty_work] = $styl_qty_work;
		$styl[styl_cust_code] = $styl_cust_code;
		$styl[styl_qty_board] = $styl_qty_board;
		$styl[styl_po_no] = $styl_po_no;
		$styles_add = base64_encode(serialize($styl));
		$_SESSION["styles_add"] = $styles_add;

	}
	if ($ht=="e") $styldtl = unserialize(base64_decode($_SESSION[styldtls_edit]));
	else $styldtl = unserialize(base64_decode($_SESSION[styldtls_add]));
	$dtl = array();
	for ($i=0;$i<count($styldtl);$i++) if (!empty($styldtl[$i])) array_push($dtl, $styldtl[$i]);
	for ($i=0;$i<count($fixedcmpt);$i++) {
		$found = false;
		for ($j=0;$j<count($styldtl);$j++) if ($styldtl[$j] == $fixedcmpt[$i]) $found = true;
		if (!$found) array_push($dtl, $fixedcmpt[$i]);
	}
	if ($ht=="e") {
		$styldtls_edit = base64_encode(serialize($dtl));
		$_SESSION["styldtls_edit"] = $styldtls_edit;

		$loc = "Location: styles.php?ty=a&ht=$ht&styl_code=$styl_code";
	} else {
		$styldtls_add = base64_encode(serialize($dtl));
		$_SESSION["styldtls_add"] = $styldtls_add;

		$loc = "Location: styles.php?ty=a&ht=$ht";
	}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "style_add") {
		$s = new Styles();
		$d = new StylDtls();
		$t = new Items();
		$sarr = $s->getStyles($styl_code);
		if (count($sarr)>0) {
			$oldrec = $s->getTextFields("", "");
			$r = new Requests();
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			$lastid = $s->insertStyles($arr);
			if (session_is_registered("styldtls_add")) $sdtls = unserialize(base64_decode($_SESSION[styldtls_add]));
			for ($i=0;$i<count($sdtls);$i++) {
				$sdtls[$i][styldtl_styl_code] = $styl_code;
				if (!$marr = $t->getItems($sdtls[$i][styldtl_item_code])) {
					$tarr = array(
						"item_code"=>"",
						"item_ave_cost"=>"",
						"item_desc"=>"",
						"item_unit"=>""
					);
					$tarr["item_code"] = $sdtls[$i][styldtl_item_code];
					$tarr["item_desc"] = $sdtls[$i][styldtl_item_desc];
					$tarr["item_unit"] = $sdtls[$i][styldtl_unit];
					$tarr["item_ave_cost"] = $sdtls[$i][styldtl_rmb_per_meter];
					$t->insertItems($tarr);
				}
				if (!$d->insertStylDtls($sdtls[$i])) {
					$errno=6;
					$errmsg="Style Detail Insertion Error";
					include("error.php");
					exit;
				}
			}
			session_unregister("styles_add");
			unset($styles_add);
			session_unregister("styldtls_add");
			unset($styldtls_add);
			$loc = "Location: styles.php?ty=a&sc=$styl_code&pr=t";
		} else {
			$loc = "Location: error.php?errno=4&errmsg=".urlencode("Style code is already in database");
		}
//echo $loc;
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "style_edit") {
		include_once("class/class.styles.php");
		$s = new Styles();
		include_once("class/class.styldtls.php");
		$d = new StylDtls();
		include_once("class/class.items.php");
		$t = new Items();
		$sarr = $s->getStyles($styl_code);
		if (count($sarr)>0) {
			$oldrec = $s->getTextFields("", "$styl_code");
			$r = new Requests();
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			$s->updateStyles($styl_code, $arr);
			if ($d->deleteStylDtlsSC($styl_code)) {
				$sdtls = unserialize(base64_decode($_SESSION[styldtls_edit]));
				for ($i=0;$i<count($sdtls);$i++) {
					$sdtls[$i][styldtl_styl_code] = $styl_code;
					if (!$tarr = $t->getItems($sdtls[$i][styldtl_item_code])) {
						$tarr = array(
							"item_code"=>"",
							"item_ave_cost"=>"",
							"item_desc"=>"",
							"item_unit"=>""
						);
						$tarr["item_code"] = $sdtls[$i][styldtl_item_code];
						$tarr["item_desc"] = $sdtls[$i][styldtl_item_desc];
						$tarr["item_unit"] = $sdtls[$i][styldtl_unit];
						$tarr["item_ave_cost"] = $sdtls[$i][styldtl_rmb_per_meter];
						$tarr["item_unit"] = $sdtls[$i][styldtl_unit];
						$t->insertItems($tarr);
					}
					if (!$ins = $d->insertStylDtls($sdtls[$i])) {
						$errno=6;
						$errmsg="Style Detail Insertion Error";
						include("error.php");
						exit;
					}
				}
				session_unregister("styles_edit");
				session_unregister("styldtls_edit");
				$loc = "Location: styles.php?ty=e&styl_code=$styl_code";
			} else {
				$loc = "Location: error.php?errno=5&errmsg=".urlencode("Style Header Insertion Error");
			}
		} else {
			$loc = "Location: error.php?errno=4&errmsg=".urlencode("Style code is already in database");
		}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "style_detail_sess_del") {
	if ($ht=="e") {
		$arr = array();
		$styldtl = unserialize(base64_decode($_SESSION[styldtls_edit]));
		for ($i=0;$i<count($styldtl);$i++) if ($i != $did) array_push($arr, $styldtl[$i]);
		$styldtls_edit = base64_encode(serialize($arr));
		$_SESSION["styldtls_edit"] = $styldtls_edit;

		$loc = "Location: styles.php?ty=e";
	} else {
		$arr = array();
		$styldtl = unserialize(base64_decode($_SESSION[styldtls_add]));
		for ($i=0;$i<count($styldtl);$i++) if ($i != $did) array_push($arr, $styldtl[$i]);
		$styldtls_add = base64_encode(serialize($arr));
		$_SESSION["styldtls_add"] = $styldtls_add;

		$loc = "Location: styles.php?ty=a";
	}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "style_clear_sess_edit") {
	session_unregister("styles_edit");
	session_unregister("styldtls_edit");
	unset($styles_edit);
	unset($styldtls_edit);
	$loc = "Location: styles.php?ty=e&styl_code=$styl_code";
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "style_clear_sess_add") {
	session_unregister("styles_add");
	session_unregister("styldtls_add");
	unset($styles_add);
	unset($styldtls_add);
	$loc = "Location: styles.php?ty=a";
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "style_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$styldtl = unserialize(base64_decode($_SESSION[styldtls_edit]));
		for ($i=0;$i<count($styldtl);$i++) {
			if ($i == $did) {
				$sdtl = array();
				$sdtl[styldtl_styl_code] = $styldtl_styl_code;
				$sdtl[styldtl_item_code] = $styldtl_item_code;
				$sdtl[styldtl_item_desc] = $styldtl_item_desc;
				$sdtl[styldtl_meter_per_pair] = $styldtl_meter_per_pair;
				$sdtl[styldtl_rmb_per_meter] = $styldtl_rmb_per_meter;
				$sdtl[styldtl_rmb_per_pair] = $styldtl_rmb_per_pair ;
				$sdtl[styldtl_usd_per_pair] = $styldtl_usd_per_pair;
				$sdtl[styldtl_unit] = $styldtl_unit;
				$sdtl[styldtl_group] = $styldtl_group;
				array_push($arr, $sdtl);
			} else {
				array_push($arr, $styldtl[$i]);
			}
		}
		$styldtls_edit = base64_encode(serialize($arr));
		$_SESSION["styldtls_edit"] = $styldtls_edit;

		$loc = "Location: style_details.php?ty=e&ht=e&did=$did";
	} else {
		$arr = array();
		$styldtl = unserialize(base64_decode($_SESSION[styldtls_add]));
		for ($i=0;$i<count($styldtl);$i++) {
			if ($i == $did) {
				$sdtl = array();
				$sdtl[styldtl_styl_code] = $styldtl_styl_code;
				$sdtl[styldtl_item_code] = $styldtl_item_code;
				$sdtl[styldtl_item_desc] = $styldtl_item_desc;
				$sdtl[styldtl_meter_per_pair] = $styldtl_meter_per_pair;
				$sdtl[styldtl_rmb_per_meter] = $styldtl_rmb_per_meter;
				$sdtl[styldtl_rmb_per_pair] = $styldtl_rmb_per_pair ;
				$sdtl[styldtl_usd_per_pair] = $styldtl_usd_per_pair;
				$sdtl[styldtl_unit] = $styldtl_unit;
				$sdtl[styldtl_group] = $styldtl_group;
				array_push($arr, $sdtl);
			} else {
				array_push($arr, $styldtl[$i]);
			}
		}
		$styldtls_add = base64_encode(serialize($arr));
		$_SESSION["styldtls_add"] = $styldtls_add;

		$loc = "Location: style_details.php?ty=e&ht=a&did=$did";
	}
	header($loc);
}
?>