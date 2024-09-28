<?php
	include_once("class/class.datex.php");
	$d = new Datex();

	include_once("class/class.formutils.php");
	$f = new FormUtil();

	include_once("class/class.navigates.php");
	$limit = 1;
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$pd = new PickDtls();
	$sd = new ItmBuilDtls();
	$pick_qty = $pd->getPickDtlsSlsHdrSum($itmbuild_id);
	$sale_qty = $sd->getSaleDtlsHdrSum($itmbuild_id);
	$diff_qty = $sale_qty - $pick_qty;

	if ($diff_qty < 0) $status = "Over Fulfilled";
	else if ($diff_qty == 0) $status = "Fully Fulfilled";
	else if ($diff_qty > 0 && $pick_qty > 0) $status = "Partially Fulfilled";
	else $status = "Not Fulfilled";

	$ca = new Receipt();
	$ca_arr = $ca->getReceiptLast($sale_cust_code);
	$last_payment = number_format($ca_arr["rcpt_amt"],2,",",".");
	$last_payment .= "(".number_format($ca_arr["rcpt_disc_amt"],2,",",".").")";
	$last_payday = $ca_arr["rcpt_date"];

//echo $itmbuild_id."/".$diff_qty."/".$sale_qty."/".$pick_qty."<br>";
?>
<SCRIPT LANGUAGE="JavaScript">
<!--

//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="1"><strong>View Sales</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> | 
<?php
	if ($diff_qty > 0 && $pick_qty >= 0) {
?>
							<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&sale_id=$itmbuild_id" ?>">Edit</a> |
<?php
	}
?>
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="<?php echo "ibm_build_proc.php?cmd=sale_print&ty=v&sale_id=$itmbuild_id" ?>">Print</a> | </font>
                      </td>
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
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone</td>
                                  <td> 
                                    <?= $sale_tel ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Status</td>
                                  <td> 
                                    <?= $status ?>
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
<!--
								<tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Cust_PO_no"] ?></td>
                                  <td> 
                                    <?= $sale_cust_po ?>
                                  </td>
                                </tr>
-->
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
								<tr> 
                                  <td align="right" bgcolor="silver">Promise Date&nbsp;</td>
                                  <td> 
                                    <?= $sale_prom_date ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Sales Rep&nbsp;</td>
                                  <td> 
                                    <?= $sale_slsrep ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Sales Term&nbsp;</td>
                                  <td> 
                                    <?= $sale_term ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Balance</td>
                                  <td> 
                                    <?= number_format($cust_balance,2,".",",") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Last Payment</td>
                                  <td> 
                                    <?= $last_payment ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Last Payday</td>
                                  <td> 
                                    <?= $last_payday ?>
                                  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7" width="100%"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item Code</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">U.Qty</font></th>
                            <th bgcolor="gray" width="1%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                          </tr>
<?php
	include_once("class/class.itm_buildtls.php");
	include_once("class/class.styles.php");

	$d = new ItmBuilDtls();
	$pd = new PickDtls();
	$recs = $d->getSaleDtlsList($itmbuild_id);
	$y = new Items();
	$subtotal = 0;

	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += $recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"];
			$pick_qty = $pd->getPickDtlsSlsSum($recs[$i]["slsdtl_id"]);
			$up_qty = $recs[$i]["slsdtl_qty"]-$recs[$i]["slsdtl_qty_cancel"]-$pick_qty;

?>
                            <td align="center"> 
                              <?= $i+1 ?>
                            </td>
                            <td > 
                              <?= $recs[$i]["slsdtl_item_code"] ?>
                            </td>
                            <td > 
                              <?= $recs[$i]["slsdtl_item_desc"] ?>
                            </td>
                            <td align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["slsdtl_cost"]) ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i]["slsdtl_qty"]+0 ?>
                            </td>
                            <td align="right"> 
                              <?= $up_qty+0 ?>
                            </td>
                            <td align="center"> 
                              <?= ($recs[$i]["slsdtl_taxable"]=="t")?"X":"&nbsp;" ?>
                            </td>
                            <td align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"]) ?>
                            </td>
                          </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
						<tr bgcolor="#EEEEEE">
                            <td colspan="7" align="center"> 
                              <b>Empty!</b>
                            </td>
                        </tr>
<?php
	}
?>
									<tr>
  <td colspan="1"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr width="20%"> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%" width="80%">
                <?= $sale_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $sale_taxrate+0 ?>%
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $sale_comnt ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Ref #&nbsp;</td>
              <td width="76%">
<?php
	$arr = $sd->getSaleDtlsListPicks($itmbuild_id);
	if ($arr) $arr_num = count($arr);
	else $arr_num =0;
	for ($i=0;$i<$arr_num;$i++) {
		if ($i!=0) echo ", ";
		echo "<a href=\"picking.php?ty=l&ft=" . $arr[$i]["pickdtl_pick_id"]."&cn=code\">" . $arr[$i]["pickdtl_pick_id"]."<a>\n";
	}
?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%"><?= $label[$lang]["Sub_Total"] ?></td>
              <td width="50%">
                <?= sprintf("%0.2f", $subtotal) ?>
              </td>
            </tr>
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%">Taxable Total</td>
              <td width="50%">
                <?= sprintf("%0.2f", $taxtotal) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td align="right">
                <?= sprintf("%0.2f", $sale_freight_amt) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= sprintf("%0.2f", $sale_tax_amt) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= sprintf("%0.2f", $sale_tax_amt+$sale_freight_amt+$subtotal) ?>
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
                      <td colspan="1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&sale_id=$itmbuild_id" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&sale_id=$itmbuild_id" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&sale_id=$itmbuild_id" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&sale_id=$itmbuild_id" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
