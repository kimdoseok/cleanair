<?php
	include_once("defaults.php");
	include_once("class/class.vendors.php");
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
  include_once("class/register_globals.php");

	$f = new FormUtil();

	if ($ht == "e") $slsdtl = $_SESSION[$porcptdtl_edit];
	else $slsdtl = $_SESSION[$porcptdtl_add];

	if ($slsdtl[$did]) foreach ($slsdtl[$did] as $k => $v) $$k = $v;
	if ($ht=="e") $sls = $_SESSION[$porcpt_edit];
	else $sls = $_SESSION[$porcpt_add];
	if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	if (!empty($porcpt_vend_code)) {
		$v = new Vends();
		$varr = $v->getVends($porcpt_vend_code);
		if ($varr) foreach ($varr as $k => $v) $$k = $v;
	}

	if ($item_code) $purdtl_item_code = $item_code;
	if (!empty($purdtl_item_code) && $ctrl == 1) {
		$t = new Items();
		if ($tarr = $t->getItems($purdtl_item_code)) {
			$porcptdtl_item_code = strtoupper($porcptdtl_item_code);
			$old_porcptdtl_item_code = $porcptdtl_item_code;
			$porcptdtl_item_desc = $tarr["item_desc"];
			$porcptdtl_qty = 1;
			$porcptdtl_cost = $tarr["item_msrp"];
			$not_found_item = 0;
		} else {
			$not_found_item = 1;
			$porcptdtl_item_code	= $old_porcptdtl_item_code;
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
		f.action = "poreceipt_proc.php";
		f.cmd.value = "porcpt_detail_sess_edit";
		f.method = "post";
		f.submit();
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_item == 1) echo "	openItemBrowseFilter('porcpt_item_code', '$item_code');";
?>
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="ap_proc.php" enctype="multipart/form-data">
						<INPUT TYPE="hidden" name="cmd" value="">
						<INPUT TYPE="hidden" name="porcptdtl_porcpt_id" value="<?= $porcptdtl_porcpt_id ?>">
						<INPUT TYPE="hidden" name="porcptdtl_id" value="<?= $porcptdtl_id ?>">
						<?= $f->fillHidden("ht", $ht) ?>						
						<?= $f->fillHidden("ty", $ty) ?>						
						<?= $f->fillHidden("did", $did) ?>
						<?= $f->fillHidden("ctrl", "") ?>
						<?= $f->fillHidden("porcpt_id", $porcpt_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit PO Receiving Detail</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
                        <a href="<?php echo "poreceipt.php?ty=$ht&porcpt_id=$porcpt_id" ?>">Header</a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["item_code"] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("porcptdtl_item_code", $porcptdtl_item_code, 20, 32, "inbox", " onChange='refreshItem(\"1\")'") ?>
					<?= $f->fillHidden("old_porcptdtl_item_code", $old_porcptdtl_item_code) ?>
					<A HREF="javascript:openItemBrowse('porcptdtl_item_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
<?php
	if ($not_found_item != 1) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	setCursor('porcptdtl_item_code');
//-->
</SCRIPT>
<?php
	}
?>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("porcptdtl_item_desc", stripslashes($porcptdtl_item_desc), 32, 250, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Quantity:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("porcptdtl_qty", $porcptdtl_qty+0, 20, 32, "inbox", " onChange='calcAmt() '") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Unit</td>
                  <td> 
                    <?= $f->fillTextBoxRO("porcptdtl_unit", $porcptdtl_unit, 4, 4, "inbox", "") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">PO Price:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("porcptdtl_cost", sprintf("%0.2f", $porcptdtl_cost), 20, 32, "inbox", " onChange='calcAmt() '") ?>
					<A HREF="javascript:openItemHistBrowse('<?= $porcpt_vend_code ?>')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:&nbsp;</td>
                  <td> 
				    <?php $amount = $porcptdtl_cost * $porcptdtl_qty; ?>
                    <?= $f->fillTextBox("amount", sprintf("%0.2f", $amount), 20, 32, "inbox", " onChange=calcPrc() ") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Comment:&nbsp;</td>
                  <td> 
				    <TEXTAREA NAME="porcptdtl_comnt" ROWS="5" COLS="40"><?= $porcptdtl_comnt ?></TEXTAREA>
                  </td>
                </tr>
              </table> </td>
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
                                    <?= $porcpt_vend_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Vendor Name:</td>
                                  <td> 
                                    <?= $vend_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Vendor Addr.</td>
                                  <td> 
                                    <?= $vend_addr1 ?><br>
                                    <?= $vend_addr2 ?><br>
                                    <?= $vend_addr3 ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $vend_city ?>
                                    <?= $vend_state ?>
                                    <?= $vend_zip ?>
                                    <?= $vend_country ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $vend_tel ?>
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
                                    <?= $porcpt_id ?>
                                  </td>
                                </tr>
<?php
	}
?>
								<tr> 
                                  <td align="right" bgcolor="silver">Date&nbsp;</td>
                                  <td> 
                                    <?= $porcpt_date ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User#&nbsp;</td>
                                  <td> 
                                    <?= $porcpt_user_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">PO #&nbsp;</td>
                                  <td> 
                                    <?= $porcpt_ponum ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Vend Inv#&nbsp;</td>
                                  <td> 
                                    <?= $porcpt_vend_inv ?>
								  </td>
                                </tr>
								<tr> 
								  <td bgcolor="silver" valign="top" align="right">Ship Via&nbsp;</td>
								  <td>
									<?= $porcpt_shipvia ?>
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
                            <th bgcolor="gray" width="10%"><font color="white">Price</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Unit</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Extended</font></th>
                          </tr>
<?php
	include_once("class/class.porcptdtls.php");

	$t = new Items();
	$d = new PoRcptDtls();

	if ($ht=="e") $recs = $_SESSION[$porcptdtl_edit];
	else  $recs = $_SESSION[$porcptdtl_add];

	$porcpt_amt = 0;
	$taxtotal = 0;
	if ($recs) $recnums = count($recs);
	else $recnums = 0;
	for ($i=0;$i<$recnums;$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i]["porcptdtl_item_code"]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$porcpt_amt += sprintf("%0.2f",$recs[$i]["porcptdtl_cost"]*$recs[$i]["porcptdtl_qty"]);
			if ($recs[$i][porcptdtl_taxable]=="t") $taxtotal += $recs[$i]["porcptdtl_cost"]*$recs[$i]["porcptdtl_qty"];
?>
                            <td width="5%" align="center"> 
							  <a href="purchase_details.php?ty=<?= $ty ?>&ht=<?= $ht ?>&did=<?= $i ?>&porcpt_id=<?= $porcpt_id ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="15%"> 
                              <?= $recs[$i]["porcptdtl_item_code"] ?>
                            </td>
                            <td width="40%"> 
                              <?= stripslashes($recs[$i]["porcptdtl_item_desc"]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["porcptdtl_cost"]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["porcptdtl_qty"]+0 ?>
                            </td>
                            <td width="5%" align="right"> 
                              <?= $recs[$i][porcptdtl_unit] ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["porcptdtl_cost"]*$recs[$i]["porcptdtl_qty"]) ?>
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
                              <?= $recs[$i][porcptdtl_comnt] ?>
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
	$porcpt_tax_amt = $taxtotal*$porcpt_taxrate/100;
?>
                    <tr> 
                    
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%">
                <?= $porcpt_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $porcpt_taxrate ?>%
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $porcpt_comnt ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Sub_Total"] ?></td>
              <td>
                <?= sprintf("%0.2f",$porcpt_amt) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td>
                <?= sprintf("%0.2f",$porcpt_freight_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= sprintf("%0.2f",$porcpt_tax_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= sprintf("%0.2f",$porcpt_tax_amt+$porcpt_freight_amt+$porcpt_amt) ?>
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


