<?php
	include_once("class/class.datex.php");
	include_once("class/class.formutils.php");
	include_once("class/class.purchase.php");
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

	if ($_SESSION[$purchases_edit]) {
		$sls = $_SESSION[$purchases_edit];
		if ($sls["purch_id"] != $purch_id) $sls = $s->getPurchase($purch_id);
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	} else if ($sls = $s->getPurchase($purch_id)) {
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v; 
	}
	$_SESSION[$purchases_edit] = $sls;

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
							 | <a href="<?php echo "purchase_print?purch_id=$purch_id" ?>">Print</a>
							 | <a href="javascript:delPurchase()"><?= $label[$lang]["Del"] ?></a> |</font>
                      </td>
                    </tr>

					<tr align="right"> 
                      <td colspan="8" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver">Vendor:</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("purch_vend_code", $purch_vend_code, 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openVendBrowse('purch_vend_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
<?php
	if ($not_found_vend != 1 || empty($purch_vend_code)) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
//	setCursor('purch_vend_code');
//-->
</SCRIPT>
<?php
	}
?>
<!--
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver">&nbsp;</td>
                                  <td width="75%"> 
<?php
	$vinfo = "";
	if (!empty($varr["vend_name"]))	$vinfo .= $varr["vend_name"]."<br>" ;
	if (!empty($varr["vend_addr1"]))	$vinfo .= $varr["vend_addr1"]."<br>" ;
	if (!empty($varr["vend_addr2"]))	$vinfo .= $varr["vend_addr2"]."<br>" ;
	if (!empty($varr["vend_addr3"]))	$vinfo .= $varr["vend_addr3"]."<br>"; 
	$cszc = "";
	if (!empty($varr["vend_city"]))	$cszc .= $varr["vend_city"].",";
	if (!empty($varr["vend_state"]))	$cszc .= $varr["vend_state"]." ";
	if (!empty($varr["vend_zip"]))	$cszc .= $varr["vend_zip"]." ";
	if (!empty($varr["vend_country"])) $cszc .= $varr["vend_country"];
	if (!empty($cszc))	$vinfo .= $cszc."<br>" ;
	if (!empty($varr["vend_tel"]))	$vinfo .= $varr["vend_tel"]."<br>" ;

?>
                                  </td>
                                </tr>
-->
								<tr> 
                                  <td width="25%" align="right" bgcolor="silver">Ship To:</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("purch_cust_code", $purch_cust_code, 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openCustBrowse('purch_cust_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                                  </td>
                                </tr>
<?php
	if ($not_found_cust != 1 && ($not_found_vend != 1 && !empty($purch_vend_code))) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
//	setCursor('purch_cust_code');
//-->
</SCRIPT>
<?php
	}
?>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_name", $purch_name, 32, 32, "inbox") ?>
<!--
									<A HREF="vendors.php?ty=a"><font size="2">New Vendor</font></A>
-->
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_addr1", $purch_addr1, 32, 32, "inbox") ?>
<!--
									<A HREF="javascript:editVendor()"><font size="2">Edit Vendor</font></A>
-->
								  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_addr2", $purch_addr2, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_addr3", $purch_addr3, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_city", $purch_city, 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_state", $purch_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_zip", $purch_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("purch_country", $purch_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_tel", $purch_tel, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver">PO #&nbsp;</td>
                                  <td> 
                                    <?= $purch_id ?>
									<?= $f->fillHidden("purch_id", $purch_id) ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Cust_PO_no"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_cust_po", $purch_cust_po, 20, 32, "inbox") ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">
<?php
	if (empty($purch_sale_id)) echo "Ref. Sale #";
	else echo "<a href='sales.php?ft=$purch_sale_id&cn=code&pg=1'>Ref. Sale #</a>";
?>
								  </td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_sale_id", $purch_sale_id, 20, 32, "inbox") ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Date_1"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_date", $purch_date, 16, 32, "inbox") ?>
									<a href="javascript:openCalendar('purch_date')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("purch_user_code", $purch_user_code, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Promise Date</td>
                                  <td> 
                                    <?= $f->fillTextBox("purch_prom_date", $purch_prom_date, 16, 32, "inbox") ?>
									<a href="javascript:openCalendar('purch_prom_date')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Balance</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("vend_balance", sprintf("%0.2f",$vend_balance), 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Custom PO</td>
                                  <td> 
								    <INPUT TYPE="checkbox" NAME="purch_custom_po" VALUE="t" <?= ($purch_custom_po=="t")?"checked":"" ?>>
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
	include_once("class/class.purdtls.php");

	$t = new Items();
	$d = new PurDtls();
	if (!empty($_SESSION[$purdtls_edit])) {
		$recs = $_SESSION[$purdtls_edit];
		if ($recs[0]["purdtl_purch_id"] != $purch_id && $purdtl_del!=1) $recs = $d->getPurDtlsList($purch_id);
	} else {
		if ($purdtl_del!=1) $recs = $d->getPurDtlsList($purch_id);
	}
	$_SESSION[$purdtls_edit] = $recs;

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
                              <?= $recs[$i]["purdtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= stripslashes($recs[$i]["purdtl_item_desc"]) ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["purdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                            <td width="5%" align="right"> 
                              <?= strtoupper($recs[$i]["purdtl_unit"]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"]) ?>
                            </td>
                            <td width="2%" align="center"> 
                              <?= ($recs[$i]["purdtl_taxable"]=="t")?"X":"&nbsp;" ?>
                            </td>
                            <td width="3%" align="center"> 
							  <a href="ap_proc.php?cmd=purch_detail_sess_del&ty=e&purch_id=<?= $purch_id ?>&did=<?= $i ?>"><?= $label[$lang]["Del"] ?></a>
                            </td>
                        </tr>
<?php
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
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $f->fillTextareaBox("["purch_comnt"]", $purch_comnt, 30, 3, "inbox") ?>
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
