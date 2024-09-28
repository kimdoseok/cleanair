<?php
	include_once("class/class.datex.php");
	$d = new Datex();
	include_once("class/class.formutils.php");
	$f = new FormUtil();
	include_once("class/class.sales.php");
	include_once("class/class.cmemo.php");
	$s = new Cmemo();

	$cust_code_old = $cmemo_cust_code;
	
	if ($_SESSION[$cmemo_edit]) {
		$sls = $_SESSION[$cmemo_edit];
		if ($sls["cmemo_id"] != $cmemo_id) $sls = $s->getCmemo($cmemo_id);
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v;
	} else if ($sls = $s->getCmemo($cmemo_id)) {
		if (!empty($sls)) foreach($sls as $k => $v) $$k = $v; 
	}
	$_SESSION[$cmemo_edit] = $sls;

	$not_found_cust = 0;
	$cmemo_code_old = $cmemo_cust_code;
	if (!empty($_POST["cmemo_cust_code"])) $cmemo_cust_code = $_POST["cmemo_cust_code"];
	if (!empty($_GET["cmemo_cust_code"])) $cmemo_cust_code = $_GET["cmemo_cust_code"];
	if (!empty($cmemo_cust_code)) {
		$v = new Custs();
		if ($varr = $v->getCusts($cmemo_cust_code)) {
			$cmemo_cust_code	= strtoupper($cmemo_cust_code);
			if (strtoupper($sls["cmemo_cust_code"]) != $cmemo_cust_code) {
				$cmemo_name		= $varr["cust_name"];
				$cmemo_addr1		= $varr["cust_addr1"];
				$cmemo_addr2		= $varr["cust_addr2"];
				$cmemo_addr3		= $varr["cust_addr3"];
				$cmemo_city		= $varr["cust_city"];
				$cmemo_state		= $varr["cust_state"];
				$cmemo_country	= $varr["cust_country"];
				$cmemo_zip		= $varr["cust_zip"];
				$x = new TaxRates();
				$xarr = $x->getTaxrates($varr["cust_tax_code"]);
				$cmemo_cust_code_old = $cmemo_cust_code;
				$not_found_cust = 0;
			}
		} else {
			$not_found_cust = 1;
			$cmemo_cust_code	= $cmemo_cust_code_old;
		}
	}

	if (empty($taxtotal)) $taxtotal = 0;
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.cmemo_id.value == "") {
			window.alert("Credit Memo number should not be blank!");
		} else {
			f.cmd.value = "cmemo_sess_add";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "cmemo_del";
		<?= ($ty=="e")?"f.ht.value = 'e';":"" ?>
		f.method = "get";
		f.action = "ar_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.cmemo_id.value == "") {
			window.alert("Credit Memo number should not be blank!");
		} else {
			f.cmd.value = "cmemo_edit";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ar_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "cmemo_clear_sess_edit";
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
							<?= $f->fillHidden("ht","") ?>
							<?= $f->fillHidden("ty","e") ?>
							<?= $f->fillHidden("cmemo_cust_code_old",$cmemo_cust_code_old) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Credit Memo</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | 
						   <a href="<?php echo "ar_proc.php?cmd=cmemo_print&ty=e&cmemo_id=$cmemo_id" ?>">Print</a> | 
						   </font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="ar_proc.php?cmd=cmemo_del&cmemo_id=<?= $cmemo_id ?>"><?= $label[$lang]["Del"] ?></a> |</font>
                      </td>
                    </tr>

					<tr align="right"> 
                      <td colspan="8" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="62%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver"><?= $label[$lang]["Customer"] ?>:</td>
                                  <td width="75%"> 
                                    <?= $f->fillTextBox("cmemo_cust_code", $cmemo_cust_code, 32, 32, "inbox", "onChange='updateForm()'") ?>
									<A HREF="javascript:openCustBrowse('cmemo_cust_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
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
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_name", $cmemo_name, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_addr1", $cmemo_addr1, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_addr2", $cmemo_addr2, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_addr3", $cmemo_addr3, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_city", $cmemo_city, 15, 32, "inbox") ?>
                                    <?= $f->fillTextBox("cmemo_state", $cmemo_state, 2, 32, "inbox") ?>
                                    <?= $f->fillTextBox("cmemo_zip", $cmemo_zip, 5, 32, "inbox") ?>
                                    <?= $f->fillTextBox("cmemo_country", $cmemo_country, 3, 32, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver">CR Memo #</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("cmemo_id", $cmemo_id, 20, 32, "inbox") ?>
                                  </td>
                                </tr>
<!--
								<tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Cust_PO_no"] ?></td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_cust_po", $cmemo_cust_po, 20, 32, "inbox") ?>
                                  </td>
                                </tr>
-->
								<tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_date", $cmemo_date, 20, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $f->fillTextBox("cmemo_user_code", $cmemo_user_code, 20, 32, "inbox") ?>
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
                           <td width="50%" align="center">&nbsp;</td>
                           <td width="50%" align="center">
                            <input type="button" name="Submit32" value="<?= $label[$lang]["Update"] ?>" onClick="UpdateForm()">
								   <input type="button" name="Submit3222" value="<?= $label[$lang]["Record"] ?>" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="<?= $label[$lang]["Clear"] ?>" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Item"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Ref. #</font></th>
                            <th bgcolor="gray" width="8%"><font color="white"><?= $label[$lang]["Cost"] ?></font></th>
                            <th bgcolor="gray" width="7%"><font color="white"><?= $label[$lang]["qty"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                            <th bgcolor="gray" width="2%"><font color="white">Tx</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	include_once("class/class.saledtls.php");
	include_once("class/class.cmemodtls.php");

	$t = new Items();
	$d = new CmemoDtl();

	if ($_SESSION[$cmemodtl_edit]) {
		$recs = $_SESSION[$cmemodtl_edit];
		if ($recs[0][cmemodtl_cmemo_id] != $cmemo_id && $cmemodtl_del!=1) $recs = $d->getCmemoDtlList($cmemo_id);
	} else {
		if ($cmemodtl_del!=1) $recs = $d->getCmemoDtlList($cmemo_id);
	}
	$_SESSION[$cmemodtl_edit] = $recs;

	$subtotal = 0;
	$tax_amt = 0;

	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["cmemodtl_cost"])*$recs[$i]["cmemodtl_qty"];
			if ($recs[$i]["cmemodtl_taxable"]=="t") $taxtotal += $recs[$i]["cmemodtl_cost"] * $recs[$i]["cmemodtl_qty"];
?>
                            <td width="5%" align="center"> 
                              <a href="cmemo_details.php?ty=e&ht=e&cmemo_id=<?= $cmemo_id ?>&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="15%"> 
                              <?= $recs[$i]["cmemodtl_item_code"] ?>
                            </td>
                            <td width="35%"> 
                              <?= stripslashes($recs[$i]["cmemodtl_item_desc"]) ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["cmemodtl_ref_code"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["cmemodtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["cmemodtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["cmemodtl_cost"] * $recs[$i]["cmemodtl_qty"]) ?>
                            </td>
                            <td width="2%" align="center"> 
                              <?= ($recs[$i]["cmemodtl_taxable"]=="t")?"X":"&nbsp;" ?>
                            </td>
                            <td width="5%" align="center"> 
                              <a href="ar_proc.php?cmd=cmemo_detail_sess_del&ty=e&cmemo_id=<?= $cmemo_id ?>&did=<?= $i ?>"><?= $label[$lang]["Del"] ?></a>
                            </td>

                        </tr>
<?php
		}
	}
	if (count($recs) == 0) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="9" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
                          </tr>
