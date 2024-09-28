				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="itemunit_edit">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Item/Unit</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo "itemunits.php?ty=a&itemunit_item=$itemunit_item" ?>">New</a>  |
						<a href="<?php echo "itemunits.php?ty=v&itemunit_item=$itemunit_item&itemunit_unit=$itemunit_unit" ?>">View</a>  |
                        <a href="<?php echo "items.php?item_code=$itemunit_item&ty=$ty" ?>">Item</a>  | </font></td>
                      <td colspan="4" align="right"><font size="2">|
                        <a href="<?= "ic_proc.php?cmd=itemunit_del&itemunit_item=$itemunit_item&itemunit_unit=$itemunit_unit" ?>">Delete</a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="476" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="25%" align="right" bgcolor="silver">Item #:&nbsp;</td>
                  <td width="75%"><?= $itemunit_item ?><INPUT TYPE="hidden" NAME="itemunit_item" VALUE="<?= $itemunit_item ?>"></td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Item Unit:&nbsp;</td>
                  <td><?= strtoupper($it_arr["item_unit"]) ?></td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Unit #:&nbsp;</td>
                  <td> 
				    <?= strtoupper($itemunit_unit) ?>
					<INPUT TYPE="hidden" NAME="itemunit_unit" VALUE="<?= $itemunit_unit ?>">
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Factor:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("itemunit_factor", $itemunit_factor+0, 16, 16, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Qty:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("itemunit_qty", $itemunit_qty+0, 16, 16, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Cost:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("itemunit_cost", $itemunit_cost, 16, 16, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Type:&nbsp;</td>
                  <td> 
				  <INPUT TYPE="checkbox" NAME="itemunit_buy" VALUE="t" <?= ($itemunit_buy=="t")?"checked":"" ?>>Buyable
				  <INPUT TYPE="checkbox" NAME="itemunit_sell" VALUE="t" <?= ($itemunit_sell=="t")?"checked":"" ?>>Sellable
				  <INPUT TYPE="checkbox" NAME="itemunit_stock" VALUE="t" <?= ($itemunit_stock=="t")?"checked":"" ?>>Stockable
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Status:</td>
                  <td> 
				    <INPUT TYPE="radio" NAME="itemunit_active" VALUE="t" <?= ($itemunit_active!="f")?"CHECKED":"" ?>>Active
					<INPUT TYPE="radio" NAME="itemunit_active" VALUE="f" <?= ($itemunit_active=="f")?"CHECKED":"" ?>>Inactive
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="submit" name="Submit32" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="reset" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
