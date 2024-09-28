<?php
include_once("class/map.default.php");
include_once("class/class.items.php");
include_once("class/class.itemtrxs.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.requests.php");
include_once("class/class.itm_builds.php");
include_once("class/class.itm_buildtls.php");


if ($cmd == "itmblds_sess_add") {
	if ($ht=="e") {
		$s = new ItmBuilds();
		$ibld = $_SESSION[itmblds_edit];
		$ibld[itmbuild_name] = $itmbuild_name;
		$ibld[itmbuild_desc] = $itmbuild_desc;
		$ibld[itmbuild_user_code] = $itmbuild_user_code;
		$_SESSION[itmblds_edit]=$ibld;
		$loc = "Location: itm_build_details.php?ty=a&ht=$ht&itmbuild_id=$itmbuild_id";
	} else {
		include_once("class/class.itm_builds.php");
		$s = new ItmBuilds();
		$ibld = $_SESSION[itmblds_add];
		$ibld[itmbuild_name] = $itmbuild_name;
		$ibld[itmbuild_desc] = $itmbuild_desc;
		$ibld[itmbuild_user_code] = $itmbuild_user_code;
		$_SESSION[itmblds_add]=$ibld;
		$loc = "Location: itm_build_details.php?ty=a&ht=$ht";
	}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "itmblds_del") {
	$s = new ItmBuilds();
	$d = new ItmBuilDtls();
	$s->deleteStyle($styl_id);
	$d->deleteStylDtlsSC($styl_id);
	
	$_SESSION[itmblds_edit] = NULL;
	$_SESSION[itmbldtls_edit] = NULL;
	$loc = "Location: itm_builds.php?ty=l";
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "itmblds_detail_sess_add") {
	if ($ht == "e") {
		$ibdtl = $_SESSION[itmbldtls_edit];
		$dtl = array();
		for ($i=0;$i<count($ibdtl);$i++) if (!empty($ibdtl[$i])) array_push($dtl, $ibdtl[$i]);
		$sdtl = array();

		$sdtl[itmbldtl_id] = $itmbldtl_id;
		$sdtl[itmbldtl_itmbuild_id] = $itmbuild_id;
		$sdtl[itmbldtl_item_code] = $itmbldtl_item_code;
		$sdtl[itmbldtl_desc] = $itmbldtl_desc;
		$sdtl[itmbldtl_unit_code] = $itmbldtl_unit_code;
		$sdtl[itmbldtl_qty] = $itmbldtl_qty;
		$sdtl[itmbldtl_cost] = $itmbldtl_cost;
		array_push($dtl, $sdtl);
		$_SESSION[itmbldtls_edit] = $dtl;
		if ($ht=="e") $loc = "Location: itmblds_details.php?ty=a&itmbuild_id=$itmbuild_id&ht=$ht";
		else $loc = "Location: itmblds_details.php?ty=a&ht=$ht";
	} else {
		$s = new Items();
		if (empty($itmbldtl_item_code)) {
			$errno=4;
			$errmsg="Item code should not be blank!";
			include("error.php");
			exit;
		} else {
			$ibdtl = $_SESSION[itmbldtls_add];
			$dtl = array();
			for ($i=0;$i<count($ibdtl);$i++) if (!empty($ibdtl[$i])) array_push($dtl, $ibdtl[$i]);
			$sdtl = array();
			$sdtl[itmbldtl_id] = $itmbldtl_id;
			$sdtl[itmbldtl_itmbuild_id] = $itmbuild_id;
			$sdtl[itmbldtl_item_code] = $itmbldtl_item_code;
			$sdtl[itmbldtl_desc] = $itmbldtl_desc;
			$sdtl[itmbldtl_unit_code] = $itmbldtl_unit_code;
			$sdtl[itmbldtl_qty] = $itmbldtl_qty;
			$sdtl[itmbldtl_cost] = $itmbldtl_cost;

			array_push($dtl, $sdtl);
			$_SESSION[itmbldtls_add] = $dtl;
			if ($ht=="e") $loc = "Location: itmblds_details.php?ty=a&ht=$ht&itmbuild_id=$itmbuild_id";
			else $loc = "Location: itmblds_details.php?ty=a&ht=$ht";
		}
	}
	header($loc);
	exit;

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "itmblds_add") {
		$s = new ItmBuilds();
		$d = new ItmBuilDtls();
		$t = new Items();
		$sarr = $s->getItmBuilds($itmbuild_id);
		if (!$sarr) {
			$oldrec = $s->getTextFields("", "");
			$r = new Requests();
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			$itmbuild_id = $s->insertItmBuilds($arr);
			if ($_SESSION[itmbldtls_add]) $sdtls = $_SESSION[itmbldtls_add];
			if ($sdtls) $sdtl_num = count($sdtls);
			else $sdtl_num = 0;
			for ($i=0;$i<$sdtl_num;$i++) {
				if (!$marr = $t->getItems($sdtls[$i][itmbldtl_item_code])) {
					$tarr = array();
					$sdtls[$i][itmbldtl_itmbuild_id] = $itmbuild_id;
					$tarr[itmbldtl_desc] = $sdtls[$i][itmbldtl_desc];
					$tarr[itmbldtl_item_code] = $sdtls[$i][itmbldtl_item_code];
					$tarr[itmbldtl_qty] = $sdtls[$i][itmbldtl_qty];
					$tarr[itmbldtl_unit_code] = $sdtls[$i][itmbldtl_unit_code];
					$t->insertItems($tarr);
					if (!$d->insertItmBuilDtls($sdtls[$i])) {
						$errno=6;
						$errmsg="Item Build Detail Insertion Error";
						include("error.php");
						exit;
					}
				}
			}

			$_SESSION[itmblds_add] = NULL;
			$_SESSION[itmbldtls_add] = NULL;
			$loc = "Location: itm_builds.php?ty=a&itmbuild_id=$itmbuild_id";
		} else {
			$errno=7;
			$errmsg="Item Build Insertion Error";
			include("error.php");
			exit;
		}
//echo $loc;
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "itmblds_edit") {
		include_once("class/class.itm_builds.php");
		$s = new ItmBuilds();
		include_once("class/class.styldtls.php");
		$d = new ItmBuilDtls();
		include_once("class/class.items.php");
		$t = new Items();
		$sarr = $s->getItmBuilds($itmbuild_id);
		if (count($sarr)>0) {
			$oldrec = $s->getTextFields("", "$itmbuild_id");
			$r = new Requests();
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			$s->updateStyles($itmbuild_id, $arr);
			if ($d->deleteStylDtlsSC($itmbuild_id)) {
				$sdtls = $_SESSION[itmbldtls_edit];
				for ($i=0;$i<count($sdtls);$i++) {
					$sdtls[$i][itmbldtl_itmbuild_id] = $itmbuild_id;
					if (!$tarr = $t->getItems($sdtls[$i][itmbldtl_item_code])) {
						$tarr = array(
							"item_code"=>"",
							"item_ave_cost"=>"",
							"item_desc"=>"",
							"item_unit"=>""
						);
						$tarr["item_code"] = $sdtls[$i][itmbldtl_item_code];
						$tarr["item_desc"] = $sdtls[$i][itmbldtl_item_desc];
						$tarr["item_unit"] = $sdtls[$i][itmbldtl_unit];
						$tarr["item_ave_cost"] = $sdtls[$i][itmbldtl_rmb_per_meter];
						$tarr["item_unit"] = $sdtls[$i][itmbldtl_unit];
						$t->insertItems($tarr);
					}
					if (!$ins = $d->insertStylDtls($sdtls[$i])) {
						$errno=6;
						$errmsg="Style Detail Insertion Error";
						include("error.php");
						exit;
					}
				}
				$_SESSION[itmblds_edit]=NULL;
				$_SESSION[itmbldtls_edit]=NULL;
				$loc = "Location: itm_builds.php?ty=e&itmbuild_id=$itmbuild_id";
			} else {
				$loc = "Location: error.php?errno=5&errmsg=".urlencode("Style Header Insertion Error");
			}
		} else {
			$loc = "Location: error.php?errno=4&errmsg=".urlencode("Style code is already in database");
		}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "itmblds_detail_sess_del") {
	if ($ht=="e") {
		$arr = array();
		$ibdtl = $_SESSION[itmbldtls_edit];
		for ($i=0;$i<count($ibdtl);$i++) if ($i != $did) array_push($arr, $ibdtl[$i]);
		$_SESSION[itmbldtls_edit] = $arr;
		$loc = "Location: itm_builds.php?ty=e";
	} else {
		$arr = array();
		$ibdtl = $_SESSION[itmbldtls_add];
		for ($i=0;$i<count($ibdtl);$i++) if ($i != $did) array_push($arr, $ibdtl[$i]);
		$_SESSION[itmbldtls_add] = $arr;
		$loc = "Location: itm_builds.php?ty=a";
	}
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "itmblds_clear_sess_edit") {
	$_SESSION[itmblds_edit] = NULL;
	$_SESSION[itmbldtls_edit] = NULL;
	$loc = "Location: itm_builds.php?ty=e&itmbuild_id=$itmbuild_id";
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "itmblds_clear_sess_add") {
	$_SESSION[itmblds_add] = NULL;
	$_SESSION[itmbldtls_add] = NULL;
	$loc = "Location: itm_builds.php?ty=a";
	header($loc);

//////////////////////////////////////////////////////////////////////////////////////////////////
} else if ($cmd == "itmblds_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$ibdtl = $_SESSION[itmbldtls_edit];
		for ($i=0;$i<count($ibdtl);$i++) {
			if ($i == $did) {
				$sdtl = array();
				$sdtl[itmbldtl_itmbuild_id] = $itmbldtl_itmbuild_id;
				$sdtl[itmbldtl_item_code] = $itmbldtl_item_code;
				$sdtl[itmbldtl_item_desc] = $itmbldtl_item_desc;
				$sdtl[itmbldtl_meter_per_pair] = $itmbldtl_meter_per_pair;
				$sdtl[itmbldtl_rmb_per_meter] = $itmbldtl_rmb_per_meter;
				$sdtl[itmbldtl_rmb_per_pair] = $itmbldtl_rmb_per_pair ;
				$sdtl[itmbldtl_usd_per_pair] = $itmbldtl_usd_per_pair;
				$sdtl[itmbldtl_unit] = $itmbldtl_unit;
				$sdtl[itmbldtl_group] = $itmbldtl_group;
				array_push($arr, $sdtl);
			} else {
				array_push($arr, $ibdtl[$i]);
			}
		}
		$_SESSION[itmbldtls_edit] = $arr;
		$loc = "Location: itmblds_details.php?ty=e&ht=e&did=$did";
	} else {
		$arr = array();
		$ibdtl = $_SESSION[itmbldtls_add];
		for ($i=0;$i<count($ibdtl);$i++) {
			if ($i == $did) {
				$sdtl = array();
				$sdtl[itmbldtl_itmbuild_id] = $itmbldtl_itmbuild_id;
				$sdtl[itmbldtl_item_code] = $itmbldtl_item_code;
				$sdtl[itmbldtl_item_desc] = $itmbldtl_item_desc;
				$sdtl[itmbldtl_meter_per_pair] = $itmbldtl_meter_per_pair;
				$sdtl[itmbldtl_rmb_per_meter] = $itmbldtl_rmb_per_meter;
				$sdtl[itmbldtl_rmb_per_pair] = $itmbldtl_rmb_per_pair ;
				$sdtl[itmbldtl_usd_per_pair] = $itmbldtl_usd_per_pair;
				$sdtl[itmbldtl_unit] = $itmbldtl_unit;
				$sdtl[itmbldtl_group] = $itmbldtl_group;
				array_push($arr, $sdtl);
			} else {
				array_push($arr, $ibdtl[$i]);
			}
		}
		$_SESSION[itmbldtls_add] = $arr;
		$loc = "Location: itmblds_details.php?ty=e&ht=a&did=$did";
	}
	header($loc);
}
?>