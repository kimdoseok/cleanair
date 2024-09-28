                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>View Sales Rep</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&slsrep_code=$slsrep_code" ?>"><?= $label[$lang]["Edit"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="466" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Sales Rep#:&nbsp;</td>
                  <td width="308"> 
                    <?= $slsrep_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Name"] ?>:&nbsp;</td>
                  <td> 
                    <?= $slsrep_name ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Address"] ?>:&nbsp;</td>
                  <td> 
                    <?= $slsrep_addr1 ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $slsrep_addr2 ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $slsrep_addr2 ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["City_State_Zip"] ?></td>
                  <td> 
                    <?= $slsrep_city ?>
                    <?= $slsrep_state ?>
                    <?= $slsrep_zip ?>
                  </td>
                </tr>
              </table> </td>
            <td width="12">&nbsp; </td>
            <td width="270" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Telephone_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $slsrep_tel ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Fax_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $slsrep_fax ?>
                  </td>
                </tr>
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Exp_Acct_no"] ?>&nbsp;</td>
                  <td width="136"> 
                    <?= $slsrep_exp_acct ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["AP_Acct_no"] ?>&nbsp; </td>
                  <td> 
                    <?= $slsrep_ap_acct ?>
                  </td>
                </tr>
<!--
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang][Balance] ?>:&nbsp;</td>
                  <td width="136"> 
                    <?= $slsrep_balance ?>
                  </td>
                </tr>
-->
				<tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang][Country] ?>:&nbsp;</td>
                  <td> 
                    <?= $slsrep_country ?>
                  </td>
                </tr>
              </table></td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8">
					    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&slsrep_code=$slsrep_code" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&slsrep_code=$slsrep_code" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&slsrep_code=$slsrep_code" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&slsrep_code=$slsrep_code" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table>
					  </td>
                    </tr>
                  </table>
