<?php
	include_once("class/class.customers.php");
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	$f = new FormUtil();

	if ($ht == "e") $slsdtl = $_SESSION[itmbldtls_edit];
	else $slsdtl = $_SESSION[itmbldtls_add];

	if ($slsdtl[$did]) foreach ($slsdtl[$did] as $k => $v) $$k = $v;
	if ($ht=="e") $sls = $_SESSION[itmbld_edit];
	else $sls = $_SESSION[itmbld_add];
	if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	if (!empty($sale_cust_code)) {
		$v = new ItmBuilds();
		$varr = $v->getCusts($sale_cust_code);
	}

	if ($item_code) $slsdtl_item_code = $item_code;
	if (!empty($slsdtl_item_code) && $ctrl == 1) {
		$t = new Items();
		if ($tarr = $t->getItems($slsdtl_item_code)) {
			$slsdtl_item_code = strtoupper($slsdtl_item_code);
			$old_slsdtl_item_code = $slsdtl_item_code;
			$slsdtl_item_desc = $tarr["item_desc"];
			$slsdtl_qty = 1;
			$slsdtl_cost = $tarr["item_msrp"];
			$not_found_item = 0;
		} else {
			$not_found_item = 1;
			$slsdtl_item_code	= $old_slsdtl_item_code;
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
		f.action = "ibm_build_proc.php";
		f.cmd.value = "sale_detail_sess_edit";
		f.method = "post";
		f.submit();
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_item == 1) echo "	openItemBrowseFilter('sale_item_code', '$item_code');";
?>
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="ibm_build_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="">
						<INPUT TYPE="hidden" name="sale_id" value="<?= $itmbuild_id ?>">
						<INPUT TYPE="hidden" name="slsdtl_sale_id" value="<?= $slsdtl_sale_id ?>">
						<INPUT TYPE="hidden" name="slsdtl_id" value="<?= $slsdtl_id ?>">
						<?= $f->fillHidden("ht", $ht) ?>						
						<?= $f->fillHidden("ty", $ty) ?>						
						<?= $f->fillHidden("did", $did) ?>
						<?= $f->fillHidden("ctrl", "") ?>
                    <tr align="right"> 
                      <td colspan="1"><strong><?= $label[$lang]["Edit_Sales_Detail"] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
                        <a href="<?php echo "itm_build.php?ty=$ht&sale_id=$itmbuild_id" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="100%" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["item_code"] ?>:&nbsp;</td>
                  <td width="450"> 
                    <?= $f->fillTextBox("slsdtl_item_code", $slsdtl_item_code, 20, 32, "inbox", " onChange='refreshItem(\"1\")'") ?>
					<?= $f->fillHidden("old_slsdtl_item_code", $old_slsdtl_item_code) ?>
					<A HREF="javascript:openItemBrowse('slsdtl_item_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
<?php
	if ($not_found_item != 1) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	setCursor('slsdtl_item_code');
//-->
</SCRIPT>
<?php
	}
