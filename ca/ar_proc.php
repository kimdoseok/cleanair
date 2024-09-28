<?php
include_once("class/map.default.php");
include_once("class/class.datex.php");
include_once("class/class.customers.php");
include_once("class/class.custships.php");
include_once("class/class.requests.php");
include_once("class/class.receipt.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.picks.php");
include_once("class/class.pickdtls.php");
include_once("class/class.cmemo.php");
include_once("class/class.cmemodtls.php");
include_once("class/class.saledtls.php");
include_once("class/class.sales.php");
include_once("class/class.items.php");
include_once("class/class.invoices.php");
include_once("class/class.taxrates.php");
include_once("class/class.salesreps.php");
include_once("class/class.lpr.php");

include_once("class/register_globals.php");


$errno = 0;
$comp = $default["comp_code"];
if (!array_key_exists($comp, $_SESSION)) $_SESSION[$comp]=array();

function makeInvoices($pick_id) {
	$arr = array("invoice_pick_id"=>"", "invoice_user_code"=>"", "invoice_date"=>"", "invoice_fin"=>"");	
	$arr[invoice_pick_id] = $pick_id;
	$arr[invoice_user_code] = $_SERVER["PHP_AUTH_USER"];
	$arr[invoice_date] = date("Y-m-d");
	$arr[invoice_fin] = "f";
	$i = new Invoices();
	if ($i->getInvoicesPick($pick_id)) {
		$errno=10;
		$errmsg= "Invoice with this Picking number($pick_id) exist already in DB!";
		include("error.php");
		exit;
	}
	if ($i->insertInvoices($arr)) {
		$p = new Picks();
		$sarr = $p->getPicks($pick_id);
		if (count($sarr)==0) {
			$errno=4;
			$errmsg="Pick number is not in database";
			include("error.php");
			exit;
		}
		$arr = array("pick_fin"=>"","pick_delv_date "=>"");
		$arr["pick_fin"] = "t";
		$arr["pick_delv_date"] = $pick_delv_date;
		if (count($arr)>0) $p->updatePicks($pick_id, $arr);

		// transaction journal...
		$j = new JrnlTrxs();
		$j->deleteJrnlTrxRefs($pick_id, "r");

		$pick_amt = $sarr["pick_amt"]+$sarr["pick_freight_amt"]+$sarr["pick_tax_amt"];
		$j->insertJrnlTrxExs($pick_id, $_SERVER["PHP_AUTH_USER"], $default["astr_acct"], "r", "d", $pick_amt, $pick_date); // ar for sale
		$j->insertJrnlTrxExs($pick_id, $_SERVER["PHP_AUTH_USER"], $default["ints_acct"], "r", "c", $pick_amt, $pick_date); // income for sale

	} else {
		$errno=11;
		$errmsg= "Can't make invoice!";
		include("error.php");
		exit;
	}

}

include_once("common_proc.php");

