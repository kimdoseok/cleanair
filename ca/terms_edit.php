                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="terms_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="terms_edit">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Terms</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&term_code=$term_code" ?>"><?= $label[$lang]["View"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="466" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
				  <td width="97" align="right" bgcolor="silver">Code:&nbsp;</td>
				  <td width="308">
					<?= $f->fillTextBox("term_code", $term_code, 32, 32, "inbox") ?>
                  
				  </td>
				</tr>
				<tr>

                 <td width="97" align="right" bgcolor="silver">Description:&nbsp;</td>
                  
				  <td><?= $f->fillTextBox("term_desc", $term_desc, 32, 32, "inbox") ?></td>
				</tr>
				<tr>
				  <td width="97" align="right" bgcolor="silver">Type:&nbsp;</td>
				  <td>
				    <INPUT TYPE="radio" NAME="term_type" VALUE="r" <?= ($term_type=="r")?"checked":"" ?>>AR
					<INPUT TYPE="radio" NAME="term_type" VALUE="p" <?= ($term_type=="p")?"checked":"" ?>>AP
					<INPUT TYPE="radio" NAME="term_type" VALUE="b" <?= ($term_type=="b")?"checked":"" ?>>Both
				  </td>
				</tr>
				<tr>
				  <td width="97" align="right" bgcolor="silver">Days:&nbsp;</td>
				  <td><?= $f->fillTextBox("term_days", $term_days, 32, 32, "inbox") ?></td>
				</tr>
              </table> </td>
            <td width="12">&nbsp; </td>
            <td width="270" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="95" align="right" bgcolor="white">&nbsp;</td>
                  <td width="136"> 
                    &nbsp;
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
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="reset" name="Submit2222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
