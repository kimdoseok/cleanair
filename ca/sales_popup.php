<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.customers.php");
	include_once("class/class.sales.php");
	include_once("class/class.taxrates.php");
	include_once("class/class.datex.php");
	include_once("class/class.receipt.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.saledtls.php");

	$f = new FormUtil();
	$d = new Datex();
	$c = new Custs();

	$selbox = array(0=>array("value"=>"code", "name"=>"Sales Number"),
					1=>array("value"=>"cust", "name"=>"Saleomer Code"),
					2=>array("value"=>"date", "name"=>"Sales Date"),
					3=>array("value"=>"tel", "name"=>"Telephone")
			  );

//------------------------------------------------------------------------
	
include_once("class/map.label.php");
//------------------------------------------------------------------------
include_once("class/map.lang.php");

$vars = array("ty","ft");
foreach ($vars as $var) {
  $$var = "";
}
$vars = array("pg","cn","rv","pend_qty","ln");
foreach ($vars as $var) {
  $$var = 0;
}

include_once("class/register_globals.php");
//-----------------------------------------------------------------------


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Popup Sales</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">

<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">

<SCRIPT LANGUAGE="JavaScript">

	function setFilter() {
		var f = document.forms[0];
		f.method = "GET" ;
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}

function updateForm(page) {
	document.forms[0].pg.value = page;
	document.forms[0].method="POST";
	document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
	document.forms[0].submit();
}

function setCode(value) {
	var f = document.forms[0];

	var s = self.opener.document.forms[0];
	self.opener.document.forms[0].<?= $objname ?>.value = value;
	self.opener.document.forms[0].<?= $objname ?>.focus();
	self.close();
}

function setClose() {
	self.opener.document.form1.<?= $objname ?>.focus();
	self.close();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Sales</strong></td>
                    </tr>
					<form>
					<tr align="right"> 
                      <td colspan="8">
								<?= $f->fillTextBox("ft",$ft,30) ?>
								<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
								<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> >Reverse
								<input type="button" name="filter" value="Filter" onClick="javascript:setFilter()">
								<?= $f->fillHidden("pg",$pg) ?>
								<?= $f->fillHidden("cmd","") ?>
					  </td>
                    </tr>
					</form>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white">Sale#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Saleomer</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Date</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">SubTotal</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Tax</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Freight</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Total</font></th>
                            <th bgcolor="gray" width="2%"><font color="white">St</font></th>

						  </tr>
<?php
	$limit = 20;
	$s = new Sales();
	$numrows = $s->getSalesRows($cn,$ft);
	$recs = $s->getSalesList($cn, $ft, $rv, $pg, $limit);

	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);

	$pd = new PickDtls();
	$sd = new SaleDtls();

	$tday = date("m/d/Y");
	for ($i=0; $i<$numrecs; $i++) {
		if ($ln == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
		$arr = $c->getCusts($recs[$i]["sale_cust_code"]);
		$line_total = $recs[$i]["sale_freight_amt"]+$recs[$i]["sale_tax_amt"]+$recs[$i]["sale_amt"];

		$pick_qty = $pd->getPickDtlsSlsHdrSum($recs[$i]["sale_id"]);
		$sale_qty = $sd->getSaleDtlsHdrSum($recs[$i]["sale_id"]);

		$sd_arr = $sd->getSaleDtlsListPicksEx($recs[$i]["sale_id"]);
		if ($sd_arr) {
			$num = count($sd_arr)-1;
			$delivery_date = $sd_arr[$num]["pick_date"];
		} else {
			$delivery_date = "<font color='green'>".$recs[$i]["sale_date"]."</font>";
		}

//echo "$pick_qty / $sale_qty <br>";
		if ($pick_qty > $sale_qty || ($sale_qty == 0 && $pend_qty==0)) $status = "<font color='red'>E</font>";
		else if ($pick_qty == $sale_qty && $pick_qty>0) $status = "F";
		else if ($pick_qty < $sale_qty && $pick_qty>0) $status = "P";
		else if ($pend_qty>0 && $sale_qty==0) $status = "B";
		else $status = "N";
?>
                            <td align="center" width="10%"> 
                              <a href="javascript:setCode('<?= addslashes($recs[$i]["sale_id"]) ?>');"><?= $recs[$i]["sale_id"] ?></a>
                            </td>
                            <td align="left" width="10%"> 
                              <?= $recs[$i]["sale_cust_code"] ?>
                            </td>
                            <td align="left" width="30%"> 
                              <?= $arr["cust_name"] ?>
                            </td>
                            <td align="center" width="10%"> 
                              <?= $delivery_date ?>
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
<?php
								echo $status;
?>
                            </td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red">No Data!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=1&cn=$cn&ft=$ft" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$prevpage&cn=$cn&ft=$ft" ?>">&lt;Prev</a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$nextpage&cn=$cn&ft=$ft" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$totalpage&cn=$cn&ft=$ft" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
