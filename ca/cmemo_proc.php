<?php
include_once("class/class.cmemo.php");
include_once("class/class.cmemodtls.php");
include_once("class/class.cmemo.php");
include_once("class/class.cmemodtls.php");
include_once("class/class.users.php");
include_once("class/map.default.php");
include_once("class/register_globals.php");

$cmemodtl_del = $default["comp_code"]."_cmemodtl_del";
$cmemodtl_edit = $default["comp_code"]."_cmemodtl_edit";
$cmemodtl_add = $default["comp_code"]."_cmemodtl_add";
$cmemo_edit = $default["comp_code"]."_cmemo_edit";
$cmemo_add = $default["comp_code"]."_cmemo_add";

function cmemoPrint($cmemo_id, $port) {
	$s = new Sales();
	$p = new Picks();
	$cm = new Cmemo();
	$r = new Receipt();

	$recs = $cm->getCmemo($cmemo_id);
	$cm->increaseCmemo($cmemo_id, "cmemo_print", 1);
	$c = new Custs();
	// for temporary purposes..
//	$c->updateCustsBalance($recs["pick_cust_code"]);
	$cust_arr = $c->getCusts($recs["cmemo_cust_code"]);
	$cd = new CmemoDtl();
	$cd_arr = $cd->getCmemoDtlList($cmemo_id);

	$cols = 80;
	$page = array();
	$page_len = 42;
	$line_len = 16;
	$col_width = 35;
	$line_idx = 0;

	$cd_num = count($cd_arr);
	$line_arr = array();

	$k = 0;
	for ($j=0;$j<$cd_num;$j++) {
		$desc_line = floor(strlen($cd_arr[$j]["cmemodtl_item_desc"])/$col_width);
		$line_arr[$k] = "";
		$line_arr[$k] .= str_pad(trim($cd_arr[$j]["cmemodtl_item_code"]), 12, " ", STR_PAD_RIGHT);
		$line_arr[$k] .= str_pad(substr($cd_arr[$j]["cmemodtl_item_desc"], 0, $col_width), 35, " ", STR_PAD_RIGHT);
		$line_arr[$k] .= str_pad($cd_arr[$j]["cmemodtl_qty"]+0, 10, " ", STR_PAD_LEFT);
		$line_arr[$k] .= str_pad(strtoupper($cd_arr[$j]["cmemodtl_unit"]), 2, " ", STR_PAD_LEFT);
		$line_arr[$k] .= str_pad(number_format($cd_arr[$j]["cmemodtl_cost"], 2, ".", ","), 9, " ", STR_PAD_LEFT);
		$line_arr[$k] .= str_pad(number_format($cd_arr[$j]["cmemodtl_qty"]*$cd_arr[$j]["cmemodtl_cost"], 2, ".", ","), 11, " ", STR_PAD_LEFT);
		if ($cd_arr[$j]["cmemodtl_taxable"]=="t") $line_arr[$k] .= "T";
		$line_arr[$k] .= "\n\r";
		$k++;
		for ($x=1;$x<$desc_line;$x++) {
			$line_arr[$k] = "";
			$line_arr[$k] .= str_repeat(" ", 12);
			$line_arr[$k] .= substr($cd_arr[$j]["cmemodtl_item_desc"], $col_width*$x, $col_width);
			$line_arr[$k] .= "\n\r";
			$k++;
		}
	}

	$total_line = $k;
	$page_num = ceil($total_line/$line_len);
	$rem_lines = $total_line % $line_len;
		
	for ($j=0;$j<$page_num;$j++) {
		$page[$j] = "";
		$page[$j] .= str_repeat(" ", 60);
		$page[$j] .= "Credit Memo\n"; // line #1
		$page[$j] .= "\n\r"; // line #2
		$page[$j] .= "\n\r"; // line #3
		$page[$j] .= "\n\r"; // line #4
		$page[$j] .= str_repeat(" ", 54);
		$page[$j] .= str_pad(date("m/d/y"), 11, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($cmemo_id, 8, " ", STR_PAD_LEFT);
		$page[$j] .= str_repeat(" ", 2);
		$page[$j] .= str_pad($j+1, 3, " ", STR_PAD_LEFT);
		$page[$j] .= "\n\r"; // line #5
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= substr($recs["cmemo_comnt"], 0, 50);
		$page[$j] .= "\n\r"; // line #6
		$page[$j] .= "\n\r"; // line #7
//		$page[$j] .= "\n\r"; // line #8
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($cust_arr["cust_name"], 30, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 11);
		$page[$j] .= str_pad($recs["cmemo_name"], 30, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n\r"; // line #9
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($cust_arr["cust_addr1"], 30, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 11);
		$page[$j] .= str_pad($recs["cmemo_addr1"], 30, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n\r"; // line #10

		if (empty($cust_arr["cust_addr2"]) && empty($recs["cmemo_addr2"])) {
			$blank_addr2 = 1;
		} else {
			$page[$j] .= str_repeat(" ", 1);
			$page[$j] .= str_pad($cust_arr["cust_addr2"], 30, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 11);
			$page[$j] .= str_pad($recs["cmemo_addr2"], 30, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n\r";
			$blank_addr2 = 0;
		}
		$blank_addr3 = 0;
		if (empty($cust_arr["cust_addr3"]) && empty($recs["sale_addr3"])) {
			$blank_addr3 = 1;
		} else {
			$page[$j] .= str_repeat(" ", 1);
			$page[$j] .= str_pad($cust_arr["cust_addr3"], 30, " ", STR_PAD_RIGHT);
			$page[$j] .= str_repeat(" ", 11);
			$page[$j] .= str_pad($recs["cmemo_addr3"], 30, " ", STR_PAD_RIGHT);
			$page[$j] .= "\n\r";
			$blank_addr3 = 0;
		}
		$page[$j] .= str_repeat(" ", 1);
		$csz = $cust_arr["cust_city"].", ".$cust_arr["cust_state"]." ".$cust_arr["cust_zip"];
		$page[$j] .= str_pad($csz, 30, " ", STR_PAD_RIGHT);
		$page[$j] .= str_repeat(" ", 11);
		$csz = $recs["cmemo_city"].", ".$recs["cmemo_state"]." ".$recs["cmemo_cmemo_zipountry"];
		$page[$j] .= str_pad($csz, 30, " ", STR_PAD_RIGHT);
		$page[$j] .= "\n\r";
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= $recs["cmemo_tel"]."\n\r";
		if ($blank_addr2==1) $page[$j] .= "\n\r";
		if ($blank_addr3==1) $page[$j] .= "\n\r";

		for ($i=0;$i<2;$i++) $page[$j] .= "\n\r";
		$page[$j] .= str_pad($recs["cmemo_user_code"], 8, " ", STR_PAD_RIGHT);
		$page[$j] .= str_pad(date("m/d/y", strtotime($recs["cmemo_date"])), 10, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($recs["cmemo_cust_code"], 8, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($recs["cmemo_po_num"], 8, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($recs["cmemo_slsrep"], 8, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad(substr($recs["cmemo_shipvia"],0,15), 15, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 1);
//		$page[$j] .= str_pad(date("m/d/y", strtotime($recs["sale_prom_date"])), 10, " ", STR_PAD_CENTER);
		$page[$j] .= str_repeat(" ", 10);
//		$page[$j] .= str_repeat(" ", 1);
		$page[$j] .= str_pad($cust_arr["cust_term"], 8, " ", STR_PAD_CENTER);
		$page[$j] .= "\n\r";
		$page[$j] .= "\n\r";
		$page[$j] .= "\n\r";

		for ($k=$j*$line_len;$k<($j+1)*$line_len;$k++) {
			$page[$j] .= $line_arr[$k];
		}

		if ($j+1 == $page_num) {
			if (($total_line % $line_len)==0) $rem_lines = 0;
			else $rem_lines = $line_len - ($total_line % $line_len);

			for ($k=0; $k<$rem_lines; $k++) $page[$j] .= "\n\r";
//			for ($i=0;$i<3;$i++) $page[$j] .= "\n\r";
			$dx = new Datex();
/*
			$day0 = date("Y-m-d");
			$day30 = $dx->getIsoDate($day0,30,"b");
			$day60 = $dx->getIsoDate($day0,60,"b");
			$day90 = $dx->getIsoDate($day0,90,"b");
			$created = $dx->toIsoDate($cust_arr["cust_created"]);

			$pick90over = $p->getPicksSumAged($recs["sale_cust_code"], "", $day90, "t", "f")+0;
			$pick90 = $p->getPicksSumAged($recs["sale_cust_code"], $day90, $day60, "t", "f")+0;
			$pick60 = $p->getPicksSumAged($recs["sale_cust_code"], $day60, $day30, "t", "f")+0;
			$pick30 = $p->getPicksSumAged($recs["sale_cust_code"], $day30, $day0, "t", "t")+0;
			$pick0 = $p->getPicksSumAged($recs["sale_cust_code"], $day0, "", "f", "t")+0;

			if (strtotime($created) <= strtotime($day0) && strtotime($created) > strtotime($day30) ) $pick30 += $cust_arr["cust_init_bal"];
			else if (strtotime($created) <= strtotime($day30) && strtotime($created) > strtotime($day60) ) $pick60 += $cust_arr["cust_init_bal"];
			if (strtotime($created) <= strtotime($day60) && strtotime($created) > strtotime($day90) ) $pick90 += $cust_arr["cust_init_bal"];
			if (strtotime($created) <= strtotime($day90)) $pick90over += $cust_arr["cust_init_bal"];

			$rcpt_sum = $r->getReceiptSumAged($recs["sale_cust_code"], "", "", "t", "t");
			$cmemo_sum = $cm->getCmemoSumAged($recs["sale_cust_code"], "", "", "t", "t");
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
			$page[$j] .= str_pad(number_format($recs["cmemo_amt"]*-1, 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n\r";
			$page[$j] .= str_repeat(" ", 65);
			$page[$j] .= str_pad(number_format($recs["cmemo_freight_amt"]*-1, 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n\r";
			$page[$j] .= str_repeat(" ", 65);
			$page[$j] .= str_pad(number_format($recs["cmemo_tax_amt"]*-1, 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n\r";
			$page[$j] .= "\n\r";
			$page[$j] .= str_repeat(" ", 65);
			$page[$j] .= str_pad(number_format(($recs["cmemo_amt"] + $recs["cmemo_freight_amt"] + $recs["cmemo_tax_amt"])*-1, 2, ".", ","), 15, " ", STR_PAD_LEFT);
			$page[$j] .= "\n\r";
			$page[$j] .= "\n\r";

		} else {
			for ($i=0;$i<6;$i++) $page[$j] .= "\n\r";
		}
		$page[$j] .= "\n\r";
		$page[$j] .= "\n\r";
	}
	$out = "";
	for ($i=0;$i<$page_num;$i++) {
		$out .= $page[$i];
	}

	list($usec,$sec)=explode(" ",microtime());
	$filename = "out/cmemo_".$sec.substr($usec, 2,6).".txt";

//echo "<pre>";
//echo $out;
//echo "</pre>";

	$redir = true;
	if ($redir == true) {
		$f=fopen ($filename,"w");
		fputs($f,$out,strlen($out));
		fclose($f);
		exec("lpr -P $port -o raw $filename");
		exec("rm $filename ");
	} else {
		echo "<pre>$out</pre>";
	}
}

$_SESSION[$cmemodtl_del]=NULL;

if ($cmd == "cmemo_sess_add") {
	if ($ht=="e") {
		$s = new Cmemo();
		$sls = $_SESSION[$cmemo_edit];
		$sls["cmemo_id"]			= $cmemo_id;
		$sls["cmemo_user_code"]	= $cmemo_user_code;
		$sls["cmemo_cust_code"]	= $cmemo_cust_code;
		$sls["cmemo_cust_code_old"]	= $cmemo_cust_code_old;
		$sls["cmemo_name"]			= $cmemo_name;
		$sls["cmemo_addr1"]		= $cmemo_addr1;
		$sls["cmemo_addr2"]		= $cmemo_addr2;
		$sls["cmemo_addr3"]		= $cmemo_addr3;
		$sls["cmemo_city"]			= $cmemo_city;
		$sls["cmemo_state"]		= $cmemo_state;
		$sls["cmemo_country"]		= $cmemo_country;
		$sls["cmemo_cmemo_zipountry"]			= $cmemo_zip;
		$sls["cmemo_tel"]			= $cmemo_tel;
		$sls["cmemo_amt"]			= $cmemo_amt;
		$sls["cmemo_date"]			= $cmemo_date ;
		$sls["cmemo_tax_amt"]		= $cmemo_tax_amt;
		$sls["cmemo_freight_amt"]	= $cmemo_freight_amt;
		$sls["cmemo_shipvia"]		= $cmemo_shipvia;
		$sls["cmemo_taxrate"]		= $cmemo_taxrate;
		$sls["cmemo_comnt"]		= $cmemo_comnt;
		$_SESSION[$cmemo_edit] = $sls;
		$loc = "Location: cmemo_details.php?ty=a&ht=e&cmemo_id=$cmemo_id";
	} else {
		$sls = $_SESSION[$cmemo_add];
		$sls["cmemo_id"]			= $cmemo_id;
		$sls["cmemo_user_code"]	= $cmemo_user_code;
		$sls["cmemo_cust_code"]	= $cmemo_cust_code;
		$sls["cmemo_cust_code_old"]	= $cmemo_cust_code_old;
		$sls["cmemo_cust_po"]		= $cmemo_cust_po;
		$sls["cmemo_name"]			= $cmemo_name;
		$sls["cmemo_addr1"]		= $cmemo_addr1;
		$sls["cmemo_addr2"]		= $cmemo_addr2;
		$sls["cmemo_addr3"]		= $cmemo_addr3;
		$sls["cmemo_city"]			= $cmemo_city;
		$sls["cmemo_state"]		= $cmemo_state;
		$sls["cmemo_country"]		= $cmemo_country;
		$sls["cmemo_cmemo_zipountry"]			= $cmemo_zip;
		$sls["cmemo_tel"]			= $cmemo_tel;
		$sls["cmemo_amt"]			= $cmemo_amt;
		$sls["cmemo_date"]			= $cmemo_date ;
		$sls["cmemo_tax_amt"]		= $cmemo_tax_amt;
		$sls["cmemo_freight_amt"]	= $cmemo_freight_amt;
		$sls["cmemo_shipvia"]		= $cmemo_shipvia;
		$sls["cmemo_taxrate"]		= $cmemo_taxrate;
		$sls["cmemo_comnt"]		= $cmemo_comnt;
		$_SESSION[$cmemo_add] = $sls;
		$loc = "Location: cmemo_details.php?ty=a&ht=a";
	}
	header($loc);

} else if ($cmd == "cmemo_detail_sess_add") {
	if ($ht == "e") {
		$cmemo = $_SESSION[$cmemo_edit];
		$cmemodtl = $_SESSION[$cmemodtl_edit];
		$dtl = array();
		for ($i=0;$i<count($cmemodtl);$i++) if (!empty($cmemodtl[$i])) array_push($dtl, $cmemodtl[$i]);
		$sls = array();
		$sls["cmemodtl_cmemo_id"]		= $cmemo_id;
		$sls["cmemodtl_item_code"]		= $cmemodtl_item_code;
		$sls["cmemodtl_item_desc"]		= $cmemodtl_item_desc;
		$sls["cmemodtl_qty"]			= $cmemodtl_qty;
		$sls["cmemodtl_cost"]			= $cmemodtl_cost;
		if ($cmemodtl_taxable != "t") $cmemodtl_taxable = "f";
		$sls["cmemodtl_taxable"]		= $cmemodtl_taxable;
		array_push($dtl, $sls);
		$_SESSION[$cmemodtl_edit] = $dtl;
		$cmemo["cmemo_amt"] += $cmemodtl_qty * $cmemodtl_cost;
		if ($cmemodtl_taxable == "t") $cmemo["cmemo_tax_amt"] += $cmemodtl_qty * $cmemodtl_cost * $cmemo["cmemo_taxrate"]/ 100;
		$_SESSION[$cmemo_edit] = $cmemo;
		$loc = "Location: cmemo_details.php?ty=a&ht=e&cmemo_id=$cmemo_id";
	} else {
		if (empty($cmemodtl_item_code)) {
			$errno=4;
			$errmsg="Item code should not be blank!";
			include("error.php");
			exit;
		} else {
			$cmemo = $_SESSION[$cmemo_add];
			$cmemodtl = $_SESSION[$cmemodtl_add];
			$dtl = array();
			for ($i=0;$i<count($cmemodtl);$i++) if (!empty($cmemodtl[$i])) array_push($dtl, $cmemodtl[$i]);
			$sls = array();
			$sls["cmemodtl_item_code"]		= $cmemodtl_item_code;
			$sls["cmemodtl_item_desc"]		= $cmemodtl_item_desc;
			$sls["cmemodtl_qty"]			= $cmemodtl_qty;
			$sls["cmemodtl_cost"]			= $cmemodtl_cost;
			if ($cmemodtl_taxable != "t") $cmemodtl_taxable = "f";
			$sls["cmemodtl_taxable"]		= $cmemodtl_taxable;
			array_push($dtl, $sls);
			$_SESSION[$cmemodtl_add] = $dtl;
			$cmemo["cmemo_amt"] += $cmemodtl_qty * $cmemodtl_cost;
			if ($cmemodtl_taxable == "t") $cmemo["cmemo_tax_amt"] += $cmemodtl_qty * $cmemodtl_cost * $cmemo["cmemo_taxrate"]/ 100;
			$_SESSION[$cmemo_edit] = $cmemo;
			$loc = "Location: cmemo_details.php?ty=a&ht=a";
		}
	}
	header($loc);

} else if ($cmd == "cmemo_add") {
	$s = new Cmemo();
	$d = new CmemoDtl();
	$t = new Items();

	$parr = $s->getCmemo($cmemo_id);
	$oldrec = $s->getTextFields("", "");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 

	$sid = $s->insertCmemo($arr);
	if (empty($cmemo_id)) $cmemo_id = $sid;
	$sdtls = $_SESSION[$cmemodtl_add];
	for ($i=0;$i<count($sdtls);$i++) {
		$sdtls[$i]["cmemodtl_cmemo_id"] = $cmemo_id;
		$lastid = $d->insertCmemoDtl($sdtls[$i]);
		$t->updateItemsQty($sdtls[$i]["cmemodtl_item_code"], $qty, 0);
		if ($lastid <= 0) {
			$errno=6;
			$errmsg="Credit Memo Detail Insertion Error";
			include("error.php");
			exit;
		}
	}


	// update customer balance
	$c = new Custs();
	$cust_arr = $c->getCusts($arr["cmemo_cust_code"]);
	if ($cust_arr) {
		$c->updateCustsBalance($arr["cmemo_cust_code"]);
	} else {
		$errno=7;
		$errmsg="Customer Not Found Error";
		include("error.php");
		exit;
	}

/*
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($cmemo_id, "r");

	$j->insertJrnlTrxExs($cmemo_id, $_SERVER["PHP_AUTH_USER"], $default["astr_acct"], "r", "d", $cmemo_amt, $cmemo_date); // ar for cmemo
	$j->insertJrnlTrxExs($cmemo_id, $_SERVER["PHP_AUTH_USER"], $default["ints_acct"], "r", "c", $cmemo_amt, $cmemo_date); // income for cmemo
*/
	$_SESSION[$cmemo_add]=NULL;
	$_SESSION[$cmemodtl_add]=NULL;
	$loc = "Location: cmemo.php?ty=e&cmemo_id=$cmemo_id";
	header($loc);

} else if ($cmd == "cmemo_edit") {

	$s = new Cmemo();
	$d = new CmemoDtl();
	$t = new Items();

	$sarr = $s->getCmemo($cmemo_id);
	if (!$sarr) {
		$errno=4;
		$errmsg="Credit Memo Number is not in database";
		include("error.php");
		exit;
	}
	$oldrec = $s->getTextFields("", "$cmemo_id");
	$r = new Requests();
	$arr = $r->getAlteredArray($oldrec, $_POST); 
	if (count($arr)>0) $s->updateCmemo($cmemo_id, $arr);

	$sdtls = $_SESSION[$cmemodtl_edit];
	$sdtl_num = count($sdtls);
	$amt = 0;
	$sd_arr = $d->getCmemoDtlHdrs($cmemo_id);

	if (!empty($sd_arr)) $sd_num = count($sd_arr);
	else $sd_num = 0;

	for ($i=0;$i<$sdtl_num;$i++) {
		$sdtls[$i]["cmemodtl_cmemo_id"] = $cmemo_id;
		$amt += $sdtls["cmemodtl_cost"];
		$found = 0;
		$qty = 0;
		for ($j=0;$j<$sd_num;$j++) {
			if ($sd_arr[$j]["cmemodtl_id"] == $sdtls[$i]["cmemodtl_id"]) {
				$qty = $sd_arr[$j]["cmemodtl_qty"];
				$found = 1;
				break;
			}
		}

		if ($found>0) {
			if (!$d->updateCmemoDtl($sdtls[$i]["cmemodtl_id"], $sdtls[$i])) {
				$errno=6;
				$errmsg="Credit Memo Detail Updating Error";
				include("error.php");
				exit;
			} else {
				$t->updateItemsQty($sd_arr["cmemodtl_item_code"], $qty, $sdtls[$i]["pickdtl_qty"]);
			}
		} else {
			unset($sdtls[$i]["cmemodtl_id"]);
			if (!empty($sdtls[$i]["cmemodtl_item_code"]) && !$d->insertCmemoDtl($sdtls[$i])) {
				$errno=7;
				$errmsg="Credit Memo Detail Inserting Error";
				include("error.php");
				exit;
			} else {
				$t->updateItemsQty($sd_arr["cmemodtl_item_code"], $sdtls[$i]["pickdtl_qty"],0);
			}
		}
	}

	for ($i=0;$i<$sd_num;$i++) {
		$found = 0;
		for ($j=0;$j<$sdtl_num;$j++) {
			if ($sd_arr[$i]["cmemodtl_id"] == $sdtls[$j]["cmemodtl_id"]) {
				$found = 1;
				break;
			}
		}
		if ($found == 0) {
			if (!$d->deleteCmemoDtl($sd_arr[$i]["cmemodtl_id"])) {
				$errno=8;
				$errmsg="Credit Memo Detail deleting Error";
				include("error.php");
				exit;
			} else {
				$t->updateItemsQty($sd_arr["cmemodtl_item_code"], 0, $sd_arr[$i]["pickdtl_qty"]);
			}
		} else if (!$sd_arr[$i]["cmemodtl_item_code"]) {
			if (!$d->deleteCmemoDtl($sd_arr[$i]["cmemodtl_id"])) {
				$errno=8;
				$errmsg="Credit Memo Detail deleting Error";
				include("error.php");
				exit;
			}
		}
	}

	// update customer balance
	$c = new Custs();
	$cust_arr = $c->getCusts($sarr["cmemo_cust_code"]);
	if ($cust_arr) {
		$c->updateCustsBalance($sarr["cmemo_cust_code"]);
	} else {
		$errno=7;
		$errmsg="Customer Not Found Error";
		include("error.php");
		exit;
	}
/*
	$j = new JrnlTrxs();
	$j->deleteJrnlTrxRefs($cmemo_id, "r");

	$j->insertJrnlTrxExs($cmemo_id, $_SERVER["PHP_AUTH_USER"], $default["astr_acct"], "r", "d", $cmemo_amt, $cmemo_date); // ar for cmemo
	$j->insertJrnlTrxExs($cmemo_id, $_SERVER["PHP_AUTH_USER"], $default["ints_acct"], "r", "c", $cmemo_amt, $cmemo_date); // income for cmemo
*/	
	$_SESSION[$cmemo_edit]=NULL;
	$_SESSION[$cmemodtl_edit]=NULL;
	$_SESSION[$cmemodtl_del] = 0;

	$loc = "Location: cmemo.php?ty=e&cmemo_id=$cmemo_id";
	header($loc);

} else if ($cmd == "cmemo_del") {
	$s = new Cmemo();
	$d = new CmemoDtl();
	$c = new Custs();
	$sarr = $s->getCmemo($cmemo_id);
	$s->deleteCmemo($cmemo_id);
	$d->deleteCmemoDtlSI($cmemo_id);
//	$j = new JrnlTrxs();
//	$j->deleteJrnlTrxRefs($cmemo_id, "r");

	// update customer balance
	$cust_arr = $c->getCusts($sarr["cmemo_cust_code"]);
	if ($cust_arr) {
		$c->updateCustsBalance($sarr["cmemo_cust_code"]);
	} else {
		$errno=7;
		$errmsg="Customer Not Found Error";
		include("error.php");
		exit;
	}

	$_SESSION[$cmemo_edit]=NULL;
	$_SESSION[$cmemodtl_edit]=NULL;
	$_SESSION[$cmemodtl_del] = 1;
	$loc = "Location: cmemo.php?ty=l";
	header($loc);

} else if ($cmd == "cmemo_detail_sess_del") {
	if ($ty=="e") {
		$arr = array();
		$cmemodtl = $_SESSION[$cmemodtl_edit];
		for ($i=0;$i<count($cmemodtl);$i++) {
			if ($i != $did) {
				array_push($arr, $cmemodtl[$i]);
			} else {
				$cmemo = $_SESSION[$cmemo_edit];
				$cmemo["cmemo_amt"] -= $cmemodtl[$i]["cmemodtl_qty"] * $cmemodtl[$i]["cmemodtl_cost"];
				if ($cmemodtl[$i]["cmemodtl_taxable"] == "t") $cmemo["cmemo_tax_amt"] -= $cmemodtl[$i]["cmemodtl_qty"] * $cmemodtl[$i]["cmemodtl_cost"] * $cmemo["cmemo_taxrate"]/ 100;
				$_SESSION[$cmemo_edit] = $cmemo;
			}
		}
		$_SESSION[$cmemodtl_edit] = $arr;
		$_SESSION[$cmemodtl_del] = 1;
		$loc = "Location: cmemo.php?ty=e&cmemo_id=$cmemo_id";
	} else {
		$arr = array();
		$cmemodtl = $_SESSION[$cmemodtl_add];
		for ($i=0;$i<count($cmemodtl);$i++) {
			if ($i != $did) {
				array_push($arr, $cmemodtl[$i]);
			} else {
				$cmemo = $_SESSION[$cmemo_add];
				$cmemo["cmemo_amt"] -= $cmemodtl[$i]["cmemodtl_qty"] * $cmemodtl[$i]["cmemodtl_cost"];
				if ($cmemodtl[$i]["cmemodtl_taxable"] == "t") $cmemo["cmemo_tax_amt"] -= $cmemodtl[$i]["cmemodtl_qty"] * $cmemodtl[$i]["cmemodtl_cost"] * $cmemo["cmemo_taxrate"]/ 100;
				$_SESSION[$cmemo_edit] = $cmemo;
			}
		}
		$_SESSION[$cmemodtl_add] = $arr;
		$loc = "Location: cmemo.php?ty=a";
	}
	header($loc);

} else if ($cmd == "cmemo_clear_sess_edit") {
	$_SESSION[$cmemo_edit]=NULL;
	$_SESSION[$cmemodtl_edit]=NULL;
	$loc = "Location: cmemo.php?ty=e&cmemo_id=$cmemo_id";
	header($loc);

} else if ($cmd == "cmemo_clear_sess_add") {
	$_SESSION[$cmemo_add]=NULL;
	$_SESSION[$cmemodtl_add]=NULL;
	$loc = "Location: cmemo.php?ty=a";
	header($loc);

} else if ($cmd == "cmemo_update_sess_add") {
	include_once("class/class.cmemo.php");
	if ($ty=="e") {
		$s = new Cmemo();
		$sls = $_SESSION[$cmemo_edit];
		$sls["cmemo_id"]			= $cmemo_id;
		$sls["cmemo_code"]			= $cmemo_code;
		$sls["cmemo_cust_po"]		= $cmemo_cust_po;
		$sls["cmemo_user_code"]	= $cmemo_user_code;
		$sls["cmemo_cust_code"]	= $cmemo_cust_code;
		$sls["cmemo_cust_code_old"]	= $cmemo_cust_code_old;
		$sls["cmemo_name"]			= $cmemo_name;
		$sls["cmemo_addr1"]		= $cmemo_addr1;
		$sls["cmemo_addr2"]		= $cmemo_addr2;
		$sls["cmemo_addr3"]		= $cmemo_addr3;
		$sls["cmemo_city"]			= $cmemo_city;
		$sls["cmemo_state"]		= $cmemo_state;
		$sls["cmemo_country"]		= $cmemo_country;
		$sls["cmemo_cmemo_zipountry"]			= $cmemo_zip;
		$sls["cmemo_tel"]			= $cmemo_tel;
		$sls["cmemo_amt"]			= $cmemo_amt;
		$sls["cmemo_date"]			= $cmemo_date ;
		$sls["cmemo_tax_amt"]		= $cmemo_tax_amt;
		$sls["cmemo_freight_amt"]	= $cmemo_freight_amt;
		$sls["cmemo_shipvia"]		= $cmemo_shipvia;
		$sls["cmemo_taxrate"]		= $cmemo_taxrate;
		$sls["cmemo_comnt"]		= $cmemo_comnt;
		$_SESSION[$cmemo_edit] = $sls;
		$loc = "Location: cmemo.php?ty=e&cmemo_id=$cmemo_id";
	} else {
		$s = new Cmemo();
		if ($arr = $s->getCmemo($cmemo_id)) {
			$loc = "Location error.php?".urlencode("errno=3&errmsg=Sales number is already in database");
		} else {
			$sls = $_SESSION[$cmemo_add];
			$sls["cmemo_id"]			= $cmemo_id;
			$sls["cmemo_code"]			= $cmemo_code;
			$sls["cmemo_cust_po"]		= $cmemo_cust_po;
			$sls["cmemo_user_code"]	= $cmemo_user_code;
			$sls["cmemo_cust_code"]	= $cmemo_cust_code;
			$sls["cmemo_cust_code_old"]	= $cmemo_cust_code_old;
			$sls["cmemo_name"]			= $cmemo_name;
			$sls["cmemo_addr1"]		= $cmemo_addr1;
			$sls["cmemo_addr2"]		= $cmemo_addr2;
			$sls["cmemo_addr3"]		= $cmemo_addr3;
			$sls["cmemo_city"]			= $cmemo_city;
			$sls["cmemo_state"]		= $cmemo_state;
			$sls["cmemo_country"]		= $cmemo_country;
			$sls["cmemo_cmemo_zipountry"]			= $cmemo_zip;
			$sls["cmemo_tel"]			= $cmemo_tel;
			$sls["cmemo_amt"]			= $cmemo_amt;
			$sls["cmemo_date"]			= $cmemo_date ;
			$sls["cmemo_tax_amt"]		= $cmemo_tax_amt;
			$sls["cmemo_freight_amt"]	= $cmemo_freight_amt;
			$sls["cmemo_shipvia"]		= $cmemo_shipvia;
			$sls["cmemo_taxrate"]		= $cmemo_taxrate;
			$sls["cmemo_comnt"]		= $cmemo_comnt;
			$_SESSION[$cmemo_add] = $sls;

			$loc = "Location: cmemo.php?ty=a";
		}
	}
	header($loc);
	exit;

} else if ($cmd == "cmemo_detail_sess_edit") {
	if ($ht == "e") {
		$arr = array();
		$cmemodtl = $_SESSION[$cmemodtl_edit];
		for ($i=0;$i<count($cmemodtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls["cmemodtl_cmemo_id"]		= $cmemo_id;
				$sls["cmemodtl_item_code"]		= $cmemodtl_item_code;
				$sls["cmemodtl_item_desc"]		= $cmemodtl_item_desc;
				$sls["cmemodtl_qty"]			= $cmemodtl_qty;
				$sls["cmemodtl_cost"]			= $cmemodtl_cost;
				if ($cmemodtl_taxable != "t") $cmemodtl_taxable = "f";
				$sls["cmemodtl_taxable"]		= $cmemodtl_taxable;
				array_push($arr, $sls);
			} else {
				array_push($arr, $cmemodtl[$i]);
			}
		}
		$_SESSION[$cmemodtl_edit] = $arr;

		$subtotal = 0;
		$taxtotal = 0;
		for ($i=0;$i<count($cmemodtl_edit);$i++) {
			$subtotal += $cmemodtl_edit[$i]["cmemodtl_qty"] * $cmemodtl_edit[$i]["cmemodtl_cost"];
			if ($cmemodtl_edit[$i]["cmemodtl_taxable"]=="t") $taxtotal += $cmemodtl_edit[$i]["cmemodtl_qty"] * $cmemodtl_edit[$i]["cmemodtl_cost"];
		}
		$_SESSION[$cmemo_edit]["cmemo_amt"] = $subtotal;
		$_SESSION[$cmemo_edit]["cmemo_tax_amt"] = $taxtotal * $cmemo_edit["cmemo_taxrate"] / 100;

		$loc = "Location: cmemo_details.php?ty=e&ht=e&cmemo_id=$cmemo_id&did=$did";

	} else {
		$arr = array();
		$cmemodtl = $_SESSION[$cmemodtl_add];
		for ($i=0;$i<count($cmemodtl);$i++) {
			if ($i == $did) {
				$sls = array();
				$sls["cmemodtl_item_code"]		= $cmemodtl_item_code;
				$sls["cmemodtl_item_desc"]		= $cmemodtl_item_desc;
				$sls["cmemodtl_qty"]			= $cmemodtl_qty;
				$sls["cmemodtl_cost"]			= $cmemodtl_cost;
				if ($cmemodtl_taxable != "t") $cmemodtl_taxable = "f";
				$sls["cmemodtl_taxable"]		= $cmemodtl_taxable;
				array_push($arr, $sls);
			} else {
				array_push($arr, $cmemodtl[$i]);
			}
		}
		$_SESSION[$cmemodtl_add] = $arr;

		$subtotal = 0;
		$taxtotal = 0;
		for ($i=0;$i<count($cmemodtl_edit);$i++) {
			$subtotal += $cmemodtl_add[$i]["cmemodtl_qty"] * $cmemodtl_add[$i]["cmemodtl_cost"];
			if ($cmemodtl_add[$i]["cmemodtl_taxable"]=="t") $taxtotal += $cmemodtl_add[$i]["cmemodtl_qty"] * $cmemodtl_add[$i]["cmemodtl_cost"];
		}
		$_SESSION[$cmemo_add]["cmemo_amt"] = $subtotal;
		$_SESSION[$cmemo_add]["cmemo_tax_amt"] = $taxtotal * $cmemo_add["cmemo_taxrate"] / 100;

		$loc = "Location: cmemo_details.php?ty=e&ht=a&did=$did";
	}
	header($loc);


} else if ($cmd == "cmemo_print") {
	$u = new Users();
	$user_arr = $u->getUsers($_SERVER["PHP_AUTH_USER"]);
	cmemoPrint($cmemo_id, $user_arr["user_printer"]);
	$loc = "Location: cmemo.php?ty=$ty&pg=$pg&cn=$cn&ft=$ft&cmemo_id=$cmemo_id";
	header($loc);

}
?>