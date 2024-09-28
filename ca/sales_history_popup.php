<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.sales.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.customers.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.taxrates.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.userauths.php");
	include_once("class/class.receipt.php");
	include_once("class/class.purchase.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/register_globals.php");
//------------------------------------------------------------------------

	foreach (array('_GET', '_POST', '_COOKIE', '_SERVER') as $_SG) {
		foreach ($$_SG as $_SGK => $_SGV) {
			$$_SGK = $_SGV;
		}
	}

	$f = new FormUtil();
	$ua = new UserAuths();
	$d = new Datex();
	$c = new Sales();
	$v = new Custs();


?>
<html>
<head>
<title>Sales</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="cleanair.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
	$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_list");
	if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
	if ($ua_arr["userauth_allow"]!="t") {
		include("permission.php");
		exit;
	}


	$selbox = array(0=>array("value"=>"code", "name"=>"Sales Number"),
					1=>array("value"=>"cust", "name"=>"Customer Code"),
					2=>array("value"=>"date", "name"=>"Sales Date"),
					3=>array("value"=>"tel", "name"=>"Telephone")
			  );

	if ($cmd=="filter") {
		$_SESSION["sales_filter"]["ft"] = $ft;
		$_SESSION["sales_filter"]["rv"] = $rv;
		$_SESSION["sales_filter"]["pg"] = $pg;
	} else {
		if (empty($ft)) $ft = $_SESSION["sales_filter"]["ft"];
		if (empty($rv)) $rv = $_SESSION["sales_filter"]["rv"];
		if (empty($pg)) $pg = $_SESSION["sales_filter"]["pg"];
	}

	if (empty($pg)) $pg = 1;
	$cust_code = $_GET["cust_code"];
?>

	<table width="100%" border="0" cellspacing="1" cellpadding="0">
	<form name="form1" method="get" action="">
	  <tr align="right"> 
		<td colspan="8"><strong>List Sales</strong></td>
      </tr>
      <tr>
        <td colspan="4" align="left"><font size="3" face="Helvetica"><b>
		  Customer: <?= strtoupper($cust_code) ?></b></font>
		</td>
		<td colspan="4" align="right"><font size="2">
			<?= $f->fillHidden("pg",$pg) ?>
			<?= $f->fillHidden("cmd","") ?>
			<?= $f->fillHidden("cust_code",$cust_code) ?>
		</td>
      </tr>
	</form>
      <tr align="right"> 
        <td colspan="8">
		  <table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <th bgcolor="gray" width="10%"><font color="white">Sale_no</font></th>
              <th colspan="2" bgcolor="gray"><font color="white">Customer</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Date</font></th>
              <th bgcolor="gray" width="10%"><font color="white">SubTotal</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Tax</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Freight</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Total</font></th>
              <th bgcolor="gray" width="2%"><font color="white">St</font></th>
              <th bgcolor="gray" width="2%"><font color="white">Pr</font></th>
            </tr>
