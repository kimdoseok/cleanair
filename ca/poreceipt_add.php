
<?php
	include_once("class/class.datex.php");
	$d = new Datex();
	include_once("class/class.formutils.php");
	$f = new FormUtil();
	include_once("class/class.vendors.php");
	include_once("class/register_globals.php");

	$vend_code = $porcpt_vend_code;
	if ($sls = $_SESSION[$porcpt_add]) foreach($sls as $k => $v) $$k = stripslashes($v);
	$not_found_vend = 0;
	$vend_code_old = $porcpt_vend_code;
	$v = new Vends();
	if (!empty($porcpt_vend_code)) {
		if ($varr = $v->getVends($porcpt_vend_code)) {
			$porcpt_vend_code	= strtoupper($porcpt_vend_code);
			$porcpt_vend_name	= $varr["vend_name"];
			$porcpt_vend_addr1	= $varr["vend_addr1"];
			$porcpt_vend_addr2	= $varr["vend_addr2"];
			$porcpt_vend_addr3	= $varr["vend_addr3"];
			$porcpt_vend_city	= $varr["vend_city"];
			$porcpt_vend_state	= $varr["vend_state"];
			$porcpt_vend_country	= $varr["vend_country"];
			$porcpt_vend_zip		= $varr["vend_zip"];
			$porcpt_vend_tel		= $varr["vend_tel"];
			$vend_balance			= $varr["vend_balance"];
			$porcpt_taxrate			= $varr["vend_taxrate"];
			$porcpt_vend_code_old	= $porcpt_vend_code;
			$not_found_vend = 0;
		} else {
			$not_found_vend = 1;
			$porcpt_vend_code	= $porcpt_vend_code_old;
		}
	}


?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.porcpt_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
		} else {
			f.cmd.value = "porcpt_sess_add";
			f.method = "post";
			f.action = "poreceipt_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "porcpt_sess_del";
		f.method = "get";
		f.action = "poreceipt_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.porcpt_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
		} else {
			f.cmd.value = "porcpt_add";
			f.method = "post";
			f.action = "poreceipt_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "porcpt_clear_sess_add";
		f.method = "post";
		f.action = "poreceipt_proc.php";
		f.submit();
	}

	function UpdateForm() {
		var f = document.forms[0];
		f.action = "poreceipt_proc.php";
		f.cmd.value = "porcpt_update_sess_add";
		f.method = "post";
		f.submit();
	}

	function calcTotal() {
		var f = document.forms[0];
		var t = parseFloat(f.porcpt_tax_amt.value);
		var r = parseFloat(f.porcpt_freight_amt.value);
		var s = parseFloat(f.porcpt_amt.value);
		f.totalamount.value = Math.round((t+r+s)*100)/100 ;
	}

	function calcTax() {
		var f = document.forms[0];
		var x = parseFloat(f.taxtotal.value);
		var a = parseFloat(f.porcpt_taxrate.value);
		f.porcpt_tax_amt.value = Math.round(x*a)/100;
		var t = parseFloat(f.porcpt_tax_amt.value);
		calcTotal();
	}

	function editVendor() {
		var f = document.forms[0];
		window.location= 'vendors.php?ty=e&vend_code='+f.porcpt_vend_code.value;
	}

