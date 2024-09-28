                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="purcv_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="purcv_add">
						<input type="hidden" name="purcv_purdtl_id" value="<?= $purcv_purdtl_id ?>">
					<tr align="right"> 
                      <td colspan="8"><strong>New Receivd Purchase</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
			| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=a" ?>">New</a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=l" ?>">List</a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="468" align="right" bgcolor="white" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
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
                    <?= $f->fillTextBox("purcv_date", date("m/d/Y"), 20, 20, "inbox") ?>
					<a href="javascript:openCalendar('purcv_date')">C</a>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Invoice#:&nbsp;</td>
                  <td width="80%"> 
                    <?= $f->fillTextBox("purcv_inv_no", $purcv_inv_no, 16, 16, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="20%" align="right" bgcolor="silver">Comment:&nbsp;</td>
                  <td width="80%"> 
                    <?= $f->fillTextBox("purcv_comnt", $purcv_comnt, 32, 64, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="submit" name="Submit32" value="Record"> 
                              <input type="reset" name="Submit222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
		  </form>
                </table>
