<?php
include_once("class/class.itm_builds.php");
include_once("class/class.itm_buildtls.php");
include_once("class/class.items.php");
include_once("class/class.datex.php");
include_once("class/class.salehists.php");
include_once("class/map.default.php");
include_once("class/class.users.php");
include_once("class/register_globals.php");

$_SESSION[itmbldtls_del]=NULL;

if ($cmd == "itm_build_sess_add") {
	if ($ht=="e") {
		$s = new ItmBuilds();
		$sls = $_SESSION[itmbld_edit];
		$sls["sale_id"]			= $itmbuild_id;
		$sls[sale_user_code]	= $sale_user_code;
		$sls["sale_cust_code"]	= $sale_cust_code;
		$sls[sale_cust_code_old]	= $sale_cust_code_old;
		$sls["sale_name"]			= $sale_name;
		$sls["sale_addr1"]		= $sale_addr1;
		$sls["sale_addr2"]		= $sale_addr2;
		$sls["sale_addr3"]		= $sale_addr3;
		$sls["sale_city"]			= $sale_city;
		$sls["sale_state"]		= $sale_state;
		$sls[sale_country]		= $sale_country;
		$sls["sale_zip"]			= $sale_zip;
		$sls["sale_tel"]			= $sale_tel;
		$sls["sale_slsrep"]		= $sale_slsrep;
		$sls[sale_term]			= $sale_term;
		$sls["sale_amt"]			= $sale_amt;
		$sls["sale_date"]			= $sale_date ;
		$sls["sale_tax_amt"]		= $sale_tax_amt;
		$sls["sale_freight_amt"]	= $sale_freight_amt;
		$sls[sale_shipvia]		= $sale_shipvia;
		$sls["pick_prom_date"]	= $sale_prom_date;
		$sls[sale_taxrate]		= $sale_taxrate;
		$sls["sale_comnt"]		= $sale_comnt;
		$itm_build_edit = $sls;
		$_SESSION["itm_build_edit"] = $itm_build_edit;
		
		$loc = "Location: sales_details.php?ty=a&ht=e&sale_id=$itmbuild_id";
	} else {
		$sls = $_SESSION[itmbld_add];
		$sls["sale_id"]			= $itmbuild_id;
		$sls[sale_user_code]	= $sale_user_code;
		$sls["sale_cust_code"]	= $sale_cust_code;
		$sls[sale_cust_code_old]	= $sale_cust_code_old;
		$sls[sale_cust_po]		= $sale_cust_po;
		$sls["sale_name"]			= $sale_name;
		$sls["sale_addr1"]		= $sale_addr1;
		$sls["sale_addr2"]		= $sale_addr2;
		$sls["sale_addr3"]		= $sale_addr3;
		$sls["sale_city"]			= $sale_city;
		$sls["sale_state"]		= $sale_state;
		$sls[sale_country]		= $sale_country;
		$sls["sale_zip"]			= $sale_zip;
		$sls["sale_tel"]			= $sale_tel;
		$sls["sale_slsrep"]		= $sale_slsrep;
		$sls[sale_term]			= $sale_term;
		$sls["sale_amt"]			= $sale_amt;
		$sls["sale_date"]			= $sale_date ;
		$sls["pick_prom_date"]	= $sale_prom_date;
		$sls["sale_tax_amt"]		= $sale_tax_amt;
		$sls["sale_freight_amt"]	= $sale_freight_amt;
		$sls[sale_shipvia]		= $sale_shipvia;
		$sls[sale_taxrate]		= $sale_taxrate;
		$sls["sale_comnt"]		= $sale_comnt;
		$itm_build_add = $sls;
		$_SESSION["itm_build_add"] = $itm_build_add;

		$loc = "Location: sales_details.php?ty=a&ht=a";
	}
	header($loc);

} else if ($cmd == "itm_build_detail_sess_add") {
	if ($ht == "e") {
		$sale = $_SESSION[itmbld_edit];
		$ibdtl = $_SESSION[itmbldtls_edit];

		$dtl = array();
		for ($i=0;$i<count($ibdtl);$i++) if (!empty($ibdtl[$i])) array_push($dtl, $ibdtl[$i]);
		$sls = array();
		$sls["slsdtl_sale_id"]		= $itmbuild_id;
		$sls["slsdtl_item_code"]		= $slsdtl_item_code;
		$sls["slsdtl_item_desc"]		= $slsdtl_item_desc;
		$sls["slsdtl_qty"]			= $slsdtl_qty;
		$sls["slsdtl_cost"]			= $slsdtl_cost;
		if ($slsdtl_taxable != "t") $slsdtl_taxable = "f";
		$sls["slsdtl_taxable"]		= $slsdtl_taxable;
		array_push($dtl, $sls);
		$ibdtls_edit = $dtl;
		$_SESSION["ibdtls_edit"] = $ibdtls_edit;

		$sale["sale_amt"] += $slsdtl_qty * $slsdtl_cost;
		if ($slsdtl_taxable == "t") $sale["sale_tax_amt"] += $slsdtl_qty * $slsdtl_cost * $sale[sale_taxrate]/ 100;
		$itm_build_edit = $sale;
		$_SESSION["itm_build_edit"] = $itm_build_edit;

		$loc = "Location: sales_details.php?ty=a&ht=e&sale_id=$itmbuild_id";
	} else {
		if (empty($slsdtl_item_code)) {
			$errno=4;
			$errmsg="Item code should not be blank!";
			include("error.php");
			exit;
		} else {
			$sale = $_SESSION[itmbld_add];
			$ibdtl = $_SESSION[itmbldtls_add];
			$dtl = array();
			for ($i=0;$i<count($ibdtl);$i++) if (!empty($ibdtl[$i])) array_push($dtl, $ibdtl[$i]);
			$sls = array();
			$sls["slsdtl_item_code"]		= $slsdtl_item_code;
			$sls["slsdtl_item_desc"]		= $slsdtl_item_desc;
			$sls["slsdtl_qty"]			= $slsdtl_qty;
			$sls["slsdtl_cost"]			= $slsdtl_cost;
			if ($slsdtl_taxable != "t") $slsdtl_taxable = "f";
			$sls["slsdtl_taxable"]		= $slsdtl_taxable;
			array_push($dtl, $sls);
			$ibdtls_add = $dtl;
			$_SESSION["ibdtls_add"] = $ibdtls_add;

			$sale["sale_amt"] += $slsdtl_qty * $slsdtl_cost;
			if ($slsdtl_taxable == "t") $sale["sale_tax_amt"] += $slsdtl_qty * $slsdtl_cost * $sale[sale_taxrate]/ 100;
			$itm_build_add = $sale;
			$_SESSION["itm_build_add"] = $itm_build_add;

			$loc = "Location: sales_details.php?ty=a&ht=a";
		}
	}
	header($loc);

} else if ($cmd == "itm_build_add") {
	$s = new ItmBuilds();
	$d = new ItmBuilDtls();
	$it = new Items();

	$parr = $s->getSales($itmbuild_id);

	$oldrec = $s->getTextFields("", "");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$sid = $s->insertSales($arr);

	if (empty($itmbuild_id)) $itmbuild_id = $sid;
	$sdtls = $_SESSION[itmbldtls_add];
	if ($sdtls) $sdtl_num = count($sdtls);
	else $sdtl_num = 0;
	$taxtotal = 0;
	$subtotal = 0;
	for ($i=0;$i<$sdtl_num;$i++) {
		$sdtls[$i]["slsdtl_sale_id"] = $itmbuild_id;
		$i_arr = $it->getItems($sdtls[$i]["slsdtl_item_code"]);
		$sdtls[$i]["slsdtl_unit"] = $i_arr["item_unit"];
		$lastid = $d->insertSaleDtls($sdtls[$i]);
		if ($sdtls[$i]["slsdtl_taxable"]=="t") $taxtotal += $sdtls[$i]["slsdtl_cost"]*$sdtls[$i]["slsdtl_qty"];
		$subtotal += $sdtls[$i]["slsdtl_cost"]*$sdtls[$i]["slsdtl_qty"];
		if ($lastid <= 0) {
			$errno=6;
			$errmsg="Sales Detail Insertion Error";
			include("error.php");
			exit;
		}
	}

	if ($save_ship == "t") {
		$cs = new CustShips();
		$cs_arr = array();
		$cs_arr["custship_cust_code"]=$sale_cust_code;
		$cs_arr["custship_name"] = $sale_name;
		$cs_arr["custship_addr1"] = $sale_addr1;
		$cs_arr[custship_addr2] = $sale_addr2;
		$cs_arr[custship_addr3] = $sale_addr2;
		$cs_arr["custship_city"] = $sale_city;
		$cs_arr[custship_state] = $sale_state;
		$cs_arr[custship_country] = $sale_country;
		$cs_arr[custship_zip] = $sale_zip;
		$cs_arr["custship_tel"] = $sale_tel;
		$cs_arr[custship_shipvia] = $sale_shipvia;
		$id = $cs->insertCustShips($cs_arr); 
	}

// tax recalculation...
/*
	if ($_POST[sale_taxrate]>0) $tax_rate = $_POST[sale_taxrate];
	else $tax_rate = 0;
	$sh_arr = array();
	$sh_arr["sale_amt"] = $subtotal;;
	$sh_arr["sale_tax_amt"] = sprintf("%0.2f", $taxtotal*$tax_rate/100);
	if ($arr && $sdtls) $s->updateSales($itmbuild_id, $sh_arr);
*/
	$h = new SaleHists();
	$hist_arr = array();
	$hist_arr[salehist_sale_id]=$itmbuild_id;
	$hist_arr[salehist_type]="i";
	$hist_arr[salehist_user_code]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr[salehist_modified]=date("YmdHis");
	$h->insertSaleHists($hist_arr);

	unset($sales_add);
	session_unregister("itm_build_add");
	unset($ibdtls_add);
	session_unregister("saledtls_add");
	$loc = "Location: itm_build.php?ty=e&sale_id=$itmbuild_id";
	header($loc);

} else if ($cmd == "itm_build_edit") {
	$it = new Items();
	$s = new ItmBuilds();
	$d = new ItmBuilDtls();
	$sarr = $s->getSales($itmbuild_id);
	if (!$sarr) {
		$errno=4;
		$errmsg="Sales id is not in database";
		include("error.php");
		exit;
	}
	$oldrec = $s->getTextFields("", "$itmbuild_id");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	if (count($arr)>0) $s->updateSales($itmbuild_id, $arr);
	//$d->deleteSaleDtlsSI($itmbuild_id);
	$sdtls = $_SESSION[itmbldtls_edit];
	$sdtl_num = count($sdtls);
	$amt = 0;
	$sd_arr = $d->getSaleDtlHdrs($itmbuild_id);

	if (!empty($sd_arr)) $sd_num = count($sd_arr);
	else $sd_num = 0;

	for ($i=0;$i<$sdtl_num;$i++) {

		$sdtls[$i]["slsdtl_sale_id"] = $itmbuild_id;
		$i_arr = $it->getItems($sdtls[$i]["slsdtl_item_code"]);
		$sdtls[$i]["slsdtl_unit"] = $i_arr["item_unit"];
		$amt += $sdtls["slsdtl_cost"];
		$found = 0;
		for ($j=0;$j<$sd_num;$j++) {
			if ($sd_arr[$j]["slsdtl_id"] == $sdtls[$i]["slsdtl_id"]) {
				$found = 1;
				break;
			}
		}

		if ($found>0) {
			$udt_res = $d->updateSaleDtls($sdtls[$i]["slsdtl_id"], $sdtls[$i]);
			if ($udt_res== false) {
				$errno=6;
				$errmsg="Sales Detail Updating Error";
				include("error.php");
				exit;
			}
		} else {
			unset($sdtls[$i]["slsdtl_id"]);
			if (!empty($sdtls[$i]["slsdtl_item_code"]) && !$d->insertSaleDtls($sdtls[$i])) {
				$errno=7;
				$errmsg="Sales Detail Inserting Error";
				include("error.php");
				exit;
			}
		}
	}

	for ($i=0;$i<$sd_num;$i++) {
		$found = 0;
		for ($j=0;$j<$sdtl_num;$j++) {
			if ($sd_arr[$i]["slsdtl_id"] == $sdtls[$j]["slsdtl_id"]) {
				$found = 1;
				break;
			}
		}
		if ($found == 0) {
			$pd = new pickdtls();
			if ($pd_arr = $pd->isPickDtled($sd_arr[$i]["slsdtl_id"])) {
				$errno=9;
				$errmsg="Picking Ticket Detail is allocated already";
				include("error.php");
				exit;
			}
			if (!$d->deleteSaleDtls($sd_arr[$i]["slsdtl_id"])) {
				$errno=8;
				$errmsg="Sales Detail deleting Error";
				include("error.php");
				exit;
			}
		} else if (!$sd_arr[$i]["slsdtl_item_code"]) {
			if (!$d->deleteSaleDtls($sd_arr[$i]["slsdtl_id"])) {
				$errno=8;
				$errmsg="Sales Detail deleting Error";
				include("error.php");
				exit;
			}
		}
	}

	if ($save_ship == "t") {
		$cs = new CustShips();
		$cs_arr = array();
		$cs_arr["custship_cust_code"]=$sale_cust_code;
		$cs_arr["custship_name"] = $sale_name;
		$cs_arr["custship_addr1"] = $sale_addr1;
		$cs_arr[custship_addr2] = $sale_addr2;
		$cs_arr[custship_addr3] = $sale_addr2;
		$cs_arr["custship_city"] = $sale_city;
		$cs_arr[custship_state] = $sale_state;
		$cs_arr[custship_country] = $sale_country;
		$cs_arr[custship_zip] = $sale_zip;
		$cs_arr["custship_tel"] = $sale_tel;
		$cs_arr[custship_shipvia] = $sale_shipvia;
		$id = $cs->insertCustShips($cs_arr); 
	}

	$sd_arr = $d->getSaleDtlHdrSum($itmbuild_id);

// tax recalcuration...
/*
	$sh_arr = array();
	$sh_arr["sale_amt"] = $sd_arr[slsdtl_sum];
	if ($_POST[sale_taxrate]>0) $tax_rate = $_POST[sale_taxrate];
	else $tax_rate = 0;
	$sh_arr["sale_tax_amt"] = sprintf("%0.2f", $sd_arr[slsdtl_tax_sum]*$tax_rate/100);
	if ($arr && $sd_arr) $s->updateSales($itmbuild_id, $sh_arr);
*/

	$h = new SaleHists();
	$hist_arr = array();
	$hist_arr[salehist_sale_id]=$itmbuild_id;
	$hist_arr[salehist_type]="u";
	$hist_arr[salehist_user_code]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr[salehist_modified]=date("YmdHis");
	$h->insertSaleHists($hist_arr);

	session_unregister("itm_build_edit");
	unset($sales_edit);
	session_unregister("saledtls_edit");
	unset($ibdtls_edit);
	$ibdtl_del = 1;
	$_SESSION["ibdtl_del"] = $ibdtl_del;

	$loc = "Location: itm_build.php?ty=e&sale_id=$itmbuild_id";
	header($loc);

} else if ($cmd == "itm_build_del") {
	$s = new ItmBuilds();
	$d = new ItmBuilDtls();
	$s->deleteSales($itmbuild_id);
	$d->deleteSaleDtlsSI($itmbuild_id);
//	$j = new JrnlTrxs();
//	$j->deleteJrnlTrxRefs($itmbuild_id, "r");

	$h = new SaleHists();
	$hist_arr = array();
	$hist_arr[salehist_sale_id]=$itmbuild_id;
	$hist_arr[salehist_type]="d";
	$hist_arr[salehist_user_code]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr[salehist_modified]=date("YmdHis");
	$h->insertSaleHists($hist_arr);

	$_SESSION[itm_build_edit]=NULL;
	$_SESSION[itmbldtls_edit]=NULL;
	$_SESSION[itmbldtls_del]=1;
	$loc = "Location: itm_build.php?ty=l";
	header($loc);

} else if ($cmd == "itm_build_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$ibdtl = $_SESSION[itmbldtls_edit];
		for ($i=0;$i<count($ibdtl);$i++) {
			if ($i != $did) {
				array_push($arr, $ibdtl[$i]);
			} else {
				$sale = $_SESSION[itmbld_edit];
				$sale["sale_amt"] -= $ibdtl[$i]["slsdtl_qty"] * $ibdtl[$i]["slsdtl_cost"];
				if ($ibdtl[$i]["slsdtl_taxable"] == "t") $sale["sale_tax_amt"] -= $ibdtl[$i]["slsdtl_qty"] * $ibdtl[$i]["slsdtl_cost"] * $sale[sale_taxrate]/ 100;
				$itm_build_edit = $sale;
				$_SESSION["itm_build_edit"] = $itm_build_edit;
			}
		}
		$ibdtls_edit = $arr;
		$_SESSION["ibdtls_edit"] = $ibdtls_edit;

		$ibdtl_del = 1;
		$_SESSION["ibdtl_del"] = $ibdtl_del;
		$loc = "Location: itm_build.php?ty=e&sale_id=$itmbuild_id";
	} else {
		$arr = array();
		$ibdtl = $_SESSION[itmbldtls_add];
		for ($i=0;$i<count($ibdtl);$i++) {
			if ($i != $did) {
				array_push($arr, $ibdtl[$i]);
			} else {
				$sale = $_SESSION[itmbld_add];
				$sale["sale_amt"] -= $ibdtl[$i]["slsdtl_qty"] * $ibdtl[$i]["slsdtl_cost"];
				if ($ibdtl[$i]["slsdtl_taxable"] == "t") $sale["sale_tax_amt"] -= $ibdtl[$i]["slsdtl_qty"] * $ibdtl[$i]["slsdtl_cost"] * $sale[sale_taxrate]/ 100;
				$itm_build_add = $sale;
				$_SESSION["itm_build_add"] = $itm_build_add;
			}
		}
		$ibdtls_add = $arr;
		$_SESSION["ibdtls_add"] = $ibdtls_add;
		$loc = "Location: itm_build.php?ty=a";
	}
	header($loc);

} else if ($cmd == "itm_build_clear_sess_edit") {
	session_unregister("itm_build_edit");
	session_unregister("saledtls_edit");
	$loc = "Location: itm_build.php?ty=e&sale_id=$itmbuild_id";
	header($loc);

} else if ($cmd == "itm_build_clear_sess_add") {
	session_unregister("itm_build_add");
	session_unregister("saledtls_add");
	$loc = "Location: itm_build.php?ty=a";
	header($loc);

} else if ($cmd == "itm_build_update_sess_add") {
	if ($ty=="e") {
		$s = new ItmBuilds();
		$sls = $_SESSION[itmbld_edit];
		$sls["sale_id"]			= $itmbuild_id;
		$sls["sale_code"]			= $sale_code;
		$sls[sale_cust_po]		= $sale_cust_po;
		$sls[sale_user_code]	= $sale_user_code;
		$sls["sale_cust_code"]	= $sale_cust_code;
		$sls[sale_cust_code_old]	= $sale_cust_code_old;
		$sls["sale_name"]			= $sale_name;
		$sls["sale_addr1"]		= $sale_addr1;
		$sls["sale_addr2"]		= $sale_addr2;
		$sls["sale_addr3"]		= $sale_addr3;
		$sls["sale_city"]			= $sale_city;
		$sls["sale_state"]		= $sale_state;
		$sls[sale_country]		= $sale_country;
		$sls["sale_zip"]			= $sale_zip;
		$sls["sale_tel"]			= $sale_tel;
		$sls["sale_slsrep"]		= $sale_slsrep;
		$sls[sale_term]			= $sale_term;
		$sls["sale_amt"]			= $sale_amt;
		$sls["sale_date"]			= $sale_date ;
		$sls["pick_prom_date"]	= $sale_prom_date;
		$sls["sale_tax_amt"]		= $sale_tax_amt;
		$sls["sale_freight_amt"]	= $sale_freight_amt;
		$sls[sale_shipvia]		= $sale_shipvia;
		$sls[sale_taxrate]		= $sale_taxrate;
		$sls["sale_comnt"]		= $sale_comnt;
		$itm_build_edit = $sls;
		$_SESSION["itm_build_edit"] = $itm_build_edit;

		$loc = "Location: itm_build.php?ty=e&sale_id=$itmbuild_id";
	} else {
		$s = new ItmBuilds();
		if ($arr = $s->getSales($itmbuild_id)) {
			$loc = "Location error.php?".urlencode("errno=3&errmsg=Sales number is already in database");
		} else {
			$sls = $_SESSION[itmbld_add];
			$sls["sale_id"]			= $itmbuild_id;
			$sls["sale_code"]			= $sale_code;
			$sls[sale_cust_po]		= $sale_cust_po;
			$sls[sale_user_code]	= $sale_user_code;
			$sls["sale_cust_code"]	= $sale_cust_code;
			$sls[sale_cust_code_old]	= $sale_cust_code_old;
			$sls["sale_name"]			= $sale_name;
			$sls["sale_addr1"]		= $sale_addr1;
			$sls["sale_addr2"]		= $sale_addr2;
			$sls["sale_addr3"]		= $sale_addr3;
			$sls["sale_city"]			= $sale_city;
			$sls["sale_state"]		= $sale_state;
			$sls[sale_country]		= $sale_country;
			$sls["sale_zip"]			= $sale_zip;
			$sls["sale_tel"]			= $sale_tel;
			$sls["sale_slsrep"]		= $sale_slsrep;
			$sls[sale_term]			= $sale_term;
			$sls["sale_amt"]			= $sale_amt;
			$sls["sale_date"]			= $sale_date ;
			$sls["pick_prom_date"]	= $sale_prom_date;
			$sls["sale_tax_amt"]		= $sale_tax_amt;
			$sls["sale_freight_amt"]	= $sale_freight_amt;
			$sls[sale_shipvia]		= $sale_shipvia;
			$sls[sale_taxrate]		= $sale_taxrate;
			$sls["sale_comnt"]		= $sale_comnt;
			$itm_build_add = $sls;
			$_SESSION["itm_build_add"] = $itm_build_add;
			$loc = "Location: itm_build.php?ty=a";
		}
	}
	header($loc);

} else if ($cmd == "itm_build_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$ibdtl = $_SESSION[itmbldtls_edit];
		for ($i=0;$i<count($ibdtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls["slsdtl_id"]				= $slsdtl_id;
				$sls["slsdtl_sale_id"]		= $itmbuild_id;
				$sls["slsdtl_item_code"]		= $slsdtl_item_code;
				$sls["slsdtl_item_desc"]		= $slsdtl_item_desc;
				$sls["slsdtl_qty"]			= $slsdtl_qty;
				$sls["slsdtl_cost"]			= $slsdtl_cost;
				if ($slsdtl_taxable != "t") $slsdtl_taxable = "f";
				$sls["slsdtl_taxable"]		= $slsdtl_taxable;
				array_push($arr, $sls);
			} else {
				array_push($arr, $ibdtl[$i]);
			}
		}
		$_SESSION[itmbldtls_edit] = $arr;

		$subtotal = 0;
		$taxtotal = 0;
		for ($i=0;$i<count($ibdtls_edit);$i++) {
			$subtotal += $ibdtls_edit[$i]["slsdtl_qty"] * $ibdtls_edit[$i]["slsdtl_cost"];
			if ($ibdtls_edit[$i]["slsdtl_taxable"]=="t") $taxtotal += $ibdtls_edit[$i]["slsdtl_qty"] * $ibdtls_edit[$i]["slsdtl_cost"];
		}
		$sales_edit = $_SESSION[itmbld_edit];
		$sales_edit["sale_amt"] = $subtotal;
		$sales_edit["sale_tax_amt"] = $taxtotal * $sales_edit[sale_taxrate] / 100;
		$_SESSION[itmbld_edit];

		$loc = "Location: sales_details.php?ty=e&ht=e&sale_id=$itmbuild_id&did=$did";

	} else {
		$arr = array();
		$ibdtl = $_SESSION[itmbldtls_add];
		for ($i=0;$i<count($ibdtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls["slsdtl_item_code"]		= $slsdtl_item_code;
				$sls["slsdtl_item_desc"]		= $slsdtl_item_desc;
				$sls["slsdtl_qty"]			= $slsdtl_qty;
				$sls["slsdtl_cost"]			= $slsdtl_cost;
				if ($slsdtl_taxable != "t") $slsdtl_taxable = "f";
				$sls["slsdtl_taxable"]		= $slsdtl_taxable;
				array_push($arr, $sls);
			} else {
				array_push($arr, $ibdtl[$i]);
			}
		}
		$ibdtls_add = $arr;
		$_SESSION["ibdtls_add"] = $ibdtls_add;

		$subtotal = 0;
		$taxtotal = 0;
		for ($i=0;$i<count($ibdtls_edit);$i++) {
			$subtotal += $ibdtls_add[$i]["slsdtl_qty"] * $ibdtls_add[$i]["slsdtl_cost"];
			if ($ibdtls_add[$i]["slsdtl_taxable"]=="t") $taxtotal += $ibdtls_add[$i]["slsdtl_qty"] * $ibdtls_add[$i]["slsdtl_cost"];
		}
		$itm_build_add = $_SESSION["itm_build_add"];
		$itm_build_add["sale_amt"] = $subtotal;
		$itm_build_add["sale_tax_amt"] = $taxtotal * $itm_build_add["sale_taxrate"] / 100;
		$_SESSION["itm_build_add"] = $itm_build_add;

		$loc = "Location: sales_details.php?ty=e&ht=a&did=$did";

	}
	header($loc);

}
?>