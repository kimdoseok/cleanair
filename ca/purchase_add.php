<?php
	include_once("class/class.datex.php");
	$d = new Datex();
	include_once("class/class.formutils.php");
	$f = new FormUtil();
	include_once("class/class.vendors.php");
	include_once("class/class.customers.php");
	include_once("class/register_globals.php");

	if (empty($purch_ship_code)) $purch_ship_code = 'CLEANA01';
	$vend_code = $purch_vend_code;
	$cust_code = $purch_cust_code;
	$ship_code = $purch_ship_code;
	if ($sls = $_SESSION["purchases_add"] ?? array()) foreach($sls as $k => $v) $$k = stripslashes($v);
	$not_found_vend = 0;
  $not_found_cust = 0;
	$not_found_ship = 0;
  $vend_code_old = $purch_vend_code;
	$cust_code_old = $purch_cust_code;
	$cust_ship_old = $purch_ship_code;
	$v = new Vends();
	$u = new Custs();
	if (!empty($purch_vend_code)) {
		if ($varr = $v->getVends($purch_vend_code)) {
			$purch_vend_code	= strtoupper($purch_vend_code);
			if (strtoupper($sls["purch_vend_code"]) != $purch_vend_code) {
				$purch_vend_name	= $varr["vend_name"];
				$purch_vend_addr1	= $varr["vend_addr1"];
				$purch_vend_addr2	= $varr["vend_addr2"];
				$purch_vend_addr3	= $varr["vend_addr3"];
				$purch_vend_city	= $varr["vend_city"];
				$purch_vend_state	= $varr["vend_state"];
				$purch_vend_country	= $varr["vend_country"];
				$purch_vend_zip		= $varr["vend_zip"];
				$purch_vend_tel		= $varr["vend_tel"];
				$vend_balance	= $varr["vend_balance"];
				$purch_taxrate	= $varr["vend_taxrate"];
				$purch_vend_code_old = $purch_vend_code;
				$not_found_vend = 0;
			}
		} else {
			$not_found_vend = 1;
			$purch_vend_code	= $purch_vend_code_old;
		}
	}

	if (!empty($purch_cust_code)) {
		$uarr = $u->getCusts($purch_cust_code);
		if ($uarr) {
			$purch_cust_code	= strtoupper($purch_cust_code);
			if (strtoupper($sls["purch_cust_code"]) != $purch_cust_code) {
				$purch_cust_name	= $uarr["cust_name"];
				$purch_cust_addr1	= $uarr["cust_addr1"];
				$purch_cust_addr2	= $uarr["cust_addr2"];
				$purch_cust_addr3	= $uarr["cust_addr3"];
				$purch_cust_city	= $uarr["cust_city"];
				$purch_cust_state	= $uarr["cust_state"];
				$purch_cust_country	= $uarr["cust_country"];
				$purch_cust_zip		= $uarr["cust_zip"];
				$purch_cust_tel		= $uarr["cust_tel"];
				$purch_cust_code_old = $purch_cust_code;
				$not_found_cust = 0;
			}
		} else {
			$not_found_cust = 1;
			$purch_cust_code	= $purch_cust_code_old;
		}
	}

	if (!empty($purch_ship_code)) {
		if ($uarr = $u->getCusts($purch_ship_code)) {
			$purch_ship_code	= strtoupper($purch_ship_code);
			if (strtoupper($sls["purch_ship_code"] ?? "") != $purch_ship_code) {
				$purch_ship_name	= $uarr["cust_name"];
				$purch_ship_addr1	= $uarr["cust_addr1"];
				$purch_ship_addr2	= $uarr["cust_addr2"];
				$purch_ship_addr3	= $uarr["cust_addr3"];
				$purch_ship_city	= $uarr["cust_city"];
				$purch_ship_state	= $uarr["cust_state"];
				$purch_ship_country	= $uarr["cust_country"];
				$purch_ship_zip		= $uarr["cust_zip"];
				$purch_ship_tel		= $uarr["cust_tel"];
				$purch_ship_code_old = $purch_ship_code;
				$not_found_ship = 0;
			}
		} else {
			$not_found_ship = 1;
			$purch_ship_code	= $purch_ship_code_old;
		}
	}

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.purch_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
		} else {
			f.cmd.value = "purch_sess_add";
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "purch_sess_del";
		f.method = "get";
		f.action = "ap_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.purch_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
		} else {
			f.cmd.value = "purch_add";
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "purch_clear_sess_add";
		f.method = "post";
		f.action = "ap_proc.php";
		f.submit();
	}

	function UpdateForm() {
		var f = document.forms[0];
		f.action = "ap_proc.php";
		f.cmd.value = "purch_update_sess_add";
		f.method = "post";
		f.submit();
	}

	function calcTotal() {
		var f = document.forms[0];
		var t = parseFloat(f.purch_tax_amt.value);
		var r = parseFloat(f.purch_freight_amt.value);
		var s = parseFloat(f.purch_amt.value);
		f.totalamount.value = Math.round((t+r+s)*100)/100 ;
	}

	function calcTax() {
		var f = document.forms[0];
		var x = parseFloat(f.taxtotal.value);
		var a = parseFloat(f.purch_taxrate.value);
		f.purch_tax_amt.value = Math.round(x*a)/100;
		var t = parseFloat(f.purch_tax_amt.value);
		calcTotal();
	}

	function editVendor() {
		var f = document.forms[0];
		window.location= 'vendors.php?ty=e&vend_code='+f.purch_vend_code.value;
	}

