<?php
	include_once("class/class.datex.php");
	include_once("class/class.rcptdtls.php");
	include_once("class/class.formutils.php");
	include_once("class/class.customers.php");
	$d = new Datex();
	$f = new FormUtil();
	$applied = 0;
	$remained = 0;
	$rcpt_amt = 0;

	if (session_is_registered("receipt_add")) if ($disb = $_SESSION[receipt_add]) foreach($disb as $k => $v) $$k = $v;

	$not_found_cust = 0;
	$rcpt_cust_code_old = $rcpt_cust_code;
	if (!empty($_POST["rcpt_cust_code"])) $sale_cust_code = $_POST["rcpt_cust_code"];
	if (!empty($_GET["rcpt_cust_code"])) $sale_cust_code = $_GET["rcpt_cust_code"];
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
			$cust_balance = $varr["cust_balance"];
			$rcpt_cust_code_old = $rcpt_cust_code;
			$not_found_cust = 0;
		} else {
			$not_found_cust = 1;
			$rcpt_cust_code	= $rcpt_cust_code_old;
		}
	}


	if ($ht=="e") $recs = $_SESSION[rcptdtls_edit];
	else $recs = $_SESSION[rcptdtls_add];
//	for ($i=0;$i<count($recs);$i++) $applied += $recs[$i]["rcptdtl_amt"];
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
		else if ($recs[$i]["rcptdtl_type"]=="fc") $applied += $recs[$i]["rcptdtl_amt"];
		else $applied += $recs[$i]["rcptdtl_amt"];
	}
	$remained = $rcpt_amt - $applied;
	$rcpt_disc_amt = $rcptdtl_dc_amt;
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.rcpt_cust_code.value == "") {
			window.alert("Customer Code should not be blank!");
//		} else if (f.rcpt_id.value == "") {
//			window.alert("Receipt id should not be blank!");
		} else {
			f.cmd.value = "rcpt_sess_add";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "rcpt_detail_sess_del";
		f.method = "get";
		f.action = "ar_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.rcpt_cust_code.value == "") {
			window.alert("Customer Code should not be blank!");
		} else if (f.remained.value != 0) {
			window.alert("Remaining value should be 0 !");
//		} else if (f.rcpt_id.value == "") {
//			window.alert("Purchase id should not be blank!");
		} else {
			f.cmd.value = "rcpt_add";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "rcpt_clear_sess_add";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function updateForm() {
		var f = document.forms[0];
		f.action = "ar_proc.php";
		f.cmd.value = "rcpt_update_sess_add";
		f.method = "post";
		f.submit();
	}

	function setRemained() {
		var f = document.forms[0];
		f.remained.value = parseFloat(f.rcpt_amt.value) - parseFloat(f.applied.value);
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_cust == 1) echo "	openCustBrowseFilter('rcpt_cust_code', '$rcpt_cust_code');";
?>
//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ar_proc.php">
							<?= $f->fillHidden("cmd","") ?>
                    <tr> 
                      <td colspan="8" align="right"><strong>New Open Payments</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New_1</a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List_1</a> | </font>
                      </td>
                    </tr>
                    <tr> 
                      
      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
<!--
						  <tr> 
                            <td width="97" bgcolor="silver">PO #:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("rcpt_po_no", $rcpt_po_no, 20, 32, "inbox") ?>
                            </td>
                          </tr>
-->
                          <tr> 
                            <td width="97" bgcolor="silver" align="right">Customer:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("rcpt_cust_code", $rcpt_cust_code, 20, 32, "inbox", "onChange='updateForm()'") ?>
							  <A HREF="javascript:openCustBrowse('rcpt_cust_code')"><font size="2">Lookup</font></A>
                            </td>
                          </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Name:</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("cust_name", $cust_name, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("cust_addr1", $cust_addr1, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("cust_addr2", $cust_addr2, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("cust_addr3", $cust_addr3, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">C_S_Z_C</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("cust_city", $cust_city, 15, 32, "inbox") ?>
                                    <?= $f->fillTextBoxRO("cust_state", $cust_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBoxRO("cust_zip", $cust_zip, 5, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Balance:</td>
                                  <td> 
		                              <?= $f->fillTextBoxRO("cust_balance", sprintf("%0.2f",$cust_balance) , 20, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Comment:</td>
                                  <td> 
		                              <?= $f->fillTextBox("rcpt_comment", $rcpt_comment, 32, 250, "inbox") ?>
                                  </td>
                                </tr>
              </table></td>
            <td width="1%">&nbsp;</td>
            <td width="37%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
<!--
                          <tr> 
                            <td width="97" bgcolor="silver">Rcpt #:</td>
                            <td width="308"> 
                              <?= $f->fillTextBoxRO("rcpt_id", $rcpt_id, 20, 32, "inbox") ?>
                            </td>
                          </tr>
--><?= $f->fillHidden("rcpt_id", $rcpt_id) ?>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Check_no:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("rcpt_check_no", $rcpt_check_no, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Pay Type:</td>
                            <td width="308"> 
							  <SELECT NAME="rcpt_type">
								<OPTION VALUE="ch" <?= ($rcpt_type=="ch")?"SELECTED":"" ?>>Check
							    <OPTION VALUE="ca" <?= ($rcpt_type=="ca")?"SELECTED":"" ?>>Cash
								<OPTION VALUE="cc" <?= ($rcpt_type=="cc")?"SELECTED":"" ?>>Credit Card
								<OPTION VALUE="dc" <?= ($rcpt_type=="dc")?"SELECTED":"" ?>>Discount Apply
								<OPTION VALUE="bc" <?= ($rcpt_type=="bc")?"SELECTED":"" ?>>Bounced Check
								<OPTION VALUE="ot" <?= ($rcpt_type=="ot")?"SELECTED":"" ?>>Other
							  </SELECT>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Amount:</td>
                            <td> 
                              <?= $f->fillTextBox("rcpt_amt", sprintf("%0.2f",$rcpt_amt) , 20, 32, "inbox", "onChange=setRemained()") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Discount:</td>
                            <td> 
                              <?= $f->fillTextBoxRO("rcpt_disc_amt", sprintf("%0.2f",$rcpt_disc_amt) , 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Date_1:</td>
                            <td> 
                              <?= $f->fillTextBox("rcpt_date", empty($rcpt_date)?$d->getToday():$rcpt_date, 20,32, "inbox") ?>
							  <a href="javascript:openCalendar('rcpt_date')">C</a>

                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">User_no&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBoxRO("rcpt_user_code", $_SERVER["PHP_AUTH_USER"], 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Applied:</td>
                            <td width="308"> 
                              <?= $f->fillTextBoxRO("applied", sprintf("%0.2f",$applied), 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Remained:</td>
                            <td> 
                              <?= $f->fillTextBoxRO("remained", sprintf("%0.2f",$remained) , 20, 32, "inbox") ?>
                            </td>
                          </tr>
              </table></td>
          </tr>
        </table> </td>
                    </tr>
                    <tr> 
					  <td colspan="8" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add_Detail</A></FONT></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">No</font></th>
                            <th bgcolor="gray"><font color="white">Inv_no</font></th>
                            <th bgcolor="gray"><font color="white">Acct_no</font></th>
                            <th bgcolor="gray"><font color="white">Type</font></th>
                            <th bgcolor="gray"><font color="white">Amount</font></th>
                            <th bgcolor="gray"><font color="white">Description</font></th>
                            <th bgcolor="gray"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              <a href="receipt_details.php?ty=e&ht=a&did=<?= $i ?>"><?= $i+1 ?></a>
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
                              <?= number_format($recs[$i]["rcptdtl_amt"],2,",",".") ?>
                            </td>
                            <td width="50%"> 
                              <?= $recs[$i]["rcptdtl_desc"] ?>
                            </td>
                            <td width="5%"> 
                              <a href="ar_proc.php?ty=a&cmd=rcpt_detail_sess_del&rcpt_id=<?= $rcpt_id ?>&did=<?= $i ?>">Del</a>
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
					</table> </td>
					</tr>
                    <tr> 
                      <td colspan="8" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="64%" align="center">&nbsp;</td>
                           <td width="36%" align="center">
                            <input type="button" name="Submit32" value="Update" onClick="UpdateForm()">
								   <input type="button" name="Submit3222" value="Record" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="Clear" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
                    </tr>
						  </form>
                  </table>
