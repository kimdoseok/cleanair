<?php
	include_once("class/class.datex.php");
	$d = new Datex();
	include_once("class/class.formutils.php");
	$f = new FormUtil();
	include_once("class/class.customers.php");
	include_once("class/class.custships.php");
	$tk = new Tickets();


	$lasticket = 0;
  if (array_key_exists("sale_cust_code",$_SESSION)) {
    $cust_code = $_SESSION["sale_cust_code"];
  } else {
    $cust_code = "";
  }
  if (array_key_exists("sales_add",$_SESSION)) {
    $sls = $_SESSION["sales_add"];
    if (is_null($sls)) {
      $sls = array();
    }
    foreach($sls as $k => $v) $$k = $v;
  }
	$not_found_cust = 0;
	$cust_code_old = $sale_cust_code;
	if (!empty($_POST["sale_cust_code"])) $sale_cust_code = $_POST["sale_cust_code"];
	if (!empty($_GET["sale_cust_code"])) $sale_cust_code = $_GET["sale_cust_code"];
	if (!empty($sale_cust_code)) {
		$v = new Custs();
		$v->active = "t";
		if ($varr = $v->getCusts($sale_cust_code)) {
			$sale_cust_code	= strtoupper($sale_cust_code);
			$cust_balance	= $varr["cust_balance"];
			$cust_cr_limit	= $varr["cust_cr_limit"];
			if (strtoupper($sls["sale_cust_code"]) != $sale_cust_code) {
				if (empty($custship_id)) {
					$sale_name		= $varr["cust_name"];
					$sale_addr1		= $varr["cust_addr1"];
					$sale_addr2		= $varr["cust_addr2"];
					$sale_addr3		= $varr["cust_addr3"];
					$sale_city		= $varr["cust_city"];
					$sale_state		= $varr["cust_state"];
					$sale_country	= $varr["cust_country"];
					$sale_zip		= $varr["cust_zip"];
					$sale_tel		= $varr["cust_tel"];
					$sale_cell		= $varr["cust_cell"];
					$sale_term		= $varr["cust_term"]; 
          $cust_memo		= $varr["cust_memo"]; 
          $cust_email		= $varr["cust_email"]; 
					$sale_prom_date = $d->nextWeekDay($varr["cust_delv_week"], date("m/d/Y"));
				}
				$sale_slsrep	= $varr["cust_slsrep"];
				$sale_shipvia   = $varr["cust_shipvia"];
				$x = new TaxRates();
				$xarr = $x->getTaxrates($varr["cust_tax_code"]);
				$sale_taxrate= settype($xarr["taxrate_pct"],"float");
				//if (!empty($sale_taxrate) && $sale_taxrate != $xarr["taxrate_pct"]) $sale_taxrate= $xarr["taxrate_pct"];
				$sale_cust_code_old = $sale_cust_code;
				$not_found_cust = 0;
			}
		} else {
			$not_found_cust = 1;
			$sale_cust_code	= $sale_cust_code_old;
		}
	}
  $credit_left = "";
	if ($cust_cr_limit==0) $credit_left = "Non Applicable";
	else $credit_left = sprintf("%0.2f",$cust_cr_limit-$cust_balance);
	
  $ca_default = array("rcpt_amt"=>0,"rcpt_disc_amt"=>0,"rcpt_date"=>"");
	$ca = new Receipt();
	$ca_arr = $ca->getReceiptLast($sale_cust_code);
  $ca_arr = array_merge($ca_default,$ca_arr);
	$last_payment = number_format($ca_arr["rcpt_amt"],2,".",",");
	$last_payment .= "(".number_format($ca_arr["rcpt_disc_amt"],2,".",",").")";
	$last_payday = $ca_arr["rcpt_date"];

	if (!empty($sale_cust_code) && !$not_found_cust ) {
		$tkt_arr = $tk->getTicketsCust($sale_cust_code);
		$numarr = count($tkt_arr);
		if ($numarr>0) $lasticket = $tkt_arr[$numarr-1]["tkt_id"];
		else $lasticket = 0;
	}
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	var clicked = false;

	function AddDtl() {
		var f = document.forms[0];
		if (f.sale_cust_code.value == "") {
			window.alert("Customer Code should not be blank!");
		} else {
			if (clicked) return;
			clicked = true;

			f.cmd.value = "sale_sess_add";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		if (clicked) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "sale_sess_del";
		f.method = "get";
		f.action = "ar_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
<?php
	if ($cust_cr_limit > 0) {
?>
		if (parseFloat(f.credit_left.value)-parseFloat(f.totalamount.value)<0) window.alert("You have reached Credit Limit!");
<?php
	}
?>
		if (f.sale_cust_code.value == "") {
			window.alert("Customer Code should not be blank!");
		} else {
			if (clicked) return;
			clicked = true;

			f.cmd.value = "sale_add";
			f.method = "post";
			f.action = "sales_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		if (clicked) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "sale_clear_sess_add";
		f.method = "post";
		f.action = "sales_proc.php";
		f.submit();
	}

	function UpdateForm() {
		if (clicked) return;
		clicked = true;

		var f = document.forms[0];
		f.action = "sales_proc.php";
		f.cmd.value = "sale_update_sess_add";
		f.method = "post";
		f.submit();
	}

	function calcTotal() {
		var f = document.forms[0];
		var t = parseFloat(f.sale_tax_amt.value);
		var r = parseFloat(f.sale_freight_amt.value);
		var s = parseFloat(f.sale_amt.value);
		var d = parseFloat(f.sale_deposit_amt.value);
		f.totalamount.value = Math.round((t+r+s-d)*100)/100 ;
	}

	function calcTax() {
		var f = document.forms[0];
		var x = parseFloat(f.taxtotal.value);
		var a = parseFloat(f.sale_taxrate.value);
		f.sale_tax_amt.value = Math.round(x*a)/100;
		var t = parseFloat(f.sale_tax_amt.value);
		calcTotal();
	}

	function editCustomer() {
		if (clicked) return;
		clicked = true;

		var f = document.forms[0];
		window.location= 'customers.php?ty=e&cust_code='+f.sale_cust_code.value;
	}

	function checkTicket() {
      var custfld = document.getElementByName('sale_cust_code');
	  window.alert(custfld.value);
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
							<?= $f->fillHidden("ty","a") ?>
							<?= $f->fillHidden("sale_cust_code_old",$sale_cust_code_old) ?>
							<?= $f->fillHidden("lasticket",$lasticket) ?>
					<tr> 
                      <td colspan="8" align="right"><strong>New Sale</strong></td>
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
                                    <?= $f->fillTextBox("sale_cust_code", $sale_cust_code, 16, 16, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openCustBrowse('sale_cust_code')"><font size="2">Lookup</font></A>
									/
									<A HREF="javascript:openShipBrowse('sale_cust_code')"><font size="2">ShipTos</font></A>
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
                                    <?= $f->fillTextBox("sale_name", stripslashes($sale_name), 32, 32, "inbox") ?>
									<A HREF="customers.php?ty=a"><font size="2">New Cust</font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_addr1", stripslashes($sale_addr1), 32, 32, "inbox") ?>
									<A HREF="javascript:editCustomer()"><font size="2">Edit Cust</font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_addr2", stripslashes($sale_addr2), 32, 32, "inbox") ?>
									<A HREF="javascript:openHistoryBrowse('sale_cust_code')"><font size="2">History</font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_addr3", stripslashes($sale_addr3), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">C/S/Z/C</td>
                                  <td> 
                                    <?= $f->fillTextBox("sale_city", stripslashes($sale_city), 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("sale_state", $sale_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("sale_zip", $sale_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("sale_country", $sale_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone </td>
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
                                  <td align="right" bgcolor="silver">Aging</td>
                                  <td> 
								    <INPUT TYPE="radio" NAME="sale_aging" VALUE="t" <?= ($sale_aging=="t")?"checked":"" ?>>Show
								    <INPUT TYPE="radio" NAME="sale_aging" VALUE="f" <?= ($sale_aging!="t")?"checked":"" ?>>Hide
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Email</td>
                                  <td> 
                                    <?= $f->fillTextBox("cust_email", stripslashes($cust_email), 32, 256, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Cust. Memo</td>
                                  <td> 
                                    <?= $f->fillTextBox("cust_memo", stripslashes($cust_memo), 32, 256, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
<!--

                                <tr> 
                                  <td align="right" bgcolor="silver">Sales_no&nbsp;</td>
                                  <td> <?= $f->fillTextBox("sale_code", $sale_code, 20, 32, "inbox") ?>
                                  </td>
                                </tr>
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
                                    <?= $f->fillTextBox("sale_date", (empty($sale_date))?$d->getToday():$sale_date, 16, 32, "inbox") ?>
									<a href="javascript:openCalendar('sale_date')">C</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User_no&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("sale_user_code", $_SERVER["PHP_AUTH_USER"], 16, 32, "inbox") ?>
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
                      <td colspan="8" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="50%" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add_Detail</A></FONT></td>
                           <td width="50%" align="center">
							<input type="button" name="checkticket" value="Ticket" onClick="viewTicket(<?= $lasticket ?>)" <?= ($lasticket>0)?"":"disabled" ?> >
                            <input type="button" name="Submit32" value="Update" onClick="UpdateForm()">
								   <input type="button" name="Submit3222" value="Record" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="Clear" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
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
	include_once("class/class.styles.php");

	$t = new SaleDtls();
  if (!array_key_exists("saledtls_add",$_SESSION)) $_SESSION["saledtls_add"] = array();
	$recs = $_SESSION["saledtls_add"];
  if (is_null($recs)) {
    $recs = array();
  }
	$subtotal = 0;
	$taxtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"]);
			if ($recs[$i]["slsdtl_taxable"]=="t") $taxtotal += $recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"];
?>
                            <td width="5%" align="center"> 
                              <a href="sales_details.php?ty=e&ht=a&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="15%"> 
                              <?= $recs[$i]["slsdtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= stripslashes($recs[$i]["slsdtl_item_desc"]) ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["slsdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["slsdtl_qty_ord"] ?? 0 ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["slsdtl_qty"] ?? 0 ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["slsdtl_qty_bo"] ?? 0 ?>
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
                              <a href="ar_proc.php?cmd=sale_detail_sess_del&ty=a&did=<?= $i ?>">Del</a>
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
  $x = new TaxRates();
  $xarr = $x->getTaxrates($cust_tax_code);

	if (!empty($sale_taxrate)) $sale_tax_amt = $taxtotal*$sale_taxrate/100;
  
?>
                    </table></td></tr>
					<tr> 
                      
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Ship Via&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("sale_shipvia", $sale_shipvia, 20, 32, "inbox") ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("sale_taxrate", $sale_taxrate, 20, 32, "inbox", " onChange='calcTax()'") ?> %
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Comment&nbsp;</td>
              <td>
                <?= $f->fillTextareaBox("sale_comnt", stripslashes($sale_comnt), 40, 2, "inbox") ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Sub Total</td>
              <td>
                <?= number_format(floatval($subtotal),2,".", ",") ?>
				<?= $f->fillHidden("sale_amt", $subtotal) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Freight&nbsp;</td>
              <td>
                <?= $f->fillTextBox("sale_freight_amt", number_format(floatval($sale_freight_amt),2,".", ","), 10, 16, "inbox", " onChange='calcTotal()'") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Tax&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("sale_tax_amt", number_format(floatval($sale_tax_amt),2,".", ","), 10, 16, "inbox") ?>
				 <?=  $f->fillHidden("taxtotal",$taxtotal) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Discount&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("sale_disc_amt", number_format(floatval($sale_disc_amt),2,".", ","), 10, 16, "inbox") ?>
				 <a href="javascript:discCalculator()">Calc</a>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Deposit&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("sale_deposit_amt", number_format(floatval($sale_deposit_amt),2,".", ","), 10, 16, "inbox", " onChange='calcTotal()'") ?>
				 <a href="javascript:openDepositEntry()">Entry</a>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Total Amount</td>
              <td>
                <?Php
                  settype($sale_tax_amt,"float");
                  settype($sale_freight_amt,"float");
                  settype($subtotal,"float");
                  settype($sale_disc_amt,"float");
                  settype($sale_deposit_amt,"float");           
                ?>
                <?= $f->fillTextBoxRO("totalamount", number_format($sale_tax_amt+$sale_freight_amt+$subtotal-$sale_disc_amt-$sale_deposit_amt,2,".", ","), 10, 16, "inbox") ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                    </tr>
						  </form>
                  </table>
<?php
//print_r("aaaaaaaaaaaa");

?>
