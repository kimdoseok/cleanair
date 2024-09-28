<?php
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

//echo $pend_id."/".$diff_qty."/".$pend_qty."/".$pick_qty."<br>";
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
var clicked=false;

function convertSales(pid) {
	if (clicked==true) return;
	clicked = true;

	if (window.confirm("Are you sure to convert new sales order?")) {
		window.location="ar_proc.php?cmd=sale_convert&pend_id="+pid;
	}
}


//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>View Pending</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4"><font size="2">
						 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | 
						</font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
                         <a href="sales_proc.php?cmd=pend_del&pend_id=<?= $pend_id ?>">Delete</a>
<?php
	if ($pend_status>0) {
?>
					   | <a href="javascript:convertSales(<?= $pend_id ?>)">convert Sales</a> | 
<?php
	} else {
		echo "&nbsp;";
	}
?>

                      </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="62%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver">Customer:</td>
                                  <td width="75%"> 
                                    <?= $pend_cust_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $pend_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $pend_addr1 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $pend_addr2 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $pend_addr3 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">C_S_Z_C</td>
                                  <td> 
                                    <?= $pend_city ?>
                                    <?= $pend_state ?>
                                    <?= $pend_zip ?>
                                    <?= $pend_country ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone</td>
                                  <td> 
                                    <?= $pend_tel ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Status</td>
                                  <td> 
                                    <?= ($pend_status>0)?"Active":"Inactive" ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver">Sales #&nbsp;</td>
                                  <td>
                                    <?= $pend_id ?>
                                  </td>
                                </tr>
<!--
								<tr> 
                                  <td align="right" bgcolor="silver">Cust_PO_no</td>
                                  <td> 
                                    <?= $pend_cust_po ?>
                                  </td>
                                </tr>
-->
								<tr> 
                                  <td align="right" bgcolor="silver">Date&nbsp;</td>
                                  <td> 
                                    <?= $pend_date ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User_no&nbsp;</td>
                                  <td> 
                                    <?= $pend_user_code ?>
								  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Promise Date&nbsp;</td>
                                  <td> 
                                    <?= $pend_prom_date ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Sales Rep&nbsp;</td>
                                  <td> 
                                    <?= $pend_slsrep ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Sales Term&nbsp;</td>
                                  <td> 
                                    <?= $pend_term ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Origin</td>
                                  <td> 
                                    <a href="sales.php?ty=e&sale_id=<?= $pend_origin ?>"><?= $pend_origin ?></a> (<?= $pend_version ?>)
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Parent</td>
                                  <td> 
                                    <a href="sales.php?ty=e&sale_id=<?= $pend_parent ?>"><?= $pend_parent ?></a>
                                  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7" width="100%"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">No</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                            <th bgcolor="gray" width="1%"><font color="white">Tx</font></th>
                          </tr>
<?php

	$pd = new PenDtls();
	$recs = $pd->getPenDtlsList($pend_id);
	$y = new Items();
	$subtotal = 0;

	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += $recs[$i][pendtl_cost]*$recs[$i][pendtl_qty];

?>
                            <td align="center"> 
                              <?= $i+1 ?>
                            </td>
                            <td > 
                              <?= $recs[$i][pendtl_item_code] ?>
                            </td>
                            <td > 
                              <?= $recs[$i][pendtl_item_desc] ?>
                            </td>
                            <td align="right"> 
                              <?= sprintf("%0.2f", $recs[$i][pendtl_cost]) ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][pendtl_qty]+0 ?>
                            </td>
                            <td align="right"> 
                              <?= sprintf("%0.2f", $recs[$i][pendtl_cost]*$recs[$i][pendtl_qty]) ?>
                            </td>
                            <td align="center"> 
                              <?= ($recs[$i][pendtl_taxable]=="t")?"X":"&nbsp;" ?>
                            </td>
                          </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
						<tr bgcolor="#EEEEEE">
                            <td colspan="7" align="center"> 
                              <b>Empty_1!</b>
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
              <td width="24%" bgcolor="silver" valign="top" align="right">Ship_Via&nbsp;</td>
              <td width="76%" width="80%">
                <?= $pend_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $pend_taxrate+0 ?>%
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Comment&nbsp;</td>
              <td>
                <?= nl2br($pend_comnt) ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%">Sub_Total</td>
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
              <td bgcolor="silver" valign="top" align="right">Freight</td>
              <td align="right">
                <?= sprintf("%0.2f", $pend_freight_amt) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right">Tax&nbsp;</td>
              <td>
                <?= sprintf("%0.2f", $pend_tax_amt) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right">Total_Amount</td>
              <td>
                <?= sprintf("%0.2f", $pend_tax_amt+$pend_freight_amt+$subtotal-$pend_deposit_amt) ?>
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
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&pend_id=$pend_id" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&pend_id=$pend_id" ?>">&lt;Prev</a> &nbsp; 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&pend_id=$pend_id" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&pend_id=$pend_id" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
