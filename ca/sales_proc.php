<?php
include_once("class/class.sales.php");
include_once("class/class.saledtls.php");
include_once("class/class.picks.php");
include_once("class/class.pickdtls.php");
include_once("class/class.items.php");
include_once("class/class.customers.php");
include_once("class/class.custships.php");
include_once("class/class.receipt.php");
include_once("class/class.rcptdtls.php");
include_once("class/class.cmemo.php");
include_once("class/class.datex.php");
include_once("class/class.salehists.php");
include_once("class/map.default.php");
include_once("class/class.users.php");
include_once("class/class.requests.php");
include_once("class/class.pends.php");
include_once("class/class.pendtls.php");
include_once("class/class.common.php");
include_once("class/map.default.php");

$vars = array("ht","sale_code","save_ship" );
foreach ($vars as $v) {
	$$v = "";
} 
$vars = array("sale_id","sale_cust_po");
foreach ($vars as $v) {
	$$v = 0;
} 

include_once("class/register_globals.php");


$dx = new Datex();
$r = new Requests();
$fields = array(
	array("sale_id",0),
	array("sale_user_code",""),
	array("sale_cust_code",""),
	array("sale_cust_po",""),
	array("sale_name",""),
	array("sale_addr1",""),
	array("sale_addr2",""),
	array("sale_addr3",""),
	array("sale_city",""),
	array("sale_state",""),
	array("sale_country",""),
	array("sale_zip",""),
	array("sale_tel",""),
	array("sale_cell",""),
	array("sale_slsrep",""),
	array("sale_term",""),
	array("sale_date",$dx->getIsoToday()),
	array("sale_prom_date",null),
	array("sale_amt",0.0),
	array("sale_tax_amt",0.0),
	array("sale_freight_amt",0.0),
	array("sale_deposit_amt",0.0),
	array("sale_disc_amt",0.0),
	array("sale_shipvia",""),
	array("sale_slsrep",""),
	array("sale_term",""),
	array("sale_taxrate",0.0),
	array("sale_comnt",""),
	array("sale_fin","f"),
	array("sale_status","n"),
	array("sale_print",0),
	array("sale_job",""),
	array("sale_ref",""),
	array("sale_closed","f"),
	array("sale_aging","f"),
	array("sale_origin",0),
	array("sale_parent",0),
	array("sale_version",0),
);
  
$dtlflds = array(
	array("slsdtl_id",0),
	array("slsdtl_sale_id",0),
	array("slsdtl_item_code",""),
	array("slsdtl_item_desc",""),
	array("slsdtl_qty_ord",0),
	array("slsdtl_qty",0),
	array("slsdtl_qty_bo",0),
	array("slsdtl_qty_picked",0),
	array("slsdtl_qty_cancel",0),
	array("slsdtl_qty_pend",0),
	array("slsdtl_unit",""),	
	array("slsdtl_cost",0.0),
	array("slsdtl_log",0.0),
	array("slsdtl_taxable","t"),
	array("slsdtl_sort",""),
	array("slsdtl_origin",""),
);

function makePicks($sale_id) {
	include_once("class/map.default.php");

	$s = new Sales();
	$p = new Picks();
	$sd = new SaleDtls();
	$pd = new PickDtls();
	$t = new Items();
	$c = new Custs();
	$com = new Common();

	$pick_qty = $pd->getPickDtlsSlsHdrSum($sale_id);
	$sale_qty = $sd->getSaleDtlsHdrSum($sale_id);
	$diff_qty = $sale_qty - $pick_qty;


	$sale_arr = $s->getSales($sale_id);
	if ($sale_arr && $diff_qty >0) {
		$pair_arr_str = array(
			array("sale_user_code","pick_user_code"),
			array("sale_cust_code","pick_cust_code"),
			array("sale_name","pick_name"),
			array("sale_addr1","pick_addr1"),
			array("sale_addr2","pick_addr2"),
			array("sale_addr3","pick_addr3"),
			array("sale_city","pick_city"),
			array("sale_state","pick_state"),
			array("sale_country","pick_country"),
			array("sale_zip","pick_zip"),
			array("sale_tel","pick_tel"),
			array("sale_shipvia","pick_shipvia")
		);
		$pair_arr_num = array(
			array("sale_taxrate","pick_taxrate"),
			array("sale_amt","pick_amt"),
			array("sale_tax_amt","pick_tax_amt"),
			array("sale_freight_amt","pick_freight_amt"),
		);

		$pair_dtlarr_str = array(
			array("slsdtl_item_code","pickdtl_item_code"),
			array("slsdtl_item_desc","pickdtl_item_desc"),
			array("slsdtl_unit","pickdtl_unit"),
		);
		$pair_dtlarr_num = array(
			array("slsdtl_cost","pickdtl_cost"),
			array("slsdtl_id","pickdtl_slsdtl_id"),
		);
	
		$picks_add = array();
		$picks_add["pick_code"]	= $p->getPickMaxId() + $default["pick_start_no"] + 1;
		$picks_add = array_merge($picks_add, 
								 $com->getPairDefaults($sale_arr, $pair_arr_str, ""),
								 $com->getPairDefaults($sale_arr, $pair_arr_num, 0));
		$picks_add["pick_date"]	= date("m/d/y");
		$picks_add["pick_prom_date"] = $sale_arr["pick_prom_date"];
		$pick_id = $p->insertPicks($picks_add);
		$darr = $sd->getSaleDtlsList($sale_id);

		$darr_num = count($darr);
		$subtotal = 0;
		$taxtotal = 0;
		for ($i=0;$i<$darr_num;$i++) {
			$pks = array();
			$pks["pickdtl_pick_id"]	= $pick_id;

			$pks = array_merge($pks, 
			$com->getPairDefaults($darr[$i], $pair_dtlarr_str, ""),
			$com->getPairDefaults($darr[$i], $pair_dtlarr_num, 0));

			$qty_picked = $pd->getPickDtlsSlsSum($pks["pickdtl_slsdtl_id"]);
			$pks["pickdtl_qty"]		= $darr[$i]["slsdtl_qty"]-$qty_picked;
			if ($pks["pickdtl_qty"]>0) {
				$subtotal += $darr[$i]["slsdtl_cost"]*$pks["pickdtl_qty"];
				if ($darr[$i]["slsdtl_taxable"] == "t") $taxtotal += $darr[$i]["slsdtl_cost"]*$pks["pickdtl_qty"];
				if ($pd->insertPickDtls($pks)) {
					$sd->updateSlsDtlsQtyPicked($pks["pickdtl_slsdtl_id"], $pks["pickdtl_qty"], 0);
					$t->updateItemsQty($darr[$i]["slsdtl_item_code"], 0, $pks["pickdtl_qty"]);
				}
			}
		}
		$cust_arr = $c->getCustsEx($sale_arr["sale_cust_code"]);

		// update subtotal & tax amount here...
		$picks = array();
		$picks["pick_amt"]			= $subtotal;
		$picks["pick_tax_amt"]		= $taxtotal*$cust_arr["taxrate_pct"]/100;

		// update deposit. get deposit amount on sales and pick. put the difference into pick deposit amt.
		$deposit_sum = $p->getPicksDepositSum($sale_id);
		$picks["pick_deposit_amt"] = $sale_arr["sale_deposit_amt"]-$deposit_sum;
		if ($picks["pick_deposit_amt"]<0) $picks["pick_deposit_amt"] = 0;

		if (!$p->updatePicks($pick_id, $picks)) {
			$errno=9;
			$errmsg="Picks updating Error";
			include("error.php");
			exit;
		}

		// update customer balance...
		if ($cust_arr) {
			$c->updateCustsBalance($sale_arr["sale_cust_code"]);
		} else {
			$errno=7;
			$errmsg="Customer Not Found Error";
			include("error.php");
			exit;
		}


	}
}

