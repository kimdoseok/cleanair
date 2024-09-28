                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="terms_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="terms_add">
                    <tr align="right"> 
                      <td colspan="8"><strong>New Terms</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="468" align="right" bgcolor="white">
			  <table width="100%" border="0" cellspacing="1" cellpadding="0">
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
				    <INPUT TYPE="radio" NAME="term_type" VALUE="r" checked>AR
					<INPUT TYPE="radio" NAME="term_type" VALUE="p">AP
					<INPUT TYPE="radio" NAME="term_type" VALUE="b">Both
				  </td>
				</tr>
				<tr>
				  <td width="97" align="right" bgcolor="silver">Days:&nbsp;</td>
				  <td><?= $f->fillTextBox("term_days", $term_days, 32, 32, "inbox") ?></td>
				</tr>
			  </table>
			</td>
            <td width="12">&nbsp; </td>
            <td width="270" align="right" bgcolor="white">
			  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
				  <td width="95" align="right" bgcolor="white">&nbsp;</td>
				  <td width="136">&nbsp;</td>
				</tr>
			  </table>
			</td>
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
