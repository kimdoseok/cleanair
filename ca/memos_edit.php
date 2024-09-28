                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="product_line_edit">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Product Line</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&productline_code=$productline_code" ?>">View</a>  |
								</font>
					  </td>
					  <td colspan="4" align="right">|
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">
								<a href="<?php echo "ic_proc?cmd=product_line_del&productline_code=$productline_code" ?>">Delete</a>  |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="100%" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Product Line#:&nbsp;</td>
                  <td width="80%"> 
                    <?= $f->fillTextBox("productline_code", $productline_code, 16, 16, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Name:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("productline_name", $productline_name, 32, 64, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("productline_desc", $productline_desc, 64, 250, "inbox") ?>
                  </td>
                </tr>
              </table></td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-2&productline_code=$productline_code" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-1&productline_code=$productline_code" ?>">&lt;Prev</a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=1&productline_code=$productline_code" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=2&productline_code=$productline_code" ?>">Last&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="Record"> 
                              <input type="reset" name="Submit2222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
