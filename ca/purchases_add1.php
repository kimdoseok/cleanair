<?php
	include_once("class/class.datex.php");
	$d = new Datex();
	include_once("class/class.formutils.php");
	$f = new FormUtil();
	include_once("class/class.vendors.php");

	if (session_is_registered("purchs_add")) if ($styl = unserialize(base64_decode($_SESSION[purchs_add]))) foreach($styl as $k => $v) $$k = $v;
	if (!empty($purch_vend_code)) {
		$v = new Vends();
		$varr = $v->getVends($purch_vend_code);
	}
		
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.purch_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
//		} else if (f.purch_id.value == "") {
//			window.alert("Purchase id should not be blank!");
		} else {
			f.cmd.value = "purch_sess_add";
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "purch_detail_sess_del";
		f.method = "get";
		f.action = "ap_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.purch_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
//		} else if (f.purch_id.value == "") {
//			window.alert("Purchase id should not be blank!");
		} else {
			f.cmd.value = "purch_add";
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "purch_clear_sess_add";
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
//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ic_proc.php">
							<?= $f->fillHidden("cmd","") ?>
                    <tr> 
                      <td colspan="8" align="right"><strong><?= $label[$lang][New_Purchase] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | </font>
                      </td>
                    </tr>
                    <tr> 
                      
      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="62%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang][Vendor] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("purch_vend_code", $purch_vend_code, 32, 32, "inbox") ?>
					<A HREF="javascript:openVendBrowse('purch_vend_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" valign="top">&nbsp;</td>
                  <td valign="top"> 
<?php
	$line1 = $varr["vend_name"];
	if (!empty($line1)) $line1 .= "<br>";
	echo $line1;
	$line2 = $varr["vend_addr1"]." ".$varr["vend_addr2"]." ".$varr["vend_addr3"];
	if (!empty($line2)) $line2 .= "<br>";
	echo $line2;
	$line3 = $varr["vend_city"]." ".$varr["vend_state"]." ".$varr["vend_zip"]." ".$varr["vend_country"];
	echo $line3;

?>
                  </td>
                </tr>
              </table></td>
            <td width="1%">&nbsp;</td>
            <td width="37%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<!--
				<tr> 
                  <td width="95" align="right" bgcolor="w"><?= $label[$lang][Purchase_no] ?>&nbsp;</td>
                  <td width="136"><?= $f->fillTextBoxRO("purch_id", $purch_id, 20, 32, "inbox") ?>
                  </td>
                </tr>
-->
				<tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang][Vendor_Inv_no] ?></td>
                  <td> 
                    <?= $f->fillTextBox("purch_vend_inv", $purch_vend_inv, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("purch_date", empty($purch_date)?$d->getToday():$purch_date, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBoxRO("purch_user_code", $_SERVER["PHP_AUTH_USER"], 20, 32, "inbox") ?>
                  </td>
                </tr>
              </table></td>
          </tr>
        </table> </td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()"><?= $label[$lang]["Add_Detail"] ?></A></FONT></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["PO_no"] ?></font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Item_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Cost"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["qty"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                            <th bgcolor="gray"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	include_once("class/class.purdtls.php");
	include_once("class/class.items.php");

	$t = new Items();
	$recs = unserialize(base64_decode($_SESSION["purdtls_add"]));

	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i]["purdtl_item_code"]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"];
?>
                            <td width="5%" align="center"> 
                              <a href="purch_details.php?ty=e&ht=a&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="10%"> 
                              <?= $recs[$i][purdtl_po_no] ?>
                            </td>
                            <td width="10%"> 
                              <?= $recs[$i]["purdtl_item_code"] ?>
                            </td>
                            <td width="30%"> 
                              <?= $arr["item_desc"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= $recs[$i]["purdtl_cost"]+0 ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                            <td width="5%" align="center"> 
                              <a href="ap_proc.php?ty=a&cmd=purch_detail_sess_del&did=<?= $i ?>"><?= $label[$lang]["Del"] ?></a>
                            </td>
                          </tr>

<?php
		}
	}
	if (empty($recs[0])) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
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
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%">
                <?= $f->fillTextBox("purch_shipvia", $purch_shipvia, 20, 32, "inbox") ?>
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
                <?= $subtotal+0 ?>
				<?= $f->fillHidden("purch_amt", $subtotal) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td>
                <?= $f->fillTextBox("purch_freight_amt", $purch_freight_amt+0, 10, 16, "inbox") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= $f->fillTextBox("purch_tax_amt", $purch_tax_amt+0, 10, 16, "inbox") ?>
              </td>
            </tr>
            <tr>
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= $f->fillTextBoxRO("totalamount", $purch_tax_amt+$purch_freight_amt+$subtotal, 10, 16, "inbox") ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
      </tr>

    </table>

  </td>

                    </tr>
                    <tr> 
                      <td colspan="8" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="64%" align="center">&nbsp;</td>
                           <td width="36%" align="center">
                            <input type="button" name="Submit32" value="<?= $label[$lang]["Update"] ?>" onClick="UpdateForm()">
								   <input type="button" name="Submit3222" value="<?= $label[$lang]["Record"] ?>" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="<?= $label[$lang]["Clear"] ?>" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
                    </tr>
						  </form>
                  </table>
				  <br>
