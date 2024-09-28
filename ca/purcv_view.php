                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>View Received Purchase</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2"> 
					  | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=a" ?>">New</a>  
					  | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=e&purcv_id=$purcv_id" ?>">Edit</a>  
					  | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=l" ?>">List</a> | </font></td>
                    </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Item:&nbsp;</td>
                  <td width="80%"> 
                    <?= $purdtl_item_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td width="80%"> 
                    <?= $purdtl_item_desc ?>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Purch.Qty:&nbsp;</td>
                  <td width="80%"> 
                    <?= $purdtl_qty ?>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Quantity:&nbsp;</td>
                  <td width="80%"> 
                    <?= $purcv_qty+0 ?>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Date:&nbsp;</td>
                  <td width="80%"> 
                    <?= $d->toUsaDate($purcv_date) ?>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Invoice#:&nbsp;</td>
                  <td width="80%"> 
                    <?= $purcv_inv_no ?>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Comment:&nbsp;</td>
                  <td width="80%"> 
                    <?= $purcv_comnt ?>
                  </td>
                </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8">
					    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=v&dir=-2&purcv_id=$purcv_id" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=v&dir=-1&purcv_id=$purcv_id" ?>">&lt;Prev</a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=v&dir=1&purcv_id=$purcv_id" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=v&dir=2&purcv_id=$purcv_id" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table>
					  </td>
                    </tr>
                  </table>