<?php
	}
	if (empty($cmemo_taxrate)) $cmemo_tax_amt = $taxtotal*$xarr["taxrate_pct"]/100;
	else $cmemo_tax_amt = $taxtotal*$cmemo_taxrate/100;
?>
                    <tr> 
                      
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("cmemo_shipvia", $cmemo_shipvia, 20, 32, "inbox") ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("cmemo_taxrate", $cmemo_taxrate+0, 20, 32, "inbox", " onChange='calcTax()'") ?> %
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $f->fillTextareaBox("cmemo_comnt", $cmemo_comnt, 30, 3, "inbox") ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Sub_Total"] ?></td>
              <td>
                <?= sprintf("%0.2f", $subtotal) ?>
				<?= $f->fillHidden("cmemo_amt", $subtotal) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td>
                <?= $f->fillTextBox("cmemo_freight_amt", sprintf("%0.2f", $cmemo_freight_amt), 10, 16, "inbox") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
				 <?= $f->fillTextBoxRO("cmemo_tax_amt", sprintf("%0.2f", $cmemo_tax_amt), 10, 16, "inbox") ?>
				 <?=  $f->fillHidden("taxtotal",$taxtotal) ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= $f->fillTextBoxRO("totalamount", sprintf("%0.2f", $cmemo_tax_amt+$cmemo_freight_amt+$subtotal), 10, 16, "inbox") ?>
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
