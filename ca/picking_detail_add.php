<?php
	$f = new FormUtil();
	if ($ht=="e") $slsdtl = $_SESSION["pickdtls_edit"];
	else $slsdtl = $_SESSION["pickdtls_add"];
	if (!empty($slsdtl)) foreach($slsdtl as $k => $v) $$k = $v;
	if ($ht=="e") $sls = $_SESSION["picks_edit"];
	else $sls = $_SESSION["picks_add"];
	if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	if (!empty($pick_cust_code)) {
		$v = new Custs();
		$carr = $v->getCusts($pick_cust_code);
	}

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		f.action = "ar_proc.php";
		f.cmd.value = "pick_detail_sess_add";
		f.method = "post";
		f.submit();
	}
	
	function ClearSale() {
		var f = document.forms[0];
		f.action = "ar_proc.php";
		f.cmd.value = "pick_clear_sess_add";
		f.method = "post";
		f.submit();
	}

	var pickBrowse;
	function openPickBrowse(objname)  {
		var f = document.forms[0];
		var url;
		if (f.hl_type[0].checked) url = 'picking_slsdtls_popup.php';
		else if (f.hl_type[1].checked) url = 'picking_sales_popup.php';
		else url = 'picking_slsdtls_popup.php';
		if (pickBrowse && !pickBrowse.closed) pickBrowse.close();
		pickBrowse = window.open(url+"?objname="+objname, "pickBrowseWin", "height=450,width=350,resizable=yes");
		pickBrowse.focus();
		pickBrowse.moveTo(100,100);
	}

//-->
</SCRIPT>

						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="ar_proc.php">
							<INPUT TYPE="hidden" name="cmd" value="">
							<?= $f->fillHidden("ht", $ht) ?>
							<?= $f->fillHidden("ty", $ty) ?>
							<?= $f->fillHidden("pick_id", $pick_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>New Picking Detail</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=a&ht=" ?><?= ($ht=="e")?"e":"a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo "picking.php?ty=$ht&pick_id=$pick_id" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
						  <tr bgcolor="white"> 
							<td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
								<tr> 
								  <td width="150" align="right" bgcolor="silver">Sale Code:&nbsp;</td>
								  <td width="308"> 
									<?= $f->fillTextBox("pickdtl_code", $pickdtl_id, 20, 32, "inbox") ?>
									<A HREF="javascript:openPickBrowse('pickdtl_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
								  </td>
								</tr>
								<tr> 
								  <td width="150" align="right" bgcolor="silver">Description:&nbsp;</td>
								  <td> 
									<?= $f->fillTextBox("pickdtl_item_desc", $pickdtl_item_desc, 20, 250, "inbox") ?>
								  </td>
								</tr>
								<tr> 
								  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Quantity"] ?>:&nbsp;</td>
								  <td> 
									<?= $f->fillTextBox("pickdtl_qty", $pickdtl_qty+0, 20, 32, "inbox", " onChange='calcAmt() '") ?>
								  </td>
								</tr>
								<tr> 
								  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Selling_Price"] ?>:&nbsp;</td>
								  <td> 
									<?= $f->fillTextBoxRO("pickdtl_cost", sprintf("%0.2f", $pickdtl_cost), 20, 32, "inbox", " onChange='calcAmt() '") ?>
								  </td>
								</tr>
								<tr> 
								  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:&nbsp;</td>
								  <td> 
									<?= $f->fillTextBoxRO("amount", sprintf("%0.2f", $amount), 20, 32, "inbox", " onChange=calcPrc() ") ?>
								  </td>
								</tr>
							  </table> </td>

							<td width="477" align="right" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">

								<tr> 
								  <td width="150" align="right" bgcolor="silver" valign="top">Type</td>
								  <td width="308" valign="top"> 
									<INPUT TYPE="radio" NAME="hl_type" value="l">Line
									<INPUT TYPE="radio" NAME="hl_type" value="h" checked>Header 
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
                              <input type="button" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>" onClick="ClearSale()"></td>
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
                                  <td width="25%" align="right" bgcolor="silver"><?= $label[$lang]["Customer"] ?>:</td>
                                  <td width="75%"> 
                                    <?= $pick_cust_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver"><?= $label[$lang][Ship_To] ?>:</td>
                                  <td> 
                                    <?= $pick_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $pick_addr1 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $pick_addr2 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $pick_addr3 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $pick_city ?>
                                    <?= $pick_state ?>
                                    <?= $pick_zip ?>
                                    <?= $pick_country ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone&nbsp;</td>
                                  <td> 
                                    <?= $pick_tel ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver" width="150"><?= $label[$lang]["Sales_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $pick_id ?>&nbsp;
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Cust_PO_no"] ?></td>
                                  <td> 
                                    <?= $["pick_cust_po"] ?>&nbsp;
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $pick_date ?>&nbsp;
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $pick_user_code ?>&nbsp;
                                  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Item"] ?></font></th>
                            <th bgcolor="gray" width="8%"><font color="white"><?= $label[$lang]["Cost"] ?></font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Ord.Qty</font></th>
                            <th bgcolor="gray" width="7%"><font color="white"><?= $label[$lang]["qty"] ?></font></th>
                            <th bgcolor="gray" width="1%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                          </tr>
<?php
	include_once("class/class.saledtls.php");

	$d = new SaleDtls();

	if ($ht=="e") $recs = $_SESSION["pickdtls_edit"];
	else $recs = $_SESSION["pickdtls_add"];

	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["pickdtl_cost"])*$recs[$i]["pickdtl_qty"];
			$arr = $d->getSaleDtls($recs[$i]["pickdtl_code"]);
			if ($arr["slsdtl_taxable"]=="t") $taxtotal += sprintf("%0.2f",$recs[$i]["pickdtl_cost"])*$recs[$i]["pickdtl_qty"];

?>
                            <td width="5%" align="center"> 
                              <?= $i+1 ?>
                            </td>
                            <td width="15%"> 
                              <?= $arr["slsdtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= $arr["item_desc"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["pickdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $arr["slsdtl_qty"]+0 ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["pickdtl_qty"]+0 ?>
                            </td>
                            <td width="1%" align="right"> 
                              <?= ($arr["item_tax"]=="t")?"X":"&nbsp;" ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["pickdtl_cost"]*$recs[$i]["pickdtl_qty"]) ?>
                            </td>
                        </tr>
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
	$pick_tax_amt = $taxtotal * $pick_taxrate / 100;

?>
                    <tr> 
                      
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%">
                <?= $pick_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $pick_taxrate+0 ?>%
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $pick_comnt ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Sub_Total"] ?></td>
              <td align="right">
                <?= number_format($subtotal, 2, ".",",") ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Taxable Total</td>
              <td align="right">
                <?= number_format($taxtotal, 2, ".",",") ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td align="right">
                <?= sprintf("%0.2f",$pick_freight_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td align="right">
                <?= sprintf("%0.2f",$pick_tax_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td align="right">
                <?= sprintf("%0.2f",$pick_tax_amt+$pick_freight_amt+$subtotal) ?>
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
