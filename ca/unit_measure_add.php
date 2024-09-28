                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="unit_add">
                    <tr align="right"> 
                      <td colspan="8"><strong>New Unit of Measure</strong></td>
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
                  <td width="20%" align="right" bgcolor="silver">Unit#:&nbsp;</td>
                  <td width="80%"> 
                    <?= $f->fillTextBox("unit_code", $unit_code, 16, 16, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Name:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("unit_name", $unit_name, 32, 64, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("unit_desc", $unit_desc, 64, 250, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Type&nbsp;</td>
                  <td> 
				    <SELECT NAME="unit_type">
					  <OPTION value="e" <?= ($unit_type=="e")?"SELECTED":"" ?>>Each</OPTION>
					  <OPTION value="v" <?= ($unit_type=="v")?"SELECTED":"" ?>>Volume</OPTION>
					  <OPTION value="l" <?= ($unit_type=="l")?"SELECTED":"" ?>>Length</OPTION>
					  <OPTION value="a" <?= ($unit_type=="a")?"SELECTED":"" ?>>Area</OPTION>
					  <OPTION value="w" <?= ($unit_type=="w")?"SELECTED":"" ?>>Weight</OPTION>
					</SELECT>
                    <?= $f->fillTextBox("unit_type", $unit_type, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Factor&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("unit_factor", (empty($unit_factor))?"1":$unit_factor, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Prime?&nbsp;</td>
                  <td> 
				    <INPUT TYPE="checkbox" NAME="unit_prime" VALUE="t" <?= ($unit_prime!="f")?"checked":"" ?>>
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
