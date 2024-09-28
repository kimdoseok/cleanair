<?php
	include_once("class/class.formutils.php");
	include_once("class/class.purchase.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.purdtls.php");
	include_once("class/class.items.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------
	$d = new Datex();
	$f = new FormUtil();
	$ph = new Purchases();
	$pd = new PurDtls();
	$t = new Items();

	if ($hdr = $ph->getPurchase($purch_id)) foreach($hdr as $k => $v) $$k = $v; 
	$recs = $pd->getPurDtlsList($purch_id);
?>
<html>
<head>
<title>Purchase Order</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function OnPrint() {
	window.print();
}

//-->
</SCRIPT>
<style type="text/css">
<!--
.between {  border-color: white black; border-style: solid; border-top-width: auto; border-right-width: auto; border-bottom-width: auto; border-left-width: auto}
-->
</style>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  onLoad="OnPrint()"">
<font face="Helvetica">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="white">
    <tr align="center"> 
      <td bgcolor="white" colspan="7"><h3>Purchase Order</h3></td>
    </tr>
    <tr align="right"> 
      <td colspan="1" valign="top">
	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
            <td width="50%">
			  <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="black">
                <tr> 
                  <td width="30%" align="right" bgcolor="silver">Vendor#</td>
                  <td bgcolor="white" width="70%"> 
                    <?= $purch_vend_code ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Ship Name:</td>
                  <td bgcolor="white"> 
                    <?= $purch_name ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Address</td>
                  <td bgcolor="white"> 
                    <?= $purch_addr1 ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $purch_addr2 ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $purch_addr3 ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                  <td bgcolor="white"> 
                    <?= $purch_city ?>
                    <?= $purch_state ?>
                    <?= $purch_zip ?>
                    <?= $purch_country ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Telephone</td>
                  <td bgcolor="white"> 
                    <?= $purch_tel ?>
                  </td>
                </tr>
              </table>
			</td>
            <td width="50%" valign="top">
			  <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="black">
                <tr> 
                  <td width="30%" align="right" bgcolor="silver">PO #&nbsp;</td>
                  <td width="70%" bgcolor="white">
                    <?= $purch_id ?>
                  </td>
                </tr>
				<tr> 
                  <td align="right" bgcolor="silver">Ref. Sale #</td>
                  <td bgcolor="white"> 
<?php
	if (empty($purch_sale_id)) echo "&nbsp;";
	else echo "<a href='sales.php?ft=$purch_sale_id&cn=code&pg=1'>$purch_sale_id</a>";
?>
                  </td>
                </tr>
				<tr> 
                  <td align="right" bgcolor="silver">Date</td>
                  <td bgcolor="white"> 
                    <?= $purch_date ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">User #</td>
                  <td bgcolor="white"> 
                    <?= $purch_user_code ?>
				  </td>
                </tr>
				<tr> 
                  <td align="right" bgcolor="silver">Ex. Date&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $purch_prom_date ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Balance</td>
                  <td bgcolor="white"> 
                    <?= number_format($vend_balance,2,".",",") ?>
                  </td>
                </tr>
				<tr> 
                  <td align="right" bgcolor="silver">Custom PO</td>
                  <td bgcolor="white"> 
                    <?= ($purch_custom_po=="t")?"X":"&nbsp;" ?>
                  </td>
                </tr>
              </table>
			</td>
          </tr>
        </table>
	  </td>
    </tr>
    <tr align="right"> 
      <td colspan="7">
		<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="black">
          <tr> 
            <th bgcolor="gray" width="5%"><font color="white">#</font></th>
            <th colspan="2" bgcolor="gray"><font color="white">Item</font></th>
            <th bgcolor="gray" width="10%"><font color="white">Cost</font></th>
            <th bgcolor="gray" width="10%"><font color="white">Qty</font></th>
            <th bgcolor="gray" width="5%"><font color="white">Unit</font></th>
            <th bgcolor="gray" width="15%"><font color="white">Amount</font></th>
          </tr>
<?php

	$d = new PurDtls();
	$recs = $d->getPurDtlsList($purch_id);
	$y = new Items();
	$subtotal = 0;

	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"];

?>
            <td width="5%" align="center"> 
              <?= $i+1 ?>
            </td>
            <td width="10%"> 
              <?= $recs[$i]["purdtl_item_code"] ?>
            </td>
            <td width="45%"> 
              <?= $recs[$i]["purdtl_item_desc"] ?>
            </td>
            <td width="10%" align="right"> 
              <?= sprintf("%0.2f", $recs[$i]["purdtl_cost"]) ?>
            </td>
            <td width="10%" align="right"> 
              <?= $recs[$i]["purdtl_qty"]+0 ?>
            </td>
            <td width="5%" align="left"> 
              <?= $recs[$i]["purdtl_unit"] ?>
            </td>
            <td width="15%" align="right"> 
              <?= sprintf("%0.2f", $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"]) ?>
            </td>
          </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
		  <tr bgcolor="#EEEEEE">
            <td colspan="7" align="center"> 
              <b><?= $label[$lang]["Empty_1"] ?>!</b>
            </td>
          </tr>
<?php
	}
?>
        </table>
	  </td>
    </tr>
    <tr>
	  <td colspan="7">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr> 
			<td width="50%" valign="top">
			  <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="black">
			    <tr> 
				  <td width="25%" bgcolor="silver" valign="top" align="right">Ship Via</td>
				  <td width="75%" bgcolor="white">
		            <?= $purch_shipvia ?>
			      </td>
				  </tr>
				  <tr> 
					<td bgcolor="silver" valign="top" align="right">Tax Rate</td>
					<td bgcolor="white">
					  <?= $purch_taxrate+0 ?>%
					</td>
				  </tr>
				  <tr> 
					<td bgcolor="silver" valign="top" align="right">Comment</td>
					<td bgcolor="white" rowspan="3" valign="top">
					  <?= $purch_comnt ?>
					</td>
				  </tr>
				  <tr> 
					<td bgcolor="silver" valign="top" align="right">&nbsp;</td>
				  </tr>
				  <tr> 
					<td bgcolor="silver" valign="top" align="right">&nbsp;</td>
				  </tr>
				</table>
			  </td>
			  <td width="50%">
			    <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="black">
				  <tr align="right"> 
					<td bgcolor="silver" valign="top" align="right" width="25%">Sub Total</td>
					<td width="75%" bgcolor="white">
					  <?= sprintf("%0.2f", $subtotal) ?>
					</td>
				  </tr>
				  <tr align="right"> 
					<td bgcolor="silver" valign="top" align="right" width="50%">Taxable Total</td>
					<td width="50%" bgcolor="white">
					  <?= sprintf("%0.2f", $taxtotal) ?>
					</td>
				  </tr>
				  <tr> 
				    <td bgcolor="silver" valign="top" align="right">Freight</td>
					<td align="right" bgcolor="white">
					  <?= sprintf("%0.2f", $purch_freight_amt) ?>
					</td>
				  </tr>
				  <tr align="right">
					<td bgcolor="silver" valign="top" align="right">Tax</td>
					<td bgcolor="white">
					  <?= sprintf("%0.2f", $purch_tax_amt) ?>
					</td>
				  </tr>
				  <tr align="right">
					<td bgcolor="silver" valign="top" align="right">Total Amount</td>
					<td bgcolor="white">
					  <?= sprintf("%0.2f", $purch_tax_amt+$purch_freight_amt+$subtotal) ?>
					</td>
				  </tr>
				</table>
			  </td>
			</tr>
		  </table>
		</td>
      </tr>
  </table>
</font>
</body>
</html>
