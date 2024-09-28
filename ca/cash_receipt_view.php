                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" align="right"><strong><?= $label[$lang]["View_Cash_Receipt"] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a&rcpt_id=$rcpt_id" ?>"><?= $label[$lang]["New_1"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&rcpt_id=$rcpt_id" ?>"><?= $label[$lang]["Edit"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&rcpt_id=$rcpt_id" ?>"><?= $label[$lang]["List_1"] ?></a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Disburse_no"] ?>:</td>
                            <td width="308"> 
                              <?= $rcpt_id ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Customer"] ?>:</td>
                            <td width="308"> 
                              <?= $rcpt_cust_code ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["PO_no"] ?>:</td>
                            <td width="308"> 
                              <?= $rcpt_po_no ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Sales_no"] ?>:</td>
                            <td width="308"> 
                              <?= $rcpt_ref_id ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Check_no"] ?>:</td>
                            <td width="308"> 
                              <?= $rcpt_check_no ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Acct_no"] ?>:</td>
                            <td width="308"> 
                              <?= $rcpt_acct_code ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
                            <td> 
                              <?= $rcpt_date ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $rcpt_user_code ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:</td>
                            <td> 
                              <?= $rcpt_amt ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td bgcolor="silver" valign="top"><?= $label[$lang]["Description"] ?>:</td>
                            <td colspan="2"> 
                              <?= $rcpt_desc ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&rcpt_id=$rcpt_id&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&rcpt_id=$rcpt_id&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&rcpt_id=$rcpt_id&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&rcpt_id=$rcpt_id&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
