                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="unit_edit">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Unit of Measure</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&unit_code=$unit_code" ?>"><?= $label[$lang]["View"] ?></a>  |
								</font>
					  </td>
					  <td colspan="4" align="right">|
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">
								<a href="<?php echo "ic_proc?cmd=unit_del&unit_code=$unit_code" ?>">Delete</a>  |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="466" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
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
              </table></td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-2&unit_code=$unit_code" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-1&unit_code=$unit_code" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=1&unit_code=$unit_code" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=2&unit_code=$unit_code" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="reset" name="Submit2222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
