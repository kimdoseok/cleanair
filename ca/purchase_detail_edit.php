<?php
	include_once("class/class.vendors.php");
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
  include_once("class/register_globals.php");

	$f = new FormUtil();

	if ($ht == "e") $slsdtl = $_SESSION["purdtls_edit"];
	else $slsdtl = $_SESSION["purdtls_add"];

	if ($slsdtl[$did]) foreach ($slsdtl[$did] as $k => $v) $$k = $v;
	if ($ht=="e") $sls = $_SESSION["purchases_edit"];
	else $sls = $_SESSION["purchases_add"];
	if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	if (!empty($purch_vend_code)) {
		$v = new Vends();
		$varr = $v->getVends($purch_vend_code);
	}

	if ($item_code) $purdtl_item_code = $item_code;
	if (!empty($purdtl_item_code) && $ctrl == 1) {
		$t = new Items();
		if ($tarr = $t->getItems($purdtl_item_code)) {
			$purdtl_item_code = strtoupper($purdtl_item_code);
			$old_purdtl_item_code = $purdtl_item_code;
			$purdtl_item_desc = $tarr["item_desc"];
			$purdtl_qty = 1;
			$purdtl_cost = $tarr["item_msrp"];
			$not_found_item = 0;
		} else {
			$not_found_item = 1;
			$purdtl_item_code	= $old_purdtl_item_code;
		}
	}

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function itemLookUp() {
		var f = document.forms[0];
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.cmd.value = "ilu";
		f.method = "get";
		f.submit();
	}

	function EditDtl() {
		var f = document.forms[0];
		f.action = "ap_proc.php";
		f.cmd.value = "purch_detail_sess_edit";
		f.method = "post";
		f.submit();
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_item == 1) echo "	openItemBrowseFilter('purch_item_code', '$item_code');";
?>
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="ap_proc.php" enctype="multipart/form-data">
						<INPUT TYPE="hidden" name="cmd" value="">
						<INPUT TYPE="hidden" name="purdtl_purch_id" value="<?= $purdtl_purch_id ?>">
						<INPUT TYPE="hidden" name="purdtl_id" value="<?= $purdtl_id ?>">
						<?= $f->fillHidden("ht", $ht) ?>						
						<?= $f->fillHidden("ty", $ty) ?>						
						<?= $f->fillHidden("did", $did) ?>
						<?= $f->fillHidden("ctrl", "") ?>
						<?= $f->fillHidden("purch_id", $purch_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Purchase Detail</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo "purchase.php?ty=$ht&purch_id=$purch_id" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Item#:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("purdtl_item_code", $purdtl_item_code, 20, 32, "inbox", " onChange='refreshItem(\"1\")'") ?>
					<?= $f->fillHidden("old_purdtl_item_code", $old_purdtl_item_code) ?>
					<A HREF="javascript:openItemBrowse('purdtl_item_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
<?php
	if ($not_found_item != 1) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	setCursor('purdtl_item_code');
//-->
</SCRIPT>
<?php
	}
