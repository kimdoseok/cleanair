                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" align="right"><strong>View_Account</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&acct_code=$acct_code" ?>">Edit</a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&acct_code=$acct_code" ?>">List</a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Reference_no:&nbsp;</td>
                  <td width="308"> 
                    <?= $rcptdtl_ref_id ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Acct_no:&nbsp;</td>
                  <td> 
                    <?= $rcptdtl_acct_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Amount:&nbsp;</td>
                  <td> 
                    <?= $rcptdtl_amt ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td> 
                    <?= $rcptdtl_desc ?>
                  </td>
                </tr>
              </table> </td>
          </tr>

                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=-2" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=-1" ?>">&lt;Prev_1</a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=1" ?>">Next_1&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=2" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
