                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="product_line_add">
                    <tr align="right"> 
                      <td colspan="8"><strong>New Product Line</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="100%" align="right" bgcolor="white" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Product Line #:&nbsp;</td>
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
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="submit" name="Submit32" value="Record"> 
                              <input type="reset" name="Submit222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>
                  </table>