<?php
	if ($not_found_vend == 1) {
		echo "	openVendBrowseFilter('purch_vend_code', '$vend_code');\n";
	}
	if ($not_found_cust == 1) {
		echo "	openCustBrowseFilter('purch_cust_code', '$cust_code', 'c');\n";
	}
	if ($not_found_ship == 1) {
		echo "	openCustBrowseFilter('purch_ship_code', '$ship_code', 's');\n";
	}
?>

//-->
</SCRIPT>
<?php
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ap_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ty","a") ?>
							<?= $f->fillHidden("purch_vend_code_old",$purch_vend_code_old) ?>
                    <tr> 
                      <td colspan="8" align="right"><strong>New Purchase Order</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver">Vendor:</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("purch_vend_code", $purch_vend_code, 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openVendBrowse('purch_vend_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Vendor Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_name", $purch_vend_name, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Vendor Addr.</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_addr1", $purch_vend_addr1, 32, 32, "inbox") ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_addr2", $purch_vend_addr2, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_addr3", $purch_vend_addr3, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_city", $purch_vend_city, 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_vend_state", $purch_vend_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_vend_zip", $purch_vend_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_vend_country", $purch_vend_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_tel", $purch_vend_tel, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
								<tr> 
                                  <td width="25%" align="right" bgcolor="silver">Customer #</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("purch_cust_code", $purch_cust_code, 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openCustBrowse('purch_cust_code', 'c')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Cust. Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_name", $purch_cust_name, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Cust. Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_addr1", $purch_cust_addr1, 32, 32, "inbox") ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_addr2", $purch_cust_addr2, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_addr3", $purch_cust_addr3, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_city", $purch_cust_city, 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_cust_state", $purch_cust_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_cust_zip", $purch_cust_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_cust_country", $purch_cust_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_tel", $purch_cust_tel, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
								<tr> 
                                  <td width="25%" align="right" bgcolor="silver">Ship Code</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("purch_ship_code", $purch_ship_code, 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openCustBrowse('purch_ship_code', 's')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_name", $purch_ship_name, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_addr1", $purch_ship_addr1, 32, 32, "inbox") ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_addr2", $purch_ship_addr2, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_addr3", $purch_ship_addr3, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_city", $purch_ship_city, 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_ship_state", $purch_ship_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_ship_zip", $purch_ship_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_ship_country", $purch_ship_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_tel", $purch_ship_tel, 32, 32, "inbox") ?>
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
                                  <td width="70%">New PO
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Date</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_date", (empty($purch_date))?date("m/d/Y"):$purch_date, 16, 32, "inbox") ?>
									<a href="javascript:openCalendar('purch_date')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("purch_user_code", (empty($purch_user_code))?$_SERVER["PHP_AUTH_USER"]:$purch_user_code, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Expected</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_prom_date", $purch_prom_date, 16, 32, "inbox") ?>
									<a href="javascript:openCalendar('purch_prom_date')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Custom?</td>
                                  <td> 
								    <INPUT TYPE="checkbox" NAME="purch_custom_po" VALUE="t" <?= ($purch_custom_po!="f")?"checked":"" ?>>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Confirm?</td>
                                  <td> 
								    <INPUT TYPE="checkbox" NAME="purch_need_confirm" VALUE="t" <?= ($purch_need_confirm!="f")?"checked":"" ?>>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Sample?</td>
                                  <td> 
								    <INPUT TYPE="checkbox" NAME="purch_sample_included" VALUE="t" <?= ($purch_sample_included!="f")?"checked":"" ?>>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Complete?</td>
                                  <td> 
								    <INPUT TYPE="checkbox" NAME="purch_completed" VALUE="t" <?= ($purch_completed!="f")?"":"checked" ?>>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Cmpl Date</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_completed_date", $purch_completed_date, 16, 32, "inbox") ?>
									<a href="javascript:openCalendar('purch_completed_date')">C</a>
								  </td>
                                </tr>
								<tr> 
								  <td width="24%" bgcolor="silver" valign="top" align="right">Comment</td>
								  <td width="76%">
								    <TEXTAREA NAME="["purch_comnt"]" ROWS="10" COLS="20"><?= $purch_comnt ?></TEXTAREA>
								  </td>
								</tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="50%" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()"><?= $label[$lang]["Add_Detail"] ?? "" ?></A></FONT></td>
                           <td width="50%" align="center">
                            <input type="button" name="Submit32" value="<?= $label[$lang]["Update"] ?>" onClick="UpdateForm()">
								   <input type="button" name="Submit3222" value="Record" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="<?= $label[$lang]["Clear"] ?>" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Item"] ?></font></th>
                            <th bgcolor="gray" width="7%"><font color="white"><?= $label[$lang]["Qty"] ?></font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Unit</font></th>
                            <th bgcolor="gray" width="3%"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	include_once("class/class.purdtls.php");

	$t = new PurDtls();
  if (array_key_exists("purdtls_add",$_SESSION)) {
    $recs = $_SESSION["purdtls_add"];
  } else {
    $recs = array();
  }
	$subtotal = 0;
	$taxtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              <a href="purchase_details.php?ty=e&ht=a&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="13%"> 
                              <?= $recs[$i]["purdtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= stripslashes($recs[$i]["purdtl_item_desc"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                            <td width="5%" align="right"> 
                              <?= $recs[$i]["purdtl_unit"] ?>
                            </td>
                            <td width="3%" align="center"> 
                              <a href="ap_proc.php?cmd=purch_detail_sess_del&ty=a&did=<?= $i ?>"><?= $label[$lang]["Del"] ?></a>
                            </td>
                          </tr>
<?php
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              &nbsp;
                            </td>
                            <td colspan="7" align="left"> 
                              <?= $recs[$i]["purdtl_comnt"] ?>
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
    </table>
  </td>
                    </tr>
<?php
  settype($purch_tax_amt,"float");
  settype($purch_freight_amt,"float");
  settype($subtotal,"float");

	$purch_tax_amt = $taxtotal*$purch_taxrate/100;
?>
                    <tr> 
                      
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("purch_shipvia", $purch_shipvia, 20, 32, "inbox") ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("purch_taxrate", $purch_taxrate+0, 20, 32, "inbox", " onChange='calcTax()'") ?>%
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Sub_Total"] ?></td>
              <td>
                <?= sprintf("%0.2f", $subtotal) ?>
				<?= $f->fillHidden("purch_amt", $subtotal) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td>
                <?= $f->fillTextBox("purch_freight_amt", sprintf("%0.2f", $purch_freight_amt), 10, 16, "inbox") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("purch_tax_amt", sprintf("%0.2f", $purch_tax_amt), 10, 16, "inbox") ?>
				 <?=  $f->fillHidden("taxtotal",$taxtotal) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= $f->fillTextBoxRO("totalamount", sprintf("%0.2f", $purch_tax_amt+$purch_freight_amt+$subtotal), 10, 16, "inbox") ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                    </tr>

						  </form>
                  </table>
