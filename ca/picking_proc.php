<?php
include_once("class/class.users.php");
include_once("class/class.openpay.php");
include_once("class/class.rcptdtls.php");
include_once("class/register_globals.php");

function pickPrint($pick_id, $port) {

	$p = new Picks();
	$cm = new Cmemo();
	$r = new Receipt();

	$recs = $p->getPicks($pick_id, $port);
	$p->increasePicks($pick_id, "pick_print", 1);
	$c = new Custs();
	$cust_arr = $c->getCusts($recs["pick_cust_code"]);
	$sd = new PickDtls();
	$sd_arr = $sd->getPickDtlsList($pick_id, "", "t");

	$cols = 80;
	$page = array();
	$page_len = 42;
	$line_len = 16;
	$col_width = 35;
	$line_idx = 0;
	
	$sd_num = count($sd_arr);
	$line_arr = array();

	$k = 0;
	for ($j=0;$j<$sd_num;$j++) {
		$desc_line = floor(strlen($sd_arr[$j]["slsdtl_item_desc"])/$col_width);
		$line_arr[$k] = "";
		$line_arr[$k] .= str_pad(substr(trim($sd_arr[$j]["slsdtl_item_code"]),0,12), 12, " ", STR_PAD_RIGHT);
		$line_arr[$k] .= str_pad(substr($sd_arr[$j]["slsdtl_item_desc"], 0, $col_width), $col_width, " ", STR_PAD_RIGHT);
		$line_arr[$k] .= str_pad($sd_arr[$j]["pickdtl_qty"]+0, 10, " ", STR_PAD_LEFT);
		$line_arr[$k] .= str_pad(strtoupper($sd_arr[$j]["slsdtl_unit"]), 2, " ", STR_PAD_LEFT);
		$line_arr[$k] .= str_pad(number_format($sd_arr[$j]["pickdtl_cost"], 2, ".", ","), 9, " ", STR_PAD_LEFT);
		$line_arr[$k] .= str_pad(number_format($sd_arr[$j]["pickdtl_qty"]*$sd_arr[$j]["pickdtl_cost"], 2, ".", ","), 11, " ", STR_PAD_LEFT);
		if ($sd_arr[$j]["slsdtl_taxable"]=="t") $line_arr[$k] .= "T";
		$line_arr[$k] .= "\n";
		$k++;
		for ($x=1;$x<$desc_line;$x++) {
			$line_arr[$k] = "";
			$line_arr[$k] .= str_repeat(" ", 12);
			$line_arr[$k] .= substr($sd_arr[$j]["slsdtl_item_desc"], $col_width*$x, $col_width);
			$line_arr[$k] .= "\n";
			$k++;
		}
	}

	$total_line = $k;
	$page_num = ceil($total_line/$line_len);
	$rem_lines = $total_line % $line_len;

	for ($j=0;$j<$page_num;$j++) {
		$page[$j] = "";
		$page[$j] .= str_repeat(" ", 60);
		$page[$j] .= "Picking Ticket\n"; // line #1
		$page[$j] .= "\n"; // line #2
		$page[$j] .= "\n"; // line #3
		$page[$j] .= "\n"; // line #4
		$page[$j] .= str_repeat(" ", 54);
		$page[$j] .= str_pad(date("m/d/y"), 11, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($pick_id, 8, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($j+1, 3, " ", STR_PAD_LEFT);
		$page[$j] .= "\n"; // line #5
		$page[$j] .= "\n"; // line #6
		$page[$j] .= "\n";
		$page[$j] .= str_pad($cust_arr["cust_name"], 39, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($recs["pick_name"], 39, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n";
		$page[$j] .= str_pad($cust_arr["cust_addr1"], 39, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($recs["pick_addr1"], 9, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n";
		$page[$j] .= "\n";

		if (empty($cust_arr["cust_addr2"]) && empty($recs["pick_addr2"])) {
			$blank_addr2 = 1;
		} else {
			$page[$j] .= str_pad($cust_arr["cust_addr2"], 39, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 2);
			$page[$j] .= str_pad($recs["pick_addr2"], 39, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n";
			$blank_addr2 = 0;
		}
		$blank_addr3 = 0;
		if (empty($cust_arr["cust_addr3"]) && empty($recs["pick_addr3"])) {
			$blank_addr3 = 1;
		} else {
			$page[$j] .= str_pad($cust_arr["cust_addr3"], 39, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 2);
			$page[$j] .= str_pad($recs["pick_addr3"], 39, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n";
			$blank_addr3 = 0;
		}
		$csz = $cust_arr["cust_city"].", ".$cust_arr["cust_state"]." ".$cust_arr["cust_zip"];
		$page[$j] .= str_pad($csz, 39, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 2);
		$csz = $recs["pick_city"].", ".$recs["pick_state"]." ".$recs["pick_zip"];
		$page[$j] .= str_pad($csz, 39, " ", STR_PAD_RIGHT);
		$page[$j] .= $recs["pick_tel"]."\n";
		if ($blank_addr2==1) $page[$j] .= "\n";
		if ($blank_addr3==1) $page[$j] .= "\n";

		$page[$j] .= "\n";
		$page[$j] .= str_pad($recs["pick_user_code"], 8, " ", STR_PAD_RIGHT);
		$page[$j] .= str_pad(date("m/d/y", strtotime($recs["pick_date"])), 10, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($recs["pick_cust_code"], 8, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($recs["sale_po_num"], 8, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($recs["sale_slsrep"], 8, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad(substr($recs["pick_shipvia"],0,15), 15, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad(date("m/d/y", strtotime($recs["pick_delv_date"])), 10, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($cust_arr["cust_term"], 6, " ", STR_PAD_CENTER);
		if ($blank_addr2==1) $page[$j] .= "\n";
		if ($blank_addr3==1) $page[$j] .= "\n";
		$page[$j] .= "\n";

		for ($k=$j*$line_len;$k<($j+1)*$line_len;$k++) {
			$page[$j] .= $line_arr[$k];
		}

		if ($j+1 == $page_num) {
			if (($total_line % $line_len)==0) $rem_lines = 0;
			else $rem_lines = $line_len - ($total_line % $line_len);

			for ($k=0; $k<$rem_lines; $k++) $page[$j] .= "\n";
			$dx = new Datex();
/*
			$day0 = date("Y-m-d");
			$day30 = $dx->getIsoDate($day0,30,"b");
			$day60 = $dx->getIsoDate($day0,60,"b");
			$day90 = $dx->getIsoDate($day0,90,"b");
			$created = $dx->toIsoDate($cust_arr["cust_created"]);

			$pick90over = $p->getPicksSumAged($recs["pick_cust_code"], "", $day90, "t", "f")+0;
			$pick90 = $p->getPicksSumAged($recs["pick_cust_code"], $day90, $day60, "t", "f")+0;
			$pick60 = $p->getPicksSumAged($recs["pick_cust_code"], $day60, $day30, "t", "f")+0;
			$pick30 = $p->getPicksSumAged($recs["pick_cust_code"], $day30, $day0, "t", "t")+0;
			$pick0 = $p->getPicksSumAged($recs["pick_cust_code"], $day0, "", "f", "t")+0;

			if (strtotime($created) <= strtotime($day0) && strtotime($created) > strtotime($day30) ) $pick30 += $cust_arr["cust_init_bal"];
			else if (strtotime($created) <= strtotime($day30) && strtotime($created) > strtotime($day60) ) $pick60 += $cust_arr["cust_init_bal"];
			if (strtotime($created) <= strtotime($day60) && strtotime($created) > strtotime($day90) ) $pick90 += $cust_arr["cust_init_bal"];
			if (strtotime($created) <= strtotime($day90)) $pick90over += $cust_arr["cust_init_bal"];

			$rcpt_sum = $r->getReceiptSumAged($recs["pick_cust_code"], "", "", "t", "t");
			$cmemo_sum = $cm->getCmemoSumAged($recs["pick_cust_code"], "", "", "t", "t");
			$pick_sum = $pick0 + $pick30 + $pick60 + $pick90 + $pick90over;
			$balance = $pick_sum - $rcpt_sum - $cmemo_sum;

			$t_bal = $balance;
			if ($t_bal > $pick0) {
				$bal0 = $pick0;
				$t_bal -= $bal0;
			} else {
				$bal0 = $t_bal;
				$t_bal = 0;
			}
			if ($t_bal > $pick30) {
				$bal30 = $pick30;
				$t_bal -= $bal30;
			} else {
				$bal30 = $t_bal;
				$t_bal = 0;
			}
			if ($t_bal > $pick60) {
				$bal60 = $pick60;
				$t_bal -= $bal60;
			} else {
				$bal60 = $t_bal;
				$t_bal = 0;
			}
			if ($t_bal > $pick90) {
				$bal90 = $pick90;
				$t_bal -= $bal90;
			} else {
				$bal90 = $t_bal;
				$t_bal = 0;
			}
			$bal90over = $t_bal;

			$page[$j] .= str_pad(number_format($bal30,2,".",","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($bal60,2,".",","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($bal90,2,".",","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($bal90over,2,".",","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= str_pad(number_format($balance,2,".",","), 13, " ", STR_PAD_LEFT);
*/
			$page[$j] .= str_repeat(" ", 65);
			$page[$j] .= str_pad(number_format($recs["pick_amt"], 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n";
			$page[$j] .= str_repeat(" ", 65);
			$page[$j] .= str_pad(number_format($recs["pick_freight_amt"], 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n";
			$page[$j] .= str_repeat(" ", 65);
			$page[$j] .= str_pad(number_format($recs["pick_tax_amt"], 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n";
			$page[$j] .= "\n";
			$page[$j] .= str_repeat(" ", 65);
			$page[$j] .= str_pad(number_format($recs["pick_amt"] + $recs["pick_freight_amt"] + $recs["pick_tax_amt"], 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n";

		} else {
			for ($i=0;$i<6;$i++) $page[$j] .= "\n";
		}
		$page[$j] .= "\n";
		$page[$j] .= "\n";
	}
	$out = "";
	for ($i=0;$i<$page_num;$i++) {
		$out .= $page[$i];
	}

	set_time_limit(0);
	error_reporting(E_ERROR);
	ob_implicit_flush();
	
	$lpr = new PrintSendLPR();
	$port = strtolower($port);
	if ($port == "lpt1") {
		$lpr->setHost("192.168.1.41");
		$lpr->setData($out);
		$job = $lpr->printJob("oki1");	
	} else if ($port == "lpt2") {
		$lpr->setHost("192.168.1.42");
		$lpr->setData($out);
		$job = $lpr->printJob("oki3");
	} else {
		echo "<pre>$out</pre>";
	}
/*
	$redir = 0;
	if ($redir == 0) {
		list($usec,$sec)=explode(" ",microtime());
		$filename = "pick_".$sec.substr($usec, 2,6).".txt";
		$f=fopen ($filename,"w");
		fputs($f,$out,strlen($out));
		fclose($f);
		exec("copy $filename $port");
		exec("del $filename ");
	} else {
		echo "<pre>$out</pre>";
	}
*/
}

$SESSION["pickdtl_del"]=NULL;


if ($cmd == "pick_sess_add") {
	$pks = array();
	$pks["pick_id"]			= $_POST["pick_id"];
	$pks["pick_code"]			= $_POST["pick_code"];
	$pks["pick_user_code"]	= $_POST["pick_user_code"];
	$pks["pick_cust_code"]	= $_POST["pick_cust_code"];
	$pks["pick_name"]			= $_POST["pick_name"];
	$pks["pick_addr1"]		= $_POST["pick_addr1"];
	$pks["pick_addr2"]		= $_POST["pick_addr2"];
	$pks["pick_addr3"]		= $_POST["pick_addr3"];
	$pks["pick_city"]			= $_POST["pick_city"];
	$pks["pick_state"]		= $_POST["pick_state"];
	$pks["pick_country"]		= $_POST["pick_country"];
	$pks["pick_zip"]			= $_POST["pick_zip"];
	$pks["pick_tel"]			= $_POST["pick_tel"];
	$pks["pick_amt"]			= $_POST["pick_amt"];
	$pks["pick_date"]			= $_POST["pick_date"];
	$pks["pick_taxrate"]		= $_POST["pick_taxrate"];
	$pks["pick_tax_amt"]		= $_POST["pick_tax_amt"];
	$pks["pick_freight_amt"]	= $_POST["pick_freight_amt"];
	$pks["pick_shipvia"]		= $_POST["pick_shipvia"];
	$pks["pick_prom_date"]	= $_POST["pick_prom_date"];
	$pks["pick_delv_date"]	= $_POST["pick_delv_date"];
	$pks["pick_comnt"]		= $_POST["pick_comnt"];
	$_SESSION["pick_cust_code"] = $pick_cust_code;
	if ($ht=="e") {
		$_SESSION["picks_edit"] = $pks;
		$loc = "Location: picking_details.php?ty=a&ht=e&pick_id=$pick_id";
	} else {
		$_SESSION["picks_add"] = $pks;
		$loc = "Location: picking_details.php?ty=a&ht=a";
	}
	header($loc);

} else if ($cmd == "pick_detail_sess_add") {
	$pd = new PickDtls();
	$h = new Sales();
	$d = new SaleDtls();
	if ($ht == "e") { // from edit header
		$pickdtl = $_SESSION["pickdtls_edit"];
		$dtl = array();
		for ($i=0;$i<count($pickdtl);$i++) if (!empty($pickdtl[$i])) array_push($dtl, $pickdtl[$i]);
		if ($_POST["hl_type"] == "h") {
			$darr = $d->getSaleDtlHdrs($pickdtl_code);
			for ($i=0;$i<count($darr);$i++) {
				$found = 0;
				for ($j=0;$j<count($pickdtl);$j++) {
					if ($pickdtl[$j]["pickdtl_code"]==$darr[$i]["slsdtl_id"]) {
//						$pickdtl["pickdtl_qty"]	= $darr[$i]["slsdtl_qty"]-$darr[$i]["slsdtl_qty_picked"];
//						$pickdtl["pickdtl_cost"]	= $darr[$i]["slsdtl_cost"];
						$found=1;
						break;
					}
				}
				if ($found == 0) {
					$pks = array();
					$pks["pickdtl_pick_id"]		= $pick_id;
					$pks["pickdtl_code"]			= $darr[$i]["slsdtl_id"];
					$qty_picked = $pd->getPickDtlsSlsSum($pks["pickdtl_slsdtl_id"]);
					$pks["pickdtl_qty"]			= $darr[$i]["slsdtl_qty"]-$qty_picked;
					$pks["pickdtl_cost"]			= $darr[$i]["slsdtl_cost"];
					if ($pks["pickdtl_qty"]>0) array_push($dtl, $pks);
				}
			}
		} else {
			$found = 0;
			for ($j=0;$j<count($pickdtl);$j++) if ($pikdtl[$j]["pickdtl_code"]==$_POST["pickdtl_code"]) $found=1;
			if ($found == 0) {
				$pks = array();
				$pks["pickdtl_pick_id"]		= $pick_id;
				$pks["pickdtl_code"]			= $_POST["pickdtl_code"];
				$pks["pickdtl_qty"]			= $_POST["pickdtl_qty"];
				$pks["pickdtl_cost"]			= $_POST["pickdtl_cost"];
				if ($pks["pickdtl_qty"]>0) array_push($dtl, $pks);
			}
		}
	
		$pickdtls_edit = $dtl;
		$_SESSION["pickdtls_edit"] = $pickdtls_edit;

		$loc = "Location: picking_details.php?ty=a&ht=e&pick_id=$pick_id";
	} else { // from new header
		if (empty($pickdtl_code)) {
			$errno=4;
			$errmsg="Sales code should not be blank!";
			include("error.php");
			exit;
		} else {
			$pickdtl = $_SESSION["pickdtls_add"];
			$dtls = array();
			for ($i=0;$i<count($pickdtl);$i++) if (!empty($pickdtl[$i])) array_push($dtls, $pickdtl[$i]);
			if ($_POST["hl_type"] == "h") {
				$harr = $h->getSales($pickdtl_code);
				if ($harr) {
					$darr = $d->getSaleDtlHdrs($pickdtl_code);
					for ($i=0;$i<count($darr);$i++) {
						$found = 0;
						for ($j=0;$j<count($pickdtl);$j++) if ($pikdtl[$j]["pickdtl_code"]==$darr[$i]["slsdtl_id"]) $found=1;
						if ($found == 0) {
							$pks = array();
							$pks["pickdtl_pick_id"]		= $pick_id;
							$pks["pickdtl_code"]	= $darr[$i]["slsdtl_id"];
							$qty_picked = $pd->getPickDtlsSlsSum($darr[$i]["slsdtl_id"]);
							$pks["pickdtl_qty"]	= $darr[$i]["slsdtl_qty"]-$qty_picked;
							$pks["pickdtl_cost"]	= $darr[$i]["slsdtl_cost"];
							if ($pks["pickdtl_qty"]>0) array_push($dtls, $pks);
						}
					}
				}
			} else {
				$found = 0;
				for ($j=0;$j<count($pickdtl);$j++) if ($pikdtl[$j]["pickdtl_code"]==$_POST["pickdtl_code"]) $found=1;
				if ($found == 0) {
					$pks = array();
					$pks["pickdtl_pick_id"]		= $pick_id;
					$pks["pickdtl_code"]			= $_POST["pickdtl_code"];
					$pks["pickdtl_qty"]			= $_POST["pickdtl_qty"];
					$pks["pickdtl_cost"]			= $_POST["pickdtl_cost"];
					if ($pks["pickdtl_qty"]>0) array_push($dtls, $pks);
				}
			}
			$pickdtls_add = $dtls;
			$_SESSION["pickdtls_add"] = $pickdtls_add;

			$loc = "Location: picking_details.php?ty=a&ht=a";
		}
	}
	header($loc);

} else if ($cmd == "pick_add") {
	$p = new Picks();
	$d = new PickDtls();
	$h = new Sales();
	$s = new SaleDtls();
	$t = new Items();
	$oldrec = $p->getTextFields("", "");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$pick_id = $p->insertPicks($arr);
	$pdtls = $_SESSION["pickdtls_add"];
	$sid_arr = array();
	for ($i=0;$i<count($pdtls);$i++) {
		$pd_code = "'".$pdtls[$i]["pickdtl_code"]."'";
		if ($sdarr = $s->getSaleDtls($pdtls[$i]["pickdtl_code"])) {
			$qty_picked = $d->getPickDtlsSlsSum($sdarr["slsdtl_id"]);
			$qty = $sdarr["slsdtl_qty"]-$qty_picked;
			if ($qty > $pdtls[$i]["pickdtl_qty"]) $qty = $pdtls[$i]["pickdtl_qty"];
			$dtl = array("pickdtl_pick_id"=>"", "pickdtl_slsdtl_id"=>"", "pickdtl_qty"=>"", "pickdtl_cost"=>"");
			$dtl["pickdtl_pick_id"] = $pick_id;
			$dtl["pickdtl_slsdtl_id"] = $pdtls[$i]["pickdtl_code"];
			$dtl["pickdtl_qty"] = $qty;
			$dtl["pickdtl_cost"] = $pdtls[$i]["pickdtl_cost"];
			if ($dtl["pickdtl_qty"]>0)  {
				if ($pickdtl_id = $d->insertPickDtls($dtl)) {
					if (!in_array($sdarr["slsdtl_sale_id"],$sid_arr)) array_push($sid_arr, $sdarr["slsdtl_sale_id"]);
					$s->updateSlsDtlsQtyPicked($pdtls[$i]["pickdtl_code"], $qty, 0);
					$t->updateItemsQty($sdarr["slsdtl_item_code"], 0, $qty);
				} else {
					$errno=6;
					$errmsg="Sales Detail Insertion Error";
					include("error.php");
					exit;
				}
			}
		}
	}

	// apply deposit amount
/*
	$op = new OpenPay();
	$rd = new RcptDtls();
	for ($i=0;$i<count($sid_arr);$i++) {
		$op_arr = $op->getOpenPaySales($sid_arr[$i]);
		$paid_amt = round($op->getOpenPaySalesSum($op_arr["rcpt_id"])*100)/100;
		$pk_amt = round(($pick_amt+$pick_tax_amt+$pick_freight_amt)*100)/100;
		if ($p_amt >= $paid_amt) {
			$amt = $paid_amt;
		} else {
			$amt = $pk_amt;
		}
		if ($amt !=0) {
			$upd = array();
			$upd["rcptdtl_pick_id"] = $pick_id;
			$upd["rcptdtl_type"] = 'ar';
			$upd[rcptdtl_rcpt_id] = $op_arr["rcpt_id"];
			$upd[rcptdtl_sale_id] = $sid_arr[$i];
			$upd[rcptdtl_acct_code] = $default["astr_acct"];
			$upd[rcptdtl_ref_code] = "";
			$upd["rcptdtl_amt"] = $amt;
			$upd["rcptdtl_desc"] = "";
			$rcptdtl_id = $rd->insertRcptDtls($upd);
		}
	}
*/

	// update customer balance
	$c = new Custs();
	$cust_arr = $c->getCusts($arr["pick_cust_code"]);
	if ($cust_arr) {
		$c->updateCustsBalance($arr["pick_cust_code"]);
	} else {
		$errno=7;
		$errmsg="Customer Not Found Error";
		include("error.php");
		exit;
	}

	$j = new JrnlTrxs();
	//$j->deleteJrnlTrxRefs($pick_id, "r");

	$j->insertJrnlTrxExs($pick_id, $_SERVER["PHP_AUTH_USER"], $default["astr_acct"], "r", "d", $pick_amt, $pick_date); // ar for sale
	$j->insertJrnlTrxExs($pick_id, $_SERVER["PHP_AUTH_USER"], $default["ints_acct"], "r", "c", $pick_amt, $pick_date); // income for sale

	$_SESSION["picks_add"]=NULL;
	$_SESSION["pickdtls_add"]=NULL;
	$_SESSION["pick_cust_code"]=NULL;

	$loc = "Location: picking.php?ty=e&pick_id=$pick_id";
	header($loc);

} else if ($cmd == "pick_edit") {
	$p = new Picks();
	$d = new PickDtls();
	$s = new SaleDtls();
	$t = new Items();
	$sarr = $p->getPicks($pick_id);
	if (count($sarr)==0) {
		$errno=4;
		$errmsg="Pick number is not in database";
		include("error.php");
		exit;
	}
	$oldrec = $p->getTextFields("", $pick_id);
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	
	if (count($arr)>0) $p->updatePicks($pick_id, $arr);
	$numold = $d->getPickDtlsRows($pick_id);
	$olds = $d->getPickDtlsList($pick_id, "", "f", 1, $numold);

	// update sales_qty_picked & Item qty...
	for ($i=0;$i<$numold;$i++) {
		if ($d->deletePickDtls($olds[$i]["pickdtl_id"])) { // update item & sales pick qty
			$arr = array("slsdtl_qty_picked"=>0);
			$arr["slsdtl_qty_picked"] = $d->getPickDtlsSlsSum($olds[$i]["pickdtl_slsdtl_id"]);
			//print_r($arr);
			$s->updateSaleDtls($olds[$i]["pickdtl_slsdtl_id"], $arr);
			$t->updateItemsQty($olds[$i]["slsdtl_item_code"], 0, $olds[$i]["pickdtl_qty"]);
		} else {
			// picking detail error
			$errno=8;
			$errmsg= "Can't delete removed picking detail!";
			include("error.php");
			exit;
		} 
	}
	
	if (array_key_exists("pickdtls_edit", $_SESSION) && !is_null($_SESSION["pickdtls_edit"])) {
		$pdtls = $_SESSION["pickdtls_edit"];
	} else {
		$pdtls = array();
	}
	$numnew = count($pdtls);
	$sid_arr = array();
	for ($i=0;$i<$numnew;$i++) {
		if ($sdarr = $s->getSaleDtls($pdtls[$i]["pickdtl_code"])) {

			$dtl = array("pickdtl_pick_id"=>"", "pickdtl_slsdtl_id"=>"", "pickdtl_qty"=>"", "pickdtl_cost"=>"");
			$dtl["pickdtl_pick_id"] = $pick_id;
			$dtl["pickdtl_slsdtl_id"] = $pdtls[$i]["pickdtl_code"];
			$dtl["pickdtl_qty"] = $pdtls[$i]["pickdtl_qty"];
			$dtl["pickdtl_cost"] = $pdtls[$i]["pickdtl_cost"];
			$amt = $pdtls[$i]["pickdtl_qty"]*$pdtls[$i]["pickdtl_cost"];
			if ($dtl["pickdtl_qty"]>0) {
				if ($d->insertPickDtls($dtl)) {
					$found = True;
					for ($j=0;$j<count($sid_arr);$j++) {
						if ($sid_arr[$j]["id"]==$sdarr["slsdtl_sale_id"]) {
							$sid_arr[$j]["amt"] += $amt;
							$found = False;
							break;
						}
					}
					if ($found==False) {
						$tmp = array();
						$tmp["id"]=$sdarr["slsdtl_sale_id"];
						$tmp["amt"]=$amt;
						array_push($sid_arr, $tmp);
					}

					$arr = array("slsdtl_qty_picked"=>"");
					$arr["slsdtl_qty_picked"] = $d->getPickDtlsSlsSum($pdtls[$i]["pickdtl_code"]);
					$s->updateSaleDtls($olds[$i]["pickdtl_slsdtl_id"], $arr);
					//$t->updateItemsQty($sdarr["slsdtl_item_code"], $sdarr["pickdtl_qty"], $dtl["pickdtl_qty"]);
				} else {
					$errno=6;
					$errmsg="Picking Detail Insertion Error";
					include("error.php");
					exit;
				}
			}
		}
	}

	// update sales_qty_picked & Item qty...
//	for ($i=0;$i<$numold;$i++) {
//		$arr = array("slsdtl_qty_picked"=>"");
//		$arr["slsdtl_qty_picked"] = $d->getPickDtlsSlsSum($olds[$i]["pickdtl_slsdtl_id"]);
//		if (!$s->updateSaleDtls($olds[$i]["pickdtl_slsdtl_id"], $arr)) {
//			// update item quantity error.
//			$errno=7;
//			$errmsg= "Can't update item quanty!";
//			include("error.php");
//			exit;
//		}
//	}

	// apply deposit amount
/*
	$op = new OpenPay();
	$rd = new RcptDtls();
	for ($i=0;$i<count($sid_arr);$i++) {
		$op_arr = $op->getOpenPaySalesPick($sid_arr[$i]["id"], $pick_id);
		$paid_amt = round($op->getOpenPaySalesSum($op_arr["rcpt_id"])*100)/100;
		$pk_amt = round(($pick_amt+$pick_tax_amt+$pick_freight_amt)*100)/100;
		if ($p_amt >= $paid_amt) {
			$amt = $paid_amt;
		} else {
			$amt = $pk_amt;
		}
		if ($amt !=0) {
			$upd = array();
			$upd["rcptdtl_pick_id"] = $pick_id;
			$upd["rcptdtl_type"] = 'ar';
			$upd[rcptdtl_rcpt_id] = $op_arr["rcpt_id"];
			$upd[rcptdtl_acct_code] = $default["astr_acct"];
			$upd[rcptdtl_ref_code] = "";
			$upd["rcptdtl_amt"] = $amt;
			$upd["rcptdtl_desc"] = "";
//			$rcptdtl_id = $rd->insertRcptDtls($upd);
		}
	}
*/

	// update customer balance
	$c = new Custs();
	$cust_arr = $c->getCusts($pick_cust_code);
	if ($cust_arr) {
		$c->updateCustsBalance($pick_cust_code);
	} else {
		$errno=7;
		$errmsg="Customer Not Found Error";
		include("error.php");
		exit;
	}

	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($pick_id, "r");

	$j->insertJrnlTrxExs($pick_id, $_SERVER["PHP_AUTH_USER"], $default["astr_acct"], "r", "d", $pick_amt, $pick_date); // ar for sale
	$j->insertJrnlTrxExs($pick_id, $_SERVER["PHP_AUTH_USER"], $default["ints_acct"], "r", "c", $pick_amt, $pick_date); // income for sale

	unset($picks_edit);
	$_SESSION["picks_edit"] = NULL;
	$_SESSION["pickdtls_edit"] = NULL;
	$_SESSION["pick_cust_code"] = NULL;

	$loc = "Location: picking.php?ty=e&pick_id=$pick_id";
	header($loc);
	exit;

} else if ($cmd == "pick_del") {
	$h = new Picks();
	$d = new PickDtls();
	$s = new SaleDtls();
	$t = new Items();
	$arr = $d->getPickDtlsHdrEx($pick_id);
	if ($arr) $arr_num = count($arr);
	else $arr_num = 0;
	$d->deletePickDtlsHdr($pick_id);
	for ($i=0;$i<$arr_num;$i++) {
		$sls = array();
		$sls["slsdtl_qty_picked"] = $d->getPickDtlsSlsSum($arr[$i]["pickdtl_slsdtl_id"]);
		if ($sls["slsdtl_qty_picked"]<0) $sls["slsdtl_qty_picked"]=0;
		$s->updateSaleDtls($sls["pickdtl_slsdtl_id"], $sls);
		$t->updateItemsQty($arr[$i]["slsdtl_item_code"], $arr[$i]["pickdtl_qty"],0);
	}
	$h->deletePicks($pick_id);

	// update customer balance
//print_r($arr);
//echo "<br>";
	$c = new Custs();
	$cust_arr = $c->getCusts($cust_code);
	if ($cust_arr) {
		$c->updateCustsBalance($cust_code);
	} else {
		$errno=7;
		$errmsg="Customer Not Found Error";
		include("error.php");
		exit;
	}

//	$j = new JrnlTrxs();
//	$j->deleteJrnlTrxRefs($pick_id, "r");
	
	$_SESSION["picks_edit"]=NULL;
	$_SESSION["pickdtls_edit"]=NULL;
	$_SESSION["pick_cust_code"]=NULL;
	$_SESSION["pick_cust_code"] = 1;

	$loc = "Location: picking.php?ty=l";
	header($loc);

} else if ($cmd == "pick_delivery") {
	$p = new Picks();
	$sarr = $p->getPicks($pick_id);
	if (count($sarr)==0) {
		$errno=4;
		$errmsg="Pick number is not in database";
		include("error.php");
		exit;
	}
	$arr = array("pick_fin"=>"","pick_delv_date"=>"");
	$arr["pick_fin"] = "t";
	if (empty($pick_delv_date) || $pick_delv_date == "0000/00/00") $pick_delv_date = date("m/d/Y");
	$arr["pick_delv_date"] = $pick_delv_date;
	if (count($arr)>0) $p->updatePicks($pick_id, $arr);
	
	$_SESSION["picks_edit"]=NULL;
	$_SESSION["pickdtls_edit"]=NULL;
	$_SESSION["pick_cust_code"]=NULL;

	$loc = "Location: picking.php?ty=e&pick_id=$pick_id";
	header($loc);

} else if ($cmd == "pick_unshipped") {
	$p = new Picks();
	$sarr = $p->getPicks($pick_id);
	if (count($sarr)==0) {
		$errno=4;
		$errmsg="Pick number is not in database";
		include("error.php");
		exit;
	}
	$arr = array("pick_fin"=>"","pick_delv_date "=>"");
	$arr["pick_fin"] = "f";
	$arr["pick_delv_date"] = "";
	if (count($arr)>0) $p->updatePicks($pick_id, $arr);
	
	$_SESSION["picks_edit"]=NULL;
	$_SESSION["pickdtls_edit"]=NULL;
	$_SESSION["pick_cust_code"]=NULL;

	$loc = "Location: picking.php?ty=e&pick_id=$pick_id";
	header($loc);

} else if ($cmd == "pick_detail_sess_del") {
	$arr = array();
	if ($ty=="e") {
		$pickdtl = $_SESSION["pickdtls_edit"];
		for ($i=0;$i<count($pickdtl);$i++) if ($i != $did) array_push($arr, $pickdtl[$i]);
		$_SESSION["pickdtls_edit"] = $arr;
		$_SESSION["pickdtl_del"] = 1;

		$loc = "Location: picking.php?ty=e&pick_id=$pick_id";
	} else {
		$pickdtl = $_SESSION["pickdtls_add"];
		for ($i=0;$i<count($pickdtl);$i++) if ($i != $did) array_push($arr, $pickdtl[$i]);
		$_SESSION["pickdtls_add"] = $arr;

		$loc = "Location: picking.php?ty=a";
	}
	header($loc);

} else if ($cmd == "pick_clear_sess_edit") {
	$_SESSION["picks_edit"]=NULL;
	$_SESSION["pickdtls_edit"]=NULL;
	$_SESSION["pick_cust_code"]=NULL;

	$loc = "Location: picking.php?ty=e&pick_id=$pick_id";
	header($loc);

} else if ($cmd == "pick_clear_sess_add") {
	$_SESSION["picks_add"]=NULL;
	$_SESSION["pickdtls_add"]=NULL;
	$_SESSION["pick_cust_code"]=NULL;

	$loc = "Location: picking.php?ty=a";
	header($loc);

} else if ($cmd == "pick_update_sess") {
	if ($ty=="a") {
		$pks = array();
		$pks["pick_id"]			= $_POST["pick_id"];
		$pks["pick_code"]			= $_POST["pick_code"];
		$pks[["pick_cust_po"]]		= $_POST[["pick_cust_po"]];
		$pks["pick_user_code"]	= $_POST["pick_user_code"];
		$pks["pick_cust_code"]	= $_POST["pick_cust_code"];
		$pks["pick_name"]			= $_POST["pick_name"];
		$pks["pick_addr1"]		= $_POST["pick_addr1"];
		$pks["pick_addr2"]		= $_POST["pick_addr2"];
		$pks["pick_addr3"]		= $_POST["pick_addr3"];
		$pks["pick_city"]			= $_POST["pick_city"];
		$pks["pick_state"]		= $_POST["pick_state"];
		$pks["pick_country"]		= $_POST["pick_country"];
		$pks["pick_zip"]			= $_POST["pick_zip"];
		$pks["pick_tel"]			= $_POST["pick_tel"];
		$pks["pick_amt"]			= $_POST["pick_amt"];
		$pks["pick_date"]			= $_POST["pick_date"];
		$pks["pick_taxrate"]		= $_POST["pick_taxrate"];
		$pks["pick_tax_amt"]		= $_POST["pick_tax_amt"];
		$pks["pick_freight_amt"]	= $_POST["pick_freight_amt"];
		$pks["pick_shipvia"]		= $_POST["pick_shipvia"];
		$pks["pick_comnt"]		= $_POST["pick_comnt"];
		$_SESSION["pick_cust_code"] = $_POST["pick_user_code"];
		$_SESSION["picks_edit"] = $pks;

		$loc = "Location: picking.php?ty=$ty";
	} else {
		$s = new Picks();
		$arr = $s->getPicks($pick_id);
		if ($arr) {
			$pks = $_SESSION["picks_add"];
			$pks["pick_id"]			= $_POST["pick_id"];
			$pks["pick_code"]			= $_POST["pick_code"];
			$pks[["pick_cust_po"]]		= $_POST[["pick_cust_po"]];
			$pks["pick_user_code"]	= $_POST["pick_user_code"];
			$pks["pick_cust_code"]	= $_POST["pick_cust_code"];
			$pks["pick_name"]			= $_POST["pick_name"];
			$pks["pick_addr1"]		= $_POST["pick_addr1"];
			$pks["pick_addr2"]		= $_POST["pick_addr2"];
			$pks["pick_addr3"]		= $_POST["pick_addr3"];
			$pks["pick_city"]			= $_POST["pick_city"];
			$pks["pick_state"]		= $_POST["pick_state"];
			$pks["pick_country"]		= $_POST["pick_country"];
			$pks["pick_zip"]			= $_POST["pick_zip"];
			$pks["pick_tel"]			= $_POST["pick_tel"];
			$pks["pick_amt"]			= $_POST["pick_amt"];
			$pks["pick_date"]			= $_POST["pick_date"];
			$pks["pick_taxrate"]		= $_POST["pick_taxrate"];
			$pks["pick_tax_amt"]		= $_POST["pick_tax_amt"];
			$pks["pick_freight_amt"]	= $_POST["pick_freight_amt"];
			$pks["pick_shipvia"]		= $_POST["pick_shipvia"];
			$pks["pick_prom_date"]	= $_POST["pick_prom_date"];
			$pks["pick_delv_date"]	= $_POST["pick_delv_date"];
			$pks["pick_comnt"]		= $_POST["pick_comnt"];
			$_SESSION["pick_cust_code"] = $_POST["pick_user_code"];
			$_SESSION["picks_add"] = $pks;

			$loc = "Location: picking.php?ty=$ty&pick_id=$pick_id";
		} else {
			$errno=3;
			$errmsg="Couldn't find picking number.";
			include("error.php");
			exit;
		}
	}
	header($loc);

} else if ($cmd == "pick_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$pickdtl = $_SESSION["pickdtls_edit"];
		for ($i=0;$i<count($pickdtl);$i++) {
			if ($i == $did) {
				$pks = array();
				$pks["pickdtl_pick_id"]		= $pick_id;
				$pks["pickdtl_id"]			= $_POST["pickdtl_id"];
				$pks["pickdtl_code"]			= $_POST["pickdtl_code"];
				$pks["pickdtl_qty"]			= $_POST["pickdtl_qty"];
				$pks["pickdtl_cost"]			= $_POST["pickdtl_cost"];
				if ($pks["pickdtl_qty"]>0) array_push($arr, $pks);
			} else {
				array_push($arr, $pickdtl[$i]);
			}
		}
		$_SESSION["pickdtls_edit"] = $arr;

		$loc = "Location: picking_details.php?ty=e&ht=e&pick_id=$pick_id&did=$did";
	} else {
		$arr = array();
		$pickdtl = $_SESSION["pickdtls_add"];
		for ($i=0;$i<count($pickdtl);$i++) {
			if ($i == $did) {
				$pks = array();
				$pks["pickdtl_pick_id"]		= $pick_id;
				$pks["pickdtl_qty"]			= $_POST["pickdtl_qty"];
				$pks["pickdtl_cost"]			= $_POST["pickdtl_cost"];
				$pks["pickdtl_code"]			= $_POST["pickdtl_code"];
				if ($pks["pickdtl_qty"]>0) array_push($arr, $pks);
			} else {
				array_push($arr, $pickdtl[$i]);
			}
		}
		$_SESSION["pickdtls_add"] = $arr;

		$loc = "Location: picking_details.php?ty=e&ht=a&did=$did";
	}
	header($loc);

} else if ($cmd == "pick_print") {
	$u = new Users();
	$user_arr = $u->getUsers($_SERVER["PHP_AUTH_USER"]);
	pickPrint($pick_id, $user_arr["user_printer"]);
	$loc = "Location: picking.php?ty=$ty&pick_id=$pick_id";
	header($loc);

} else if ($cmd == "pick_batch") {
	$d = new Datex();
	$p = new Picks();
	if (!empty($from) && !empty($to)) {
		$errno =0;
		if ($codetype == "code") {
			$pick_arr = $p->getPicksRange($from, $to, "pick_id");
		} else if ($codetype == "date") {
			if (!empty($from)) $from = $d->toIsoDate($from);
			if (!empty($to)) $to = $d->toIsoDate($to);
			$pick_arr = $p->getPicksRange($from, $to, "pick_date");
		} else {
			// no codetype.... 
			$errno = 9;
		}

		$pick_num = count($pick_arr);
		if ($worktype == "print") {
			$u = new Users();
			$user_arr = $u->getUsers($_SERVER["PHP_AUTH_USER"]);
			for ($i=0;$i<$pick_num;$i++) pickPrint($pick_arr[$i]["pick_id"], $user_arr["user_printer"]);
		} else if ($worktype == "invoice") {
			for ($i=0;$i<$pick_num;$i++) makeInvoices($pick_arr[$i]["pick_id"]);
		}
	}
	$loc = "Location: picking_batch_popup.php?close=t&objname=$objname&errno=$errno";
	header($loc);

} else if ($cmd == "pick_make_invoice") {
	//makeInvoice($pick_id);
	$loc = "Location: picking.php?ty=$ty&pick_id=$pick_id";
	//echo $loc."<br>";
	header($loc);

}
?>