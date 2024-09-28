<?php
	include_once("class/class.datex.php");
	$d = new Datex();
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function AddRcpt() {
	var f = document.forms[0];
	if (f.rcpt_cust_code.value == "") {
		window.alert("Customer Code should not be blank!");
	} else if (f.rcpt_amt.value == "" || f.rcpt_amt.value == 0) {
		window.alert("Amount should not be blank or zero!");
	} else {
		f.cmd.value = "rcpts_add";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}
}
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post>
						<INPUT TYPE="hidden" name="cmd" value="">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang]["New_Cash_Receipt"] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Customer"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("rcpt_cust_code", $rcpt_cust_code, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["PO_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("rcpt_po_no", $rcpt_po_no, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Sales_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("rcpt_ref_id", $rcpt_ref_id, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Check_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("rcpt_check_no", $rcpt_check_no, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Acct_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("rcpt_acct_code", $rcpt_acct_code, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
                            <td> 
                              <?= $f->fillTextBox("rcpt_date", empty($rcpt_date)?$d->getToday():$rcpt_date, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBoxRO("rcpt_user_code", $_SERVER["PHP_AUTH_USER"], 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:</td>
                            <td> 
                              <?= $f->fillTextBox("rcpt_amt", $rcpt_amt , 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td bgcolor="silver" valign="top"><?= $label[$lang]["Description"] ?>:</td>
                            <td colspan="2"> 
                              <?= $f->fillTextareaBox("rcpt_desc", $rcpt_desc, 40, 5, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>

                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="AddRcpt()"> 
                              <input type="reset" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>
                  </table>
