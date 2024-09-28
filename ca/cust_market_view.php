                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" align="right"><strong>Customer View To Be Called</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
						| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&cust_code=$cust_code" ?>">List</a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <td width="97" align="right" bgcolor="silver"><?= $label[$lang][Customer_no] ?>:&nbsp;</td>
                            <td width="308"> 
                              <?= $cust_code  ?> 
                            </td>
                            <td width="11">&nbsp; </td>
                            <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Telephone_no"] ?>&nbsp;</td>
                            <td width="136"> 
                              <?= $cust_tel  ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Name"] ?>:&nbsp;</td>
                            <td> 
                              <?= $cust_name ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Fax_no"] ?>&nbsp;</td>
                            <td width="136"> 
                              <?= $cust_fax ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Address"] ?>:&nbsp;</td>
                            <td> 
                              <?= $cust_addr1 ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right" bgcolor="silver"><?= $label[$lang]["Sales_Acct_no"] ?>&nbsp;</td>
                            <td width="136"> 
                              <?= $cust_sls_acct ?>
                            </td>
                          </tr>
                          <tr> 
                            <td align="right" bgcolor="silver">&nbsp;</td>
                            <td> 
                              <?= $cust_addr2 ?>
                            </td>
                            <td>&nbsp;</td>
                            <td align="right" bgcolor="silver">&nbsp; </td>
                            <td> 
                              <?= $cust_ar_acct ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver">&nbsp;</td>
                            <td> 
                              <?= $cust_addr3 ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right" bgcolor="silver">Inti.Balance</td>
                            <td width="136"> 
                              <?= $cust_init_bal ?>
                            </td>
                          </tr>
                          <tr> 
                            <td align="right" bgcolor="silver"><?= $label[$lang]["City_State_Zip"] ?></td>
                            <td> 
                              <?= $cust_city ?>
                              <?= $cust_state ?>
                              <?= $cust_zip ?>
                            </td>
                            <td>&nbsp;</td>
                            <td align="right" bgcolor="silver">Balance</td>
                            <td> 
                              <?= $cust_balance ?>
                            </td>
                          </tr>
						  <tr> 
							<td align="right" bgcolor="silver">Delv Day:</td>
							<td><?= $weekbox[$cust_delv_week] ?></td>
                            <td>&nbsp;</td>
                            <td align="right" bgcolor="silver">Tax Code:&nbsp;</td>
                            <td> 
                              <?= $cust_tax_code ?>
                            </td>
						  </tr>
                          <tr> 
                            <td align="right" bgcolor="silver">Delivery</td>
                            <td><?= strtoupper($cust_delv_week) ?></td>
                            <td>&nbsp;</td>
                            <td align="right" bgcolor="silver">Term</td>
                            <td> 
                              <?= $cust_term ?>
                            </td>
                          </tr>
						  <tr> 
						    <td align="right" bgcolor="silver">Need Mkting:&nbsp;</td>
                            <td> 
								<?= ($cust_marketing=="t")?"YES":"NO" ?>
							</td>
                            <td>&nbsp;</td>
                            <td align="right" bgcolor="silver">Sales Rep.</td>
                            <td> 
                              <?= $cust_slsrep ?>
                            </td>
						  </tr>
						  <tr> 
						    <td align="right" bgcolor="silver">Status:</td>
							<td> 
							  <?= ($cust_active!="f")?"Active":"Inactive" ?>
							</td>
                            <td>&nbsp;</td>
                            <td align="right" bgcolor="silver">Credit Limit</td>
                            <td> 
                              <?= $cust_cr_limit ?>
                            </td>
						  </tr>

						</table>
					  </td>
                    </tr>
                  </table>
<?php
	include("custships_list.php"); 
?>