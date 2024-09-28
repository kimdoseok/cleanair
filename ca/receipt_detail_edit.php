<?php
	$f = new FormUtil();

	if ($recs[$did]) foreach ($recs[$did] as $k => $v) $$k = $v;

	if (!empty($rcpt_cust_code)) {
		$v = new Custs();
		if ($varr = $v->getCusts($rcpt_cust_code)) {
			$rcpt_cust_code	= strtoupper($rcpt_cust_code);
			$cust_name = $varr["cust_name"];
			$cust_addr1 = $varr["cust_addr1"];
			$cust_addr2 = $varr["cust_addr2"];
			$cust_addr3 = $varr["cust_addr3"];
			$cust_city = $varr["cust_city"];
			$cust_state = $varr["cust_state"];
			$cust_zip = $varr["cust_zip"];
			$rcpt_cust_code_old = $rcpt_cust_code;
			$not_found_cust = 0;
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
		f.action = "ar_proc.php";
		f.cmd.value = "rcpt_detail_sess_edit";
		f.method = "post";
		f.submit();
	}
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="ar_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="">
						<INPUT TYPE="hidden" name="rcptdtl_rcpt_id" value="<?= $rcptdtl_rcpt_id ?>">
						<?= $f->fillHidden("ht", $ht) ?>						
						<?= $f->fillHidden("did", $did) ?>
						<?= $f->fillHidden("rcpt_id", $rcpt_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][Edit_Cash_Receipt_Detail] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo "receipt.php?ty=$ht&rcpt_id=$rcpt_id" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Reference No:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("rcptdtl_pick_id", $rcptdtl_pick_id, 20, 32, "inbox") ?>
					<A HREF="javascript:openPickBrowse('rcptdtl_pick_id')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Acct_no"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("rcptdtl_acct_code", $rcptdtl_acct_code, 20, 32, "inbox") ?>
					<A HREF="javascript:openAcctBrowse('rcptdtl_acct_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Type</td>
                  <td> 
				  <SELECT NAME="rcptdtl_type">
				    <OPTION VALUE="ar" <?= ($rcptdtl_type!="ar")?"CHECKED":"" ?>>Account Receivable
				    <OPTION VALUE="dc" <?= ($rcptdtl_type!="dc")?"CHECKED":"" ?>>Discount
				    <OPTION VALUE="fc" <?= ($rcptdtl_type!="ar")?"CHECKED":"" ?>>Financial Charge
				  </SELECT>
                  </td>
                </tr>
				<tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("rcptdtl_amt", $rcptdtl_amt, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Description"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("rcptdtl_desc", $rcptdtl_desc, 50, 250, "inbox") ?>
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
                              <input type="button" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><hr></td>
                    </tr>
					<tr>
					  <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                          <tr> 
                            <td width="97" bgcolor="silver" align="right">Customer:</td>
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
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $cust_city ?>
                                    <?= $cust_state ?>
                                    <?= $cust_zip ?>
                                  </td>
                                </tr>
			              </table></td>
						<td width="1%">&nbsp;</td>
						<td width="37%"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Check_no"] ?>:</td>
                            <td width="308"> 
                              <?= $rcpt_check_no ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver">Pay Type:</td>
                            <td width="308"> 
<?php
	if ($rcpt_type=="ca") echo "Cash";
	else if ($rcpt_type=="ch") echo "Check";
	else if ($rcpt_type=="op") echo "Open Payment";
	else if ($rcpt_type=="cc") echo "Credit Card";
	else if ($rcpt_type=="ot") echo "Other";
?>
                          </td>
                          </tr>

                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:</td>
                            <td> 
                              <?= number_format($rcpt_amt,2,".",",") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver">Discount:</td>
                            <td> 
                              <?= number_format($rcpt_disc_amt,2,".",",") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver">Date:</td>
                            <td> 
                              <?= $rcpt_date ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $rcpt_user_code ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Applied] ?>:</td>
                            <td width="308"> 
                              <?= number_format($applied,2,".",",") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Remained] ?>:</td>
                            <td> 
                              <?= number_format($remained,2,".",",") ?>
                            </td>
                          </tr>
              </table></td>
						  </tr>
						</table>
					  
					    
    </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang][Inv_no] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Acct_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white">Type</font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
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
                            <td width="13%"> 
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
                            <td width="12%" align="right"> 
                              <?= $recs[$i]["rcptdtl_amt"] ?>
                            </td>
                            <td width="60%"> 
                              <?= $recs[$i]["rcptdtl_desc"] ?>
                            </td>
                          </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
						  <tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
                          </tr>
<?php
	}
?>
                        </table></td>
                    </tr>


						  </form>
                  </table>
