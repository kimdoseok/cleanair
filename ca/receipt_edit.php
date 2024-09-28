<?php
	include_once("class/class.datex.php");
	include_once("class/class.rcptdtls.php");
	include_once("class/class.formutils.php");
	include_once("class/class.vendors.php");
	$x = new Datex();
	$f = new FormUtil();
	$s = new Receipt();
	$d = new RcptDtls();

	if ($_SESSION[receipt_edit]) {
		$sls = $_SESSION[receipt_edit];
		if ($sls["rcpt_id"] != $rcpt_id) $sls = $s->getReceipt($rcpt_id);
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	} else if ($sls = $s->getReceipt($rcpt_id)) {
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v; 
	}
	$_SESSION[receipt_edit] = $sls;

	if ($_SESSION[rcptdtls_edit]) {
		$recs = $_SESSION[rcptdtls_edit];
		if ($recs[0][rcptdtl_rcpt_id] != $rcpt_id && $rcptdtl_del!=1) $recs = $d->getRcptDtlsList($rcpt_id);
	} else {
		if ($_SESSION[rcptdtl_del]!=1) $recs = $d->getRcptDtlsList($rcpt_id);
	}
	$_SESSION[rcptdtls_edit] = $recs;

//	for ($i=0;$i<count($recs);$i++) $applied += $recs[$i]["rcptdtl_amt"];
	$applied = 0;
	$rcptdtl_disc_amt = 0;
	$rcptdtl_op_amt = 0;
	$rcptdtl_cm_amt = 0;
	$rcptdtl_aw_amt = 0;
	for ($i=0;$i<count($recs);$i++) {
		if ($recs[$i]["rcptdtl_type"]=="ar") $applied += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="fc") $applied += $recs[$i]["rcptdtl_amt"];
		else $applied += $recs[$i]["rcptdtl_amt"];
	}
	$remained = $rcpt_amt - $applied;
	$rcpt_disc_amt = $rcptdtl_dc_amt;

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
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
//		if (f.rcpt_id.value == "") {
//			window.alert("Receiptment number should not be blank!");
//		} else {
			f.cmd.value = "rcpt_sess_add";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
//		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "rcpt_sess_del";
		<?= ($ty=="e")?"f.ht.value = 'e';":"" ?>
		f.method = "get";
		f.action = "ar_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.remained.value != 0) {
			window.alert("Remaining value should be 0 !");
		} else {
			f.cmd.value = "rcpt_edit";
			<?= ($ty=="e")?"f.ht.value = 'e';":"" ?>
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function bouncedCheck() {
		var f = document.forms[0];
		if (f.remained.value != 0) {
			window.alert("Remaining value should be 0 !");
		} else {
			if (confirm("Are you sure to make this check bounced?")) {
				window.location='receipt_proc.php?cmd=rcpt_bounced_check&rcpt_id=<?= $rcpt_id ?>';
			}
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "rcpt_clear_sess_edit";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function UpdateForm() {
		var f = document.forms[0];
		f.ht.value = "<?= $ty ?>";
		f.action = "ar_proc.php";
		f.cmd.value = "rcpt_update_sess_add";
		f.method = "post";
		f.submit();
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
							<?= $f->fillHidden("ht","") ?>
							<?= $f->fillHidden("rcpt_id",$rcpt_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Cash Receipt</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="8"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font>
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
                              <?= $f->fillTextBox("rcpt_po_no", $rcpt_po_no, 20, 32, "inbox") ?>
                            </td>
                          </tr>
-->
                          <tr> 
                            <td width="97" bgcolor="silver">Customer:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("rcpt_cust_code", $rcpt_cust_code, 20, 32, "inbox", "onChange='UpdateForm()'") ?>
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
                                  <td align="right" bgcolor="silver">C/S/Z/C</td>
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
-->
<?= $f->fillHidden("rcpt_id",$rcpt_id) ?>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Check#:</td>
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
                              <?= $f->fillTextBox("rcpt_amt", sprintf("%0.2f", $rcpt_amt), 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Discount:</td>
                            <td> 
                              <?= $f->fillTextBoxRO("rcpt_disc_amt", sprintf("%0.2f", $rcpt_disc_amt), 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">Date:</td>
                            <td> 
                              <?= $f->fillTextBox("rcpt_date", empty($rcpt_date)?$x->getToday():$rcpt_date, 20,32, "inbox") ?>
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
						</table>
					  
					    
    </td>
                    </tr>
                    <tr> 
                      <td colspan="1" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add Detail</A></FONT></td>
                      <td colspan="1" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="40%" align="center">&nbsp;</td>
                           <td width="60%" align="center">
                            <input type="button" name="Submit32" value="Update" onClick="UpdateForm()">
							<input type="button" name="Submit3222" value="Record" onClick="SaveToDB()"> 
                            <input type="button" name="Submit22222" value="Clear" onClick="clearSess()">
                            <input type="button" name="Submit22" value="Bounced" onClick="bouncedCheck()">
						   </td>
                         </tr>
                      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">#</font></th>
                            <th bgcolor="gray"><font color="white">Inv#</font></th>
                            <th bgcolor="gray"><font color="white">Acct#</font></th>
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
                              <a href="receipt_details.php?ty=e&rcpt_id=<?= $rcpt_id ?>&ht=e&did=<?= $i ?>"><?= $i+1 ?></a>
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
                            <td width="55%"> 
                              <?= $recs[$i]["rcptdtl_desc"] ?>
                            </td>
                            <td width="5%"> 
                              <a href="ar_proc.php?ty=e&cmd=rcpt_detail_sess_del&rcpt_id=<?= $rcpt_id ?>&did=<?= $i ?>">Del</a>
                            </td>
                          </tr>

<?php
		}
	}
	if (empty($recs[0])) {
?>
						<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b>Empty!</b>
                            </td>
                          </tr>
<?php
	}
?>
                        </table></td>
                    </tr>
                  </table>
<br>
<?php
//	print_r($_SESSION);
?>