function salePrint($sale_id, $port) {	
	$s = new Sales();
	$p = new Picks();
	$cm = new Cmemo();
	$r = new Receipt();
	$dx = new Datex();
	$com = new Common();

	$recs = $s->getSales($sale_id);
	$s->increaseSales($sale_id, "sale_print", 1);
	$c = new Custs();
	// for temporary purposes..
//	$c->updateCustsBalance($recs["pick_cust_code"]);
	$cust_arr = $c->getCusts($recs["sale_cust_code"]);
	$sd = new SaleDtls();
	$sd_arr = $sd->getSaleDtlsList($sale_id);

	$cols = 80;
	$page = array();
	$page_len = 42;
	$line_len = 16;
	$col_width = 35;
	$line_idx = 0;
    $cutoff_digit = 100;

	$sd_num = count($sd_arr);
	$line_arr = array();

	$key_sd_arr_str = array("slsdtl_item_code","slsdtl_item_desc", "slsdtl_unit",);
	$key_sd_arr_num = array("slsdtl_qty","slsdtl_cost", "slsdtl_unit",);

	$k = 0;
	for ($j=0;$j<$sd_num;$j++) {
		$com->setKeyDefaults($sd_arr[$j], $key_sd_arr_str, "");
		$com->setKeyDefaults($sd_arr[$j], $key_sd_arr_num, 0);

		$desc_line = ceil(strlen($sd_arr[$j]["slsdtl_item_desc"])/$col_width);
		$line_arr[$k] = "";
		$line_arr[$k] .= str_pad(substr(trim($sd_arr[$j]["slsdtl_item_code"]),0,12), 12, " ", STR_PAD_RIGHT);
		$line_arr[$k] .= str_pad(substr($sd_arr[$j]["slsdtl_item_desc"], 0, $col_width), $col_width, " ", STR_PAD_RIGHT);
		$line_arr[$k] .= str_pad($sd_arr[$j]["slsdtl_qty"], 10, " ", STR_PAD_LEFT);
		$line_arr[$k] .= str_pad(strtoupper($sd_arr[$j]["slsdtl_unit"]), 2, " ", STR_PAD_LEFT);
		$line_arr[$k] .= str_pad(number_format($sd_arr[$j]["slsdtl_cost"], 2, ".", ","), 9, " ", STR_PAD_LEFT);
		$line_arr[$k] .= str_pad(number_format(($sd_arr[$j]["slsdtl_qty"]) * $sd_arr[$j]["slsdtl_cost"], 2, ".", ","), 11, " ", STR_PAD_LEFT);
		if ($sd_arr[$j]["slsdtl_taxable"]=="t") $line_arr[$k] .= "T";
		$line_arr[$k] .= "\n\r";
		$k++;
		for ($x=1;$x<$desc_line;$x++) {
			$line_arr[$k] = "";
			$line_arr[$k] .= str_repeat(" ", 12);
			$line_arr[$k] .= substr($sd_arr[$j]["slsdtl_item_desc"], $col_width*$x, $col_width);
			$line_arr[$k] .= "\n\r";
			$k++;
		}
	}
	$total_line = $k;
	$page_num = ceil($total_line/$line_len);
	$rem_lines = $total_line % $line_len;

	if (!isset($recs["sale_shipvia"])) $recs["sale_shipvia"] = $cust_arr["cust_shipvia"];

	$key_recs_str = array("sale_comnt","sale_slsrep","sale_shipvia");
	$key_recs_num = array("sale_po_num",);
	$com->setKeyDefaults($recs, $key_recs_str, "");
	$com->setKeyDefaults($recs, $key_recs_num, 0);

	for ($j=0;$j<$page_num;$j++) {
		$page[$j] = "";
		$page[$j] .= str_repeat(" ", 60);
		$page[$j] .= "Sales Slip\n\r"; // line #1
		$page[$j] .= "\n\r"; // line #2
		$page[$j] .= "\n\r"; // line #3
		$page[$j] .= "\n\r"; // line #4
		$page[$j] .= str_repeat(" ", 54);
		$page[$j] .= str_pad(date("m/d/y"), 11, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($sale_id, 8, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($j+1, 3, " ", STR_PAD_LEFT);
		$page[$j] .= "\n\r"; // line #5
		$page[$j] .= substr($recs["sale_comnt"], 0, 50);
		$page[$j] .= "\n\r"; // line #6
		$page[$j] .= "\n\r"; // line #7
		$page[$j] .= "\n\r"; // line #8
		$page[$j] .= str_pad($cust_arr["cust_name"], 39, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($recs["sale_name"], 39, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n\r"; // line #9
		$page[$j] .= str_pad($cust_arr["cust_addr1"], 39, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($recs["sale_addr1"], 39, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n\r"; // line #10

		if (empty($cust_arr["cust_addr2"]) && empty($recs["sale_addr2"])) {
			$blank_addr2 = 1;
		} else {
			$page[$j] .= str_pad($cust_arr["cust_addr2"], 39, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 2);
			$page[$j] .= str_pad($recs["sale_addr2"], 39, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n\r";
			$blank_addr2 = 0;
		}
		$blank_addr3 = 0;
		if (empty($cust_arr["cust_addr3"]) && empty($recs["sale_addr3"])) {
			$blank_addr3 = 1;
		} else {
			$page[$j] .= str_pad($cust_arr["cust_addr3"], 39, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 2);
			$page[$j] .= str_pad($recs["sale_addr3"], 39, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n\r";
			$blank_addr3 = 0;
		}
		$csz = $cust_arr["cust_city"].", ".$cust_arr["cust_state"]." ".$cust_arr["cust_zip"];
		$page[$j] .= str_pad($csz, 39, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 2);
		$csz = $recs["sale_city"].", ".$recs["sale_state"]." ".$recs["sale_zip"];
		$page[$j] .= str_pad($csz, 39, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n\r";
		$page[$j] .= str_pad($cust_arr["cust_tel"], 39, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($recs["sale_tel"], 39, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n\r";
		if ($blank_addr2==1) $page[$j] .= "\n\r";
		if ($blank_addr3==1) $page[$j] .= "\n\r";

		$page[$j] .= "\n\r";
		$page[$j] .= str_pad(substr($recs["sale_user_code"],0,8), 8, " ", STR_PAD_RIGHT);
		$page[$j] .= str_pad(date("m/d/y", strtotime($recs["sale_date"])), 10, " ", STR_PAD_BOTH);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($recs["sale_cust_code"], 10, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($recs["sale_po_num"], 9, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($recs["sale_slsrep"], 10, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad(substr($recs["sale_shipvia"],0,13), 13, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 1);
//		$page[$j] .= str_pad(date("m/d/y", strtotime($recs["sale_prom_date"])), 10, " ", STR_PAD_BOTH);
		$page[$j] .= str_repeat(" ", 10);
//		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad(substr($recs["sale_term"],0,5), 5, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n\r";
		$page[$j] .= "\n\r";
		$page[$j] .= "\n\r";

		for ($k=$j*$line_len;$k<($j+1)*$line_len;$k++) {
			if (isset($line_arr[$k])) {
				$page[$j] .= $line_arr[$k];
			}
		}

		if ($j+1 == $page_num) {
			if (($total_line % $line_len)==0) $rem_lines = 0;
			else $rem_lines = $line_len - ($total_line % $line_len);

			for ($k=0; $k<$rem_lines; $k++) $page[$j] .= "\n\r";

			$page[$j] .= str_repeat(" ", 65);
			$page[$j] .= str_pad(number_format($recs["sale_amt"], 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n\r";

            if ($cust_arr["cust_arinfo"]=='t') {
		            $pick_sum = $cust_arr["cust_init_bal"] + $p->getPicksSumAged($cust_arr["cust_code"], '', '', "t", "t");
		            $rcpt_sum = $r->getReceiptSumAged($cust_arr["cust_code"], '', '', "t", "t");
		            $cmemo_sum = $cm->getCmemoSumAged($cust_arr["cust_code"], '', '', "t", "t");
		            $pick_sum = round($pick_sum*$cutoff_digit)/$cutoff_digit;
		            $rcpt_sum = round($rcpt_sum*$cutoff_digit)/$cutoff_digit;
		            $cmemo_sum = round($cmemo_sum*$cutoff_digit)/$cutoff_digit;
		            $balance = $pick_sum - $rcpt_sum - $cmemo_sum;
		            $balance = round($balance*$cutoff_digit)/$cutoff_digit;

  		            $day0 = date("Y-m-d");
  		            $day30 = $dx->getIsoDate($day0,30,"b");
  		            $day60 = $dx->getIsoDate($day0,60,"b");
  		            $day90 = $dx->getIsoDate($day0,90,"b");
        		    $created = $dx->toIsoDate($cust_arr["cust_created"]);

		            $pick90over = $p->getPicksSumAged($cust_arr["cust_code"], "", $day90, "t", "f");
		            $pick90 = $p->getPicksSumAged($cust_arr["cust_code"], $day90, $day60, "t", "f");
		            $pick60 = $p->getPicksSumAged($cust_arr["cust_code"], $day60, $day30, "t", "f");
		            $pick30 = $p->getPicksSumAged($cust_arr["cust_code"], $day30, $day0, "t", "t");
		            $pick0 = $p->getPicksSumAged($cust_arr["cust_code"], $day0, "", "f", "t");
		            $pick90over = round($pick90over*$cutoff_digit)/$cutoff_digit;
		            $pick90 = round($pick90*$cutoff_digit)/$cutoff_digit;
		            $pick60 = round($pick60*$cutoff_digit)/$cutoff_digit;
		            $pick30 = round($pick30*$cutoff_digit)/$cutoff_digit;
		            $pick0 = round($pick0*$cutoff_digit)/$cutoff_digit;
//print "$balance = $bal_forwarded + $pick_sum - $rcpt_sum - $cmemo_sum <br>";
//print "$pick90over, $pick90, $pick60, $pick30, $pick0 <br>";
		            if (strtotime($created) <= strtotime($day0) && strtotime($created) > strtotime($day30) ) $pick30 += $cust_arr["cust_init_bal"];
		            else if (strtotime($created) <= strtotime($day30) && strtotime($created) > strtotime($day60) ) $pick60 += $cust_arr["cust_init_bal"];
		            else if (strtotime($created) <= strtotime($day60) && strtotime($created) > strtotime($day90) ) $pick90 += $cust_arr["cust_init_bal"];
		            else if (strtotime($created) <= strtotime($day90)) $pick90over += $cust_arr["cust_init_bal"];

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


              		    $page[$j] .= str_pad(number_format($bal30,2,".",","), 10, " ", STR_PAD_LEFT);
			    $page[$j] .= str_pad(number_format($bal60,2,".",","), 12, " ", STR_PAD_LEFT);
			    $page[$j] .= str_pad(number_format($bal90,2,".",","), 12, " ", STR_PAD_LEFT);
			    $page[$j] .= str_pad(number_format($bal90over,2,".",","), 12, " ", STR_PAD_LEFT);
			    $page[$j] .= str_pad(number_format($balance,2,".",","), 12, " ", STR_PAD_LEFT);
			} else {
			    $page[$j] .= str_repeat(" ", 58);
			}
			$page[$j] .= str_repeat(" ", 7);
			$page[$j] .= str_pad(number_format($recs["sale_freight_amt"], 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n\r";
			$page[$j] .= str_repeat(" ", 61);
			$page[$j] .= str_pad($recs["sale_taxrate"]+0, 5, " ", STR_PAD_LEFT);
			$page[$j] .= "%";
			$page[$j] .= str_pad(number_format($recs["sale_tax_amt"], 2, ".", ","), 13, " ", STR_PAD_LEFT);
			$page[$j] .= "\n\r";
			$page[$j] .= "\n\r";
			$page[$j] .= str_repeat(" ", 65);
			$page[$j] .= str_pad(number_format($recs["sale_amt"] + $recs["sale_freight_amt"] + $recs["sale_tax_amt"], 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n\r";
		} else {
			for ($i=0;$i<5;$i++) $page[$j] .= "\n\r";
		}
		for ($i=0;$i<3;$i++) $page[$j] .= "\n\r";
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
	}

/*
	list($usec,$sec)=explode(" ",microtime());
	$filename = "out/sale_".$sec.substr($usec, 2,6).".txt";

	$windows = 0;
	$redir = true;
	if ($redir == true) {
		$f=fopen ($filename,"w");
		fputs($f,$out,strlen($out));
		fclose($f);
		$port = strtolower($port);
		if ($windows>0) {
		  exec("copy $filename $port");
		  exec("del $filename ");
		} else {
		  $prtcmd = "lpr -P $port -o raw $filename";
		  echo $prtcmd;
		  exec($prtcmd);
		  exec("rm $filename ");
		}
	} else {
		echo "<pre>$out</pre>";
		print "lpr -P $port -o raw $filename";
	}
*/
}

$_SESSION["saledtl_del"]=NULL;
$sh_edit = "sales_edit_".$sale_id;
$sd_edit = "saledtls_edit_".$sale_id;

$cmd = "";
if (array_key_exists("cmd", $_POST)) {
	$cmd = $_POST["cmd"];
}
$ht = "";
if (array_key_exists("ht", $_POST)) {
	$ht = $_POST["ht"];
}

$cmd = $_POST["cmd"] ?? "";
if (empty($cmd)) {
	$cmd = $_GET["cmd"] ?? "";
}

//print_r($_SESSION[$sh_edit]);
if ($cmd == "sale_sess_add") {
	$psls = $r->getRecordData($fields, $_POST);
	$r = new Requests();
	$s = new Sales();
	if ($ht=="e") {
		$sls = $_SESSION[$sh_edit] ?? array();
		$sls = array_merge($sls,$psls);
		$sls["sale_cust_code_old"]	= $_POST["sale_cust_code_old"];
		$_SESSION[$sales_edit] = $sls;
		$loc = "Location: sales_details.php?ty=a&ht=e&sale_id=$sale_id";
	} else {
		$sls = $_SESSION["sales_add"] ?? array();
		$sls = array_merge($sls,$psls);
		$sls["sale_cust_code_old"]	= $_POST["sale_cust_code_old"];
		$_SESSION["sales_add"] = $sls;
		$loc = "Location: sales_details.php?ty=a&ht=a";
	}
	header($loc);
	exit;

} else if ($cmd == "sale_detail_sess_add") {
	$pslsdtl = $r->getRecordData($dtlflds,$_POST);
	if ($ht == "e") {
		$sale = $_SESSION[$sh_edit] ?? array();
		$saledtl = $_SESSION[$sd_edit] ?? array();

		$pslsdtl["slsdtl_sale_id"] = $sale["sale_id"];
		if ($pslsdtl["slsdtl_taxable"] != "t") {
			$pslsdtl["slsdtl_taxable"] = "f";
		}
		$pslsdtl["slsdtl_sort"] = $_POST["slsdtl_sort"];
		array_push($_SESSION[$sd_edit], $pslsdtl);

		$sale["sale_amt"] = 0;
		$sale["sale_tax_amt"] = 0;
		for ($i=0;$i<count($saledtl);$i++) {
			if (!empty($saledtl[$i])) {
				$sale["sale_amt"] += $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"];
				if ($saledtl[$i]["slsdtl_taxable"] == "t") {
					$sale["sale_tax_amt"] += $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"] * $sale["sale_taxrate"]/ 100;
				}
			}
		}

		$_SESSION[$sh_edit] = $sale;
		$_SESSION[$sd_edit] = $saledtl;
		$loc = "Location: sales_details.php?ty=a&ht=e&sale_id=$sale_id";
	} else {		
		if (empty($_POST["slsdtl_item_code"])) {
			$errno=4;
			$errmsg="Item code should not be blank!";
			include("error.php");
			exit;
		} else {
			$sale = $_SESSION["sales_add"] ?? array();
			$saledtl = $_SESSION["saledtls_add"] ?? array();
			echo("====================");
			print_r($sale);
			echo("====================");
			print_r($saledtl);
			echo("====================");
			print_r($_POST);
			echo("====================");

			$dtl = array();
			$sale["sale_amt"] = 0;
			$sale["slsdtl_taxable"] = "t";
			for ($i=0;$i<count($saledtl ?? array());$i++) {
				if (!empty($saledtl[$i])) {
					array_push($dtl, $saledtl[$i]);
					$sale["sale_amt"] += $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"];
					if ($saledtl[$i]["slsdtl_taxable"] == "t") {
						$sale["sale_tax_amt"] += $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"] * $sale["sale_taxrate"]/ 100;
					}
				}
			}

			$slsdtl = array();
			$slsdtl = array_merge($slsdtl, $pslsdtl);
			$slsdtl["slsdtl_sale_id"] = $sale["sale_id"] ?? 0;
			if ($slsdtl["slsdtl_taxable"] ?? "f" != "t") {
				$slsdtl["slsdtl_taxable"] = "f";
			}
			$slsdtl["slsdtl_sort"] = $_POST["slsdtl_sort"];
			array_push($dtl, $slsdtl);
			$_SESSION[$sd_edit] = $dtl;
	
			$sale["sale_amt"] += $slsdtl["slsdtl_qty"] * $slsdtl["slsdtl_cost"];
			if ($slsdtl_taxable == "t") {
				$sale["sale_tax_amt"] += $slsdtl["slsdtl_qty"] * $slsdtl["slsdtl_cost"] * $sale["sale_taxrate"]/ 100;
			}
			$_SESSION["sales_add"] = $sale;
			$loc = "Location: sales_details.php?ty=a&ht=a";
		}
	}
	header($loc);

} else if ($cmd == "sale_add") {
	$s = new Sales();
	$d = new SaleDtls();
	$it = new Items();
	$cu = new Custs();
	$dx = new Datex();

	if (empty($_POST["sale_cust_code"])) {
		$errno=7;
		$errmsg="Customer code is empty";
		include("error.php");
		exit;
	}
	$cu_arr = $cu->getCusts($_POST["sale_cust_code"]);
	if (empty($cu_arr)) {
		$errno=8;
		$errmsg="Customer code has not been found";
		include("error.php");
		exit;
	}

	//print_r($_POST);
	if (!empty($cu_arr['cust_email'])) {
		$carr = array('cust_email'=>$_POST['cust_email']);
		$cu->updateCusts($cu_arr['sale_cust_code'], $carr); 
	}
	
	if (!empty($cu_arr['cust_memo'])) {
		$carr = array('cust_memo'=>$_POST['cust_memo']);
		$cu->updateCusts($_POST['sale_cust_code'], $carr); 
	}

	$oldrec = $s->getTextFields("", "");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	$dflds = array("sale_date", "sale_prom_date");
	for ($i=0;$i<count($dflds);$i++) {
		if (array_key_exists($dflds[$i],$arr) && !$dx->isIsoDate($arr[$dflds[$i]]) && !$dx->isUsaDate($arr[$dflds[$i]])) {
			$arr[$dflds[$i]] = null;
			//print_r($dflds[$i].$arr[$dflds[$i]]);
		}
	}

	/*
	if (count($arr)<=1) {
		$loc = "Location: sales.php?ty=e&sale_id=$sale_id";
		header($loc);
		exit;	
	}
	*/
	
	$sale_id = $s->insertSales($arr);

	//if (empty($sale_id)) $sale_id = $sid;
	$sdtls = $_SESSION["saledtls_add"] ?? array();
	if ($sdtls) $sdtl_num = count($sdtls);
	else $sdtl_num = 0;
	$taxtotal = 0;
	$subtotal = 0;
	for ($i=0;$i<$sdtl_num;$i++) {
		$sdtls[$i]["slsdtl_sale_id"] = $sale_id;
		$i_arr = $it->getItems($sdtls[$i]["slsdtl_item_code"]);
		$sdtls[$i]["slsdtl_unit"] = $i_arr["item_unit"];
		if (empty($sdtls[$i]["slsdtl_qty_bo"])) {
			$sdtls[$i]["slsdtl_qty_bo"] = 0;
		}
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
		$cs_arr["custship_addr2"] = $sale_addr2;
		$cs_arr[["custship_addr3"]] = $sale_addr2;
		$cs_arr[["custship_city"]] = $sale_city;
		$cs_arr["custship_state"] = $sale_state;
		$cs_arr["custship_country"] = $sale_country;
		$cs_arr["custship_zip"] = $sale_zip;
		$cs_arr["custship_tel"] = $sale_tel;
		$cs_arr["custship_shipvia"] = $sale_shipvia;
		$id = $cs->insertCustShips($cs_arr); 
	}

// tax recalculation...
	if ($_POST["sale_taxrate"]>0) $tax_rate = $_POST["sale_taxrate"];
	else $tax_rate = 0;
	$sh_arr = array();
	$sh_arr["sale_amt"] = $subtotal;;
	$sh_arr["sale_tax_amt"] = round($taxtotal*$tax_rate)/100;
	if ($arr && $sdtls) $s->updateSales($sale_id, $sh_arr);

	// handle deposit..
	if ($_POST["sale_deposit_amt"] !=0 || $_POST["sale_disc_amt"] != 0) {
		$jt = new JrnlTrxs();
		$rh = new Receipt();
		$rd = new RcptDtls();

		$rh_arr = array();
		$rh_arr["rcpt_date"] = $_POST["sale_date"];
		$rh_arr["rcpt_ref_code"] = $sale_id;
		$rh_arr["rcpt_type"] = $_SESSION["sale_deposit"]["rcpt_type"];
		$rh_arr["rcpt_cust_code"] = $_POST["sale_cust_code"];
		$rh_arr["rcpt_po_no"] = "";
		$rh_arr["rcpt_check_no"] = $_SESSION["sale_deposit"]["rcpt_check_no"];
		$rh_arr["rcpt_amt"] = $_POST["sale_deposit_amt"]+0;
		$rh_arr["rcpt_desc"] = "Customer Deposit";
		$rh_arr["rcpt_user_code"] = $_SERVER["PHP_AUTH_USER"];
		$rh_arr["rcpt_comment"] = $_SESSION["sale_deposit"]["rcpt_comment"];

		if ($rcpt_id = $rh->insertReceipt($rh_arr)) {
			if (!empty($_POST["sale_deposit_amt"]) && $_POST["sale_deposit_amt"] != 0) {
				$jt->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $default["ascr_acct"], "c", "d", $rh_arr["rcpt_amt"], $rh_arr["rcpt_date"]);

				$sdtls = array();
				$sdtls["rcptdtl_type"] = "op";
				$sdtls["rcptdtl_rcpt_id"] = $rcpt_id;
				$sdtls["rcptdtl_acct_code"] = $default["licd_acct"];
				$sdtls["rcptdtl_sale_id"] = $sale_id;
				$sdtls["rcptdtl_amt"] = $_POST["sale_deposit_amt"];
				$sdtls["rcptdtl_desc"] = "Deposit";
				if (!$iddtl = $rd->insertRcptDtls($sdtls)) {
					$errno=6;
					$errmsg="Receiptment Detail Insertion Error";
					include("error.php");
					exit;
				}
				$jt->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $sdtls["rcptdtl_acct_code"], "c", "c", $rh_arr["rcpt_amt"], $rh_arr["rcpt_date"]);
			}
		}
	}

	$h = new SaleHists();
	$hist_arr = array();
	$hist_arr["salehist_sale_id"]=$sale_id;
	$hist_arr["salehist_type"]="i";
	$hist_arr["salehist_user_code"]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr["salehist_modified"]=date("YmdHis");
	$hdr = $s->getSales($sale_id);
	$hist_arr["salehist_header"] = "";
	foreach ($hdr as $k => $v) {
		if ($hist_arr["salehist_header"] != "") $hist_arr["salehist_header"] .= ",";
		$hist_arr["salehist_header"] .= addslashes($v ?? "");
	}
	$lin = $d->getSaleDtlsList($sale_id);
	$hist_arr["salehist_lines"] = "";
	for ($i=0;$i<count($lin);$i++) {
		if ($i>0) $hist_arr["salehist_lines"] .= ",";
		$hist_arr["salehist_lines"] .= "(";
		$first = True;
		foreach ($lin[$i] as $k => $v) {
			if ($first != True) {
				$hist_arr["salehist_lines"] .= ",";
				$first = False;
			}
			$hist_arr["salehist_lines"] .= addslashes($v);
		}
		$hist_arr["salehist_lines"] .= ")";
	}
	$h->insertSaleHists($hist_arr);

	$_SESSION["sale_deposit"]=NULL;
	$_SESSION["sales_add"]=NULL;
	$_SESSION["saledtls_add"]=NULL;
	$loc = "Location: sales.php?ty=e&sale_id=$sale_id";
	header($loc);
	exit;

} else if ($cmd == "sale_edit") {
	$it = new Items();
	$s = new Sales();
	$d = new SaleDtls();
	$h = new SaleHists();
	$cu = new Custs();


	$sale_id = $_POST["sale_id"];
	$sarr = $s->getSales($sale_id);
	if (!$sarr) {
		$errno=4;
		$errmsg="Sales id is not in database";
		include("error.php");
		exit;
	}

	$sales_edit_data = $_POST;
    $sales_edit_data["sale_amt"]=str_replace(",","",$_POST["sale_amt"]);
    $sales_edit_data["sale_tax_amt"]=str_replace(",","",$_POST["sale_tax_amt"]);
    $sales_edit_data["sale_freight_amt"]=str_replace(",","",$_POST["sale_freight_amt"]);
    $sales_edit_data["sale_deposit_amt"]=str_replace(",","",$_POST["sale_deposit_amt"]);
    $sales_edit_data["sale_disc_amt"]=str_replace(",","",$_POST["sale_disc_amt"]);

	if ($_POST["conflict"]==1 and $default["overwrite"]==$_POST["edit_overwrite"]) {
		$sales_edit_data = $_SESSION[$sh_edit] ?? array();
	} else if ($_POST["conflict"]==1 and $default["overwrite"]!=$_POST["edit_overwrite"]) {
		header("Location: sales.php?ty=c&sale_id=$sale_id");
		exit;
	} else if ($sarr["sale_user_code"]!=$_SERVER["PHP_AUTH_USER"]) {
		$_SESSION[$sh_edit]=$_POST;
		header("Location: sales.php?ty=c&sale_id=$sale_id");
		exit;
	}

	$sales_edit_data["sale_user_code"]=$_SERVER["PHP_AUTH_USER"];

	//$lastmod = $h->getLastEditUser($sale_id);
	//if ($lastmod and $_SESSION["PHP_AUTH_USER"]==$lastmod["salehist_user_code"]) {
	//	$loc = "Location: sales.php?ty=c&sale_id=$sale_id";
	//	header($loc);
	//	exit;
	//}

	if (!empty($_POST['cust_email'])) {
		$carr = array('cust_email'=>$_POST['cust_email']);
		$cu->updateCusts($_POST['sale_cust_code'], $carr); 
	}
	
	if (!empty($_POST['cust_memo'])) {
		$carr = array('cust_memo'=>$_POST['cust_memo']);
		$cu->updateCusts($_POST['sale_cust_code'], $carr); 
	}

	//$d->deleteSaleDtlsSI($sale_id);
	$sdtls = $_SESSION[$sd_edit];
    //print_r($_SESSION);
	if ($sdtls) $sdtl_num = count($sdtls);
	else $sdtl_num = 0;

	if ($sdtl_num<=0) { // if there is no lines, issue error message...
		$hist_arr = array();
		$hist_arr["salehist_sale_id"]=$sale_id;
		$hist_arr["salehist_type"]="u";
		$hist_arr["salehist_user_code"]=$_SERVER["PHP_AUTH_USER"];
		$hist_arr["salehist_modified"]=date("YmdHis");
		$hdr = $s->getSales($sale_id);
		$hist_arr["salehist_header"] = "";
		foreach ($hdr as $k => $v) {
			if ($hist_arr["salehist_header"] != "") $hist_arr["salehist_header"] .= ",";
			$hist_arr["salehist_header"] .= addslashes($v);
		}
		$h->insertSaleHists($hist_arr);

		$_SESSION[$sh_edit]=NULL;
		$_SESSION[$sd_edit]=NULL;
		$_SESSION["saledtl_del"]=1;

		$errno=5;
		$errmsg="Can't find sales lines";
		include("error.php");
		exit;
	}

	$amt = 0;
	$sd_arr = $d->getSaleDtlHdrs($sale_id);

	if (!empty($sd_arr)) $sd_num = count($sd_arr);
	else $sd_num = 0;


	for ($i=0;$i<$sdtl_num;$i++) {
		$sdtls[$i]["slsdtl_sale_id"] = $sale_id;
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
			if (!$udt_res) {
				//print_r($udt_res );
				//print_r($sdtls[$i]);
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

    // update sales header...
	$oldrec = $s->getTextFields("", "$sale_id");
	$r = new Requests();
	$sh_arr = $r->getAlteredArray($oldrec, $sales_edit_data);
	/*
	$sd_arr = $d->getSaleDtlsList($sale_id);
	$sh_arr["sale_amt"]=0;
    $sh_arr["sale_tax_amt"]=0;
    for ($i=0;$i<count($sd_arr);$i++) {
		$sh_arr["sale_amt"] += round($sd_arr[$i]["slsdtl_cost"]*$sd_arr[$i]["slsdtl_qty"],2);
		if ($sd_arr[$i]["slsdtl_taxable"]=="t") $sh_arr["sale_tax_amt"] += round($sd_arr[$i]["slsdtl_cost"]*$sd_arr[$i]["slsdtl_qty"]*$sh_arr["sale_taxrate"]/100,2);
    }
	*/

    $s->updateSales($sale_id, $sh_arr);

    // shipping address handling...
	if ($save_ship == "t") {
		$cs = new CustShips();
		$cs_arr = array();
		$cs_arr["custship_cust_code"]=$sale_cust_code;
		$cs_arr["custship_name"] = $sale_name;
		$cs_arr["custship_addr1"] = $sale_addr1;
		$cs_arr["custship_addr2"] = $sale_addr2;
		$cs_arr["custship_addr3"] = $sale_addr2;
		$cs_arr["custship_city"] = $sale_city;
		$cs_arr["custship_state"] = $sale_state;
		$cs_arr["custship_country"] = $sale_country;
		$cs_arr["custship_zip"] = $sale_zip;
		$cs_arr["custship_tel"] = $sale_tel;
		$cs_arr["custship_shipvia"] = $sale_shipvia;
		$id = $cs->insertCustShips($cs_arr); 
	}


// tax recalcuration...
/*
	$sh_arr = array();
	$sh_arr["sale_amt"] = $sd_arr[slsdtl_sum];
	if ($_POST["sale_taxrate"]>0) $tax_rate = $_POST["sale_taxrate"];
	else $tax_rate = 0;
	$sh_arr["sale_tax_amt"] = sprintf("%0.2f", $sd_arr[slsdtl_tax_sum]*$tax_rate/100);
	if ($arr && $sd_arr) $s->updateSales($sale_id, $sh_arr);
*/

	// handle deposit..
	if ($_POST["sale_deposit_amt"] !=0 || $_POST["sale_disc_amt"] != 0) {
		$jt = new JrnlTrxs();
		$rh = new Receipt();
		$rd = new RcptDtls();
		$rh_arr = array();
		$rc_arr = $rh->getReceiptSales($sale_id);
		if ($rc_arr) { // update
			$rh_arr["rcpt_date"] = $_POST["sale_date"];
			$rh_arr["rcpt_ref_code"] = $sale_id;
//			$rh_arr[rcpt_sale_id] = $sale_id;
			$rh_arr["rcpt_type"] = $_SESSION["sale_deposit"]["rcpt_type"];
			$rh_arr["rcpt_cust_code"] = $_POST["sale_cust_code"];
			$rh_arr["rcpt_po_no"] = "";
			$rh_arr["rcpt_check_no"] = $_SESSION["sale_deposit"]["rcpt_check_no"];;
			$rh_arr["rcpt_amt"] = $_POST["sale_deposit_amt"]+0;
			$rh_arr["rcpt_desc"] = "Customer Deposit";
			$rh_arr["rcpt_user_code"] = $_SERVER["PHP_AUTH_USER"];
			$rh_arr["rcpt_comment"] = $_SESSION["sale_deposit"]["rcpt_comment"];
			if ($rh->updateReceipt($rc_arr["rcpt_id"], $rh_arr)) {
				if (!empty($_POST["sale_deposit_amt"]) && $_POST["sale_deposit_amt"] != 0) {
					$jt->deleteJrnlTrxRefs($rc_arr["rcpt_id"], "c");

					$jt->insertJrnlTrxExs($rc_arr["rcpt_id"], $_SERVER["PHP_AUTH_USER"], $default["ascr_acct"], "c", "d", $rh_arr["rcpt_amt"], $rh_arr["rcpt_date"]);
					$rd->deleteRcptDtlsPI($rc_arr["rcpt_id"]);
					$sdtls = array();
					$sdtls["rcptdtl_type"] = "op";
					$sdtls["rcptdtl_rcpt_id"] = $rc_arr["rcpt_id"];
					$sdtls["rcptdtl_acct_code"] = $default["licd_acct"];
					$sdtls["rcptdtl_sale_id"] = $sale_id;
					$sdtls["rcptdtl_amt"] = $_POST["sale_deposit_amt"];
					$sdtls["rcptdtl_desc"] = "Deposit";
					if (!$iddtl = $rd->insertRcptDtls($sdtls)) {
						$errno=6;
						$errmsg="Receiptment Detail Insertion Error";
						include("error.php");
						exit;
					}
					$jt->insertJrnlTrxExs($rc_arr["rcpt_id"], $_SERVER["PHP_AUTH_USER"], $sdtls["rcptdtl_acct_code"], "c", "c", $rh_arr["rcpt_amt"], $rh_arr["rcpt_date"]);
				}
			}
		} else { // new deposit
			$rh_arr["rcpt_date"] = $_POST["sale_date"];
			$rh_arr["rcpt_ref_code"] = $sale_id;
			$rh_arr["rcpt_type"] = $_SESSION["sale_deposit"]["rcpt_type"];
			$rh_arr["rcpt_cust_code"] = $_POST["sale_cust_code"];
			$rh_arr["rcpt_po_no"] = "";
			$rh_arr["rcpt_check_no"] = $_SESSION["sale_deposit"]["rcpt_check_no"];
			$rh_arr["rcpt_amt"] = $_POST["sale_deposit_amt"]+0;
			$rh_arr["rcpt_desc"] = "Customer Deposit";
			$rh_arr["rcpt_user_code"] = $_SERVER["PHP_AUTH_USER"];
			$rh_arr["rcpt_comment"] = $_SESSION["sale_deposit"]["rcpt_comment"];

			if ($rcpt_id = $rh->insertReceipt($rh_arr)) {
				if (!empty($_POST["sale_deposit_amt"]) && $_POST["sale_deposit_amt"] != 0) {
					$jt->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $default["ascr_acct"], "c", "d", $rh_arr["rcpt_amt"], $rh_arr["rcpt_date"]);
					$sdtls = array();
					$sdtls["rcptdtl_type"] = "op";
					$sdtls["rcptdtl_rcpt_id"] = $rcpt_id;
					$sdtls["rcptdtl_acct_code"] = $default["licd_acct"];
					$sdtls["rcptdtl_sale_id"] = $sale_id;
					$sdtls["rcptdtl_amt"] = $_POST["sale_deposit_amt"];
					$sdtls["rcptdtl_desc"] = "Deposit";
					if (!$iddtl = $rd->insertRcptDtls($sdtls)) {
						$errno=6;
						$errmsg="Receiptment Detail Insertion Error";
						include("error.php");
						exit;
					}
					$jt->insertJrnlTrxExs($rcpt_id, $_SERVER["PHP_AUTH_USER"], $sdtls["rcptdtl_acct_code"], "c", "c", $rh_arr["rcpt_amt"], $rh_arr["rcpt_date"]);
				}
			}
		}
	}


	$hist_arr = array();
	$hist_arr["salehist_sale_id"]=$sale_id;
	$hist_arr["salehist_type"]="u";
	$hist_arr["salehist_user_code"]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr["salehist_modified"]=date("YmdHis");
	$hdr = $s->getSales($sale_id);
	$hist_arr["salehist_header"] = "";
	foreach ($hdr as $k => $v) {
		if ($hist_arr["salehist_header"] != "") $hist_arr["salehist_header"] .= ",";
		$hist_arr["salehist_header"] .= addslashes($v);
	}
	$lin = $d->getSaleDtlsList($sale_id);
	$hist_arr["salehist_lines"] = "";
	for ($i=0;$i<count($lin);$i++) {
		if ($i>0) $hist_arr["salehist_lines"] .= ",";
		$hist_arr["salehist_lines"] .= "(";
		$first = True;
		foreach ($lin[$i] as $k => $v) {
			if ($first != True) {
				$hist_arr["salehist_lines"] .= ",";
				$first = False;
			}
			$hist_arr["salehist_lines"] .= addslashes($v);
		}
		$hist_arr["salehist_lines"] .= ")";
	}
	$h->insertSaleHists($hist_arr);

	$_SESSION[$sh_edit]=NULL;
	$_SESSION[$sd_edit]=NULL;
	$_SESSION["saledtl_del"]=1;

	$loc = "Location: sales.php?ty=e&sale_id=$sale_id";
	header($loc);
	exit;

} else if ($cmd == "sale_del") {
	$s = new Sales();
	$d = new SaleDtls();

	$h = new SaleHists();
	$hist_arr = array();
	$hist_arr["salehist_sale_id"]=$sale_id;
	$hist_arr["salehist_type"]="d";
	$hist_arr["salehist_user_code"]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr["salehist_modified"]=date("YmdHis");
	$hdr = $s->getSales($sale_id);
	$hist_arr["salehist_header"] = "";
	foreach ($hdr as $k => $v) {
		if ($hist_arr["salehist_header"] != "") $hist_arr["salehist_header"] .= ",";
		$hist_arr["salehist_header"] .= addslashes($v);
	}
	$lin = $d->getSaleDtlsList($sale_id);
	$hist_arr["salehist_lines"] = "";
	for ($i=0;$i<count($lin);$i++) {
		if ($i>0) $hist_arr["salehist_lines"] .= ",";
		$hist_arr["salehist_lines"] .= "(";
		$first = True;
		foreach ($lin[$i] as $k => $v) {
			if ($first != True) {
				$hist_arr["salehist_lines"] .= ",";
				$first = False;
			}
			$hist_arr["salehist_lines"] .= addslashes($v);
		}
		$hist_arr["salehist_lines"] .= ")";
	}
	$h->insertSaleHists($hist_arr);

	$s->deleteSales($_GET["sale_id"]);
	$d->deleteSaleDtlsSI($_GET["sale_id"]);
//	$j = new JrnlTrxs();
//	$j->deleteJrnlTrxRefs($sale_id, "r");

	$_SESSION[$sh_edit]=NULL;
	$_SESSION[$sd_edit]=NULL;
	$_SESSION["saledtl_del"]=1;
	$loc = "Location: sales.php?ty=l";
	header($loc);
	exit;

} else if ($cmd == "sale_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$saledtl = $_SESSION[$sd_edit];
		for ($i=0;$i<count($saledtl);$i++) {
			if ($i != $did) {
				array_push($arr, $saledtl[$i]);
			} else {
				$sale = $_SESSION[$sh_edit];
				$sale["sale_amt"] -= $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"];
				if ($saledtl[$i]["slsdtl_taxable"] == "t") $sale["sale_tax_amt"] -= $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"] * $sale["sale_taxrate"]/ 100;
				$_SESSION[$sh_edit] = $sale;
			}
		}
		$_SESSION[$sd_edit] = $arr;
		$_SESSION["saledtl_del"] = 1;
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
				if ($saledtl[$i]["slsdtl_taxable"] == "t") $sale["sale_tax_amt"] -= $saledtl[$i]["slsdtl_qty"] * $saledtl[$i]["slsdtl_cost"] * $sale["sale_taxrate"]/ 100;
				$_SESSION["sales_add"] = $sale;
			}
		}
		$_SESSION["saledtls_add"] = $arr;
		$loc = "Location: sales.php?ty=a";
	}
	header($loc);

} else if ($cmd == "sale_clear_sess_edit") {
	$_SESSION[$sh_edit]=NULL;
	$_SESSION[$sd_edit]=NULL;
	$loc = "Location: sales.php?ty=e&sale_id=$sale_id";
	header($loc);

} else if ($cmd == "sale_clear_sess_add") {
	$_SESSION["sales_add"]=array(); //=NULL;
	$_SESSION["saledtls_add"]=array(); //NULL;
	$loc = "Location: sales.php?ty=a";
	header($loc);

} else if ($cmd == "sale_update_sess_add") {
	$psls = $r->getRecordData($fields, $_POST);
	if ($ty=="e") {
		$_SESSION[$sh_edit] = $psls;
		$loc = "Location: sales.php?ty=e&sale_id=".$_POST["sale_id"];
	} else {
		$s = new Sales();
		if ($arr = $s->getSales($sale_id)) {
			$loc = "Location error.php?".urlencode("errno=3&errmsg=Sales number is already in database");
		} else {
			$_SESSION["sales_add"] = $psls;
			$loc = "Location: sales.php?ty=a";
		}
	}
	header($loc);

} else if ($cmd == "sale_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$saledtl = $_SESSION[$sd_edit];
		for ($i=0;$i<count($saledtl);$i++) {
			if ($i == $_POST["did"]) {
				$pslsdtl = $r->getRecordData($dtlflds,$_POST);
				if ($pslsdtl["slsdtl_taxable"] ?? "t" != "t") $pslsdtl["slsdtl_taxable"] = "f";
				array_push($arr, $pslsdtl);
			} else {
				array_push($arr, $saledtl[$i]);
			}
		}
		$_SESSION[$sd_edit] = $arr;

		$subtotal = 0;
		$taxtotal = 0;
		for ($i=0;$i<count($_SESSION[$sd_edit]);$i++) {
			$subtotal += $_SESSION[$sd_edit][$i]["slsdtl_qty"] * $_SESSION[$sd_edit][$i]["slsdtl_cost"];
			if ($_SESSION[$sd_edit][$i]["slsdtl_taxable"]=="t") $taxtotal += $_SESSION[$sd_edit][$i]["slsdtl_qty"] * $_SESSION[$sd_edit][$i]["slsdtl_cost"];
		}
		$_SESSION[$sh_edit]["sale_amt"] = $subtotal;
		$_SESSION[$sh_edit]["sale_tax_amt"] = $taxtotal * $sales_edit["sale_taxrate"] / 100;
		$loc = "Location: sales_details.php?ty=e&ht=e&sale_id=$sale_id&did=$did";

	} else {
		$pslsdtl = $r->getRecordData($dtlflds,$_POST);
		$arr = array();
		$saledtl = $_SESSION["saledtls_add"];
		for ($i=0;$i<count($saledtl ?? array());$i++) {
			if ($i == $_POST["did"]) {
				$pslsdtl = $r->getRecordData($dtlflds,$_POST);
				if ($pslsdtl["slsdtl_taxable"] ?? "t" != "t") $pslsdtl["slsdtl_taxable"] = "f";
				array_push($arr, $pslsdtl);
			} else {
				array_push($arr, $saledtl[$i]);
			}
		}
		$_SESSION["saledtls_add"] = $arr;

		$subtotal = 0;
		$taxtotal = 0;
		for ($i=0;$i<count($_SESSION["saledtls_add"]);$i++) {
			$subtotal += $_SESSION["saledtls_add"][$i]["slsdtl_qty"] * $_SESSION["saledtls_add"][$i]["slsdtl_cost"];
			if ($_SESSION["saledtls_add"][$i]["slsdtl_taxable"]=="t") $taxtotal += $_SESSION["saledtls_add"][$i]["slsdtl_qty"] * $_SESSION["saledtls_add"][$i]["slsdtl_cost"];
		}
		$_SESSION["sales_add"]["sale_amt"] = $subtotal;
		$_SESSION["sales_add"]["sale_tax_amt"] = $taxtotal * $_SESSION["sales_add"]["sale_taxrate"] / 100;

		$loc = "Location: sales_details.php?ty=e&ht=a&did=$did";

	}
	header($loc);

} else if ($cmd == "sale_print") {
	$u = new Users();
	$user_arr = $u->getUsers($_SERVER["PHP_AUTH_USER"]);
	salePrint($sale_id, $user_arr["user_printer"]);

	$s = new Sales();
	$d = new SaleDtls();
	$h = new SaleHists();
	$hist_arr = array();
	$hist_arr["salehist_sale_id"]=$sale_id;
	$hist_arr["salehist_type"]="p";
	$hist_arr["salehist_user_code"]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr["salehist_modified"]=date("YmdHis");
	$hdr = $s->getSales($sale_id);
	$hist_arr["salehist_header"] = "";
	foreach ($hdr as $k => $v) {
		if ($hist_arr["salehist_header"] != "") $hist_arr["salehist_header"] .= ",";
		if (isset($v)) {
			$hist_arr["salehist_header"] .= addslashes($v);
		}
	}
	$lin = $d->getSaleDtlsList($sale_id);
	$hist_arr["salehist_lines"] = "";
	for ($i=0;$i<count($lin);$i++) {
		if ($i>0) $hist_arr["salehist_lines"] .= ",";
		$hist_arr["salehist_lines"] .= "(";
		$first = True;
		foreach ($lin[$i] as $k => $v) {
			if ($first != True) {
				$hist_arr["salehist_lines"] .= ",";
				$first = False;
			}
			$hist_arr["salehist_lines"] .= addslashes($v);
		}
		$hist_arr["salehist_lines"] .= ")";
	}
	$h->insertSaleHists($hist_arr);

	//$loc = "Location: sales.php?ty=$ty&pg=$pg&cn=$cn&ft=$ft&sale_id=$sale_id";
	$loc = "Location: sales.php?ty=$ty&sale_id=$sale_id";
	header($loc);

} else if ($cmd == "sale_to_pick_add") {
	$s = new Sales();
	$p = new Picks();
	$sale_arr = $s->getSales($sale_id);
	if ($sale_arr) {
		$picks_add = array();
		$picks_add["pick_code"]			= $p->getPickMaxId() + $default[pick_start_no] + 1;
		$picks_add["pick_user_code"]		= $sale_arr["sale_user_code"];
		$picks_add["pick_cust_code"]		= $sale_arr["sale_cust_code"];
		$picks_add["pick_name"]			= $sale_arr["sale_name"];
		$picks_add["pick_addr1"]			= $sale_arr["sale_addr1"];
		$picks_add["pick_addr2"]			= $sale_arr["sale_addr2"];
		$picks_add["pick_addr3"]			= $sale_arr["sale_addr3"];
		$picks_add["pick_city"]			= $sale_arr["sale_city"];
		$picks_add["pick_state"]			= $sale_arr["sale_state"];
		$picks_add["pick_country"]		= $sale_arr["sale_country"];
		$picks_add["pick_zip"]			= $sale_arr["sale_zip"];
		$picks_add["pick_tel"]			= $sale_arr["sale_tel"];
		$picks_add["pick_date"]			= date("m/d/y");
		$picks_add["pick_prom_date"]		= $sale_arr["sale_prom_date"];
		$picks_add["pick_shipvia"]		= $sale_arr["sale_shipvia"];
		$picks_add["pick_taxrate"]		= $sale_arr["sale_taxrate"];

		$picks_add["pick_amt"]			= $sale_arr["sale_amt"];
		$picks_add["pick_tax_amt"]		= $sale_arr["sale_tax_amt"];
		$picks_add["pick_freight_amt"]	= $sale_arr["sale_freight_amt"];
		$_SESSION["pick_cust_code"]=$sale_arr["sale_user_code"];
		$_SESSION["picks_add"]=$picks_add;

		$d = new SaleDtls();
		$pd = new PickDtls();
		$darr = $d->getSaleDtlsList($sale_id);
		$subtotal = 0;
		$pickdtls_add = array();
		for ($i=0;$i<count($darr);$i++) {
			$pks = array();
			$pks["pickdtl_pick_id"]	= $pick_id;
			$pks["pickdtl_code"]		= $darr[$i]["slsdtl_id"];
			$qty_picked = $pd->getPickDtlsSlsSum($darr[$i]["slsdtl_id"]);
			$pks["pickdtl_qty"]		= $darr[$i]["slsdtl_qty"]-$qty_picked;
			$pks["pickdtl_cost"]		= $darr[$i]["slsdtl_cost"];
			$pks["pickdtl_unit"]		= $darr[$i]["slsdtl_unit"];
			if ($pks["pickdtl_qty"]>0) array_push($pickdtls_add, $pks);
		}
		$_SESSION["pickdtls_add"]=$pickdtls_add;
		$loc = "Location: picking.php?ty=a";
	} else {
		$loc = "Location: sales.php?ty=l&pg=$pg";
	}
	header($loc);
} else if ($cmd == "sale_custom_po") {
	$s = new Sales();
	$d = new SaleDtls();
	$t = new Items();
	$sale_arr = $s->getSales($sale_id);
	if ($sale_arr) {
		$pur_add = array();
		$pur_add["purch_sale_id"]		= $sale_id;
		$pur_add["purch_user_code"]	= $_SERVER["PHP_AUTH_USER"];
		$pur_add["purch_cust_code"]	= $sale_arr["sale_cust_code"];
		$pur_add["purch_name"]		= $sale_arr["sale_name"];
		$pur_add["purch_addr1"]		= $sale_arr["sale_addr1"];
		$pur_add["purch_addr2"]		= $sale_arr["sale_addr2"];
		$pur_add["purch_addr3"]		= $sale_arr["sale_addr3"];
		$pur_add["purch_city"]		= $sale_arr["sale_city"];
		$pur_add["purch_state"]		= $sale_arr["sale_state"];
		$pur_add["purch_country"]		= $sale_arr["sale_country"];
		$pur_add["purch_zip"]			= $sale_arr["sale_zip"];
		$pur_add["purch_tel"]			= $sale_arr["sale_tel"];
		$pur_add["purch_date"]		= date("m/d/y");
		$pur_add["purch_custom_po"]	= "t";
		$_SESSION["purchases_add"] = $pur_add;
		$_SESSION["purch_cust_code"] = $sale_arr["sale_cust_code"];

		$darr = $d->getSaleDtlsList($sale_id);
		$subtotal = 0;
		$pd_add = array();
		for ($i=0;$i<count($darr);$i++) {
			$pks = array();
			$pks["purdtl_item_code"]		= $darr[$i]["slsdtl_item_code"];
			$pks["purdtl_item_desc"]		= $darr[$i]["slsdtl_item_desc"];
			$pks["purdtl_qty"]			= $darr[$i]["slsdtl_qty"];
			$item_arr = $t->getItems($pks["purdtl_item_code"]);
			$pks["purdtl_cost"]			= $item_arr["item_last_cost"];
			if ($pks["purdtl_qty"]>0) array_push($pd_add, $pks);
		}
		$_SESSION["purdtls_add"] = $pd_add;
		$loc = "Location: purchase.php?ty=a";
	} else {
		$loc = "Location: sales.php?ty=l&pg=1";
	}
	header($loc);

} else if ($cmd == "sale_deposit") {
	$deposit = array();
	$deposit["rcpt_amt"] = $rcpt_amt;
	$deposit["rcpt_type"] = $rcpt_type;
	$deposit["rcpt_check_no"] = $rcpt_check_no;
	$deposit["rcpt_comment"] = $rcpt_comment;
	$_SESSION["sale_deposit"]=$deposit;
	$loc = "Location: sales_deposit_entry_popup.php?close=t";
	header($loc);
	exit;

} else if ($cmd == "sale_batch") {
	$d = new Datex();
	$s = new Sales();
	$u = new Users();

	if (!empty($from) && !empty($to)) {
		$errno =0;
		if ($codetype == "code") {
			$sale_arr = $s->getSalesRange($from, $to, "sale_id");
		} else if ($codetype == "date") {
			if (!empty($from)) $from = $d->toIsoDate($from);
			if (!empty($to)) $to = $d->toIsoDate($to);
			$sale_arr = $s->getSalesRange($from, $to, "sale_date");
		} else {
			// no codetype.... 
			$errno = 9;
		}

		$sale_num = count($sale_arr);
		if ($worktype == "print") {
			$user_arr = $u->getUsers($_SERVER["PHP_AUTH_USER"]);
			for ($i=0;$i<$sale_num;$i++) {
				salePrint($sale_arr[$i]["sale_id"], $user_arr["user_printer"]);

				$h = new SaleHists();
				$hist_arr = array();
				$hist_arr["salehist_sale_id"]=$sale_arr[$i]["sale_id"];
				$hist_arr["salehist_type"]="p";
				$hist_arr["salehist_user_code"]=$_SERVER["PHP_AUTH_USER"];
				$hist_arr["salehist_modified"]=date("YmdHis");
				$h->insertSaleHists($hist_arr);
			}
		} else if ($worktype == "pick") {
			for ($i=0;$i<$sale_num;$i++) {
				makePicks($sale_arr[$i]["sale_id"]);

				$h = new SaleHists();
				$hist_arr = array();
				$hist_arr["salehist_sale_id"]=$sale_arr[$i]["sale_id"];
				$hist_arr["salehist_type"]="k";
				$hist_arr["salehist_user_code"]=$_SERVER["PHP_AUTH_USER"];
				$hist_arr["salehist_modified"]=date("YmdHis");
				$h->insertSaleHists($hist_arr);
			}
		}
	}
	$loc = "Location: sales_batch_popup.php?close=t&objname=$objname&errno=$errno";
	header($loc);
	exit;

} else if ($cmd == "sale_pending") {
	$s = new Sales();
	$d = new SaleDtls();
	$it = new Items();
	$cu = new Custs();
	$ph =  new Pends();
	$pl = new PenDtls();

	$sale_id = $_POST["sale_id"];

	if (empty($_POST["sale_cust_code"])) {
		$errno=7;
		$errmsg="Customer code is empty";
		include("error.php");
		exit;
	}
	$cu_arr = $cu->getCusts($_POST["sale_cust_code"]);
	if (empty($cu_arr)) {
		$errno=8;
		$errmsg="Customer code has not been found";
		include("error.php");
		exit;
	}

	$sale =  $s->getSales($_POST["sale_id"]);
	if (!$sale) {
		$errno=9;
		$errmsg="There is no such sales number!";
		include("error.php");
		exit;
	}

	if ($d->getSaleDtlBoQty($_POST["sale_id"])<=0) {
		$errno=10;
		$errmsg="There is no backorder quantity in the sales!";
		include("error.php");
		exit;
	}

	$pend = array();
	foreach ($sale as $k=>$v) {
		if (substr($k,0,4)!="sale") continue;
		if ($k=="sale_id") continue;
		if ($k=="sale_status") continue;
		$x = substr_replace($k,"pend",0,4);
		$pend[$x]=$v;
	}
	$pend['pend_user_code']=$_SERVER["PHP_AUTH_USER"];
	$pend['pend_date']=date("Y-m-d");
	if ($sale['sale_origin']>0) $pend['pend_origin']=$sale['sale_origin'];
	else $pend['pend_origin']=$sale_id;
	$pend['pend_parent']=$sale_id;
	$pend['pend_version']+=1;
	$pend_id = $ph->insertPends($pend);
	$pend['pend_id']=$pend_id;

	$sdtls = $d->getSaleDtlsList($sale_id);
	if ($sdtls) $sdtl_num = count($sdtls);
	else $sdtl_num = 0;
	$taxtotal_sale = 0;
	$subtotal_sale = 0;
	$taxtotal_pend = 0;
	$subtotal_pend = 0;
	for ($i=0;$i<$sdtl_num;$i++) {
		if ($sdtls[$i]['slsdtl_qty_bo']>0) {
			$pndtl = array();
			$pndtl['pendtl_pend_id']=$pend_id;
			$pndtl['pendtl_item_code']=$sdtls[$i]['slsdtl_item_code'];
			$pndtl['pendtl_item_desc']=$sdtls[$i]['slsdtl_item_desc'];
			$pndtl['pendtl_qty_ord']=$sdtls[$i]['slsdtl_qty_ord'];
			$pndtl['pendtl_qty']=$sdtls[$i]['slsdtl_qty_bo'];
			$pndtl['pendtl_qty_picked']=$sdtls[$i]['slsdtl_qty_picked'];
			$pndtl['pendtl_qty_cancel']=$sdtls[$i]['slsdtl_qty_cancel'];
			$pndtl['pendtl_unit']=$sdtls[$i]['slsdtl_unit'];
			$pndtl['pendtl_cost']=$sdtls[$i]['slsdtl_cost'];
			$pndtl['pendtl_log']=$sdtls[$i]['slsdtl_log'];
			$pndtl['pendtl_taxable']=$sdtls[$i]['slsdtl_taxable'];
			$pndtl['pendtl_sort']=$sdtls[$i]['slsdtl_sort'];
			$pndtl['pendtl_origin']=$sdtls[$i]['slsdtl_id'];
			$pl->insertPenDtls($pndtl);
			$tmpsd = array();
			$tmpsd['slsdtl_qty_pend'] = $pndtl['pendtl_qty'];
			$tmpsd['slsdtl_qty'] = $sdtls[$i]['slsdtl_qty'];
			$tmpsd['slsdtl_qty_bo'] = 0;
			$d->updateSaleDtls($sdtls[$i]['slsdtl_id'], $tmpsd);
			//if ($tmpsd['slsdtl_qty']>0) $d->updateSaleDtls($sdtls[$i]['slsdtl_id'], $tmpsd);
			//else $d->deleteSaleDtls($sdtls[$i]['slsdtl_id']);
		}
		if ($sdtls[$i]['slsdtl_taxable']=="t") {
			$taxtotal_sale += $sdtls[$i]['slsdtl_cost'] * $sdtls[$i]['slsdtl_qty'];
			$taxtotal_pend += $pndtl['pendtl_cost'] * $pndtl['pendtl_qty'];
		}
		$subtotal_sale += $sdtls[$i]['slsdtl_cost'] * $sdtls[$i]['slsdtl_qty'] ;
		$subtotal_pend += $pndtl['pendtl_cost'] * $pndtl['pendtl_qty'] ;
	}

// tax recalculation...
	$tmpsh = array();
	if ($sale['sale_taxrate']>0) $tmpsh['pend_taxrate'] = $sale['sale_taxrate'];
	else $tmpsh['pend_taxrate'] = 0;
	$tmpsh[pend_amt] = $subtotal_pend;
	$tmpsh[pend_tax_amt] = round($taxtotal_pend*$tmpsh['pend_taxrate'])/100;
	$ph->updatePends($pend_id, $tmpsh);

	$sh_arr = array();
	$sh_arr["sale_amt"] = $subtotal_sale;
	$sh_arr["sale_tax_amt"] = round($taxtotal_sale*$tmpsh['pend_taxrate'])/100;
	if ($sale && $sdtls) $s->updateSales($sale_id, $sh_arr);


	$h = new SaleHists();
	$hist_arr = array();
	$hist_arr["salehist_sale_id"]=$sale_id;
	$hist_arr["salehist_type"]="n";
	$hist_arr["salehist_user_code"]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr["salehist_modified"]=date("YmdHis");
	$hdr = $s->getSales($sale_id);
	$hist_arr["salehist_header"] = "";
	foreach ($hdr as $k => $v) {
		if ($hist_arr["salehist_header"] != "") $hist_arr["salehist_header"] .= ",";
		$hist_arr["salehist_header"] .= addslashes($v);
	}
	$lin = $d->getSaleDtlsList($sale_id);
	$hist_arr["salehist_lines"] = "";
	for ($i=0;$i<count($lin);$i++) {
		if ($i>0) $hist_arr["salehist_lines"] .= ",";
		$hist_arr["salehist_lines"] .= "(";
		$first = True;
		foreach ($lin[$i] as $k => $v) {
			if ($first != True) {
				$hist_arr["salehist_lines"] .= ",";
				$first = False;
			}
			$hist_arr["salehist_lines"] .= addslashes($v);
		}
		$hist_arr["salehist_lines"] .= ")";
	}

	$h->insertSaleHists($hist_arr);

	$_SESSION["sale_deposit"]=NULL;
	$_SESSION[$sh_edit]=NULL;
	$_SESSION[$sd_edit]=NULL;
	$loc = "Location: sales.php?ty=e&sale_id=$sale_id";
	header($loc);
	exit;

} else if ($cmd == "pend_del") {
	$p = new Pends();
	$d = new PenDtls();

	$p->deletePends($_GET["pend_id"]);
	$d->deletePenDtlsAll($_GET["pend_id"]);

	$loc = "Location: pending.php";
	header($loc);
	exit;

} else if ($cmd == "sale_convert") {
	$sh = new Sales();
	$sl = new SaleDtls();
	$it = new Items();
	$cu = new Custs();
	$ph =  new Pends();
	$pl = new PenDtls();

	$pend_id = $_GET["pend_id"];

	$pend =  $ph->getPends($pend_id);

	if (!$pend) {
		$errno=9;
		$errmsg="There is no such a pending sales number!";
		include("error.php");
		exit;
	}

	if ($pend[pend_status]<=0) {
		$errno=10;
		$errmsg="This pending sales has already been converted to sales order!";
		include("error.php");
		exit;
	}

	$sale = array();
	foreach ($pend as $k=>$v) {
		if (substr($k,0,4)!="pend") continue;
		if ($k=="pend_id") continue;
		if ($k=="pend_status") continue;
		$x = substr_replace($k,"sale",0,4);
		$sale[$x]=$v;
	}
	$sale['sale_user_code']=$_SERVER["PHP_AUTH_USER"];
	$sale['sale_date']=date("Y-m-d");
	if ($pend['pend_origin']>0) $sale['sale_origin']=$pend['pend_origin'];
	else $pend['sale_origin']=$_GET["pend_id"];
	$sale['sale_parent']=$_GET["pend_id"];
	$sale_id = $sh->insertSales($sale);

	$sdtls = $pl->getPenDtlsList($pend_id);
	if ($sdtls) $sdtl_num = count($sdtls);
	else $sdtl_num = 0;
	$taxtotal = 0;
	$subtotal = 0;
	for ($i=0;$i<$sdtl_num;$i++) {
		$pndtl = array();
		$pndtl['slsdtl_sale_id']=$sale_id;
		$pndtl['slsdtl_item_code']=$sdtls[$i]['pendtl_item_code'];
		$pndtl['slsdtl_item_desc']=$sdtls[$i]['pendtl_item_desc'];
		$pndtl['slsdtl_qty_ord']=$sdtls[$i]['pendtl_qty'];
		$pndtl['slsdtl_qty']=$sdtls[$i]['pendtl_qty'];
		$pndtl['slsdtl_qty_picked']=$sdtls[$i]['pendtl_qty_picked'];
		$pndtl['slsdtl_qty_cancel']=$sdtls[$i]['pendtl_qty_cancel'];
		$pndtl['slsdtl_unit']=$sdtls[$i]['pendtl_unit'];
		$pndtl['slsdtl_cost']=$sdtls[$i]['pendtl_cost'];
		$pndtl['slsdtl_taxable']=$sdtls[$i]['pendtl_taxable'];
		$pndtl['slsdtl_sort']=$sdtls[$i]['pendtl_sort'];
		$pndtl['slsdtl_origin']=$sdtls[$i]['pendtl_id'];
		$sl->insertSaleDtls($pndtl);
    $tmpsd = array();
		if ($sdtls[$i]['slsdtl_taxable']=="t") {
			$taxtotal += $sdtls[$i]['pendtl_cost'] * $sdtls[$i]['pendtl_qty'];
		}
		$subtotal += $sdtls[$i]['pendtl_cost'] * $sdtls[$i]['pendtl_qty'] ;
	}

// tax recalculation...
/*
	$tmpsh = array();
	if ($sale['sale_taxrate']>0) $tmpsh['pend_taxrate'] = $sale['sale_taxrate'];
	else $tmpsh['pend_taxrate'] = 0;
	$tmpsh["sale_amt"] = $subtotal;
	$tmpsh["sale_tax_amt"] = round($taxtotal*$tmpsh['sale_taxrate'])/100;
	$ph->updateSales($sale_id, $tmpsh);
*/
	$tmph = array('pend_status'=>0);
	if ($sale && $sdtls) $ph->updatePends($_GET["pend_id"], $tmph);

	$h = new SaleHists();
	$hist_arr = array();
	$hist_arr["salehist_sale_id"]=$sale_id;
	$hist_arr["salehist_type"]="v";
	$hist_arr["salehist_user_code"]=$_SERVER["PHP_AUTH_USER"];
	$hist_arr["salehist_modified"]=date("YmdHis");
	$hdr = $sh->getSales($sale_id);
	$hist_arr["salehist_header"] = "";
	foreach ($hdr as $k => $v) {
		if ($hist_arr["salehist_header"] != "") $hist_arr["salehist_header"] .= ",";
		$hist_arr["salehist_header"] .= addslashes($v);
	}
	$lin = $sl->getSaleDtlsList($sale_id);
	$hist_arr["salehist_lines"] = "";
  if (!$lin) $lin = array();
  	for ($i=0;$i<count($lin);$i++) {
  		if ($i>0) $hist_arr["salehist_lines"] .= ",";
  		$hist_arr["salehist_lines"] .= "(";
  		$first = True;
	 	  foreach ($lin[$i] as $k => $v) {
			  if ($first != True) {
				  $hist_arr["salehist_lines"] .= ",";
				  $first = False;
			  }
			  $hist_arr["salehist_lines"] .= addslashes($v);
		  }
		  $hist_arr["salehist_lines"] .= ")";
   }

	$h->insertSaleHists($hist_arr);
	$loc = "Location: pending.php";
	header($loc);
	exit;

}
?>
