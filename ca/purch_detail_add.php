<?php
	include_once("class/class.formutils.php");
	include_once("class/class.styles.php");
	include_once("class/class.styldtls.php");
	include_once("class/class.vendors.php");
	include_once("class/class.items.php");

	$t = new Items();
	$f = new FormUtil();
	$s = new Styles();

	$last = unserialize(base64_decode($_SESSION["Last"]));
	if (!empty($last)) {
		foreach($last as $k => $v) $$k = $v;
		unset($last);
		session_unregister("last");
	}

	if ($ht == "e") $recs = unserialize(base64_decode($_SESSION[purchs_edit]));
	else $recs = unserialize(base64_decode($_SESSION[purchs_add]));
	if (!empty($recs)) foreach($recs as $k => $v) $$k = $v; 


	if (!empty($po_no)) $purdtl_po_no = $po_no;
	$selbox = array();
	$temp = array("value"=>"", "name"=>"");
	if (!empty($purdtl_po_no)) {
		$y = new StylDtls();
		$yarr = $y->getStylDtlsPO($purdtl_po_no);
		for ($i=0;$i<count($yarr);$i++) {
			$temp["value"] = $yarr[$i][styldtl_item_code];
//			$temp["Name"] = $yarr[$i][styldtl_item_desc];
			$temp["Name"] = $yarr[$i][styldtl_item_code]." : ".$temp["value"];
			array_push($selbox, $temp);
		}
	}

	if (empty($purdtl_item_code))	$purdtl_item_code	= $yarr[0][styldtl_item_code];
	if (empty($purdtl_cost))		$purdtl_cost		= $yarr[0][styldtl_rmb_per_meter];
	if (empty($purdtl_unit))		$purdtl_unit		= $yarr[0][styldtl_unit];
	if (empty($purdtl_qty))		$purdtl_qty		= $yarr[0][styldtl_meter_per_pair]*$yarr[0][styl_qty_work];

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		f.action = "ap_proc.php";
		f.cmd.value = "purch_detail_sess_add";
		f.method = "post";
		f.submit();
	}
	
	function ClearPurch() {
		var f = document.forms[0];
		f.action = "ap_proc.php";
		f.cmd.value = "purch_clear_sess_add";
		f.method = "post";
		f.submit();
	}

	function updateForm() {
		var f = document.forms[0];
		f.ht.value = "<?= $ht ?>";
		f.ty.value = "<?= $ty ?>";
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.method = "get";
		f.submit();
	}

	var acctBrowse;
	function openAcctBrowse(objname)  {
		if (!acctBrowse || acctBrowse.closed) {
			acctBrowse = window.open("accounts_popup.php?objname="+objname, "acctBrowseWin", "height=450, width=350");
		} else {
			acctBrowse.focus();
		}
		acctBrowse.moveTo(100,100);
	}

	var poBrowse;
	function openPOBrowse(objname)  {
		if (!poBrowse || poBrowse.closed) {
			poBrowse = window.open("styles_po_popup.php?objname="+objname, "poBrowseWin", "height=450, width=350");
		} else {
			poBrowse.focus();
		}
		poBrowse.moveTo(100,100);
	}

	function selectItem(num) {
		var code = new Array();
		var qty = new Array();
		var unit = new Array();
<?php
	for ($i=0;$i<count($yarr);$i++) {
		$qty_styl = 0;
		echo "		code[".$i."] = '".$yarr[$i][styldtl_item_code]."' ;\n" ;
		echo "		qty[".$i."] = '".$yarr[$i][styldtl_meter_per_pair]*$darr[$i][styl_qty_work]."' ;\n" ;
		echo "		unit[".$i."] = '".$yarr[$i][styldtl_unit]."' ;\n" ;
	}
?>
		var f = document.forms[0];
		var num = f.purdtl_item_code.selectedIndex;
		f.purdtl_qty.value = qty[num];
		f.purdtl_unit.value = unit[num];
	}
