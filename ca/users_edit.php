				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="sy_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="user_edit">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit User</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&user_code=$user_code" ?>"><?= $label[$lang]["View"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                      <td colspan="4" align="right"><font size="2">|
                        <a href="<?php echo "sy_proc.php?cmd=user_del&user_code=$user_code" ?>">Delete</a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="465" align="right" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="100" align="right" bgcolor="silver">Code:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("user_code", $user_code, 16, 16, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Password:&nbsp;</td>
                  <td> 
				    <INPUT TYPE="password" CLASS="inbox" NAME="user_passwd" VALUE="<?= $user_passwd ?>">
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Name"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("user_name", $user_name, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("user_desc", $user_desc, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Printer:</td>
                  <td>
				    <INPUT TYPE="radio" NAME="user_printer" VALUE="LPT1" <?= ($user_printer=="LPT1")?"CHECKED":"" ?>>LPT1
				    <INPUT TYPE="radio" NAME="user_printer" VALUE="LPT2" <?= ($user_printer=="LPT2")?"CHECKED":"" ?>>LPT2
					  <INPUT TYPE="radio" NAME="user_printer" VALUE="LPT3" <?= ($user_printer=="LPT3")?"CHECKED":"" ?>>LPT3
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Status:</td>
                  <td> 
				    <INPUT TYPE="radio" NAME="user_active" VALUE="t" <?= ($user_active!="f")?"CHECKED":"" ?>>Active
					<INPUT TYPE="radio" NAME="user_active" VALUE="f" <?= ($user_active=="f")?"CHECKED":"" ?>>Inactive
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&user_code=$user_code&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&user_code=$user_code&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&user_code=$user_code&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&user_code=$user_code&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="reset" name="Submit2222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