?>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">&nbsp;</td>
                  <td width="450"> 
                    <?= $f->fillTextBox("slsdtl_item_desc", stripslashes($slsdtl_item_desc), 32, 250, "inbox") ?>
					<A HREF="items.php?ty=a">New Item</A> &nbsp; <A HREF="javascript:editItem()">Edit Item</A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Unit:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBoxRO("slsdtl_unit", $slsdtl_unit, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Quantity"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("slsdtl_qty", $slsdtl_qty+0, 20, 32, "inbox", " onChange='calcAmt() '") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Selling_Price"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("slsdtl_cost", sprintf("%0.2f", $slsdtl_cost), 20, 32, "inbox", " onChange='calcAmt() '") ?>
					<A HREF="javascript:openItemHistBrowse('<?= $sale_cust_code ?>')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Amount:&nbsp;</td>
                  <td> 
				    <?php $amount = $slsdtl_cost * $slsdtl_qty; ?>
                    <?= $f->fillTextBox("amount", sprintf("%0.2f", $amount), 20, 32, "inbox", " onChange=calcPrc() ") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Taxable:&nbsp;</td>
                  <td> 
                    <INPUT TYPE="checkbox" NAME="slsdtl_taxable" VALUE="t" <?= ($slsdtl_taxable == "t")?"checked":"" ?>>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="EditDtl()"> 
                              <input type="reset" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1"><hr height="1"></td>
                    </tr>
					<tr align="right"> 
                      <td colspan="1" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="62%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver"><?= $label[$lang]["Customer"] ?>:</td>
                                  <td width="75%"> 
                                    <?= $sale_cust_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $sale_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $sale_addr1 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $sale_addr2 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $sale_addr3 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $sale_city ?>
                                    <?= $sale_state ?>
                                    <?= $sale_zip ?>
                                    <?= $sale_country ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Sales_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $itmbuild_id ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Cust_PO_no"] ?></td>
                                  <td> 
                                    <?= $sale_cust_po ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $sale_date ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $sale_user_code ?>
                                  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item Code</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">UP Qty</font></th>
                            <th bgcolor="gray" width="2%"><font color="white">Unit</font></th>
                            <th bgcolor="gray" width="2%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                          </tr>
<?php
	include_once("class/class.itm_buildtls.php");
	include_once("class/class.styles.php");

	$t = new Items();
	$d = new ItmBuilDtls();
	$pd = new PickDtls();

	if ($ht=="e") $recs = $_SESSION[itmbldtls_edit];
	else  $recs = $_SESSION[itmbldtls_add];

	$sale_amt = 0;
	$taxtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i]["slsdtl_item_code"]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$sale_amt += sprintf("%0.2f",$recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"]);
			if ($recs[$i]["slsdtl_taxable"]=="t") $taxtotal += $recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"];

			if ($ht=="e") {
				$pick_qty = $pd->getPickDtlsSlsSum($recs[$i]["slsdtl_id"]);
				$sale_qty = $recs[$i]["slsdtl_qty"];
				$diff_qty = $sale_qty - $pick_qty;
			}

?>
                            <td width="5%" align="center"> 
<?php
	if ($diff_qty > 0 && $pick_qty >=0) {
?>
							  <a href="itm_build_details.php?ty=<?= $ty ?>&ht=<?= $ht ?>&did=<?= $i ?>&sale_id=<?= $itmbuild_id ?>"><?= $i+1 ?></a>
<?php
	} else {
		echo $i+1;
	}
?>
                            </td>
                            <td width="15%"> 
                              <?= $recs[$i]["slsdtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= stripslashes($recs[$i]["slsdtl_item_desc"]) ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["slsdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["slsdtl_qty"]+0 ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["slsdtl_qty"]-$pick_qty ?>
                            </td>
                            <td width="2%" align="right"> 
                              <?= strtoupper($recs[$i]["slsdtl_unit"]) ?>
                            </td>
                            <td width="2%" align="center"> 
                              <?= ($recs[$i]["slsdtl_taxable"]=="t")?"X":"&nbsp;" ?>
                            </td>

                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"]) ?>
                            </td>
                        </tr>
<?php
		}
	}
	if (count($arr) == 0) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="1" align="center"> 
                              <b>Empty!</b>
                            </td>
                          </tr>
<?php
	}
	if (empty($sale_taxrate)) $sale_tax_amt = $taxtotal*$xarr["taxrate_pct"]/100;
	else $sale_tax_amt = $taxtotal*$sale_taxrate/100;

?>
                    <tr> 
                      
  <td colspan="1"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%">
                <?= $sale_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $sale_taxrate ?>%
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $sale_comnt ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Sub_Total"] ?></td>
              <td>
                <?= sprintf("%0.2f",$sale_amt) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td>
                <?= sprintf("%0.2f",$sale_freight_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= sprintf("%0.2f",$sale_tax_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= sprintf("%0.2f",$sale_tax_amt+$sale_freight_amt+$sale_amt) ?>
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