<?php
	if ($not_found_vend == 1) {
		echo "	openVendBrowseFilter('porcpt_vend_code', '$vend_code');\n";
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
							<?= $f->fillHidden("porcpt_vend_code_old",$porcpt_vend_code_old) ?>
                    <tr> 
                      <td colspan="8" align="right"><strong>New PO Receiving</strong></td>
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
                                    <?= $f->fillTextBox("porcpt_vend_code", $porcpt_vend_code, 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openVendBrowse('porcpt_vend_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Vendor Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("porcpt_vend_name", $porcpt_vend_name, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Vendor Addr.</td>
                                  <td> 
                                    <?= $f->fillTextBox("porcpt_vend_addr1", $porcpt_vend_addr1, 32, 32, "inbox") ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("porcpt_vend_addr2", $porcpt_vend_addr2, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("porcpt_vend_addr3", $porcpt_vend_addr3, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("porcpt_vend_city", $porcpt_vend_city, 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("porcpt_vend_state", $porcpt_vend_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("porcpt_vend_zip", $porcpt_vend_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("porcpt_vend_country", $porcpt_vend_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $f->fillTextBox("porcpt_vend_tel", $porcpt_vend_tel, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Date&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("porcpt_date", (empty($porcpt_date))?date("m/d/Y"):$porcpt_date, 16, 32, "inbox") ?>
									<a href="javascript:openCalendar('porcpt_date')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User#&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("porcpt_user_code", (empty($porcpt_user_code))?$_SERVER["PHP_AUTH_USER"]:$porcpt_user_code, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">PO #&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("porcpt_ponum", $porcpt_ponum, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Vend Inv#&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("porcpt_vend_inv", $porcpt_vend_inv, 16, 32, "inbox") ?>
								  </td>
                                </tr>
								<tr> 
								  <td bgcolor="silver" valign="top" align="right">Ship Via&nbsp;</td>
								  <td>
									<?= $f->fillTextBox("porcpt_shipvia", $porcpt_shipvia, 16, 32, "inbox") ?>
								  </td>
								</tr>
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="50%" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add Detail</A></FONT></td>
                           <td width="50%" align="center">
                            <input type="button" name="Submit32" value="Update" onClick="UpdateForm()">
								   <input type="button" name="Submit3222" value="Record" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="Clear" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Unit</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                            <th bgcolor="gray" width="3%"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	include_once("class/class.purdtls.php");

	$t = new PurDtls();
	$recs = $_SESSION[$porcptdtl_add];
	$subtotal = 0;
	$taxtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              <a href="poreceipt_details.php?ty=e&ht=a&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="10%"> 
                              <?= $recs[$i]["porcptdtl_item_code"] ?>
                            </td>
                            <td width="50%"> 
                              <?= stripslashes($recs[$i]["porcptdtl_item_desc"]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["porcptdtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i][porcptdtl_unit] ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["porcptdtl_cost"] ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= number_format($recs[$i]["porcptdtl_qty"]*$recs[$i]["porcptdtl_cost"],2,".",",") ?>
                            </td>
                            <td width="5%" align="center"> 
                              <a href="poreceipt_proc.php?cmd=porcpt_detail_sess_del&ty=a&did=<?= $i ?>">Del</a>
                            </td>
                          </tr>
<?php
/*
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              &nbsp;
                            </td>
                            <td colspan="7" align="left"> 
                              <?= $recs[$i][porcptdtl_comnt] ?>
                            </td>
                          </tr>
<?php
*/
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
    </table>
  </td>
                    </tr>
<?php
	$porcpt_tax_amt = $taxtotal*$porcpt_taxrate/100;
?>
                    <tr> 
                      
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
			<tr> 
			  <td width="24%" bgcolor="silver" valign="top" align="right">Comment</td>
			  <td width="76%">
			    <TEXTAREA NAME="porcpt_comnt" ROWS="4" COLS="32"><?= $porcpt_comnt ?></TEXTAREA>
			  </td>
			</tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Sub Total&nbsp;</td>
              <td>
                <?= $f->fillTextBoxRO("porcpt_amt", sprintf("%0.2f", $porcpt_amt), 10, 16, "inbox") ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Freight&nbsp;</td>
              <td>
                <?= $f->fillTextBox("porcpt_freight_amt", sprintf("%0.2f", $porcpt_freight_amt), 10, 16, "inbox") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Tax&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("porcpt_tax_amt", sprintf("%0.2f", $porcpt_tax_amt), 10, 16, "inbox") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Total Amount&nbsp;</td>
              <td>
                <?= $f->fillTextBoxRO("totalamount", sprintf("%0.2f", $porcpt_tax_amt+$porcpt_freight_amt+$porcpt_amt), 10, 16, "inbox") ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                    </tr>

						  </form>
                  </table>
