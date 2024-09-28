<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.customers.php");
	$f = new FormUtil();
	if ($ht=="e") $sls = $_SESSION[$cmemo_edit];
	else $sls = $_SESSION[$cmemo_add];
	if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;

	if (!empty($cmemo_cust_code)) {
		$v = new Custs();
		$varr = $v->getCusts($cmemo_cust_code);
	}

	if ($item_code) $cmemodtl_item_code = $item_code;
	if (!empty($cmemodtl_item_code)) {
		$t = new Items();
		if ($tarr = $t->getItems($cmemodtl_item_code)) {
			$cmemodtl_item_code = strtoupper($cmemodtl_item_code);
			$old_cmemodtl_item_code = $cmemodtl_item_code;
			if (empty($cmemodtl_item_desc)) $cmemodtl_item_desc = $tarr["item_desc"];
			if (empty($cmemodtl_qty)) $cmemodtl_qty = 1;
			if (empty($cmemodtl_msrp) || $cmemodtl_msrp==0) $cmemodtl_cost = $tarr["item_msrp"];
			$not_found_item = 0;
		} else {
			$not_found_item = 1;
			$cmemodtl_item_code	= $old_cmemodtl_item_code;
		}
	}
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		f.action = "ar_proc.php";
		f.cmd.value = "cmemo_detail_sess_add";
		f.method = "post";
		f.submit();
	}
	
	function ClearSale() {
		var f = document.forms[0];
		f.action = "ar_proc.php";
		f.cmd.value = "cmemo_clear_sess_add";
		f.method = "post";
		f.submit();
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_item == 1) echo "	openItemBrowseFilter('cmemo_item_code', '$item_code', '$cmemo_cust_code');";
?>
//-->
</SCRIPT>

						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="ar_proc.php">
							<INPUT TYPE="hidden" name="cmd" value="">
							<?= $f->fillHidden("ht", $ht) ?>
							<?= $f->fillHidden("ty", $ty) ?>
							<?= $f->fillHidden("cmemo_id", $cmemo_id) ?>
							<?= $f->fillHidden("thisfocus", "") ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>New Credit Memo Detail</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=a&ht=$ht&cmemo_id=$cmemo_id" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo "cmemo.php?ty=$ht&cmemo_id=$cmemo_id" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["item_code"] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("cmemodtl_item_code", $cmemodtl_item_code, 20, 32, "inbox", " onChange='updateForm()'") ?>
					<?= $f->fillHidden("old_cmemodtl_item_code", $old_cmemodtl_item_code) ?>
					<A HREF="javascript:openItemBrowse('cmemodtl_item_code','<?= $cmemo_cust_code ?>')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
<?php
	if ($not_found_item != 1) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	setCursor('cmemodtl_item_code');
//-->
</SCRIPT>
<?php
	}
?>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Description</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("cmemodtl_item_desc", stripslashes($cmemodtl_item_desc), 40, 250, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Quantity"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("cmemodtl_qty", $cmemodtl_qty+0, 20, 32, "inbox", " onChange='calcAmt() '") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Selling_Price"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("cmemodtl_cost", sprintf("%0.2f", $cmemodtl_cost), 20, 32, "inbox", " onChange='calcAmt() '") ?>
					<A HREF="javascript:openItemHistBrowse('<?= $cmemo_cust_code ?>')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:&nbsp;</td>
                  <td> 
				    <?php $amount = $cmemodtl_cost * $cmemodtl_qty; ?>
                    <?= $f->fillTextBox("amount", sprintf("%0.2f", $amount), 20, 32, "inbox", " onChange=calcPrc() ") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Taxable:&nbsp;</td>
                  <td> 
                    <INPUT TYPE="checkbox" NAME="cmemodtl_taxable" VALUE="t" <?= ($cmemodtl_taxable == "t" || empty($cmemodtl_taxable))?"checked":"" ?>>
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
                                  <td width="25%" align="right" bgcolor="silver"><?= $label[$lang]["Customer"] ?>:</td>
                                  <td width="75%"> 
                                    <?= $cmemo_cust_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $cmemo_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $cmemo_addr1 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $cmemo_addr2 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $cmemo_addr3 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $cmemo_city ?>
                                    <?= $cmemo_state ?>
                                    <?= $cmemo_zip ?>
                                    <?= $cmemo_country ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Sales_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $cmemo_id ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Cust_PO_no"] ?></td>
                                  <td> 
                                    <?= $cmemo_cust_po ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $cmemo_date ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $cmemo_user_code ?>
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
                            <th bgcolor="gray" width="7%"><font color="white"><?= $label[$lang]["qty"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                            <th bgcolor="gray" width="2%"><font color="white">Tx</font></th>
                          </tr>
<?php
	include_once("class/class.cmemodtls.php");
	include_once("class/class.styles.php");

	$t = new Items();
	if ($ht=="e") $recs = $_SESSION[$cmemodtl_edit];
	else  $recs = $_SESSION[$cmemodtl_add];

	$cmemo_amt = 0;
	$taxtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i]["cmemodtl_item_code"]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$cmemo_amt += sprintf("%0.2f",$recs[$i]["cmemodtl_cost"]*$recs[$i]["cmemodtl_qty"]);
			if ($recs[$i]["cmemodtl_taxable"]=="t") $taxtotal += $recs[$i]["cmemodtl_cost"]*$recs[$i]["cmemodtl_qty"];
?>
                            <td width="5%" align="center"> 
                              <a href="cmemo_details.php?ty=e&ht=<?= $ht ?>&cmemo_id=<?= $cmemo_id ?>&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="15%"> 
                              <?= $recs[$i]["cmemodtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= stripslashes($recs[$i]["cmemodtl_item_desc"]) ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["cmemodtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["cmemodtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["cmemodtl_cost"]*$recs[$i]["cmemodtl_qty"]) ?>
                            </td>
                            <td width="2%" align="center"> 
                              <?= ($recs[$i]["cmemodtl_taxable"] == "t")?"X":"" ?>
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
	if (empty($cmemo_taxrate)) $cmemo_tax_amt = $taxtotal*$xarr["taxrate_pct"]/100;
	else $cmemo_tax_amt = $taxtotal*$cmemo_taxrate/100;
?>
                    <tr> 
                      
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%">
                <?= $cmemo_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $cmemo_taxrate ?>%
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $cmemo_comnt ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Sub_Total"] ?></td>
              <td align="right">
                <?= sprintf("%0.2f",$cmemo_amt) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td align="right">
                <?= sprintf("%0.2f",$cmemo_freight_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td align="right">
                <?= sprintf("%0.2f",$cmemo_tax_amt) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td align="right">
                <?= sprintf("%0.2f",$cmemo_tax_amt+$cmemo_freight_amt+$cmemo_amt) ?>
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
