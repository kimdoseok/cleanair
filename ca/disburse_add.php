<?php
	include_once("class/class.datex.php");
	include_once("class/class.disburdtls.php");
	include_once("class/class.formutils.php");
	include_once("class/class.vendors.php");
	$d = new Datex();
	$f = new FormUtil();
	$applied = 0;
	$remained = 0;
	$disbur_amt = 0;

	if ($_SESSION[$disburse_add]) if ($disb = $_SESSION[$disburse_add]) foreach($disb as $k => $v) $$k = $v;
	if (!empty($disbur_vend_code)) {
		$v = new Vends();
		$varr = $v->getVends($disbur_vend_code);
	}

	$recs = $_SESSION[$disburdtls_add];
	for ($i=0;$i<count($recs);$i++) $applied += $recs[$i][disburdtl_amt];
	$remained = $disbur_amt - $applied;
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.disbur_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
//		} else if (f.disbur_id.value == "") {
//			window.alert("Disburse id should not be blank!");
		} else {
			f.cmd.value = "disbur_sess_add";
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "disbur_detail_sess_del";
		f.method = "get";
		f.action = "ap_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.disbur_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
//		} else if (f.disbur_id.value == "") {
//			window.alert("Purchase id should not be blank!");
		} else {
			f.cmd.value = "disbur_add";
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "disbur_clear_sess_add";
		f.method = "post";
		f.action = "ap_proc.php";
		f.submit();
	}

	function UpdateForm() {
		var f = document.forms[0];
		f.action = "ap_proc.php";
		f.cmd.value = "disbur_update_sess_add";
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
                            <td width="97" bgcolor="silver"><?= $label[$lang]["PO_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_po_no", $disbur_po_no, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Vendor] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_vend_code", $disbur_vend_code, 20, 32, "inbox") ?>
							  <A HREF="javascript:openVendBrowse('disbur_vend_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                            </td>
                          </tr>
<!--
                          <tr> 
                            <td width="97" bgcolor="silver">Vendor Inv. #:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_vend_inv", $disbur_vend_inv, 20, 32, "inbox") ?>
                            </td>
                          </tr>
-->
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Check_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_check_no", $disbur_check_no, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:</td>
                            <td> 
                              <?= $f->fillTextBox("disbur_amt", $disbur_amt , 20, 32, "inbox") ?>
                            </td>
                          </tr>
              </table></td>
            <td width="1%">&nbsp;</td>
            <td width="37%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<!--
                          <tr> 
                            <td width="97" bgcolor="silver">Disb #:</td>
                            <td width="308"> 
                              <?= $f->fillTextBoxRO("disbur_id", $disbur_id, 20, 32, "inbox") ?>
                            </td>
                          </tr>
-->
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
                            <td> 
                              <?= $f->fillTextBox("disbur_date", empty($disbur_date)?$d->getToday():$disbur_date, 20,32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBoxRO("disbur_user_code", $_SERVER["PHP_AUTH_USER"], 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Applied] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBoxRO("applied", $applied, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Remained] ?>:</td>
                            <td> 
                              <?= $f->fillTextBoxRO("remained", $remained , 20, 32, "inbox") ?>
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
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang][Vend_Inv_no] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Acct_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
                            <th bgcolor="gray"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += $recs[$i][disburse_amt];
?>
                            <td width="5%" align="center"> 
                              <a href="disburse_details.php?ty=e&ht=a&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i][disburdtl_vend_inv] ?>
                            </td>
                            <td width="10%"> 
                              <?= $recs[$i][disburdtl_acct_code] ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i][disburdtl_amt] ?>
                            </td>
                            <td width="60%"> 
                              <?= $recs[$i][disburdtl_desc] ?>
                            </td>
                            <td width="5%"> 
                              <a href="ap_proc.php?ty=a&cmd=disbur_detail_sess_del&disbur_id=<?= $disbur_id ?>&did=<?= $i ?>"><?= $label[$lang]["Del"] ?></a>
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
					</table> </td>
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
