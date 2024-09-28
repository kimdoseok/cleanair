<SCRIPT LANGUAGE="JavaScript">
<!--
function EditRcpt() {
	var f = document.forms[0];
	if (f.rcpt_cust_code.value == "") {
		window.alert("Customer Code should not be blank!");
	} else if (f.rcpt_amt.value == "" || f.rcpt_amt.value == 0) {
		window.alert("Amount should not be blank or zero!");
	} else {
		f.cmd.value = "rcpts_edit";
		f.method = "post";
		f.action = "ar_proc.php";
		f.submit();
	}
}
//-->
</SCRIPT>
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post>
						<INPUT TYPE="hidden" name="cmd" value="rcpts_edit">
						<?= $f->fillHidden("rcpt_id", $rcpt_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang]["Edit_Cash_Receipt"] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&rcpt_id=$rcpt_id" ?>"><?= $label[$lang]["View"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                      <td colspan="4" align="right"><font size="2"> | <a href="ar_proc.php?cmd=rcpts_del&rcpt_id=<?= $rcpt_id ?>"><?= $label[$lang]["Del"] ?></a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Receipt_no"] ?>:</td>
                            <td width="308"> 
                              <?= $rcpt_id ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
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
                              <?= $f->fillTextBox("rcpt_date", $rcpt_date, 32, 32, "inbox") ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&rcpt_id=$rcpt_id&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&rcpt_id=$rcpt_id&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&rcpt_id=$rcpt_id&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&rcpt_id=$rcpt_id&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                            <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="EditRcpt()"> 
                              <input type="reset" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
