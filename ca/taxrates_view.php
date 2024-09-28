                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>View Tax Rates</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&taxrate_code=$taxrate_code" ?>"><?= $label[$lang]["Edit"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="466" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Code:&nbsp;</td>
                  <td width="308"> 
                    <?= $taxrate_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td> 
                    <?= $taxrate_desc ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Rate(%):&nbsp;</td>
                  <td> 
                    <?= $taxrate_pct ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $taxrate_user_code ?>
                  </td>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&taxrate_code=$taxrate_code" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&taxrate_code=$taxrate_code" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&taxrate_code=$taxrate_code" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&taxrate_code=$taxrate_code" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