//-->
</SCRIPT>

						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="ap_proc.php">
							<INPUT TYPE="hidden" name="cmd" value="">
							<?= $f->fillHidden("ht", $ht) ?>
							<?= $f->fillHidden("ty", $ty) ?>
							<?= $f->fillHidden("purch_id", $purch_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][New_Purchase_Detail] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=a&ht=" ?><?= ($ht=="e")?"e":"a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo "purchases.php?ty=$ht&purch_id=$purch_id" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["PO_no"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBoxRefresh("purdtl_po_no", $purdtl_po_no, 20, 32, "inbox") ?>
					<A HREF="javascript:updateForm()"><font size="2"><?= $label[$lang][Search] ?></font></A>

<!--					<A HREF="javascript:openPOBrowse('purdtl_po_no')"><font size="2">Lookup</font></A> -->
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["item_code"] ?>:&nbsp;</td>
                  <td width="308"> 
					<?= $f->fillSelectBox($selbox, "purdtl_item_code", "value", "name", $purdtl_item_code, "inbox", " onChange='selectItem()' "); ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang][Account_no] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("purdtl_acct_code", $purdtl_acct_code, 20, 32, "inbox") ?>
					<A HREF="javascript:openAcctBrowse('purdtl_acct_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Quantity"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("purdtl_qty", $purdtl_qty+0, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Cost"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("purdtl_cost", $purdtl_cost+0, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Unit:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("purdtl_unit", $purdtl_unit, 20, 32, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
									 <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="AddDtl()"> 
                              <input type="button" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>" onClick="ClearPurch()"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>
                  </table>

<!-- // View ////////////////////////////////////////////////////////////////// -->
<?php
	if ($ht == "e") $recs = unserialize(base64_decode($_SESSION["purdtls_edit"]));
	else $recs = unserialize(base64_decode($_SESSION["purdtls_add"]));

	if (!empty($purch_vend_code)) {
		$v = new Vends();
		$varr = $v->getVends($purch_vend_code);
	}

?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><hr></td>
					</tr>
					<tr align="right"> 
                      <td colspan="8"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
			
      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="62%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang][Vendor] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $purch_vend_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" valign="top">&nbsp;</td>
                  <td valign="top"> 
<?php
	$line1 = $varr["vend_name"];
	if (!empty($line1)) $line1 .= "<br>";
	echo $line1;
	$line2 = $varr["vend_addr1"]." ".$varr["vend_addr2"]." ".$varr["vend_addr3"];
	if (!empty($line2)) $line2 .= "<br>";
	echo $line2;
	$line3 = $varr["vend_city"]." ".$varr["vend_state"]." ".$varr["vend_zip"]." ".$varr["vend_country"];
	echo $line3;
?>
                  </td>
                </tr>
              </table></td>
            <td width="1%">&nbsp;</td>
            <td width="37%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang][Vendor_Inv_no] ?></td>
                  <td> 
                    <?= $purch_vend_inv ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>&nbsp;</td>
                  <td> 
                    <?= $purch_date ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                  <td> 
                    <?= $purch_user_code ?>
                  </td>
                </tr>
              </table></td>
          </tr>
        </table> </td>
						  </tr>
						</table>
    </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["PO_no"] ?></font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Item_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Cost"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["qty"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                          </tr>
<?php
	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i]["purdtl_item_code"]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"];
?>
                            <td width="6%" align="center"> 
                              <a href="purch_details.php?ty=e&ht=<?= $ht ?>&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="10%"> 
                              <?= $recs[$i][purdtl_po_no] ?>
                            </td>
                            <td width="10%"> 
                              <?= $recs[$i]["purdtl_item_code"] ?>
                            </td>
                            <td width="30%"> 
                              <?= $arr["item_desc"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= $recs[$i]["purdtl_cost"]+0 ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= $recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                          </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
                          </tr>
<?php
	}
?>
									<tr>
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr width="20%"> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%" width="80%">
                <?= $purch_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $purch_comnt ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%"><?= $label[$lang]["Sub_Total"] ?></td>
              <td width="50%">
                <?= $subtotal+0 ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td align="right">
                <?= $purch_freight_amt+0 ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= $purch_tax_amt+0 ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= $purch_tax_amt+$purch_freight_amt+$subtotal ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
				  <br>

