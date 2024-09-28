                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" align="right"><strong><?= $label[$lang]["View_Account"] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&acct_code=$acct_code" ?>"><?= $label[$lang]["Edit"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&acct_code=$acct_code" ?>"><?= $label[$lang]["List_1"] ?></a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Acct_no"] ?>:&nbsp;</td>
                            <td width="308"> 
                              <?= $acct_code  ?>
                            </td>
                            <td width="11">&nbsp; </td>
                            <td width="95" align="right">&nbsp;</td>
                            <td width="136"> 
                              &nbsp;
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Type"] ?>:&nbsp;</td>
                            <td> 
                              <?= $type ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">&nbsp;</td>
                            <td width="136"> 
                              &nbsp;
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Description"] ?>:&nbsp;</td>
                            <td> 
                              <?= $acct_desc ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">&nbsp;</td>
                            <td width="136"> 
                              &nbsp;
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
