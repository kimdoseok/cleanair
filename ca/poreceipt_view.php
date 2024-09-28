<?php
	include_once("class/class.datex.php");
	include_once("class/class.formutils.php");
	include_once("class/class.navigates.php");
	include_once("class/register_globals.php");

	$v = new Vends();
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

	if (!empty($porcpt_vend_code)) {
		if ($varr = $v->getVends($porcpt_vend_code)) {
			foreach ( $varr as $k => $v ) $$k = $v;
		}
	}

?>
<SCRIPT LANGUAGE="JavaScript">
<!--

//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="7"><strong>View PO Receiving</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="3"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> | 
<?php
	$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_edit");
	if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
	if ($ua_arr["userauth_allow"]=="t") {
?>
							<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&porcpt_id=$porcpt_id" ?>">Edit</a> |
<?php
	}
?>
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="<?php echo "poreceipt_print_pdf.php?porcpt_id=$porcpt_id" ?>">Print</a> | </font>
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
                                    <?= $vend_code ?>
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
                                <tr> 
                                  <td align="right" bgcolor="silver" width="30%">PO Rcpt#&nbsp;</td>
                                  <td width="70%">
                                    <?= $porcpt_id ?>
                                  </td>
                                </tr>
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
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Quantity</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Unit</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                          </tr>
<?php
	include_once("class/class.purdtls.php");
	include_once("class/class.styles.php");

	$d = new PoRcptDtls();
	$recs = $d->getPoRcptDtlsList($porcpt_id);
	$y = new Items();
	$subtotal = 0;

	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += $recs[$i]["porcptdtl_cost"]*$recs[$i]["porcptdtl_qty"];
			$iit_arr = $y->getItems($recs[$i]["porcptdtl_item_code"]);
?>
                            <td width="5%" align="center"> 
                              <?= $i+1 ?>
                            </td>
                            <td width="15%"> 
                              <?= $recs[$i]["porcptdtl_item_code"] ?>
                            </td>
                            <td width="50%"> 
                              <?= $iit_arr["item_desc"] ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["porcptdtl_cost"]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["porcptdtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i][porcptdtl_unit] ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["porcptdtl_cost"]*$recs[$i]["porcptdtl_qty"]) ?>
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
                              <?= $recs[$i]["purdtl_comnt"] ?>
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
            <tr> 
              <td bgcolor="silver" valign="top" align="left" width="25%">Comment&nbsp;</td>
              <td width="75%">
                <?= $porcpt_comnt ?>
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
                <?= sprintf("%0.2f", $porcpt_freight_amt) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= sprintf("%0.2f", $porcpt_tax_amt) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= sprintf("%0.2f", $porcpt_tax_amt+$porcpt_freight_amt+$subtotal) ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&porcpt_id=$porcpt_id" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&porcpt_id=$porcpt_id" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&porcpt_id=$porcpt_id" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&porcpt_id=$porcpt_id" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
