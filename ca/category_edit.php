                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="cate_edit">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Category</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&cate_code=$cate_code" ?>"><?= $label[$lang]["View"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="ic_proc.php?cmd=cate_del&cate_code=<?= $cate_code ?>"><?= $label[$lang]["Del"] ?></a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="400" align="right" bgcolor="white"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Code:</td>
                  <td width="308"> 
                    <?= $f->fillHidden("cate_code", $cate_code) ?>
                    <?= strtoupper($cate_code) ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Name 1:</td>
                  <td valign="top"> 
                    <?= $f->fillTextBox("cate_name1", $cate_name1, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Name 2:</td>
                  <td valign="top"> 
                    <?= $f->fillTextBox("cate_name2", $cate_name2, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Name 3:</td>
                  <td valign="top"> 
                    <?= $f->fillTextBox("cate_name3", $cate_name3, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Name 4:</td>
                  <td valign="top"> 
                    <?= $f->fillTextBox("cate_name4", $cate_name4, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver"><?= $label[$lang]["Description"] ?>:</td>
                  <td valign="top"> 
                    <?= $f->fillTextBox("cate_desc", $cate_desc, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Up Cat.Code:</td>
                  <td valign="top"> 
					<?= $f->fillTextBox("cate_up_code", $cate_up_code, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver"><?= $label[$lang]["Average_Cost"] ?>:</td>
                  <td> 
                    <?= $f->fillTextBox("cate_ave_cost", $cate_ave_cost, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver"><?= $label[$lang]["Unit"] ?>:</td>
                  <td> 
                    <?= $f->fillTextBox("cate_unit", $cate_unit, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Taxable:&nbsp;</td>
                  <td> 
                    <INPUT TYPE="checkbox" NAME="cate_tax" value="t" <?= ($cate_tax=="t")?"checked":"" ?>>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&cate_code=$cate_code&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&cate_code=$cate_code&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&cate_code=$cate_code&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&cate_code=$cate_code&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="submit" name="Submit2222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
