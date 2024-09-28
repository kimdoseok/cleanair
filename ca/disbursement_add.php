<?php
	include_once("class/class.datex.php");
	$d = new Datex();
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function AddDisbur() {
	var f = document.forms[0];
	if (f.disbur_vend_code.value == "") {
		window.alert("Vendor Code should not be blank!");
	} else if (f.disbur_amt.value == "" || f.disbur_amt.value == 0) {
		window.alert("Amount should not be blank or zero!");
	} else {
		f.cmd.value = "disburs_add";
		f.method = "post";
		f.action = "ap_proc.php";
		f.submit();
	}
}
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ap_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][New_Disbursement] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Vendor] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_vend_code", $disbur_vend_code, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["PO_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_po_no", $disbur_po_no, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Vendor_Inv_dno] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_vend_inv", $disbur_vend_inv, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Purchase_no] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_ref_id", $disbur_ref_id, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Check_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_check_no", $disbur_check_no, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Acct_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_acct_code", $disbur_acct_code, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
                            <td> 
                              <?= $f->fillTextBox("disbur_date", empty($disbur_date)?$d->getToday():$disbur_date, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBoxRO("disbur_user_code", $_SERVER["PHP_AUTH_USER"], 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:</td>
                            <td> 
                              <?= $f->fillTextBox("disbur_amt", $disbur_amt , 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td bgcolor="silver" valign="top"><?= $label[$lang]["Description"] ?>:</td>
                            <td colspan="2"> 
                              <?= $f->fillTextareaBox("disbur_desc", $disbur_desc, 40, 5, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>

                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="AddDisbur()"> 
                              <input type="reset" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>
                  </table>
