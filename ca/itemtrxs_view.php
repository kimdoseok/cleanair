                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" align="right"><strong><?= $label[$lang][View_Item_Transaction] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&invtrx_id=$invtrx_id" ?>"><?= $label[$lang]["Edit"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&invtrx_id=$invtrx_id" ?>">List</a> | </font></td>
                    </tr>
                      <tr align="right"> 
                        <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                            <tr> 
                              <td width="120" bgcolor="silver">InvTrx #</td>
                              <td width="308"> 
                                <?= $invtrx_id ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">PO #&nbsp;</td>
                              <td> 
                                <?= $invtrx_po_no ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
	                        <tr>

                              <td width="120" bgcolor="silver">Vendor#</td>

                              <td><?= $invtrx_vend_code ?></td>
							  <td>&nbsp; </td>
							</tr>
							<tr> 
                              <td width="120" bgcolor="silver">Item Code</td>
                              <td><?= $invtrx_item_code ?></td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver"><?= $label[$lang]["Unit"] ?></td>
                              <td> 
                                <?= $invtrx_unit ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td bgcolor="silver"><?= $label[$lang]["Date_1"] ?></td>
                              <td> 
                                <?= $invtrx_date ?>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">Acct #</td>
                              <td> 
                                <?= $invtrx_acct_code ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">Invoice #</td>
                              <td> 
                                <?= $invtrx_ref_code ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver"><?= $label[$lang]["Cost"] ?>:</td>
                              <td> 
                                <?= $invtrx_cost ?>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td bgcolor="silver">Qty:</td>
                              <td> 
                                <?= $invtrx_qty ?>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td bgcolor="silver">Type:</td>
                              <td> <?php
								  if ($invtrx_type=="s") echo $label[$lang]["Sales"];
								  else if ($invtrx_type=="r") echo $label[$lang][Receiving];
								  else if ($invtrx_type=="a") echo $label[$lang][Adjust];
							  ?> </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td bgcolor="silver"><?= $label[$lang][Reference] ?>:</td>
                              <td colspan="2"> 
                                <?= $invtrx_desc ?>
                              </td>
                            </tr>




                          </table></td>
                      </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&invtrx_id=$invtrx_id&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&invtrx_id=$invtrx_id&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&invtrx_id=$invtrx_id&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&invtrx_id=$invtrx_id&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
