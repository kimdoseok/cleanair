                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" align="right"><strong><?= $label[$lang][View_Disbursements] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a&disbur_id=$disbur_id" ?>"><?= $label[$lang]["New_1"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&disbur_id=$disbur_id" ?>"><?= $label[$lang]["Edit"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&disbur_id=$disbur_id" ?>"><?= $label[$lang]["List_1"] ?></a> | </font></td>
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
                              <?= $disbur_po_no ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Vendor_Inv_dno] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_vend_inv ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Purchase_no] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_ref_id ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Check_no"] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_check_no ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Acct_no"] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_acct_code ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Vendor] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_vend_code ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
                            <td> 
                              <?= $disbur_date ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $disbur_user_code ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:</td>
                            <td> 
                              <?= $disbur_amt ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td bgcolor="silver" valign="top"><?= $label[$lang]["Description"] ?>:</td>
                            <td colspan="2"> 
                              <?= $disbur_desc ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&disbur_id=$disbur_id&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&disbur_id=$disbur_id&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&disbur_id=$disbur_id&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&disbur_id=$disbur_id&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
