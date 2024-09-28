                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ar_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="slsrep_add">
                    <tr align="right"> 
                      <td colspan="8"><strong>New Sales Rep.</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="468" align="right" bgcolor="white" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Sales Rep#:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("slsrep_code", $slsrep_code, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Name:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("slsrep_name", $slsrep_name, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Address:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("slsrep_addr1", $slsrep_addr1, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("slsrep_addr2", $slsrep_addr2, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("slsrep_addr3", $slsrep_addr3, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">City/State/Zip</td>
                  <td> 
                    <?= $f->fillTextBox("slsrep_city", $slsrep_city, 20, 32, "inbox") ?>
                    <?= $f->fillTextBox("slsrep_state", $slsrep_state, 2, 32, "inbox") ?>
                    <?= $f->fillTextBox("slsrep_zip", $slsrep_zip, 5, 32, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
            <td width="12">&nbsp; </td>
            <td width="270" align="right" bgcolor="white"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Telephone_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $f->fillTextBox("slsrep_tel", $slsrep_tel, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Fax_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $f->fillTextBox("slsrep_fax", $slsrep_fax, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Exp_Acct_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $f->fillTextBox("slsrep_exp_acct", $slsrep_exp_acct, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["AP_Acct_no"] ?>&nbsp; </td>
                  <td> 
                    <?= $f->fillTextBox("slsrep_ap_acct", $slsrep_ap_acct, 20, 32, "inbox") ?>
                  </td>
                </tr>
<!--
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang][Balance] ?>:&nbsp;</td>
                  <td width="136"> 
                    <?= $f->fillTextBox("slsrep_balance", sprintf("%0.2f", $slsrep_balance), 20, 32, "inbox") ?>
                  </td>
                </tr>
-->
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang][Country] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("slsrep_country", $slsrep_country, 20, 32, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="submit" name="Submit32" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="reset" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>
                  </table>
