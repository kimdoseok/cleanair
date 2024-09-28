<?php
	include_once("class/class.datex.php");
	include_once("class/class.formutils.php");
	include_once("class/class.navigates.php");
	include_once("class/register_globals.php");

	$d = new Datex();
	$f = new FormUtil();
	$limit = 1;
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

//echo $purch_id."/".$diff_qty."/".$purch_qty."/".$pick_qty."<br>";
?>
<SCRIPT LANGUAGE="JavaScript">
<!--

//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="7"><strong>View Purchase Order</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="3"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> | 
<?php
	$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_edit");
	if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
	if ($ua_arr["userauth_allow"]=="t") {
?>
							<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&purch_id=$purch_id" ?>"><?= $label[$lang]["Edit"] ?></a> |
<?php
	}
?>
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | </font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="<?php echo "purchase_print_pdf.php?purch_id=$purch_id" ?>">Print</a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="62%" valign="top">
							  <table width="100%" border="0" cellspacing="1" cellpadding="0">
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
                                <tr> 
                                  <td align="right" bgcolor="silver" width="30%">PO #&nbsp;</td>
                                  <td width="70%">
                                    <?= $purch_id ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver" width="30%">VC #&nbsp;</td>
                                  <td width="70%">
									<?= $purch_vend_serial ?>
                                  </td>
                                </tr>
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
                                  <td align="right" bgcolor="silver">Confirm?</td>
                                  <td> 
								    <?= ($purch_need_confirm!="f")?"Yes":"No" ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Sample?</td>
                                  <td> 
								    <?= ($purch_sample_included!="f")?"Yes":"No" ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Complete?</td>
                                  <td> 
								    <?= ($purch_completed!="f")?"Yes":"No" ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Cmpl Date</td>
                                  <td> 
                                    <?= ($purch_completed_date!="0000/00/00")?$d->toUsaDate($purch_completed_date):"" ?>
								  </td>
                                </tr>
								<tr> 
								  <td width="24%" bgcolor="silver" valign="top" align="right">Comment</td>
								  <td width="76%">
								    <?= nl2br($purch_comnt ?? "") ?>
								  </td>
								</tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Item"] ?></font></th>
                            <th bgcolor="gray" width="8%"><font color="white"><?= $label[$lang]["Cost"] ?></font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Unit</font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                          </tr>
<?php
	include_once("class/class.purdtls.php");
	include_once("class/class.styles.php");

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
                            <td width="15%"> 
                              <?= $recs[$i]["purdtl_item_code"] ?>
                            </td>
                            <td width="50%"> 
                              <?= $recs[$i]["purdtl_item_desc"] ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["purdtl_cost"]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["purdtl_unit"] ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"]) ?>
                            </td>
                          </tr>
<?php
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              &nbsp;
                            </td>
                            <td width="100%" align="left" colspan="7"> 
                              <?= $recs[$i]["purdtl_comnt"] ?? "" ?>
                            </td>
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
									<tr>
  <td colspan="7"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr width="20%"> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Ship Via&nbsp;</td>
              <td width="76%">
                <?= $purch_shipvia ?>
              </td>
            </tr>
            <tr width="20%"> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tracking #</td>
              <td width="76%" width="80%">
                <?= $purch_tracking ?>
              </td>
            </tr>
            <tr width="20%"> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Shipped</td>
              <td width="76%">
                <?= $purch_ship_date ?>
              </td>
            </tr>
            <tr width="20%"> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Received</td>
              <td width="76%">
                <?= $purch_rcvd_date ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $purch_taxrate ?>%
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%"><?= $label[$lang]["Sub_Total"] ?></td>
              <td width="50%">
                <?= sprintf("%0.2f", $subtotal ?? 0) ?>
              </td>
            </tr>
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%">Taxable Total</td>
              <td width="50%">
                <?= sprintf("%0.2f", $taxtotal ?? 0) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td align="right">
                <?= sprintf("%0.2f", $purch_freight_amt ?? 0) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= sprintf("%0.2f", $purch_tax_amt) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= sprintf("%0.2f", $purch_tax_amt+$purch_freight_amt+$subtotal) ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&purch_id=$purch_id" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&purch_id=$purch_id" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&purch_id=$purch_id" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&purch_id=$purch_id" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
