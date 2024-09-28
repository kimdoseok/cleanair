                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ap_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="disburs_edit">
						<?= $f->fillHidden("disbur_id", $disbur_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][Edit_Disbursement] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&disbur_id=$disbur_id" ?>"><?= $label[$lang]["View"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                      <td colspan="4" align="right"><font size="2"> | <a href="ap_proc.php?cmd=disburs_del&disbur_id=<?= $disbur_id ?>"><?= $label[$lang]["Del"] ?></a> | </font></td>
                    </tr>

                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Disburse_no"] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_id ?>
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
                            <td width="97" bgcolor="silver"><?= $label[$lang][Vendor] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_vend_code", $disbur_vend_code, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
                            <td> 
                              <?= $f->fillTextBox("disbur_date", $disbur_date, 32, 32, "inbox") ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&disbur_id=$disbur_id&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&disbur_id=$disbur_id&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&disbur_id=$disbur_id&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&disbur_id=$disbur_id&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="submit" name="Submit2222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
