<?php
	include_once("class/class.pickdtls.php");
	include_once("class/class.picks.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.items.php");
	include_once("class/class.datex.php");
	include_once("class/class.formutils.php");
	include_once("class/class.customers.php");
	include_once("class/class.taxrates.php");
	include_once("class/map.taxrates.php");
	include_once("class/map.default.php");
	$f = new FormUtil();
	$d = new Datex();
	$p = new Picks();
	if (empty($pick_code)) $pick_code = $p->getPickMaxId() + $default[pick_start_no] + 1;

	if ($_SESSION["picks_add"] && $sls = $_SESSION["picks_add"]) foreach($sls as $k => $v) $$k = $v;
//	$tax_rate = 6;
//	if (!empty($pick_cust_code)) {
//		$v = new Custs();
//		$varr = $v->getCusts($pick_cust_code);
//		foreach($taxrate as $k => $v) if ($k == $varr["cust_state"]) $tax_rate = $v;
//	}

	$not_found_cust = 0;
	$cust_code_old = $_POST["pick_cust_code"];
	if (!empty($cust_code_old)) {
		$v = new Custs();
		if ($varr = $v->getCusts($cust_code_old)) {
			$pick_cust_code	= strtoupper($cust_code_old);
			if (strtoupper($sls["pick_cust_code"]) != $pick_cust_code) {
				$pick_name		= $varr["cust_name"];
				$pick_addr1		= $varr["cust_addr1"];
				$pick_addr2		= $varr["cust_addr2"];
				$pick_addr3		= $varr["cust_addr3"];
				$pick_city		= $varr["cust_city"];
				$pick_state		= $varr["cust_state"];
				$pick_country	= $varr["cust_country"];
				$pick_zip		= $varr["cust_zip"];
				$pick_tel		= $varr["cust_tel"];
				$cust_balance	= $varr["cust_balance"];
				$x = new TaxRates();
				$xarr = $x->getTaxrates($varr["cust_tax_code"]);
				$pick_taxrate= $xarr["taxrate_pct"];
	//			if (!empty($sale_taxrate) && $sale_taxrate != $xarr["taxrate_pct"]) $sale_taxrate= $xarr["taxrate_pct"];
				$pick_cust_code_old = $pick_cust_code;
				$not_found_cust = 0;
			}
		} else {
			$not_found_cust = 1;
			$pick_cust_code	= $pick_cust_code_old;
		}
	}

		
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	var clicked = false;

	function AddDtl() {
		var f = document.forms[0];
		if (f.pick_cust_code.value == "") {
			window.alert("Customer Code should not be blank!");
		} else {
			if (clicked) return;
			clicked = true;

			f.cmd.value = "pick_sess_add";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		if (clicked) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "pick_sess_del";
		f.method = "get";
		f.action = "ar_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.pick_cust_code.value == "") {
			window.alert("Customer Code should not be blank!");
		} else if (f.pick_code.value == "") {
			window.alert("Picking ticket code should not be blank!");
		} else if (f.pick_date.value == "") {
			window.alert("Picking ticket date should not be blank!");
		} else {
			if (clicked) return;
			clicked = true;

			f.cmd.value = "pick_add";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		if (clicked) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "pick_clear_sess_add";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function UpdateForm() {
		if (clicked) return;
		clicked = true;

		var f = document.forms[0];
		f.action = "ar_proc.php";
		f.cmd.value = "pick_update_sess";
		f.method = "post";
		f.submit();
	}

	function refreshForm() {
		if (clicked) return;
		clicked = true;

		var f = document.forms[0];
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.method = "post";
		f.submit();
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_cust == 1) echo "	openCustBrowseFilter('pick_cust_code', '".$_POST["pick_cust_code"]."');";
?>

//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ar_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ty", "a") ?>
							<?= $f->fillHidden("ht", "a") ?>

                    <tr> 
                      <td colspan="8" align="right"><strong>New Picking Ticket</strong></td>
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
                                  <td width="25%" align="right" bgcolor="silver">Customer:</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("pick_cust_code", $pick_cust_code, 32, 32, "inbox", "onChange='refreshForm()'") ?>
									<A HREF="javascript:openCustBrowse('pick_cust_code')"><font size="2">Lookup</font></A>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship_To:</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_name", $pick_name, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_addr1", $pick_addr1, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_addr2", $pick_addr2, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_addr3", $pick_addr3, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">C_S_Z_C</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_city", $pick_city, 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("pick_state", $pick_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("pick_zip", $pick_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("pick_country", $pick_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_tel", $pick_tel, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver" width="50%">Slip #&nbsp;</td>
                                  <td width="50%"> <?= $f->fillTextBox("pick_code", $pick_code, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
<!--
								<tr> 
                                  <td align="right" bgcolor="silver">Cust_PO_no</td>
                                  <td> 
                                    <?= $f->fillTextBox("["pick_cust_po"]", $["pick_cust_po"], 20, 32, "inbox") ?>
                                  </td>
                                </tr>
-->
								<tr> 
                                  <td align="right" bgcolor="silver">Date_1&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_date", (empty($pick_date))?$d->getToday():$pick_date, 10, 32, "inbox") ?>
									<a href="javascript:openCalendar('pick_date')">Cal</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User_no&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_user_code", $_SERVER["PHP_AUTH_USER"], 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Promise Date:&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_prom_date", $pick_prom_date, 10, 32, "inbox") ?>
									<a href="javascript:openCalendar('pick_prom_date')">Cal</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Balance&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("cust_balance", number_format($cust_balance,2,".",","), 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="20%" align="left">
							 <FONT SIZE="2"><A HREF="javascript:AddDtl()">Add_Detail</A></FONT>
						   </td>
                           <td width="50%" align="left">
						     &nbsp;
						   </td>
                           <td width="30%" align="center">
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
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                            <th bgcolor="gray" width="1%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	$t = new PickDtls();
	$s = new SaleDtls();
	$recs = $_SESSION["pickdtls_add"];
	$y = new Items();
	$subtotal = 0;
	$taxtotal = 0;
	$tax_amt = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $s->getSaleDtls($recs[$i]["pickdtl_code"]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["pickdtl_cost"])*$recs[$i]["pickdtl_qty"];
			if ($arr["slsdtl_taxable"]=="t") $taxtotal += $recs[$i]["pickdtl_cost"]*$recs[$i]["pickdtl_qty"];
			$line_amount = $recs[$i]["pickdtl_cost"]*$recs[$i]["pickdtl_qty"];
?>
                            <td width="5%" align="center"> 
                              <a href="picking_details.php?ty=e&ht=a&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="15%"> 
                              <?= $arr["slsdtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= $arr["slsdtl_item_desc"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["pickdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["pickdtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$line_amount) ?>
                            </td>
                            <td width="1%" align="center"> 
                              <?= ($arr["slsdtl_taxable"]=="t")?"X":"&nbsp;" ?>
                            </td>
                            <td width="5%" align="center"> 
                              <a href="ar_proc.php?cmd=pick_detail_sess_del&pick_id=<?= $pick_id ?>&ty=<?= $ty ?>&did=<?= $i ?>">Del</a>
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
	$pick_tax_amt = $taxtotal * $pick_taxrate / 100;

?>
                    </table></td></tr>
					<tr> 
                      
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Ship_Via&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("pick_shipvia", $pick_shipvia, 20, 32, "inbox") ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("pick_taxrate", $pick_taxrate+0, 20, 32, "inbox", " onChange='calcTax()'") ?>%
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Comment&nbsp;</td>
              <td>
                <?= $f->fillTextareaBox("pick_comnt", $pick_comnt, 40, 2, "inbox") ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Sub Total</td>
              <td align="right">
                <?= number_format($subtotal,2,".",",") ?>
				<?= $f->fillHidden("pick_amt", sprintf("%0.2f", $subtotal)) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Taxable Total</td>
              <td align="right">
                <?= number_format($taxtotal,2,".",",") ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Freight</td>
              <td>
                <?= $f->fillTextBox("pick_freight_amt", sprintf("%0.2f", $pick_freight_amt), 10, 16, "inbox", " onChange='calcTotal()'") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Tax&nbsp;</td>
              <td>
                <?= $f->fillTextBox("pick_tax_amt", sprintf("%0.2f", $pick_tax_amt), 10, 16, "inbox") ?>
				<?= $f->fillHidden("taxtotal", $taxtotal) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Deposit&nbsp;</td>
              <td>
                <?= $f->fillTextBox("pick_deposit_amt", sprintf("%0.2f", $pick_deposit_amt), 10, 16, "inbox") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Total Amount</td>
              <td>
                <?= $f->fillTextBoxRO("totalamount", sprintf("%0.2f", sprintf("%0.2f", $pick_tax_amt+$pick_freight_amt+$subtotal-$pick_deposit_amt)), 10, 16, "inbox") ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                    </tr>
						  </form>
                  </table>
