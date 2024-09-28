<?php
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.rcptdtls.php");
	include_once("class/class.customers.php");
	include_once("class/class.receipt.php");
	$x = new Datex();

	foreach (array('_GET', '_POST', '_COOKIE', '_SERVER') as $_SG) {
		foreach ($$_SG as $_SGK => $_SGV) {
			$$_SGK = $_SGV;
		}
	}

	$limit = 1;
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$recs = $op->getOpenPayDtlsList($rcpt_id);
	$applied = 0;
	$rcptdtl_disc_amt = 0;
	$rcptdtl_op_amt = 0;
	$rcptdtl_cm_amt = 0;
	$rcptdtl_aw_amt = 0;

	for ($i=0;$i<count($recs);$i++) {
		if ($recs[$i]["rcptdtl_type"]=="ar") $applied += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="op") $applied += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $recs[$i]["rcptdtl_amt"];
		else $applied += $recs[$i]["rcptdtl_amt"];
	}
	$remained = $rcpt_amt - $applied;

	if (!empty($rcpt_cust_code)) {
		$v = new Custs();
		$varr = $v->getCusts($rcpt_cust_code);
		$cust_name = $varr["cust_name"];
		$cust_addr1 = $varr["cust_addr1"];
		$cust_addr2 = $varr["cust_addr2"];
		$cust_addr3 = $varr["cust_addr3"];
		$cust_city = $varr["cust_city"];
		$cust_state = $varr["cust_state"];
		$cust_zip = $varr["cust_zip"];
		$cust_balance = $varr["cust_balance"];
	}

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>Open Payments View</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="8"><font size="2">
						 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							
      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
<!--
						  <tr> 
                            <td width="97" bgcolor="silver">PO #:</td>
                            <td width="308"> 
                              <?= $rcpt_po_no ?>
                            </td>
                          </tr>
-->
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Customer:</td>
                            <td width="308"> 
                              <?= $rcpt_cust_code ?>
                            </td>
                          </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Name:</td>
                                  <td> 
                                    <?= $cust_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $cust_addr1 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $cust_addr2 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $cust_addr3 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">C/S/Z/C</td>
                                  <td> 
                                    <?= $cust_city ?>
                                    <?= $cust_state ?>
                                    <?= $cust_zip ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Balance</td>
                                  <td> 
                                    <?= number_format($cust_balance,2,".",",") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Comment:</td>
                                  <td> 
		                              <?= $rcpt_comment ?>
                                  </td>
                                </tr>
              </table></td>
            <td width="1%">&nbsp;</td>
            <td width="37%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
<!--
						  <tr> 
                            <td width="97" align="right" bgcolor="silver">Rcpt #:</td>
                            <td width="308"> 
                              <?= $rcpt_id ?>
                            </td>
                          </tr>
-->
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Check_no:</td>
                            <td width="308"> 
                              <?= $rcpt_check_no ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Pay Type:</td>
                            <td width="308"> 
<?php
	if ($rcpt_type=="ca") echo "Cash";
	else if ($rcpt_type=="ch") echo "Check";
	else if ($rcpt_type=="op") echo "Open Payment";
	else if ($rcpt_type=="cc") echo "Credit Card";
	else if ($rcpt_type=="dc") echo "Discount Apply";
	else if ($rcpt_type=="bc") echo "Bounced Check";
	else if ($rcpt_type=="ot") echo "Other";
?>
                          </td>
                          </tr>

                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Amount:</td>
                            <td> 
                              <?= number_format($rcpt_amt,2,".",",") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Discount:</td>
                            <td> 
                              <?= number_format($rcpt_disc_amt,2,".",",") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Date:</td>
                            <td> 
                              <?= $rcpt_date ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">User_no&nbsp;</td>
                            <td> 
                              <?= $rcpt_user_code ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Applied:</td>
                            <td width="308"> 
                              <?= number_format($applied,2,".",",") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Remained:</td>
                            <td> 
                              <?= number_format($remained,2,".",",") ?>
                            </td>
                          </tr>
              </table></td>
          </tr>
        </table> </td>
						  </tr>
						</table>
					  
					    
    </td>
                    </tr>
	<tr> 
      <td colspan="7" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add Detail</A></FONT></td>
	</tr>
                    <tr align="right"> 
                      <td colspan="7"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">No</font></th>
                            <th bgcolor="gray"><font color="white">Inv #</font></th>
                            <th bgcolor="gray"><font color="white">Acct #</font></th>
                            <th bgcolor="gray"><font color="white">Type</font></th>
                            <th bgcolor="gray"><font color="white">Amount</font></th>
                            <th bgcolor="gray"><font color="white">Description</font></th>
                            <th bgcolor="gray"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              <?= $i+1 ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["rcptdtl_pick_id"] ?>
                            </td>
                            <td width="10%"> 
                              <?= $recs[$i][rcptdtl_acct_code] ?>
                            </td>
                            <td width="10%" align="center"> 
<?php
	echo strtoupper($recs[$i]["rcptdtl_type"]);
/*
	if ($recs[$i]["rcptdtl_type"] == "ar") {
		echo "Account Receivable";
	} else if ($recs[$i]["rcptdtl_type"] == "op") {
		echo "Open Payment";
	} else if ($recs[$i]["rcptdtl_type"] == "cm") {
		echo "Credit Memo";
	} else if ($recs[$i]["rcptdtl_type"] == "dc") {
		echo "Discount";
	} else if ($recs[$i]["rcptdtl_type"] == "aw") {
		echo "Allowance";
	} else {
		echo "Invoice";
	}
*/
?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= number_format($recs[$i]["rcptdtl_amt"],2,".",",") ?>
                            </td>
                            <td width="45%"> 
                              <?= $recs[$i]["rcptdtl_desc"] ?>
                            </td>
                            <td width="10%"> 
<?php
	if ($recs[$i]["rcptdtl_type"] == "op") {
		echo "NA &nbsp;";
	} else {
		echo "Apply &nbsp;";
	}
?>
                            </td>
                          </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
						  <tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b>Empty_1!</b>
                            </td>
                          </tr>
<?php
	}
?>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&rcpt_id=$rcpt_id" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&rcpt_id=$rcpt_id" ?>">&lt;Prev</a> &nbsp; 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&rcpt_id=$rcpt_id" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&rcpt_id=$rcpt_id" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
