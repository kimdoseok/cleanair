                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" align="right"><strong>View Category</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a&cate_code=$cate_code" ?>"><?= $label[$lang]["New_1"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&cate_code=$cate_code" ?>"><?= $label[$lang]["Edit"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&cate_code=$cate_code" ?>"><?= $label[$lang]["List_1"] ?></a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Code:</td>
                  <td width="308"> 
                    <?= $cate_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Name 1:</td>
                  <td valign="top"> 
                    <?= $cate_name1 ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Name 2:</td>
                  <td valign="top"> 
                    <?= $cate_name2 ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Name 3:</td>
                  <td valign="top"> 
                    <?= $cate_name3 ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Name 4:</td>
                  <td valign="top"> 
                    <?= $cate_name4 ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver"><?= $label[$lang]["Description"] ?>:</td>
                  <td valign="top"> 
                    <?= $cate_desc ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Up Cat. Code:</td>
                  <td valign="top"> 
                    <?= $cate_up_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver"><?= $label[$lang]["Average_Cost"] ?>:</td>
                  <td> 
                    <?= $cate_ave_cost ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver"><?= $label[$lang]["Qty_On_Hand"] ?>:&nbsp;</td>
                  <td> 
                    <?= $cate_qty_onhnd ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver"><?= $label[$lang]["Unit"] ?>:&nbsp;</td>
                  <td> 
                    <?= $cate_unit ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Taxable:&nbsp;</td>
                  <td> 
                    <?= ($cate_tax=="t")?"Yes":"No" ?>
                  </td>
                </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&cate_code=$cate_code&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&cate_code=$cate_code&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
          										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&cate_code=$cate_code&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&cate_code=$cate_code&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>

                    </tr>

                  </table>

