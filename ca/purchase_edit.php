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
	$s = new Purchases();
	$sd = new PurDtls();

//	$purch_qty = $sd->getPurDtlsHdrSum($purch_id);

	$vend_code_old = $purch_vend_code;

	if ($_SESSION["purchases_edit"] ?? array()) {
		$sls = $_SESSION["purchases_edit"];
		if ($sls["purch_id"] != $purch_id) $sls = $s->getPurchase($purch_id);
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	} else if ($sls = $s->getPurchase($purch_id)) {
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v; 
	}
	$_SESSION["purchases_edit"] = $sls;

	$not_found_vend = 0;
	$vend_code_old = $purch_vend_code;
	if (!empty($_POST["purch_vend_code"])) $purch_vend_code = $_POST["purch_vend_code"];
	if (!empty($_GET["purch_vend_code"])) $purch_vend_code = $_GET["purch_vend_code"];
	if (!empty($purch_vend_code)) {
		$v = new Vends();
		if ($varr = $v->getVends($purch_vend_code)) {
			$purch_vend_code	= strtoupper($purch_vend_code);
			if (strtoupper($sls["purch_vend_code"]) != $purch_vend_code) {
				$purch_name		= $varr["vend_name"];
				$purch_addr1		= $varr["vend_addr1"];
				$purch_addr2		= $varr["vend_addr2"];
				$purch_addr3		= $varr["vend_addr3"];
				$purch_city		= $varr["vend_city"];
				$purch_state		= $varr["vend_state"];
				$purch_country	= $varr["vend_country"];
				$purch_zip		= $varr["vend_zip"];
				$purch_tel		= $varr["vend_tel"];
				$vend_balance	= $varr["vend_balance"];
				$purch_taxrate		= $varr["vend_taxrate"];
				$purch_vend_code_old = $purch_vend_code;
				$not_found_vend = 0;
			}
		} else {
			$not_found_vend = 1;
			$purch_vend_code	= $purch_vend_code_old;
		}
	}

	if (empty($taxtotal)) $taxtotal = 0;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.purch_id.value == "") {
			window.alert("PO number should not be blank!");
		} else {
			f.cmd.value = "purch_sess_add";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "purch_sess_del";
		<?= ($ty=="e")?"f.ht.value = 'e';":"" ?>
		f.method = "get";
		f.action = "ap_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.purch_id.value == "") {
			window.alert("Purchase number should not be blank!");
		} else {
			f.cmd.value = "purch_edit";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "purch_clear_sess_edit";
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

	function delPurchase() {
		if (window.confirm("Are you SURE to delete this purchase order?")) {
			document.location="ap_proc.php?cmd=purch_del&purch_id=<?= $purch_id ?>";
		}
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_vend == 1) echo "	openVendBrowseFilter('purch_vend_code', '$vend_code');";
?>

//-->
</SCRIPT>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ic_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ht","") ?>
							<?= $f->fillHidden("ty","e") ?>
							<?= $f->fillHidden("purch_vend_code_old",$purch_vend_code_old) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Purchase</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | </font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="<?php echo "purchase_print_pdf.php?purch_id=$purch_id" ?>">Print</a>
							 | <a href="javascript:delPurchase()"><?= $label[$lang]["Del"] ?></a> |</font>
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
                                    <?= $f->fillTextBox("purch_vend_code", stripslashes($purch_vend_code), 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openVendBrowse('purch_vend_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Vendor Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_name", stripslashes($purch_vend_name), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Vendor Addr.</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_addr1", stripslashes($purch_vend_addr1), 32, 32, "inbox") ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_addr2", stripslashes($purch_vend_addr2), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_addr3", stripslashes($purch_vend_addr3 ?? ""), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_city", stripslashes($purch_vend_city ?? ""), 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_vend_state", stripslashes($purch_vend_state ?? ""), 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_vend_zip", stripslashes($purch_vend_zip ?? ""), 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_vend_country", stripslashes($purch_vend_country ?? ""), 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_vend_tel", stripslashes($purch_vend_tel), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
								<tr> 
                                  <td width="25%" align="right" bgcolor="silver">Customer #</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("purch_cust_code", stripslashes($purch_cust_code), 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openCustBrowse('purch_cust_code', 'c')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Cust. Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_name", stripslashes($purch_cust_name), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Cust. Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_addr1", stripslashes($purch_cust_addr1 ?? ""), 32, 32, "inbox") ?>
                								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_addr2", stripslashes($purch_cust_addr2 ?? ""), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_addr3", stripslashes($purch_cust_addr3 ?? ""), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?? "" ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_city", stripslashes($purch_cust_city ?? ""), 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_cust_state", stripslashes($purch_cust_state ?? ""), 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_cust_zip", stripslashes($purch_cust_zip ?? ""), 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_cust_country", stripslashes($purch_cust_country ?? ""), 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_tel", stripslashes($purch_cust_tel ?? ""), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td colspan="2" bgcolor="silver"></td>
                                </tr>
								<tr> 
                                  <td width="25%" align="right" bgcolor="silver">Ship Code</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("purch_ship_code", stripslashes($purch_ship_code ?? ""), 32, 32, "inbox", "onChange='updateForm()'") ?>
                  									<A HREF="javascript:openCustBrowse('purch_ship_code', 's')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_name", stripslashes($purch_ship_name ?? ""), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_addr1", stripslashes($purch_ship_addr1 ?? ""), 32, 32, "inbox") ?>
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_addr2", stripslashes($purch_ship_addr2 ?? ""), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_addr3", stripslashes($purch_ship_addr3 ?? ""), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?? "" ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_city", stripslashes($purch_ship_city ?? ""), 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_ship_state", stripslashes($purch_ship_state ?? ""), 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_ship_zip", stripslashes($purch_ship_zip ?? ""), 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_ship_country", stripslashes($purch_ship_country ?? ""), 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_ship_tel", stripslashes($purch_ship_tel ?? ""), 32, 32, "inbox") ?>
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
                                  <td width="70%">
                  									<?= $f->fillTextBoxRO("purch_id", $purch_id, 16,16,"inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver" width="30%">VC #&nbsp;</td>
                                  <td width="70%">
									                  <?= $f->fillTextBoxRO("purch_vend_serial", $purch_vend_serial ?? "", 16,16,"inbox") ?>
                                  </td>
                                </tr>
	                							<tr> 
                                  <td align="right" bgcolor="silver">Date</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_date", (empty($purch_date))?date("m/d/Y"):$purch_date ?? "", 16, 32, "inbox") ?>
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
								                    <INPUT TYPE="checkbox" NAME="purch_completed" VALUE="t" <?= ($purch_completed!="f")?"checked":"" ?>>
                                  </td>
                                </tr>
                								<tr> 
                                  <td align="right" bgcolor="silver">Cmpl Date</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_completed_date",($purch_completed_date!="0000/00/00")?$purch_completed_date:"", 16, 32, "inbox") ?>
								                  	<a href="javascript:openCalendar('purch_completed_date')">C</a>
                								  </td>
                                </tr>
								<tr> 
								  <td width="24%" bgcolor="silver" valign="top" align="right">Comment</td>
								  <td width="76%">
								    <TEXTAREA NAME="["purch_comnt"]" ROWS="10" COLS="20"><?= stripslashes($purch_comnt ?? "") ?></TEXTAREA>
								  </td>
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
                            <input type="button" name="Submit32" value="<?= $label[$lang]["Update"] ?>" onClick="UpdateForm()">
								   <input type="button" name="Submit3222" value="<?= $label[$lang]["Record"] ?>" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="<?= $label[$lang]["Clear"] ?>" onClick="clearSess()"></td>
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
                            <th bgcolor="gray" width="2%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="3%"><font color="white">&nbsp;</font></th>
                          </tr>
<?php

	$t = new Items();
	$d = new PurDtls();
//print_r($_SESSION["purdtls_edit"]);
	if (!empty($_SESSION["purdtls_edit"])) {
		$recs = $_SESSION["purdtls_edit"];
		if ($recs[0]["purdtl_purch_id"] != $purch_id && $purdtl_del!=1) $recs = $d->getPurDtlsList($purch_id);
	} else {
		if ($purdtl_del!=1) $recs = $d->getPurDtlsList($purch_id);
	}
	$_SESSION["purdtls_edit"] = $recs;

	$subtotal = 0;
	$tax_amt = 0;

	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["purdtl_cost"])*$recs[$i]["purdtl_qty"];
			if ($recs[$i]["purdtl_taxable"]=="t") $taxtotal += $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"];
?>
                            <td width="5%" align="center"> 
				<a href="purchase_details.php?ty=e&ht=e&purch_id=<?= $purch_id ?>&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="13%"> 
                              <?= $recs[$i]["purdtl_item_code"] ?? "" ?>
                            </td>
                            <td width="35%"> 
                              <?= stripslashes($recs[$i]["purdtl_item_desc"] ?? "") ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["purdtl_cost"] ?? "") ?>
                            </td>
                            <td width="7%" align="right">
                              <a href="javascript:openRcvdBrowse('<?= $recs[$i]["purdtl_id"] ?? 0 ?>')"><?= $recs[$i]["purdtl_qty"] ?? 0 ?></a>
                            </td>
                            <td width="5%" align="right"> 
                              <?= strtoupper($recs[$i]["purdtl_unit"] ?? "") ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["purdtl_cost"] ?? 0 * $recs[$i]["purdtl_qty"] ?? 0) ?>
                            </td>
                            <td width="2%" align="center"> 
                              <?= ($recs[$i]["purdtl_taxable"]=="t")?"X":"&nbsp;" ?>
                            </td>
                            <td width="3%" align="center"> 
			      <a href="ap_proc.php?cmd=purch_detail_sess_del&ty=e&purch_id=<?= $purch_id ?>&did=<?= $i ?>"><?= $label[$lang]["Del"] ?></a>
                            </td>
                        </tr>
<?php
			if (!empty($recs[$i]["purdtl_comnt"])) {

?>
                            <td align="center"> 
                              &nbsp;
                            </td>
                            <td align="left" colspan="8"> 
                              <?= $recs[$i]["purdtl_comnt"] ?>
                            </td>
<?php
      }; 
		}
	}
	if (count($recs) == 0) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
                          </tr>
<?php
	}
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
                        </table></td>
                    </tr>
                  </table>
