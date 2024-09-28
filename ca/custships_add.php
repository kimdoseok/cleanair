                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ar_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="custship_add">
                    <tr align="right"> 
                      <td colspan="8"><strong>New Shipping Address</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a&cust_code=$cust_code" ?>">New</a> |
                        <a href="<?php echo "customers.php?cust_code=$cust_code&ty=e" ?>">Customer</a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="476" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Customer #:&nbsp;</td>
                  <td width="308"><?= $cust_code ?><INPUT TYPE="hidden" NAME="cust_code" VALUE="<?= $cust_code ?>"></td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Name"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("custship_name", $custship_name, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Address"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("custship_addr1", $custship_addr1, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("custship_addr2", $custship_addr2, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("custship_addr3", $custship_addr3, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["City_State_Zip"] ?></td>
                  <td> 
                    <?= $f->fillTextBox("custship_city", $custship_city, 20, 32, "inbox") ?>
                    <?= $f->fillTextBox("custship_state", $custship_state, 2, 32, "inbox") ?>
                    <?= $f->fillTextBox("custship_zip", $custship_zip, 5, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Tel</td>
                  <td> 
                    <?= $f->fillTextBox("custship_tel", $custship_tel, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Fax</td>
                  <td> 
                    <?= $f->fillTextBox("custship_fax", $custship_fax, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Delvery</td>
                  <td> 
                    <?= $f->fillSelectBox($f->weekbox,"custship_delv_week", "value", "name", $custship_delv_week) ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Ship Via</td>
                  <td> 
                    <?= $f->fillTextBox("custship_shipvia", $custship_shipvia, 20, 32, "inbox") ?>
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
