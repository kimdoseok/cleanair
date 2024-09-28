                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td align="right"><strong><?= $label[$lang][View_Customer] ?></strong></td>
                    </tr>
                    <tr> 
                      <td align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a&cust_code=$cust_code" ?>"><?= $label[$lang]["New_1"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&cust_code=$cust_code&custship_id=$custship_id" ?>"><?= $label[$lang]["Edit"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&cust_code=$cust_code" ?>"><?= $label[$lang]["List_1"] ?></a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <td width="100" align="right" bgcolor="silver">Customer #:</td>
                            <td width="500"> 
                              <?= $custship_cust_code  ?> 
                            </td>
                          </tr>
                          <tr> 
                            <td align="right" bgcolor="silver"><?= $label[$lang]["Name"] ?>:&nbsp;</td>
                            <td> 
                              <?= $custship_name ?>
                            </td>
                          </tr>
                          <tr> 
                            <td align="right" bgcolor="silver"><?= $label[$lang]["Address"] ?>:&nbsp;</td>
                            <td> 
                              <?= $custship_addr1 ?>
                            </td>
                          </tr>
                          <tr> 
                            <td align="right" bgcolor="silver">&nbsp;</td>
                            <td> 
                              <?= $custship_addr2 ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">&nbsp;</td>
                            <td> 
                              <?= $custship_addr3 ?>
                            </td>
                          </tr>
                          <tr> 
                            <td align="right" bgcolor="silver"><?= $label[$lang]["City_State_Zip"] ?></td>
                            <td> 
                              <?= $custship_city ?>
                              <?= $custship_state ?>
                              <?= $custship_zip ?>
                            </td>
                          </tr>
                          <tr> 
                            <td align="right" bgcolor="silver"><?= $label[$lang]["Telephone_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $custship_tel  ?>
                            </td>
                          </tr>
						  <tr> 
                            <td align="right" bgcolor="silver"><?= $label[$lang]["Fax_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $custship_fax ?>
                            </td>
                          </tr>
						  <tr> 
							<td align="right" bgcolor="silver">Delv Day:</td>
							<td><?= strtoupper($custship_delv_week) ?></td>
						  </tr>
						  <tr> 
						    <td align="right" bgcolor="silver">Status:</td>
							<td> 
							  <?= ($custship_active!="f")?"Active":"Inactive" ?>
							</td>
						  </tr>

						</table>
					  </td>
                    </tr>
                    <tr align="right"> 
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&custship_custship_custship_code=$custship_custship_custship_code&dir=-2" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&custship_custship_code=$custship_custship_code&dir=-1" ?>">&lt;Prev</a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&custship_custship_code=$custship_custship_code&dir=1" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&custship_custship_code=$custship_custship_code&dir=2" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
