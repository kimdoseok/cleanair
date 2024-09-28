<?php
	include_once("class/class.datex.php");
	$d = new Datex();
	include_once("class/class.formutils.php");
	$f = new FormUtil();
	include_once("class/class.customers.php");

	$cust_code = $cmemo_cust_code;
	if ($_SESSION[$cmemo_add] && $sls = $_SESSION[$cmemo_add]) foreach($sls as $k => $v) $$k = $v;
	$not_found_cust = 0;
	$cust_code_old = $cmemo_cust_code;
	if (!empty($_POST["cmemo_cust_code"])) $cmemo_cust_code = $_POST["cmemo_cust_code"];
	if (!empty($_GET["cmemo_cust_code"])) $cmemo_cust_code = $_GET["cmemo_cust_code"];
	if (!empty($cmemo_cust_code)) {
		$v = new Custs();
		if ($varr = $v->getCusts($cmemo_cust_code)) {
			$cmemo_cust_code	= strtoupper($cmemo_cust_code);
			if (strtoupper($sls["cmemo_cust_code"]) != $cmemo_cust_code) {
				$cmemo_name		= $varr["cust_name"];
				$cmemo_addr1	= $varr["cust_addr1"];
				$cmemo_addr2	= $varr["cust_addr2"];
				$cmemo_addr3	= $varr["cust_addr3"];
				$cmemo_city		= $varr["cust_city"];
				$cmemo_state	= $varr["cust_state"];
				$cmemo_country	= $varr["cust_country"];
				$cmemo_zip		= $varr["cust_zip"];
				$x = new TaxRates();
				$xarr = $x->getTaxrates($varr["cust_tax_code"]);
				$cmemo_taxrate = $xarr["taxrate_pct"];
				$cmemo_cust_code_old = $cmemo_cust_code;
				$not_found_cust = 0;
			}
		} else {
			$not_found_cust = 1;
			$cmemo_cust_code	= $cmemo_cust_code_old;
		}
	}

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.cmemo_cust_code.value == "") {
			window.alert("Customer Code should not be blank!");
		} else {
			f.cmd.value = "cmemo_sess_add";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "cmemo_sess_del";
		f.method = "get";
		f.action = "ar_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.cmemo_cust_code.value == "") {
			window.alert("Customer Code should not be blank!");
		} else {
			f.cmd.value = "cmemo_add";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "cmemo_clear_sess_add";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function UpdateForm() {
		var f = document.forms[0];
		f.action = "ar_proc.php";
		f.cmd.value = "cmemo_update_sess_add";
		f.method = "post";
		f.submit();
	}

	function calcTotal() {
		var f = document.forms[0];
		var t = parseFloat(f.cmemo_tax_amt.value);
		var r = parseFloat(f.cmemo_freight_amt.value);
		var s = parseFloat(f.cmemo_amt.value);
		f.totalamount.value = Math.round((t+r+s)*100)/100 ;
	}

	function calcTax() {
		var f = document.forms[0];
		var x = parseFloat(f.taxtotal.value);
		var a = parseFloat(f.cmemo_taxrate.value);
		f.cmemo_tax_amt.value = Math.round(x*a)/100;
		var t = parseFloat(f.cmemo_tax_amt.value);
		calcTotal();
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_cust == 1) echo "	openCustBrowseFilter('cmemo_cust_code', '$cust_code');";
?>
//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ar_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ty","a") ?>
							<?= $f->fillHidden("cmemo_cust_code_old",$cmemo_cust_code_old) ?>
                    <tr> 
                      <td colspan="8" align="right"><strong>New Credit Memo</strong></td>
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
                            <td width="62%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver">Customer:</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("cmemo_cust_code", $cmemo_cust_code, 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openCustBrowse('cmemo_cust_code')"><font size="2">Lookup</font></A>
                                  </td>
                                </tr>
<?php
	if ($not_found_cust != 1) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	setCursor('cmemo_cust_code');
//-->
</SCRIPT>
<?php
	}
?>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_name", stripslashes($cmemo_name), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_addr1", stripslashes($cmemo_addr1), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_addr2", stripslashes($cmemo_addr2), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_addr3", stripslashes($cmemo_addr3), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">City/State/Zip</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_city", stripslashes($cmemo_city), 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("cmemo_state", $cmemo_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("cmemo_zip", $cmemo_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("cmemo_country", $cmemo_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
<!--

                                <tr> 
                                  <td align="right" bgcolor="silver">Sales#&nbsp;</td>
                                  <td> <?= $f->fillTextBox("cmemo_code", $cmemo_code, 20, 32, "inbox") ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Cust_PO#</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_cust_po", $cmemo_cust_po, 20, 32, "inbox") ?>
                                  </td>
                                </tr>
-->
								<tr> 
                                  <td align="right" bgcolor="silver">Date&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_date", (empty($cmemo_date))?$d->getToday():$cmemo_date, 20, 32, "inbox") ?>
									<a href="javascript:openCalendar('cmemo_date')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User#&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_user_code", $_SERVER["PHP_AUTH_USER"], 20, 32, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="50%" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add_Detail</A></FONT></td>
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
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                            <th bgcolor="gray" width="2%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	include_once("class/class.cmemodtls.php");

	$t = new CmemoDtl();
	$recs = $_SESSION[$cmemodtl_add];
	$subtotal = 0;
	$taxtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["cmemodtl_cost"]*$recs[$i]["cmemodtl_qty"]);
			if ($recs[$i]["cmemodtl_taxable"]=="t") $taxtotal += $recs[$i]["cmemodtl_cost"]*$recs[$i]["cmemodtl_qty"];
?>
                            <td width="5%" align="center"> 
                              <a href="cmemo_details.php?ty=e&ht=a&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="15%"> 
                              <?= $recs[$i]["cmemodtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= stripslashes($recs[$i]["cmemodtl_item_desc"]) ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["cmemodtl_cost"])+0 ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["cmemodtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["cmemodtl_cost"]*$recs[$i]["cmemodtl_qty"]) ?>
                            </td>
                            <td width="2%" align="center"> 
                              <?= ($recs[$i]["cmemodtl_taxable"]=="t")?"X":"&nbsp;" ?>
                            </td>
                            <td width="5%" align="center"> 
                              <a href="ar_proc.php?cmd=cmemo_detail_sess_del&ty=a&did=<?= $i ?>">Del</a>
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
	 
	if (empty($cmemo_taxrate)) $cmemo_tax_amt = $taxtotal*$xarr["taxrate_pct"]/100;
	else $cmemo_tax_amt = $taxtotal*$cmemo_taxrate/100;

?>
                    </table></td></tr>
					<tr> 
                      
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Ship_Via&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("cmemo_shipvia", $cmemo_shipvia, 20, 32, "inbox") ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("cmemo_taxrate", $cmemo_taxrate+0, 20, 32, "inbox", " onChange='calcTax()'") ?>%
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Comment&nbsp;</td>
              <td>
                <?= $f->fillTextareaBox("cmemo_comnt", $cmemo_comnt, 40, 2, "inbox") ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Sub_Total</td>
              <td>
                <?= sprintf("%0.2f", $subtotal) ?>
				<?= $f->fillHidden("cmemo_amt", $subtotal) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Freight</td>
              <td>
                <?= $f->fillTextBox("cmemo_freight_amt", sprintf("%0.2f", $cmemo_freight_amt), 10, 16, "inbox", " onChange='calcTotal()'") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Tax&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("cmemo_tax_amt", sprintf("%0.2f", $cmemo_tax_amt), 10, 16, "inbox") ?>
				 <?=  $f->fillHidden("taxtotal",$taxtotal) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Total_Amount</td>
              <td>
                <?= $f->fillTextBoxRO("totalamount", sprintf("%0.2f", $cmemo_tax_amt+$cmemo_freight_amt+$subtotal), 10, 16, "inbox") ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                    </tr>
						  </form>
                  </table>
