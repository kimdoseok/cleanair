                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="purcv_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="purcv_edit">
						<input type="hidden" name="purcv_purdtl_id" value="<?= $purcv_purdtl_id ?>">
						<input type="hidden" name="purcv_id" value="<?= $purcv_id ?>">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Received Purchase</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=v&purcv_id=$purcv_id" ?>">View</a>  |
								</font>
					  </td>
					  <td colspan="4" align="right">|
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">
								<a href="<?php echo "purcv_proc.php?cmd=purcv_del&purcv_id=$purcv_id" ?>">Delete</a>  |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="466" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Item:&nbsp;</td>
                  <td width="80%"> 
                    <?= $purdtl_item_code ?>
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
                    <?= $f->fillTextBox("purcv_qty", $purcv_qty, 32, 64, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Date:&nbsp;</td>
                  <td width="80%"> 
                    <?= $f->fillTextBox("purcv_date", $d->toUsaDate($purcv_date), 32, 64, "inbox") ?>
					<a href="javascript:openCalendar('purcv_date')">C</a>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Invoice#:&nbsp;</td>
                  <td width="80%"> 
                    <?= $f->fillTextBox("purcv_inv_no", $purcv_inv_no, 32, 64, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Comment:&nbsp;</td>
                  <td width="80%"> 
                    <?= $f->fillTextBox("purcv_comnt", $purcv_comnt, 32, 64, "inbox") ?>
                  </td>
                </tr>
              </table></td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=e&dir=-2&purcv_id=$purcv_id" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=e&dir=-1&purcv_id=$purcv_id" ?>">&lt;Prev</a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=e&dir=1&purcv_id=$purcv_id" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=e&dir=2&purcv_id=$purcv_id" ?>">Last&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="Record"> 
                              <input type="reset" name="Submit2222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
