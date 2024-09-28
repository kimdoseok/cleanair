<?php
	$d = new Datex();
	$f = new FormUtil();
	$s = new Sales();

	$pd = new PickDtls();
	$sd = new SaleDtls();
	$pick_qty = $pd->getPickDtlsSlsHdrSum($sale_id);
	$sale_qty = $sd->getSaleDtlsHdrSum($sale_id);
	$diff_qty = $sale_qty - $pick_qty;

	if ($diff_qty < 0) {
		$status = "Over Fulfilled";
		$st_code = "O";
	} else if ($diff_qty == 0) {
		$status = "Fully Fulfilled";
		$st_code = "F";
	} else if ($diff_qty > 0 && $pick_qty > 0) {
		$status = "Partially Fulfilled";
		$st_code = "P";
	} else if ($pick_qty == 0 && $sale_qty > 0) {
		$status = "Not Fulfilled";
		$st_code = "N";
	} else {
		$status = "Error";
		$st_code = "E";
	}

    $sh_edit = "sales_edit_".$sale_id;
    $sd_edit = "saledtls_edit_".$sale_id;
    $sales_deposit = "sales_deposit_".$sale_id;

    $cust_code_old = $sale_cust_code;
	
	if (array_key_exists($sh_edit, $_SESSION) && !empty($_SESSION[$sh_edit])) {
		$sls = $_SESSION[$sh_edit];
		if ($sls["sale_id"] != $sale_id) $sls = $s->getSales($sale_id);
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	} else if ($sls = $s->getSales($sale_id)) {
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v; 
	}
	$_SESSION[$sh_edit] = $sls;

	//print_r($_SESSION[$sh_edit]);
	$not_found_cust = 0;
	$sale_cust_code_old = $sale_cust_code;
	$cust_code_old = $sale_cust_code;
	if (!empty($_POST["sale_cust_code"])) $sale_cust_code = $_POST["sale_cust_code"];
	if (!empty($_GET["sale_cust_code"])) $sale_cust_code = $_GET["sale_cust_code"];
	if (!empty($sale_cust_code)) {
		$v = new Custs();
		if ($varr = $v->getCusts($sale_cust_code)) {
			$sale_cust_code	= strtoupper($sale_cust_code);
			if (isset($varr["cust_balance"])) {$cust_balance = $varr["cust_balance"];} else {$cust_balance = 0;};
			if (isset($varr["cust_cr_limit"])) {$cust_cr_limit = $varr["cust_cr_limit"];} else {$cust_cr_limit = 0;};
			if (isset($varr["cust_cust_memoname"])) {$cust_memo = $varr["cust_memo"];} else {$cust_memo = "";};
			if (isset($varr["cust_email"])) {$cust_email = $varr["cust_email"];} else {$cust_email = "";};
			if (strtoupper($sls["sale_cust_code"]) != $sale_cust_code) {
				if (isset($varr["cust_name"])) {$sale_name = $varr["cust_name"];} else {$sale_name = "";};
				if (isset($varr["cust_addr1"])) {$sale_addr1 = $varr["cust_addr1"];} else {$sale_addr1 = "";};
				if (isset($varr["cust_addr2"])) {$sale_addr2 = $varr["cust_addr2"];} else {$sale_addr2 = "";};
				if (isset($varr["cust_addr3"])) {$sale_addr3 = $varr["cust_addr3"];} else {$sale_addr3 = "";};
				if (isset($varr["cust_city"])) {$sale_city = $varr["cust_city"];} else {$sale_city = "";};
				if (isset($varr["cust_state"])) {$sale_state = $varr["cust_state"];} else {$sale_state = "";};
				if (isset($varr["cust_country"])) {$sale_country = $varr["cust_country"];} else {$sale_country = "";};
				if (isset($varr["cust_zip"])) {$sale_zip = $varr["cust_zip"];} else {$sale_zip = "";};
				if (isset($varr["cust_tel"])) {$sale_tel = $varr["cust_tel"];} else {$sale_tel = "";};
				if (isset($varr["cust_cell"])) {$sale_cell = $varr["cust_cell"];} else {$sale_cell = "";};
				if (isset($varr["cust_term"])) {$sale_term = $varr["cust_term"];} else {$sale_term = "";};
				if (isset($varr["cust_memo"])) {$sale_memo = $varr["cust_memo"];} else {$sale_memo = "";};
				if (isset($varr["cust_email"])) {$sale_email = $varr["cust_email"];} else {$sale_email = "";};
				//$sale_slsrep	= $varr["cust_slsrep"];
				$x = new TaxRates();
				$xarr = $x->getTaxrates($varr["cust_tax_code"]);
				if (empty($sale_taxrate)) $sale_taxrate= $xarr["taxrate_pct"];
//				if (!empty($sale_taxrate) && $sale_taxrate != $xarr["taxrate_pct"]) $sale_taxrate= $xarr["taxrate_pct"];
				$sale_cust_code_old = $sale_cust_code;
				$not_found_cust = 0;
			}
		} else {
			$not_found_cust = 1;
			$sale_cust_code	= $sale_cust_code_old;
		}
	}

	if ($cust_cr_limit==0) $credit_left = "Non Applicable";
	else $credit_left = sprintf("%0.2f",$cust_cr_limit-$cust_balance);

	if (empty($taxtotal)) $taxtotal = 0;
	$ca = new Receipt();
	$ca_arr = $ca->getReceiptLast($sale_cust_code);
	$last_payment = number_format($ca_arr["rcpt_amt"] ?? 0,2,",",".");
	$last_payment .= "(".number_format($ca_arr["rcpt_disc_amt"] ?? 0,2,",",".").")";
	$last_payday = $ca_arr["rcpt_date"] ?? "";


	if ($sale_deposit_amt!=0 && $dp_arr = $ca->getReceiptSales($sale_id)) {
		$_SESSION[$sales_deposit]["rcpt_type"]=$dp_arr["rcpt_type"];
		$_SESSION[$sales_deposit]["rcpt_check_no"]=$dp_arr["rcpt_check_no"];
		$_SESSION[$sales_deposit]["rcpt_amt"]=$sale_deposit_amt;
		$_SESSION[$sales_deposit]["rcpt_comment"]=$dp_arr["rcpt_comment"];
	}

	if (!empty($sale_cust_code) && !$not_found_cust ) {
		$tkt_arr = $tk->getTicketsCust($sale_cust_code);
		$numarr = count($tkt_arr);
		if (count($tkt_arr)>0) {
			$lasticket = $tkt_arr[$numarr-1]["tkt_id"];	
		} else {
			$lasticket = 0;	
		}
	}

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	var clicked = false;

	function AddDtl() {
		var f = document.forms[0];
		if (f.sale_id.value == "") {
			window.alert("Sales number should not be blank!");
		} else {
			if (clicked==true) return;
			clicked = true;

			f.cmd.value = "sale_sess_add";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "sale_sess_del";
		<?= ($ty=="e")?"f.ht.value = 'e';":"" ?>
		f.method = "get";
		f.action = "ar_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
<?php
	if ($cust_cr_limit > 0) {
?>
		if (parseFloat(f.credit_left.value)<0) window.alert("You have reached Credit Limit!");
<?php
	}
?>
		if (f.sale_id.value == "") {
			window.alert("Sales number should not be blank!");
		} else {
    		if (clicked==true) {
    		    f.SubmitRecord.Disable();
    		    return;
    		}
			clicked = true;

			f.cmd.value = "sale_edit";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "sale_clear_sess_edit";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function customPO() {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "sale_custom_po";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function pendSale() {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "sale_pending";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function UpdateForm() {
		if (clicked==true) {
		    f.SubmitUpdate.Disable();
		    return;
		}
		clicked = true;

		var f = document.forms[0];
		f.action = "ar_proc.php";
		f.cmd.value = "sale_update_sess_add";
		f.method = "post";
		f.submit();
	}

	function calcTotal() {
		var f = document.forms[0];
		var t = parseFloat(f.sale_tax_amt.value);
		var r = parseFloat(f.sale_freight_amt.value);
		var s = parseFloat(f.sale_amt.value);
		f.totalamount.value = Math.round((t+r+s)*100)/100 ;
	}

	function calcTax() {
		var f = document.forms[0];
		var x = parseFloat(f.taxtotal.value);
		var a = parseFloat(f.sale_taxrate.value);
		f.sale_tax_amt.value = Math.round(x*a)/100;
		var t = parseFloat(f.sale_tax_amt.value);
		calcTotal();
	}

	function delSale() {
		if (window.confirm("Are you SURE to delete this sales slip?")) {
			if (clicked==true) return;
			clicked = true;

			document.location="ar_proc.php?cmd=sale_del&sale_id=<?= $sale_id ?>";
		}
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_cust == 1) echo "	openCustBrowseFilter('sale_cust_code', '$cust_code');";
?>

//-->
</SCRIPT>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ar_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ht","") ?>
							<?= $f->fillHidden("ty","e") ?>
							<?= $f->fillHidden("sale_cust_code_old",$sale_cust_code_old) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Sales</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4"><font size="2">
						 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | 
					       <a href="javascript:openProposalBrowse(<?= $sale_id ?>)">Proposal</a> | 
					       <!--<a href="<?php echo "ar_proc.php?cmd=sale_pending&sale_id=$sale_id" ?>">Pending</a> | -->
						   </font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="<?php echo "ar_proc.php?cmd=sale_print&ty=e&sale_id=$sale_id" ?>">Print Sales Order</a>
							 | <a href="javascript:delSale()">Del</a> |</font>
                      </td>
                    </tr>

					<tr align="right"> 
                      <td colspan="8" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="62%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver">Customer:</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("sale_cust_code", $sale_cust_code, 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openCustBrowse('sale_cust_code')"><font size="2">Lookup</font></A>
                                  </td>
                                </tr>
<?php
	if ($not_found_cust != 1) {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	setCursor('sale_cust_code');
//-->
</SCRIPT>
<?php
	}
?>
								<tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_name", ($sale_name), 32, 32, "inbox") ?>
									<A HREF="javascript:openShipBrowse('sale_cust_code')"><font size="2">ShipTos</font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_addr1", ($sale_addr1), 32, 32, "inbox") ?>
									<A HREF="javascript:openHistoryBrowse('sale_cust_code')"><font size="2">History</font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_addr2", ($sale_addr2), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_addr3", ($sale_addr3), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">C_S_Z_C</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_city", ($sale_city), 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("sale_state", $sale_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("sale_zip", $sale_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("sale_country", $sale_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_tel", $sale_tel, 32, 32, "inbox") ?>
									<INPUT TYPE="checkbox" NAME="save_ship" VALUE="t">Save
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Cellphone </td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_cell", $sale_cell, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Status</td>
                                  <td> <FONT SIZE="2">
<?php
	if ($st_code != "F") {
		if ($sd->getSaleDtlBoQty($sale_id)>0) {
?>
							  <a href="javascript:window.alert('Back order quantity should be cleared first!\nPlease may create pending sales.');"><?= $status ?></a>

<?php
		} else {
?>
							  <a href="ar_proc.php?cmd=sale_to_pick_add&sale_id=<?= $sale_id ?>"><?= $status ?></a>
<?php
		}
	} else  {
								echo $status;
	}
?>
                                  </FONT></td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Aging</td>
                                  <td> 
								    <INPUT TYPE="radio" NAME="sale_aging" VALUE="t" <?= ($sale_aging=="t")?"checked":"" ?>>Show
								    <INPUT TYPE="radio" NAME="sale_aging" VALUE="f" <?= ($sale_aging!="t")?"checked":"" ?>>Hide
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Email</td>
                                  <td> 
                                    <?= $f->fillTextBox("cust_email", ($cust_email), 32, 256, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Cust. Memo</td>
                                  <td> 
                                    <?= $f->fillTextBox("cust_memo", ($cust_memo), 32, 256, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver">Sales #&nbsp;</td>
                                  <td> 
                                    <?= $sale_id ?>
									<?= $f->fillHidden("sale_id", $sale_id) ?>
                                  </td>
                                </tr>
<!--
								<tr> 
                                  <td align="right" bgcolor="silver">Cust_PO_no</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_cust_po", $sale_cust_po, 20, 32, "inbox") ?>
                                  </td>
                                </tr>
-->
								<tr> 
                                  <td align="right" bgcolor="silver">Date&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_date", $sale_date, 16, 32, "inbox") ?>
									<a href="javascript:openCalendar('sale_date')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User_no&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("sale_user_code", $sale_user_code, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Promise Date&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_prom_date", $sale_prom_date, 16, 32, "inbox") ?>
									<a href="javascript:openCalendar('sale_prom_date')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Sales Rep&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_slsrep", $sale_slsrep, 16, 32, "inbox") ?>
									<a href="javascript:openSlsRepBrowse('sale_slsrep')">C</a>

                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Sales Terms&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_term", $sale_term, 16, 32, "inbox") ?>
									<a href="javascript:openTermBrowse('sale_term')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Balance</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("cust_balance", $cust_balance, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Last Payment</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("last_payment", $last_payment, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Last Payday</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("last_payday", $last_payday, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Avl. Credit</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("credit_left", $credit_left, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>

                    <tr> 
                      <td colspan="4" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add_Detail</A></FONT></td>
                      <td colspan="4" align="right">
						<input type="button" name="checkticket" value="Ticket" onClick="viewTicket(<?= $lasticket ?>)" <?= ($lasticket>0)?"":"disabled" ?>>
                         <input type="button" name="SubmitUpdate" value="Update" onClick="UpdateForm()">
					     <input type="button" name="SubmitRecord" value="Record" onClick="SaveToDB()"> 
                         <input type="button" name="SubmitClear" value="Clear" onClick="clearSess()">
                         <input type="button" name="SubmitCustomPO" value="Custom PO" onClick="customPO()">
                         <input type="button" name="SubmitPendSale" value="Pend Sale" onClick="pendSale()">
					  </td>
                    </tr>
					<tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">No</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Ord Qty</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Shp Qty</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">BO Qty</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Unit</font></th>
                            <th bgcolor="gray" width="2%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	include_once("class/class.saledtls.php");
	include_once("class/class.pendtls.php");

	$t = new Items();
	$d = new SaleDtls();
	$nd = new PenDtls();
	if (!empty($_SESSION[$sd_edit])) {
		$recs = $_SESSION[$sd_edit];
		if ($recs[0]["slsdtl_sale_id"] != $sale_id && $slsdtl_del!=1) $recs = $d->getSaleDtlsList($sale_id);
	} else {
		if ($slsdtl_del ?? 0 != 1) $recs = $d->getSaleDtlsList($sale_id);
	}
	$_SESSION[$sd_edit] = $recs;
	$subtotal = 0;
	$tax_amt = 0;

	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"]);
			if ($recs[$i]["slsdtl_taxable"]=="t") $taxtotal += $recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"];
			if (!empty($recs[$i]["slsdtl_id"])) $pick_qty = $pd->getPickDtlsSlsSum($recs[$i]["slsdtl_id"]);
			else $pick_qty = 0;
			$pendtl_qty = $nd->getPenDtlsBOQty($sale_id, $recs[$i]["slsdtl_item_code"]);
			if ($pendtl_qty>0) $pendtlstr = sprintf("(%d)",$pendtl_qty);
			else $pendtlstr = "";
?>
                            <td width="5%" align="center"> 
<?php
	if ($recs[$i]["slsdtl_qty"] != $pick_qty || $recs[$i]["slsdtl_qty"] == 0) {
?>

							  <a href="sales_details.php?ty=e&ht=e&sale_id=<?= $sale_id ?>&did=<?= $i ?>"><?= $i+1 ?></a>
<?php
	} else {
		echo $i+1;
	}
?>
                            </td>
                            <td width="15%"> 
                              <?= $recs[$i]["slsdtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= ($recs[$i]["slsdtl_item_desc"]) ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["slsdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["slsdtl_qty_ord"]+0 ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["slsdtl_qty"]+0 ?>
                            </td>
              				  <td width="7%" align="right">
                              <?= $recs[$i]["slsdtl_qty_bo"]+0 ?>
                              <?= $pendtlstr ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"]) ?>
                            </td>
                            <td width="2%" align="center"> 
                              <?= strtoupper($recs[$i]["slsdtl_unit"]) ?>
                            </td>
                            <td width="2%" align="center"> 
                              <?= ($recs[$i]["slsdtl_taxable"]=="t")?"X":"&nbsp;" ?>
                            </td>
                            <td width="5%" align="center"> 
<?php
	if ($pick_qty == 0) {
?>
							  <a href="ar_proc.php?cmd=sale_detail_sess_del&ty=e&sale_id=<?= $sale_id ?>&did=<?= $i ?>">Del</a>
<?php
	} else {
		echo "&nbsp;";
	}
?>
                            </td>

                        </tr>
<?php
		}
	}
	if (count($recs) == 0) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b>Empty_1!</b>
                            </td>
                          </tr>
<?php
	}
	if (empty($sale_taxrate)) $sale_tax_amt = $taxtotal*$xarr["taxrate_pct"]/100;
	else $sale_tax_amt = $taxtotal*$sale_taxrate/100;
?>
                    <tr> 
                      
  <td colspan="10">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top">
          <table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="30%" bgcolor="silver" valign="top" align="right">Ship_Via&nbsp;</td>
              <td width="70%">
                <?= $f->fillTextBox("sale_shipvia", $sale_shipvia, 20, 32, "inbox") ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("sale_taxrate", $sale_taxrate+0, 20, 32, "inbox", " onChange='calcTax()'") ?>%
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Comment&nbsp;</td>
              <td>
                <?= $f->fillTextareaBox("sale_comnt", ($sale_comnt), 30, 3, "inbox") ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Pick Ref #&nbsp;</td>
              <td width="76%">
<?php
	$arr = $sd->getSaleDtlsListPicks($sale_id);
	if ($arr) $arr_num = count($arr);
	else $arr_num =0;
	for ($i=0;$i<$arr_num;$i++) {
		if ($i!=0) echo ", ";
		echo "<a href=\"picking.php?ty=l&ft=" . $arr[$i]["pickdtl_pick_id"]."&cn=code\">" . $arr[$i]["pickdtl_pick_id"]."<a>\n";
	}
?>
              </td>
            </tr>
          </table>
        </td>
        <td width="2%">&nbsp;</td>
        <td width="36%">
          <table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td  bgcolor="silver" valign="top" align="right">Sub Total</td>
              <td>
                <?= number_format($subtotal,2,".", ",") ?>
				<?= $f->fillHidden("sale_amt", $subtotal) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Freight&nbsp;</td>
              <td>
                <?= $f->fillTextBox("sale_freight_amt", number_format($sale_freight_amt,2,".", ","), 10, 16, "inbox", " onChange='calcTotal()'") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Tax&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("sale_tax_amt", number_format($sale_tax_amt,2,".", ","), 10, 16, "inbox") ?>
				 <?=  $f->fillHidden("taxtotal",$taxtotal) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Discount&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("sale_disc_amt", number_format($sale_disc_amt,2,".", ","), 10, 16, "inbox") ?>
				 <a href="javascript:discCalculator()">Calc</a>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Deposit&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("sale_deposit_amt", number_format($sale_deposit_amt,2,".", ","), 10, 16, "inbox", " onChange='calcTotal()'") ?>
				 <a href="javascript:openDepositEntry()">Entry</a>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Total Amount</td>
              <td>
                <?= $f->fillTextBoxRO("totalamount", number_format($sale_tax_amt+$sale_freight_amt+$subtotal-$sale_disc_amt-$sale_deposit_amt,2,".", ","), 10, 16, "inbox") ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                    </tr>
                        </table></td>
                    </tr>
					</form>

				  </table>
<?php
//print_r($_SESSION["sales_edit"])
?>