<?php
	include_once("class/class.datex.php");
	include_once("class/class.formutils.php");
	include_once("class/class.purchase.php");
	include_once("class/class.purdtls.php");
	include_once("class/register_globals.php");

	$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "purchase_edit");
	if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
	if ($ua_arr["userauth_allow"]!="t") {
		include("permission.php");
		exit;
	}

	$d = new Datex();
	$f = new FormUtil();
	$s = new PoRcpts();
	$sd = new PoRcptDtls();

//	$porcpt_qty = $sd->getPoRcptDtlsHdrSum($porcpt_id);

	$vend_code_old = $porcpt_vend_code;

	if ($_SESSION[$porcpt_edit]) {
		$sls = $_SESSION[$porcpt_edit];
		if ($sls["porcpt_id"] != $porcpt_id) $sls = $s->getPoRcpts($porcpt_id);
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	} else if ($sls = $s->getPoRcpts($porcpt_id)) {
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v; 
	}
	$_SESSION[$porcpt_edit] = $sls;

	$not_found_vend = 0;
	$vend_code_old = $porcpt_vend_code;
	if (!empty($_POST["porcpt_vend_code"])) $porcpt_vend_code = $_POST["porcpt_vend_code"];
	if (!empty($_GET["porcpt_vend_code"])) $porcpt_vend_code = $_GET["porcpt_vend_code"];
	if (!empty($porcpt_vend_code)) {
		$v = new Vends();
		if ($varr = $v->getVends($porcpt_vend_code)) {
			foreach($varr as $k => $v) $$k = $v;
		}
	}

	if (empty($taxtotal)) $taxtotal = 0;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.porcpt_id.value == "") {
			window.alert("PO number should not be blank!");
		} else {
			f.cmd.value = "porcpt_sess_add";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "poreceipt_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "porcpt_sess_del";
		<?= ($ty=="e")?"f.ht.value = 'e';":"" ?>
		f.method = "get";
		f.action = "poreceipt_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.porcpt_id.value == "") {
			window.alert("Purchase number should not be blank!");
		} else {
			f.cmd.value = "porcpt_edit";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "poreceipt_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "porcpt_clear_sess_edit";
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

	function delPoRcpt() {
		if (window.confirm("Are you SURE to delete this purchase order?")) {
			document.location="poreceipt_proc.php?cmd=porcpt_del&porcpt_id=<?= $porcpt_id ?>";
		}
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_vend == 1) echo "	openVendBrowseFilter('porcpt_vend_code', '$vend_code');";
?>

//-->
</SCRIPT>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ic_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ht","") ?>
							<?= $f->fillHidden("ty","e") ?>
							<?= $f->fillHidden("porcpt_vend_code_old",$porcpt_vend_code_old) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit PO Receiving</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | </font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="<?php echo "poreceive_print_pdf.php?porcpt_id=$porcpt_id" ?>">Print</a>
							 | <a href="javascript:delPoRcpt()"><?= $label[$lang]["Del"] ?></a> |</font>
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
                                    <?= $f->fillTextBox("porcpt_vend_code", stripslashes($porcpt_vend_code), 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openVendBrowse('porcpt_vend_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Vendor Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("vend_name", stripslashes($vend_name), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Vendor Addr.</td>
                                  <td> 
                                    <?= $f->fillTextBox("vend_addr1", stripslashes($vend_addr1), 32, 32, "inbox") ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("vend_addr2", stripslashes($vend_addr2), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("vend_addr3", stripslashes($vend_addr3), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("vend_city", stripslashes($vend_city), 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("vend_state", stripslashes($vend_state), 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("vend_zip", stripslashes($vend_zip), 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("vend_country", stripslashes($vend_country), 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $f->fillTextBox("vend_tel", stripslashes($vend_tel), 32, 32, "inbox") ?>
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
                                  <td align="right" bgcolor="silver" width="30%">PO Rcpt#&nbsp;</td>
                                  <td width="70%">
									<?= $f->fillTextBoxRO("porcpt_id", $porcpt_id, 16,16,"inbox") ?>
                                  </td>
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
                      <td colspan="1" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()"><?= $label[$lang]["Add_Detail"] ?></A></FONT></td>
                      <td colspan="1" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="40%" align="center">&nbsp;</td>
                           <td width="60%" align="right">
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

	$t = new Items();
	$d = new PoRcptDtls();

	if (!empty($_SESSION[$porcptdtl_edit])) {
		$recs = $_SESSION[$porcptdtl_edit];
		if ($recs[0][porcptdtl_porcpt_id] != $porcpt_id && $porcptdtl_del!=1) $recs = $d->getPoRcptDtlsList($porcpt_id);
	} else {
		if ($_SESSION[$porcptdtl_del]!=1) $recs = $d->getPoRcptDtlsList($porcpt_id);
	}
	$_SESSION[$porcptdtl_edit] = $recs;

	$subtotal = 0;
	$tax_amt = 0;

	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["porcptdtl_cost"])*$recs[$i]["porcptdtl_qty"];
			$iit_arr = $t->getItems($recs[$i]["porcptdtl_item_code"]);
?>
                            <td width="5%" align="center"> 
				<a href="poreceipt_details.php?ty=e&ht=e&porcpt_id=<?= $porcpt_id ?>&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="13%"> 
                              <?= $recs[$i]["porcptdtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= stripslashes($iit_arr["item_desc"]) ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["porcptdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right">
                              <a href="javascript:openRcvdBrowse('<?= $recs[$i][porcptdtl_id] ?>')"><?= $recs[$i]["porcptdtl_qty"]+0 ?></a>
                            </td>
                            <td width="5%" align="right"> 
                              <?= strtoupper($recs[$i][porcptdtl_unit]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["porcptdtl_cost"]*$recs[$i]["porcptdtl_qty"]) ?>
                            </td>
                            <td width="3%" align="center"> 
			      <a href="poreceipt_proc.php?cmd=porcpt_detail_sess_del&ty=e&porcpt_id=<?= $porcpt_id ?>&did=<?= $i ?>"><?= $label[$lang]["Del"] ?></a>
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
                              <?= $recs[$i][porcptdtl_comnt] ?>
                            </td>
<?php
		}
	}
	if (count($recs) == 0) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b>Empty!</b>
                            </td>
                          </tr>
<?php
	}
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
              <td bgcolor="silver" valign="top" align="right">Sub Total</td>
              <td>
                <?= sprintf("%0.2f", $subtotal) ?>
				<?= $f->fillHidden("porcpt_amt", $subtotal) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Freight</td>
              <td>
                <?= $f->fillTextBox("porcpt_freight_amt", sprintf("%0.2f", $porcpt_freight_amt), 10, 16, "inbox") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Tax&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("porcpt_tax_amt", sprintf("%0.2f", $porcpt_tax_amt), 10, 16, "inbox") ?>
				 <?=  $f->fillHidden("taxtotal",$taxtotal) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Total Amount</td>
              <td>
                <?= $f->fillTextBoxRO("totalamount", sprintf("%0.2f", $porcpt_tax_amt+$porcpt_freight_amt+$subtotal), 10, 16, "inbox") ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                    </tr>
                        </table></td>
                    </tr>
                  </table>