if ($cmd =="cust_edit") {
	$c = new Custs();
	$r = new Requests();
	if (empty($cust_code)) {
		$errno=11;
		$errmsg= "Customer code shouldn't be empty!";
		include("error.php");
		exit;
	}
	$arr = array();
	if ($oldrec = $c->getCusts($cust_code)) {
		if ($_POST["cust_marketing"]!="t") $_POST["cust_marketing"]="f";
		if ($_POST["cust_arinfo"]!="t") $_POST["cust_arinfo"]="f";
		unset($_POST["cust_init_bal"]);
		$arr = $r->getAlteredArray($oldrec, $_POST);
		$c->updateCusts($cust_code, $arr); 
		$c->updateCustsBalance($cust_code);
	} else {
		$errno = 2;
		$errmsg = "Couldn't find customer code entered.";
		include("error.php");
		exit;
	}

	//print_r($_SERVER);
	$loc = "Location: customers.php?ty=e&cust_code=$cust_code";
	header($loc);
	exit;

} else if ($cmd =="cust_add") {
	$c = new Custs();
	$r = new Requests();
	$arr = array();
	$cust_coe = trim($cust_code);
	if (empty($cust_code)) {
		$errno=11;
		$errmsg= "Customer code shouldn't be empty!";
		include("error.php");
		exit;
	}

	if ($check = $c->getCusts($cust_code)) {
		$errno = 1;
		$errmsg = "Customer code should be unique";
		include("error.php");
		exit;
	} else {
		//$arr = $r->getAlteredArray($_SESSION["olds"], $_POST); 
		$arr = $r->getAlteredArray($r->getConvertArray($c->getARFields()), $_POST); 
		$arr["cust_created"] = date("Y-m-d");
		$arr["cust_balance"] = $_POST["cust_init_bal"];
		$c->insertCusts($arr); 
	}

	$loc = "Location: customers.php?ty=e&cust_code=$cust_code";
	header($loc);
	exit;

} else if ($cmd =="cust_del") {
	$c = new Custs();
	$s = new Sales();
	$p = new Picks();
	$m = new Cmemo();
	$r = new Receipt();

	if (empty($cust_code)) {
		$errno=11;
		$errmsg= "Customer code shouldn't be empty!";
		include("error.php");
		exit;
	}
	if ($check = $c->getCusts($cust_code)) {
		if ($check = $s->getSalesCount($cust_code)) {
			$errno = 3;
			$errmsg = "Customer code($cust_code) has been used in sales already.";
			include("error.php");
			exit;
		}
		if ($check = $p->getPicksCount($cust_code)) {
			$errno = 4;
			$errmsg = "Customer code($cust_code) has been used in picking ticket already.";
			include("error.php");
			exit;
		}
		if ($check = $m->getCmemoCount($cust_code)) {
			$errno = 5;
			$errmsg = "Customer code($cust_code) has been used in credit memo already.";
			include("error.php");
			exit;
		}
		if ($check = $r->getReceiptCount($cust_code)) {
			$errno = 6;
			$errmsg = "Customer code($cust_code) has been used in cash receipt already.";
			include("error.php");
			exit;
		}
		if (!$c->deleteCusts($cust_code)) {
			$errno = 7;
			$errmsg = "There is an error on deleting customer($cust_code).";
			include("error.php");
			exit;
		}
	} else {
		$errno = 2;
		$errmsg = "Couldn't find customer code entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: customers.php?ty=l";
	header($loc);
	exit;

} else if ($cmd =="custship_add") {
	if (empty($cust_code)) {
		$errno=11;
		$errmsg= "Customer code shouldn't be empty!";
		include("error.php");
		exit;
	}
	$c = new CustShips();
	$r = new Requests();
	$arr = array();
	$blank = $c->getCustShipsFields();
	$arr = $r->getAlteredArray($blank, $_POST); 
	$arr["custship_cust_code"]=$cust_code;
	$id = $c->insertCustShips($arr); 
	$loc = "Location: custships.php?ty=e&cust_code=$cust_code&custship_id=$id";
	header($loc);
	exit;

} if ($cmd =="custship_edit") {
	if (empty($cust_code)) {
		$errno=11;
		$errmsg= "Customer code shouldn't be empty!";
		include("error.php");
		exit;
	}
	if (empty($custship_id)) {
		$errno=12;
		$errmsg= "Customer Shipping address should be selected!";
		include("error.php");
		exit;
	}
	$c = new CustShips();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getCustShips($custship_id, $cust_code)) {
		$arr = $r->getAlteredArray($check, $_POST); 
		$c->updateCustShips($custship_id, $arr); 
	}
	$loc = "Location: custships.php?ty=e&cust_code=$cust_code&custship_id=$custship_id";
	header($loc);
	exit;

} if ($cmd =="custship_del") {
	if (empty($cust_code)) {
		$errno=11;
		$errmsg= "Customer code shouldn't be empty!";
		include("error.php");
		exit;
	}
	if (empty($custship_id)) {
		$errno=12;
		$errmsg= "Customer Shipping address should be selected!";
		include("error.php");
		exit;
	}
	$c = new CustShips();
	$arr = array();
	if ($check = $c->getCustShips($custship_id, $cust_code)) {
		$c->deleteCustShips($custship_id); 
	}
	$loc = "Location: customers.php?ty=e&cust_code=$cust_code";
	header($loc);
	exit;

} else if ($cmd =="slsrep_edit") {
	if (empty($slsrep_code)) {
		$errno=13;
		$errmsg= "Sales Rep's code shouldn't be blank!";
		include("error.php");
		exit;
	}
	$c = new SalesReps();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getSalesReps($slsrep_code)) {
		$oldrec = $_SESSION["olds"];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		$c->updateSalesReps($slsrep_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find sales rep code entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: salesreps.php?ty=e&slsrep_code=$slsrep_code";
	header($loc);
	exit;

} else if ($cmd =="slsrep_add") {
	if (empty($slsrep_code)) {
		$errno=13;
		$errmsg= "Sales Rep's code shouldn't be blank!";
		include("error.php");
		exit;
	}
	$c = new SalesReps();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getSalesReps($slsrep_code)) {
		$errno = 1;
		$errmsg = "Sales Rep code should be unique";
		include("error.php");
		exit;
	} else {
		$arr = $r->getAlteredArray($olds, $_POST); 
		$c->insertSalesReps($arr); 
	}

	$loc = "Location: salesreps.php?slsrep_code=$slsrep_code";
	header($loc);
	exit;

} else if ($cmd =="taxrate_edit") {
	if (empty($taxrate_code)) {
		$loc = "Location: taxrates.php?ty=l";
		header($loc);
		exit;
	}
	$c = new TaxRates();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getTaxRates($taxrate_code)) {
		$olds = $default["comp_code"]."_olds";
		$arr = $r->getAlteredArray($_SESSION[$olds], $_POST); 
		$c->updateTaxRates($taxrate_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find tax rate code entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: taxrates.php?ty=$ty&taxrate_code=$taxrate_code";
	header($loc);
	exit;

} else if ($cmd =="taxrate_add") {
	if (empty($taxrate_code)) {
		$loc = "Location: taxrates.php?ty=a";
		header($loc);
		exit;
	}
	$c = new TaxRates();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getTaxRates($taxrate_code)) {
		$errno = 1;
		$errmsg = "Tax rate code should be unique";
		include("error.php");
		exit;
	} else {
		$olds = $default["comp_code"]."_olds";
		$arr = $r->getAlteredArray($_SESSION[$olds], $_POST); 
		$c->insertTaxRates($arr); 
	}

	$loc = "Location: taxrates.php?taxrate_code=$taxrate_code";
	header($loc);
	exit;

} else if ($cmd =="rcpts_edit") {
	if (empty($rcpt_id)) {
		$loc = "Location: cash_receipt.php?ty=l";
		header($loc);
		exit;
	}
	$c = new Rcpts();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getRcpts($rcpt_id)) {
		$oldrec = $_SESSION["olds"];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		$c->updateRcpts($rcpt_id, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find cash receipt number entered.";
		include("error.php");
	}

	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($rcpt_id, "c");
	$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $default["astr_acct"], "c", "c", $rcpt_amt, $rcpt_date); // ar for receipt
	$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $rcpt_acct_code, "c", "d", $rcpt_amt, $rcpt_date); // account for receipt

	$loc = "Location: cash_receipt.php?ty=e&rcpt_id=$rcpt_id";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="rcpts_add") {
	$c = new Rcpts();
	$r = new Requests();
	$v = new Custs();
	$arr = array();

	if (!$check = $v->getCusts($rcpt_cust_code)) {
		$errno = 10;
		$errmsg = "Customer code is not found in DB!";
		include("error.php");
		exit;
	}

	$oldrec = $_SESSION["olds"];
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	if (!$rcpt_id = $c->insertRcpts($arr)) {
		$errno = 1;
		$errmsg = "Cash Receipt Insertion Failure";
		include("error.php");
		exit;
	}

	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($rcpt_id, "c");
	$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $default["astr_acct"], "c", "c", $rcpt_amt, $rcpt_date); // ar for receipt
	$j->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $rcpt_acct_code, "c", "d", $rcpt_amt, $rcpt_date); // account for receipt
	$loc = "Location: cash_receipt.php?ty=a";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="rcpts_del") {
	$c = new Rcpts();
	if ($check = $c->getRcpts($rcpt_id)) {
		$c->deleteRcpts($rcpt_id);
		$j = new JrnlTrxs();
		$j->deleteJrnlTrxRefs($rcpt_id, "c");
	} else {
		$errno = 6;
		$errmsg = "Cash Receipt Delete Failure";
		include("error.php");
	}
	$loc = "Location: cash_receipt.php?ty=l";
	if ($errno == 0) header($loc);
	exit;

//} else if (substr($cmd, 0, 5) == "cust_") {
//	include("custs_proc.php");

} else if (substr($cmd, 0,5) == "sale_") {
	include("sales_proc.php");

} else if (substr($cmd, 0,6) == "cmemo_") {
	include("cmemo_proc.php");

} else if (substr($cmd, 0,5) == "rcpt_") {
	include("receipt_proc.php");

} else if (substr($cmd, 0, 5) == "pick_") {
	include("picking_proc.php");

} else if (substr($cmd, 0, 8) == "openpay_") {
	include("openpayments_proc.php");

} else if ($cmd == "make_invoice") {
	makeInvoices($pick_id);
	$loc = "Location: picking.php?ty=v&pick_id=$pick_id";
	header($loc);
	exit;

} else {
	header("Location: $HTTP_REFERER");
	exit;
}
?>
