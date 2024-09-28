                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ap_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="vend_add">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][New_Vendor] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="468" valign="top" align="right" bgcolor="white"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang][Vendor_no] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("vend_code", $vend_code, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Name"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("vend_name", $vend_name, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Contact&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("vend_contact", $vend_contact, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Address"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("vend_addr1", $vend_addr1, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("vend_addr2", $vend_addr2, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("vend_addr3", $vend_addr3, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["City_State_Zip"] ?></td>
                  <td> 
                    <?= $f->fillTextBox("vend_city", $vend_city, 20, 32, "inbox") ?>
                    <?= $f->fillTextBox("vend_state", $vend_state, 2, 32, "inbox") ?>
                    <?= $f->fillTextBox("vend_zip", $vend_zip, 5, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang][Country] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("vend_country", $vend_country, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Email:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("vend_email", $vend_email, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Website:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("vend_url", $vend_url, 20, 64, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
            <td width="12">&nbsp; </td>
            <td width="270" valign="top" align="right" bgcolor="white"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Telephone_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $f->fillTextBox("vend_tel", $vend_tel, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver">Alt. Tel:&nbsp;</td>
                  <td width="136"> 
                    <?= $f->fillTextBox("vend_tel_alt", $vend_tel_alt, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Fax_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $f->fillTextBox("vend_fax", $vend_fax, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Exp_Acct_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $f->fillTextBox("vend_exp_acct", $vend_exp_acct, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["AP_Acct_no"] ?>&nbsp; </td>
                  <td> 
                    <?= $f->fillTextBox("vend_ap_acct", $vend_ap_acct, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Tax Rate&nbsp; </td>
                  <td> 
                    <?= $f->fillTextBox("vend_taxrate", $vend_taxrate+0, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang][Balance] ?>:&nbsp;</td>
                  <td width="136"> 
                    <?= $f->fillTextBox("vend_balance", sprintf("%0.2f", $vend_balance), 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">VC#&nbsp; </td>
                  <td> 
                    <?= $vend_last_no ?>
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
