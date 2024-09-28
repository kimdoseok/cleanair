<?php
	$f = new FormUtil();
	$d = new Datex();
	$s = new Picks();

	if (array_key_exists("picks_edit", $_SESSION)) {
		$sls = $_SESSION["picks_edit"];
		if (array_key_exists("pick_id", $sls) && $sls["pick_id"] != $pick_id) {
			$sls = $s->getPicks($pick_id);
			$forcedb = 1;
		}
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	} else if ($sls = $s->getPicks($pick_id)) {
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v; 
	}
	$_SESSION["picks_edit"] = $sls;
  $cust_balance = 0;
	if (!empty($pick_cust_code)) {
		$v = new Custs();
		if ($varr = $v->getCusts($pick_cust_code)) {
			$pick_cust_code	= strtoupper($pick_cust_code);
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
				$cust_balance = $varr["cust_balance"];
				$x = new TaxRates();
				$xarr = $x->getTaxrates($varr["cust_tax_code"]);
				$pick_cust_code_old = $pick_cust_code;
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
		//if (f.pick_id.value == "") {
			//window.alert("Pick number should not be blank!");
		//} else {
			if (clicked==true) return;
			clicked = true;

			f.cmd.value = "pick_sess_add";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		//}
	}

	function DelDtl(did) {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "pick_del";
		<?= ($ty=="e")?"f.ht.value = 'e';":"" ?>
		f.method = "get";
		f.action = "ar_proc.php";
		f.submit();
	}

	function setDelivery() {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "pick_delivery";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function setUnshipped() {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "pick_unshipped";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		//if (f.pick_id.value == "") {
		//	window.alert("Pick number should not be blank!");
		//} else {
			if (clicked==true) return;
			clicked = true;

			f.cmd.value = "pick_edit";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		//}
	}

	function clearSess() {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.cmd.value = "pick_clear_sess_edit";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}

	function UpdateForm() {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.action = "ar_proc.php";
		f.cmd.value = "pick_update_sess";
		f.method = "post";
		f.submit();
	}

	function delPick() {
		if (window.confirm("Are you SURE to delete this picking ticket?")) {
			if (clicked==true) return;
			clicked = true;
			document.location="ar_proc.php?cmd=pick_del&cust_code=<?= $pick_cust_code ?>&pick_id=<?= $pick_id ?>";
		}
	}
//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ic_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ht","") ?>
							<?= $f->fillHidden("ty","e") ?>
							<?= $f->fillHidden("pick_id",$pick_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Picking Ticket</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> |
						   <a href="<?php echo "sales.php?ty=a" ?>">New Sale</a> |
						   <a href="javascript:openInvoiceBrowse(<?= $pick_id ?>)">Print Invoice</a> | </font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
						| <a href="<?php echo "ar_proc.php?cmd=pick_print&pick_id=$pick_id" ?>">Print</a>
						|  <a href="javascript:makeInvoice();">Make Invoice</a> | <a href="javascript:delPick()">Del</a> |</font>
                      </td>
                    </tr>

					<tr align="right"> 
                      <td colspan="8" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="59%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver">Customer:</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("pick_cust_code", $pick_cust_code, 32, 32, "inbox") ?>
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
                                <tr> 
                                  <td> 
                                  </td>
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
                            <td width="39%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver" width="120">Picking #&nbsp;</td>
                                  <td width="200"> 
                                    <?= $pick_id ?>
									<?= $f->fillHidden("pick_id", $pick_id) ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Slip #</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_code", $pick_code, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Picking Date&nbsp;</td>
                                  <td> 
                                  <?php 
                                  $pick_usdate = date('m/d/Y', strtotime($pick_date));
                                  ?>
                                    <?= $f->fillTextBox("pick_date", $pick_usdate, 10, 32, "inbox") ?>
                                    <a href="javascript:openCalendar('pick_date')">Cal</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User_no&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_user_code", $pick_user_code, 16, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Prom. Date:&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_prom_date", $pick_prom_date, 10, 32, "inbox") ?>
									<a href="javascript:openCalendar('pick_prom_date')">Cal</a>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Deliv. Date:&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("pick_delv_date", $pick_delv_date, 10, 32, "inbox") ?>
									<a href="javascript:openCalendar('pick_delv_date')">Cal</a>
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
                      <td colspan="4" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add_Detail</A></FONT></td>
                      <td colspan="4" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="100%" align="right">
                           <input type="button" name="Submit3" value="Update" onClick="UpdateForm()">
						   <input type="button" name="Submit2" value="Record" onClick="SaveToDB()"> 
<?php
	if ($pick_fin != "t") {
?>
						   <input type="button" name="Submit4" value="Shipped" onClick="setDelivery()">
<?php
	} else {
?>
						   <input type="button" name="Submit4" value="Unshipped" onClick="setUnshipped()">
<?php
	}
?>
                           <input type="button" name="Submit5" value="Clear" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">No</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Ord.Qty</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                            <th bgcolor="gray" width="1%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	$d = new PickDtls();
	$s = new SaleDtls();

	if (!empty($_SESSION["pickdtls_edit"])) {
		$recs = $_SESSION["pickdtls_edit"];
		$forcedb = 0;
		if ($recs[0]["pickdtl_pick_id"] != $pick_id) $forcedb = 1;
		if ($forcedb>0) {
			$olds = $d->getPickDtlsList($pick_id);
			$old_num = $d->getPickDtlsRows($pick_id);
			$recs = array();
			for ($i=0;$i<$old_num;$i++) {
				$rec=array();
				$rec["pickdtl_pick_id"]		= $pick_id;
				$rec["pickdtl_id"]			= $olds[$i]["pickdtl_id"];
				$rec["pickdtl_code"]			= $olds[$i]["pickdtl_slsdtl_id"];
				$rec["pickdtl_qty"]			= $olds[$i]["pickdtl_qty"];
				$rec["pickdtl_cost"]			= $olds[$i]["pickdtl_cost"];
				array_push($recs, $rec);
			}
			if ($old_num) {
				$_SESSION["pickdtls_edit"] = $recs;
			}
		}
	} else {
		$olds = $d->getPickDtlsList($pick_id);
		if (!empty($olds)){
			$recs = array();
			for ($i=0;$i<count($olds);$i++) {
				$rec=array();
				$rec["pickdtl_pick_id"]		= $pick_id;
				$rec["pickdtl_id"]			= $olds[$i]["pickdtl_id"];
				$rec["pickdtl_code"]			= $olds[$i]["pickdtl_slsdtl_id"];
				$rec["pickdtl_qty"]			= $olds[$i]["pickdtl_qty"];
				$rec["pickdtl_cost"]			= $olds[$i]["pickdtl_cost"];
				array_push($recs, $rec);
			}
			$_SESSION["pickdtls_edit"] = $recs;
		}
	}

	$subtotal = 0;
	$taxtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["pickdtl_cost"])*$recs[$i]["pickdtl_qty"];
			$arr = $s->getSaleDtls($recs[$i]["pickdtl_code"]);
			if ($arr["slsdtl_taxable"]=="t") $taxtotal += sprintf("%0.2f",$recs[$i]["pickdtl_cost"])*$recs[$i]["pickdtl_qty"];
?>
                            <td width="5%" align="center"> 
                              <a href="picking_details.php?ty=e&ht=e&pick_id=<?= $pick_id ?>&did=<?= $i ?>"><?= $i+1 ?></a>
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
                              <?= $arr["slsdtl_qty"]+0 ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["pickdtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["pickdtl_cost"]*$recs[$i]["pickdtl_qty"]) ?>
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
	if (count($recs) == 0) {
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
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Ref #&nbsp;</td>
              <td width="76%">
<?php
	$arr = $d->getPickDtlsListSales($pick_id);
	if ($arr) $arr_num = count($arr);
	else $arr_num =0;
	for ($i=0;$i<$arr_num;$i++) {
		if ($i!=0) echo ", ";
		echo "<a href=\"sales.php?ty=l&ft=" . $arr[$i]["slsdtl_sale_id"]."&cn=code\">" . $arr[$i]["slsdtl_sale_id"]."<a>\n";
	}
?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right">Sub_Total</td>
              <td align="right">
                <?= number_format($subtotal,2,".",",") ?>
				<?= $f->fillHidden("pick_amt", $subtotal) ?>
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
                <?= $f->fillTextBox("pick_deposit_amt", sprintf("%0.2f", $pick_deposit_amt), 10, 16, "inbox", " onChange='calcTotal()'") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right">Total Amount</td>
              <td>
                <?= $f->fillTextBoxRO("totalamount", sprintf("%0.2f", $pick_tax_amt+$pick_freight_amt+$subtotal-$pick_deposit_amt), 10, 16, "inbox") ?>
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