?>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("purdtl_item_desc", stripslashes($purdtl_item_desc), 32, 250, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Quantity"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("purdtl_qty", $purdtl_qty+0, 20, 32, "inbox", " onChange='calcAmt() '") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Unit</td>
                  <td> 
                    <?= $f->fillTextBoxRO("purdtl_unit", $purdtl_unit, 4, 4, "inbox", "") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">PO Price:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("purdtl_cost", sprintf("%0.2f", $purdtl_cost), 20, 32, "inbox", " onChange='calcAmt() '") ?>
					<A HREF="javascript:openItemHistBrowse('<?= $purch_vend_code ?>')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:&nbsp;</td>
                  <td> 
				    <?php $amount = $purdtl_cost * $purdtl_qty; ?>
                    <?= $f->fillTextBox("amount", sprintf("%0.2f", $amount), 20, 32, "inbox", " onChange=calcPrc() ") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Taxable:&nbsp;</td>
                  <td> 
                    <INPUT TYPE="checkbox" NAME="purdtl_taxable" VALUE="t" <?= ($purdtl_taxable == "f" || empty($purdtl_taxable))?"":"checked" ?>>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver" valign="top">Picture:&nbsp;</td>
                  <td> 
                    <INPUT TYPE="file" NAME="picture" size="30">
					<br>
					<INPUT TYPE="checkbox" NAME="keepic" VALUE="t" checked> Keep Picture
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Comment:&nbsp;</td>
                  <td> 
				    <TEXTAREA NAME="purdtl_comnt" ROWS="5" COLS="40"><?= $purdtl_comnt ?></TEXTAREA>
                  </td>
                </tr>
              </table> </td>
            <td width="150" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="center" bgcolor="white">
<?php
	if (empty($purdtl_filename)) {
		echo "&nbsp;";
	} else {
?>
				  <IMG SRC="<?= $dirname.$purdtl_filename ?>" WIDTH="150" BORDER="0" ALT="<?= $purdtl_filename ?>">
<?php
	}
?>
				  </td>
                </tr>
                <tr> 
                  <td width="150" align="center" bgcolor="white">
				  <?= (empty($purdtl_filename))?"&nbsp;":$purdtl_filename ?>
				  </td>
                </tr>
              </table>
			</td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="EditDtl()"> 
                              <input type="reset" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><hr height="1"></td>
                    </tr>
					<tr align="right"> 
                      <td colspan="8" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="62%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver">Vendor:</td>
                                  <td width="75%"> 
                                    <?= $purch_vend_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Vendor Name:</td>
                                  <td> 
                                    <?= $purch_vend_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Vendor Addr.</td>
                                  <td> 
                                    <?= $purch_vend_addr1 ?><br>
                                    <?= $purch_vend_addr2 ?><br>
                                    <?= $purch_vend_addr3 ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $purch_vend_city ?>
                                    <?= $purch_vend_state ?>
                                    <?= $purch_vend_zip ?>
                                    <?= $purch_vend_country ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $purch_vend_tel ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
								<tr> 
                                  <td width="25%" align="right" bgcolor="silver">Customer #</td>
                                  <td width="75%"> 
                                    <?= $purch_cust_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Cust. Name:</td>
                                  <td> 
                                    <?= $purch_cust_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Cust. Address</td>
                                  <td> 
                                    <?= $purch_cust_addr1 ?><br>
                                    <?= $purch_cust_addr2 ?><br>
                                    <?= $purch_cust_addr3 ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $purch_cust_city ?>
                                    <?= $purch_cust_state ?>
                                    <?= $purch_cust_zip ?>
                                    <?= $purch_cust_country ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $purch_cust_tel ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
								<tr> 
                                  <td width="25%" align="right" bgcolor="silver">Ship Code</td>
                                  <td width="75%"> 
                                    <?= $purch_ship_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $purch_ship_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $purch_ship_addr1 ?><br>
                                    <?= $purch_ship_addr2 ?><br>
                                    <?= $purch_ship_addr3 ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $purch_ship_city ?>
                                    <?= $purch_ship_state ?>
                                    <?= $purch_ship_zip ?>
                                    <?= $purch_ship_country ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $purch_ship_tel ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
<?php
	if ($ht!="a") {
?>
                                <tr> 
                                  <td align="right" bgcolor="silver" width="30%">PO #&nbsp;</td>
                                  <td width="70%">
                                    <?= $purch_id ?>
                                  </td>
                                </tr>
<?php
	}
?>
								<tr> 
                                  <td align="right" bgcolor="silver">Date</td>
                                  <td> 
                                    <?= $purch_date ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User #&nbsp;</td>
                                  <td> 
                                    <?= $purch_user_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Expected</td>
                                  <td> 
                                    <?= $purch_prom_date ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Custom?</td>
                                  <td> 
								    <?= ($purch_custom_po!="f")?"Yes":"No" ?>
                                  </td>
                                </tr>
								<tr> 
								  <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
								  <td width="76%">
									<?= $purch_shipvia ?>
								  </td>
								</tr>
								<tr> 
								  <td width="24%" bgcolor="silver" valign="top" align="right">Comment</td>
								  <td width="76%">
									<?= $purch_comnt ?>
								  </td>
								</tr>
                              </table>
							</td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Unit</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                          </tr>
<?php
	include_once("class/class.purdtls.php");

	$t = new Items();
	$d = new PurDtls();

	if ($ht=="e") $recs = $_SESSION["purdtls_edit"];
	else  $recs = $_SESSION["purdtls_add"];

	$purch_amt = 0;
	$taxtotal = 0;
	if ($recs) $recnums = count($recs);
	else $recnums = 0;
	for ($i=0;$i<$recnums;$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i]["purdtl_item_code"]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$purch_amt += sprintf("%0.2f",$recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"]);
			if ($recs[$i]["purdtl_taxable"]=="t") $taxtotal += $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"];
?>
                            <td width="5%" align="center"> 
							  <a href="purchase_details.php?ty=<?= $ty ?>&ht=<?= $ht ?>&did=<?= $i ?>&purch_id=<?= $purch_id ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="15%"> 
                              <?= $recs[$i]["purdtl_item_code"] ?>
                            </td>
                            <td width="40%"> 
                              <?= stripslashes($recs[$i]["purdtl_item_desc"]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["purdtl_cost"]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                            <td width="5%" align="right"> 
                              <?= $recs[$i]["purdtl_unit"] ?>
                            </td>
                            <td width="5%" align="center"> 
                              <?= ($recs[$i]["purdtl_taxable"]=="t")?"X":"&nbsp;" ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"]) ?>
                            </td>
                        </tr>
<?php
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              &nbsp;
                            </td>
                            <td width="100%" align="left" colspan="4"> 
                              <?= $recs[$i]["purdtl_comnt"] ?>
                            </td>
<?php
		}
	}
	if (count($arr) == 0) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
                          </tr>
<?php
	}
	$purch_tax_amt = $taxtotal*$purch_taxrate/100;
  
?>
                    <tr> 
                    
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%">
                <?= $purch_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $purch_taxrate ?>%
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
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Sub_Total"] ?></td>
              <td>
                <?= sprintf("%0.2f",$purch_amt) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td>
                <?= sprintf("%0.2f",$purch_freight_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= sprintf("%0.2f",$purch_tax_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= sprintf("%0.2f",$purch_tax_amt+$purch_freight_amt+$purch_amt) ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                    </tr>
                        </table></td>
                    </tr>

						  </form>
                  </table>


