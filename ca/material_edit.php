                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="material_edit">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Material</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&material_code=$material_code" ?>">View</a>  |
								</font>
					  </td>
					  <td colspan="4" align="right">|
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">
								<a href="<?php echo "ic_proc?cmd=material_del&material_code=$material_code" ?>">Delete</a>  |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="466" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Material#:&nbsp;</td>
                  <td width="80%"> 
                    <?= $f->fillTextBox("material_code", $material_code, 16, 16, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Name:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("material_name", $material_name, 32, 64, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("material_desc", $material_desc, 64, 250, "inbox") ?>
                  </td>
                </tr>
              </table></td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-2&material_code=$material_code" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-1&material_code=$material_code" ?>">&lt;Prev</a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=1&material_code=$material_code" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=2&material_code=$material_code" ?>">Last&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="Record"> 
                              <input type="reset" name="Submit2222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