<?php
	$limit = 100;
	if ($cn == "date") {
		$old_ft = $ft;
		$ft = $d->toIsoDate($ft);
	}

	$cust_arr = $v->getCusts($cust_code);
	if ($cust_arr && !empty($cust_arr["cust_code"])) {
		$numrows = $c->getSalesRows("cust", $cust_code);
	//	$recs = $c->getSalesListEx($cn, $ft, $rv, $pg, $limit);
		$recs = $c->getSalesList("cust", $cust_code, $rv, $pg, $limit);
	} else {
		$numrows = 0;
		$recs = array();
	}
	if ($cn == "date") $ft = $old_ft;

	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

	$linenum = 1;
	$pd = new PickDtls();
	$sd = new SaleDtls();
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($ln == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
		$arr = $v->getCusts($recs[$i]["sale_cust_code"]);
		$line_total = $recs[$i]["sale_freight_amt"]+$recs[$i]["sale_tax_amt"]+$recs[$i]["sale_amt"];

		$pick_qty = $pd->getPickDtlsSlsHdrSum($recs[$i]["sale_id"]);
		$sale_qty = $sd->getSaleDtlsHdrSum($recs[$i]["sale_id"]);
//echo "$pick_qty / $sale_qty <br>";
		if ($pick_qty > $sale_qty || $sale_qty == 0) $status = "E";
		else if ($pick_qty == $sale_qty && $pick_qty>0) $status = "F";
		else if ($pick_qty < $sale_qty && $pick_qty>0) $status = "P";
		else $status = "N";
?>
              <td align="center" width="10%"> 
                <?= $recs[$i]["sale_id"] ?>
              </td>
              <td align="left" width="10%"> 
                <?= $recs[$i]["sale_cust_code"] ?>
              </td>
              <td align="left" width="30%"> 
                <?= $arr["cust_name"] ?>
              </td>
              <td align="center" width="10%"> 
                <?= $recs[$i]["sale_date"] ?>
              </td>
              <td  width="10%" align="right"> 
                <?= sprintf("%0.2f", $recs[$i]["sale_amt"]) ?>
              </td>
              <td  width="10%" align="right"> 
                <?= sprintf("%0.2f", $recs[$i]["sale_tax_amt"]) ?>
              </td>
              <td  width="10%" align="right"> 
                <?= sprintf("%0.2f", $recs[$i]["sale_freight_amt"]) ?>
              </td>
              <td  width="10%" align="right"> 
                <?= number_format($line_total, 2, ".", ",") ?>
              </td>
              <td  width="2%" align="center"> 
			    <?= strtoupper($status) ?>
              </td>
              <td  width="2%" align="center"> 
				<?= $recs[$i]["sale_print"] ?>
              </td>
            </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="7">
				<table width="100%" bgcolor="#EEEEEE" border="0" cellspacing="1" cellpadding="0">
<?php
			$d = new SaleDtls();
			$recd = $d->getSaleDtlsList($recs[$i]["sale_id"]);
			for ($j=0;$j<count($recd);$j++) {
				if (!empty($recd[$j])) {
					$pickdtl_qty = $pd->getPickDtlsSlsSum($recd[$j]["slsdtl_id"]);
					$up_qty = $recd[$j]["slsdtl_qty"]-$recd[$j]["slsdtl_qty_cancel"]-$pickdtl_qty;
?>
						  <tr>
                            <td width="10%" bgcolor="white"> 
                              <?= $recd[$j]["slsdtl_item_code"] ?>
                            </td>
                            <td width="60%" bgcolor="white"> 
                              <?= $recd[$j]["slsdtl_item_desc"] ?>
                            </td>
                            <td width="20%" align="right" bgcolor="white"> 
							  (<?= $pickdtl_qty+0 ?>/<?= $recd[$j]["slsdtl_qty"]+0 ?>,<?= $up_qty+0 ?>)x<?= sprintf("%0.2f", $recd[$j]["slsdtl_cost"]) ?>

                            </td>
                            <td width="10%" align="right" bgcolor="white"> 
                              <?= sprintf("%0.2f", $recd[$j]["slsdtl_cost"]*$recd[$j]["slsdtl_qty"]) ?>
                            </td>
                          </tr>
<?php
				}
			}	
?>
			  </table>
			</td>
			<td colspan="2"></td>
		  </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="9" align="center"><font color="red">No Data!</font></td></tr>
<?php
	}
?>
	  </table>
	</td>
  </tr>
  <tr align="center"> 
    <td colspan="8">
	  <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&cn=$cn&ft=$ft&cust_code=$cust_code" ?>">&lt;&lt;First</a>
        &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&cn=$cn&ft=$ft&cust_code=$cust_code" ?>">&lt;Prev</a> &nbsp; 
	  <font color="gray"><?= "[$pg / $totalpage]" ?></font>
      &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&cn=$cn&ft=$ft&cust_code=$cust_code" ?>">Next&gt;</a>
      &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&cn=$cn&ft=$ft&cust_code=$cust_code" ?>">Last&gt;&gt;</a>
	</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
