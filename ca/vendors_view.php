                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][View_Vendor] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&vend_code=$vend_code" ?>"><?= $label[$lang]["Edit"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="466" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang][Vendor_no] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $vend_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Name"] ?>:&nbsp;</td>
                  <td> 
                    <?= $vend_name ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Contact&nbsp;</td>
                  <td> 
                    <?= $vend_contact ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Address"] ?>:&nbsp;</td>
                  <td> 
                    <?= $vend_addr1 ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $vend_addr2 ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $vend_addr2 ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["City_State_Zip"] ?></td>
                  <td> 
                    <?= $vend_city ?>
                    <?= $vend_state ?>
                    <?= $vend_zip ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang][Country] ?>:&nbsp;</td>
                  <td> 
                    <?= $vend_country ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Email:&nbsp;</td>
                  <td> 
                    <?= $vend_email ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Website:&nbsp;</td>
                  <td> 
                    <?= $vend_url ?>
                  </td>
                </tr>
              </table> </td>
            <td width="12">&nbsp; </td>
            <td width="270" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Telephone_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $vend_tel ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver">Alt. Tel.&nbsp;</td>
                  <td width="136"> 
                    <?= $vend_tel_alt ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Fax_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $vend_fax ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Exp_Acct_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $vend_exp_acct ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["AP_Acct_no"] ?>&nbsp; </td>
                  <td> 
                    <?= $vend_ap_acct ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Tax Rate&nbsp; </td>
                  <td> 
                    <?= $vend_taxrate+0 ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang][Balance] ?>:&nbsp;</td>
                  <td width="136"> 
                    <?= $vend_balance ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">VC#&nbsp; </td>
                  <td> 
                    <?= $vend_last_no ?>
                  </td>
                </tr>
              </table></td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
