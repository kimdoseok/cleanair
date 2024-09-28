<?php
if ($cmd == "sale_sess_add") {
	if ($ht=="e") {
		$s = new Sales();
		$sls = $_SESSION[sales_edit];
		$sls["sale_id"]			= $sale_id;
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
		$sls["sale_amt"]			= $sale_amt;
		$sls["sale_date"]			= $sale_date ;
		$sls["sale_tax_amt"]		= $sale_tax_amt;
		$sls["sale_freight_amt"]	= $sale_freight_amt;
		$sls[sale_shipvia]		= $sale_shipvia;
		$sls[sale_taxrate]		= $sale_taxrate;
		$sls["sale_comnt"]		= $sale_comnt;
		$sales_edit = $sls;
		$_SESSION["sales_edit"] = $sales_edit;

		$loc = "Location: sales_details.php?ty=a&ht=e&sale_id=$sale_id";
	} else {
		$sls = $_SESSION["sales_add"];
		$sls["sale_id"]			= $sale_id;
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
		$sls["sale_amt"]			= $sale_amt;
		$sls["sale_date"]			= $sale_date ;
		$sls["sale_tax_amt"]		= $sale_tax_amt;
		$sls["sale_freight_amt"]	= $sale_freight_amt;
		$sls[sale_shipvia]		= $sale_shipvia;
		$sls[sale_taxrate]		= $sale_taxrate;
		$sls["sale_comnt"]		= $sale_comnt;
		$sales_add = $sls;
		$_SESSION["sales_add"] = $sales_add;
		$loc = "Location: sales_details.php?ty=a&ht=a";
	}
	header($loc);

} else if ($cmd == "sale_detail_sess_add") {
	if ($ht == "e") {
		$sale = $_SESSION[sales_edit];
		$saledtl = $_SESSION[saledtls_edit];
		$dtl = array();
		for ($i=0;$i<count($saledtl);$i++) if (!empty($saledtl[$i])) array_push($dtl, $saledtl[$i]);
		$sls = array();
		$sls["slsdtl_sale_id"]		= $sale_id;
		$sls["slsdtl_item_code"]		= $slsdtl_item_code;
		$sls["slsdtl_item_desc"]		= $slsdtl_item_desc;
		$sls["slsdtl_qty"]			= $slsdtl_qty;
		$sls["slsdtl_cost"]			= $slsdtl_cost;
		if ($slsdtl_taxable != "t") $slsdtl_taxable = "f";
		$sls["slsdtl_taxable"]		= $slsdtl_taxable;
		array_push($dtl, $sls);
		$saledtls_edit = $dtl;
		$_SESSION["saledtls_edit"] = $saledtls_edit;

		$sale["sale_amt"] += $slsdtl_qty * $slsdtl_cost;
		if ($slsdtl_taxable == "t") $sale["sale_tax_amt"] += $slsdtl_qty * $slsdtl_cost * $sale[sale_taxrate]/ 100;
		$sales_edit = $sale;
		$_SESSION["sales_edit"] = $sales_edit;
		$loc = "Location: sales_details.php?ty=a&ht=e&sale_id=$sale_id";
	} else {
		if (empty($slsdtl_item_code)) {
			$errno=4;
			$errmsg="Item code should not be blank!";
			include("error.php");
			exit;
		} else {
			$sale = $_SESSION["sales_add"];
			$saledtl = $_SESSION["saledtls_add"];
			$dtl = array();
			for ($i=0;$i<count($saledtl);$i++) if (!empty($saledtl[$i])) array_push($dtl, $saledtl[$i]);
			$sls = array();
			$sls["slsdtl_item_code"]		= $slsdtl_item_code;
			$sls["slsdtl_item_desc"]		= $slsdtl_item_desc;
			$sls["slsdtl_qty"]			= $slsdtl_qty;
			$sls["slsdtl_cost"]			= $slsdtl_cost;
			if ($slsdtl_taxable != "t") $slsdtl_taxable = "f";
			$sls["slsdtl_taxable"]		= $slsdtl_taxable;
			array_push($dtl, $sls);
			$saledtls_add = $dtl;
			$_SESSION["saledtls_add"] = $saledtls_add;

			$sale["sale_amt"] += $slsdtl_qty * $slsdtl_cost;
			if ($slsdtl_taxable == "t") $sale["sale_tax_amt"] += $slsdtl_qty * $slsdtl_cost * $sale[sale_taxrate]/ 100;
			$sales_edit = $sale;
			$_SESSION["sales_edit"] = $sales_edit;
			$loc = "Location: sales_details.php?ty=a&ht=a";
		}
	}
	header($loc);

} else if ($cmd == "sale_add") {
	$s = new Sales();
	$d = new SaleDtls();
	$parr = $s->getSales($sale_id);

	$oldrec = $s->getTextFields("", "");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$sid = $s->insertSales($arr);
	if (empty($sale_id)) $sale_id = $sid;
	$sdtls = $_SESSION["saledtls_add"];
	for ($i=0;$i<count($sdtls);$i++) {
		$sdtls[$i]["slsdtl_sale_id"] = $sale_id;
		$lastid = $d->insertSaleDtls($sdtls[$i]);
		if ($lastid <= 0) {
			$errno=6;
			$errmsg="Sales Detail Insertion Error";
			include("error.php");
			exit;
		}
	}
/*
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($sale_id, "r");

	$j->insertJrnlTrxExs($sale_id, $_SERVER["PHP_AUTH_USER"], $default["astr_acct"], "r", "d", $sale_amt, $sale_date); // ar for sale
	$j->insertJrnlTrxExs($sale_id, $_SERVER["PHP_AUTH_USER"], $default["ints_acct"], "r", "c", $sale_amt, $sale_date); // income for sale
*/
	unset($sales_add);
	session_unregister("sales_add");
	unset($saledtls_add);
	session_unregister("saledtls_add");
	$loc = "Location: sales.php?ty=a";
	header($loc);

} else if ($cmd == "sale_edit") {
	$s = new Sales();
	$d = new SaleDtls();
	$sarr = $s->getSales($sale_id);
	if (!$sarr) {
		$errno=4;
		$errmsg="Sales id is not in database";
		include("error.php");
		exit;
	}
	$oldrec = $s->getTextFields("", "$sale_id");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	if (count($arr)>0) $s->updateSales($sale_id, $arr);
	//$d->deleteSaleDtlsSI($sale_id);
	$sdtls = $_SESSION[saledtls_edit];
	$sdtl_num = count($sdtls);
	$amt = 0;
	$sd_arr = $d->getSaleDtlHdrs($sale_id);
	if (!empty($sd_arr)) $sd_num = count($sd_arr);
	else $sd_num = 0;

	for ($i=0;$i<$sdtl_num;$i++) {
		$sdtls[$i]["slsdtl_sale_id"] = $sale_id;
		$amt += $sdtls["slsdtl_cost"];
		$found = 0;
		for ($j=0;$j<$sd_num;$j++) {
			if ($sd_arr[$j]["slsdtl_id"] == $sdtls[$i]["slsdtl_id"]) {
				$found = 1;
				break;
			}
		}

		if ($found>0) {
			if (!$d->updateSaleDtls($sdtls[$i]["slsdtl_id"], $sdtls[$i])) {
				$errno=6;
				$errmsg="Sales Detail Updating Error";
				include("error.php");
				exit;
			}
		} else {
			if (!empty($sdtls[$i]["Item_no"]) && !$d->insertSaleDtls($sdtls[$i])) {
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
			if (!$d->deleteSaleDtls($sd_arr[$i]["slsdtl_id"])) {
				$errno=8;
				$errmsg="Sales Detail deleting Error";
				include("error.php");
				exit;
			}
		} else if ($sd_arr[$i]["slsdtl_item_code"]) {
			if (!$d->deleteSaleDtls($sd_arr[$i]["slsdtl_id"])) {
				$errno=8;
				$errmsg="Sales Detail deleting Error";
				include("error.php");
				exit;
			}
		}
	}
/*
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($sale_id, "r");

	$j->insertJrnlTrxExs($sale_id, $_SERVER["PHP_AUTH_USER"], $default["astr_acct"], "r", "d", $sale_amt, $sale_date); // ar for sale
	$j->insertJrnlTrxExs($sale_id, $_SERVER["PHP_AUTH_USER"], $default["ints_acct"], "r", "c", $sale_amt, $sale_date); // income for sale
*/	
	session_unregister("sales_edit");
	unset($sales_edit);
	session_unregister("saledtls_edit");
	unset($saledtls_edit);

	$loc = "Location: sales.php?ty=e&sale_id=$sale_id";
	header($loc);

} else if ($cmd == "sale_del") {
	include_once("class/class.sales.php");
	$s = new Sales();
	include_once("class/class.saledtls.php");
	$d = new SaleDtls();
	$s->deleteSales($sale_id);
	$d->deleteSaleDtlsSI($sale_id);
//	$j = new JrnlTrxs();
//	$j->deleteJrnlTrxRefs($sale_id, "r");
	
	unset($sales_edit);
	session_unregister("sales_edit");
	unset($saledtls_edit);
	session_unregister("saledtls_edit");
	$loc = "Location: sales.php?ty=l";
	header($loc);

} else if ($cmd == "sale_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$saledtl = $_SESSION[saledtls_edit];
		for ($i=0;$i<count($saledtl);$i++) {
			if ($i != $did) {
				array_push($arr, $saledtl[$i]);
			} else {
				$sale = $_SESSION[sales_edit];
				$sale["sale_amt"] -= $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"];
				if ($saledtl[$i]["slsdtl_taxable"] == "t") $sale["sale_tax_amt"] -= $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"] * $sale[sale_taxrate]/ 100;
				$sales_edit = $sale;
				$_SESSION["sales_edit"] = $sales_edit;

			}
		}
		$saledtls_edit = $arr;
		$_SESSION["saledtls_edit"] = $saledtls_edit;
		$loc = "Location: sales.php?ty=e&sale_id=$sale_id";
	} else {
		$arr = array();
		$saledtl = $_SESSION["saledtls_add"];
		for ($i=0;$i<count($saledtl);$i++) {
			if ($i != $did) {
				array_push($arr, $saledtl[$i]);
			} else {
				$sale = $_SESSION["sales_add"];
				$sale["sale_amt"] -= $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"];
				if ($saledtl[$i]["slsdtl_taxable"] == "t") $sale["sale_tax_amt"] -= $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"] * $sale[sale_taxrate]/ 100;
				$sales_edit = $sale;
				$_SESSION["sales_edit"] = $sales_edit;
			}
		}
		$saledtls_add = $arr;
		$_SESSION["saledtls_add"] = $saledtls_add;
		$loc = "Location: sales.php?ty=a";
	}
	header($loc);

} else if ($cmd == "sale_clear_sess_edit") {
	session_unregister("sales_edit");
	session_unregister("saledtls_edit");
	$loc = "Location: sales.php?ty=e&sale_id=$sale_id";
	header($loc);

} else if ($cmd == "sale_clear_sess_add") {
	session_unregister("sales_add");
	session_unregister("saledtls_add");
	$loc = "Location: sales.php?ty=a";
	header($loc);

} else if ($cmd == "sale_update_sess_add") {
	include_once("class/class.sales.php");
	if ($ty=="e") {
		$s = new Sales();
		$sls = $_SESSION[sales_edit];
		$sls["sale_id"]			= $sale_id;
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
		$sls["sale_amt"]			= $sale_amt;
		$sls["sale_date"]			= $sale_date ;
		$sls["sale_tax_amt"]		= $sale_tax_amt;
		$sls["sale_freight_amt"]	= $sale_freight_amt;
		$sls[sale_shipvia]		= $sale_shipvia;
		$sls[sale_taxrate]		= $sale_taxrate;
		$sls["sale_comnt"]		= $sale_comnt;
		$sales_edit = $sls;
		$_SESSION["sales_edit"] = $sales_edit;

		$loc = "Location: sales.php?ty=e&sale_id=$sale_id";
	} else {
		$s = new Sales();
		if ($arr = $s->getSales($sale_id)) {
			$loc = "Location error.php?".urlencode("errno=3&errmsg=Sales number is already in database");
		} else {
			$sls = $_SESSION["sales_add"];
			$sls["sale_id"]			= $sale_id;
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
			$sls["sale_amt"]			= $sale_amt;
			$sls["sale_date"]			= $sale_date ;
			$sls["sale_tax_amt"]		= $sale_tax_amt;
			$sls["sale_freight_amt"]	= $sale_freight_amt;
			$sls[sale_shipvia]		= $sale_shipvia;
			$sls[sale_taxrate]		= $sale_taxrate;
			$sls["sale_comnt"]		= $sale_comnt;
			$sales_add = $sls;
			$_SESSION["sales_add"] = $sales_add;

			$loc = "Location: sales.php?ty=a";
		}
	}
	header($loc);

} else if ($cmd == "sale_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$saledtl = $_SESSION[saledtls_edit];
		for ($i=0;$i<count($saledtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls["slsdtl_sale_id"]		= $sale_id;
				$sls["slsdtl_item_code"]		= $slsdtl_item_code;
				$sls["slsdtl_item_desc"]		= $slsdtl_item_desc;
				$sls["slsdtl_qty"]			= $slsdtl_qty;
				$sls["slsdtl_cost"]			= $slsdtl_cost;
				if ($slsdtl_taxable != "t") $slsdtl_taxable = "f";
				$sls["slsdtl_taxable"]		= $slsdtl_taxable;
				array_push($arr, $sls);
			} else {
				array_push($arr, $saledtl[$i]);
			}
		}
		$saledtls_edit = $arr;
		$_SESSION["saledtls_edit"] = $saledtls_edit;

		$subtotal = 0;
		$taxtotal = 0;
		for ($i=0;$i<count($saledtls_edit);$i++) {
			$subtotal += $saledtls_edit[$i]["slsdtl_qty"] * $saledtls_edit[$i]["slsdtl_cost"];
			if ($saledtls_edit[$i]["slsdtl_taxable"]=="t") $taxtotal += $saledtls_edit[$i]["slsdtl_qty"] * $saledtls_edit[$i]["slsdtl_cost"];
		}
		$sales_edit = $_SESSION["sales_edit"];
		$sales_edit["sale_amt"] = $subtotal;
		$sales_edit["sale_tax_amt"] = $taxtotal * $sales_edit[sale_taxrate] / 100;
		$_SESSION["sales_edit"] = $sales_edit;

		$loc = "Location: sales_details.php?ty=e&ht=e&sale_id=$sale_id&did=$did";

	} else {
		$arr = array();
		$saledtl = $_SESSION["saledtls_add"];
		for ($i=0;$i<count($saledtl);$i++) {
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
				array_push($arr, $saledtl[$i]);
			}
		}
		$saledtls_add = $arr;
		$_SESSION["saledtls_add"] = $saledtls_add;

		$subtotal = 0;
		$taxtotal = 0;
		for ($i=0;$i<count($saledtls_edit);$i++) {
			$subtotal += $saledtls_add[$i]["slsdtl_qty"] * $saledtls_add[$i]["slsdtl_cost"];
			if ($saledtls_add[$i]["slsdtl_taxable"]=="t") $taxtotal += $saledtls_add[$i]["slsdtl_qty"] * $saledtls_add[$i]["slsdtl_cost"];
		}
		$sales_add = $_SESSION["sales_add"];
		$sales_add["sale_amt"] = $subtotal;
		$sales_add["sale_tax_amt"] = $taxtotal * $sales_add[sale_taxrate] / 100;
		$_SESSION["sales_add"] = $sales_add;

		$loc = "Location: sales_details.php?ty=e&ht=a&did=$did";

	}
	header($loc);

} else if ($cmd == "sale_print") {
	$s = new Sales();
	$recs = $s->getSales($sale_id);
	$c = new Custs();
	$cust_arr = $c->getCusts($recs["sale_cust_code"]);
	$sd = new SaleDtls();
	$sd_arr = $sd->getSaleDtlsList($sale_id);

	$cols = 80;
	$page = array();
	$page_len = 42;
	$line_len = 12;
	
	$sd_num = count($sd_arr);
	$page_num = ceil($sd_num/$page_len);

	for ($j=0;$j<$page_num;$j++) {
		$page[$j] = "";
		$page[$j] .= "CLEANAIR SUPPLY, INC.\n";
		$page[$j] .= "1301 E. Linden Ave., Linden, NJ 07036\n";
		$page[$j] .= "Tel:1-800-435-0581(NY/NJ) 908-925-7722\n";
		$page[$j] .= "Fax: 908-925-5535\n";
		$page[$j] .= str_repeat(" ", 54);
		$page[$j] .= str_pad(date("m/d/y"), 10, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($sale_id, 8, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($j+1, 2, " ", STR_PAD_LEFT);
		$page[$j] .= "\n";
		for ($i=0;$i<5;$i++) $page[$j] .= "\n";
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad($cust_arr["cust_name"], 30, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad($cust_arr["cust_name"], 30, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n";
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad($cust_arr["cust_addr1"], 30, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad($recs["sale_addr1"], 30, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n";

		if (empty($cust_arr["cust_addr2"]) && empty($recs["sale_addr2"])) {
			$blank_addr2 = 1;
		} else {
			$page[$j] .= str_repeat(" ", 6);
			$page[$j] .= str_pad($cust_arr["cust_addr2"], 30, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 6);
			$page[$j] .= str_pad($recs["sale_addr2"], 30, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n";
			$blank_addr2 = 0;
		}
		$blank_addr3 = 0;
		if (empty($cust_arr["cust_addr3"]) && empty($recs["sale_addr3"])) {
			$blank_addr3 = 1;
		} else {
			$page[$j] .= str_repeat(" ", 6);
			$page[$j] .= str_pad($cust_arr["cust_addr3"], 30, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 6);
			$page[$j] .= str_pad($recs["sale_addr3"], 30, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n";
			$blank_addr3 = 0;
		}
		$page[$j] .= str_repeat(" ", 6);
		$csz = $cust_arr["cust_city"].", ".$cust_arr["cust_state"]." ".$cust_arr["cust_zip"];
		$page[$j] .= str_pad($csz, 30, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 6);
		$csz = $recs["sale_city"].", ".$recs["sale_state"]." ".$recs["sale_zip"];
		$page[$j] .= str_pad($csz, 30, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n";
		for ($i=0;$i<3;$i++) $page[$j] .= "\n";
		$page[$j] .= str_repeat(" ", 6);
		$page[$j] .= str_pad(date("m/d/y", strtotime($recs["sale_date"])), 10, " ", STR_PAD_CENTER);
		$page[$j] .= str_pad($recs["sale_cust_code"], 8, " ", STR_PAD_CENTER);
		$page[$j] .= str_pad($recs[sale_user_code], 10, " ", STR_PAD_CENTER);
		$page[$j] .= str_pad($recs[sale_shipvia], 16, " ", STR_PAD_CENTER);
		$page[$j] .= "\n";
		if ($blank_addr2==1) $page[$j] .= "\n";
		if ($blank_addr3==1) $page[$j] .= "\n";

		for ($i=0;$i<2;$i++) $page[$j] .= "\n";

		$start = $j*$line_len;
		if ($j+1 == $page_num) $end = count($sd_arr);
		else $end = ($j+1)*$line_len;
		for ($k=$start;$k<$end;$k++) {
			$page[$j] .= str_pad($sd_arr[$k]["slsdtl_item_code"], 15, " ", STR_PAD_RIGHT);
			$page[$j] .= str_pad(substr($sd_arr[$k]["slsdtl_item_desc"],0,34), 34, " ", STR_PAD_RIGHT);
			$page[$j] .= str_pad($sd_arr[$k]["slsdtl_qty"]+0, 8, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(sprintf("%0.2f", $sd_arr[$k]["slsdtl_cost"]), 10, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(sprintf("%0.2f", $sd_arr[$k]["slsdtl_qty"]*$sd_arr[$k]["slsdtl_cost"]), 12, " ", STR_PAD_LEFT);
			$page[$j] .= "\n";
		}	
		if ($j+1 == $page_num) {
			$res = $line_len - ($sd_num % $line_len);
			for ($k=0; $k<$res; $k++) $page[$j] .= "\n";
			for ($i=0;$i<3;$i++) $page[$j] .= "\n";
			$page[$j] .= str_pad(sprintf("%0.2f", $recs[x]), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(sprintf("%0.2f", $recs[x]), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(sprintf("%0.2f", $recs[x]), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(sprintf("%0.2f", $recs[x]), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(sprintf("%0.2f", $recs[x]), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(sprintf("%0.2f", $recs["sale_amt"]), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n";
			$page[$j] .= "\n";
			$page[$j] .= str_pad(sprintf("%0.2f", $recs[$k]["sale_freight_amt"]), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(sprintf("%0.2f", $recs[$k]["sale_tax_amt"]), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_repeat(" ", 39);
			$page[$j] .= str_pad(sprintf("%0.2f", $recs["sale_amt"] + $recs[$k]["sale_freight_amt"] + $recs[$k]["sale_tax_amt"]), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n";

		} else {
			for ($i=0;$i<9;$i++) $page[$j] .= "\n";
		}
		$page[$j] .= "\n";
		$page[$j] .= "\n";
		$page[$j] .= "\n";
	}
	$out = "";
	for ($i=0;$i<$page_num;$i++) {
		$out .= $page[$i];
	}

	list($usec,$sec)=explode(" ",microtime());
	$filename = "sale_".$sec.substr($usec, 2,6).".txt";
	$f=fopen ($filename,"w");
	fputs($f,$out,strlen($out));
	fclose($f);
	exec("copy $filename lpt3");
	exec("del $filename ");
echo "<pre>";
echo "$out";
echo "</pre>";
print_r($page);
print (substr_count("$page[0]", "\n"));

}
?>