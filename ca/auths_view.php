                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="1" align="right"><strong>View Auth</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="1" align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&auth_code=$auth_code" ?>"><?= $label[$lang]["Edit"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&auth_code=$auth_code" ?>"><?= $label[$lang]["List_1"] ?></a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1" width="450"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<tr> 
						  <td align="right" bgcolor="silver">Code:&nbsp;</td>
						  <td width="308"> 
							<?= $auth_code ?>
						  </td>
						</tr>
						<tr> 
						  <td align="right" bgcolor="silver"><?= $label[$lang]["Name"] ?>:&nbsp;</td>
						  <td> 
							<?= $auth_name ?>
						  </td>
						</tr>
						<tr> 
						  <td align="right" bgcolor="silver">Description:&nbsp;</td>
						  <td> 
							<?= $auth_desc ?>
						  </td>
						</tr>
						<tr> 
						  <td align="right" bgcolor="silver">Status:</td>
						  <td> 
							<?= ($auth_active!="f")?"Active":"Inactive" ?>
						  </td>
						</tr>

						</table>
					  </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&auth_code=$auth_code&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&auth_code=$auth_code&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&auth_code=$auth_code&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&auth_code=$auth_code&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
