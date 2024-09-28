<?php
	if (empty($itemunit_unit)) $itemunit_unit = "ea";
?>
				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="itemunit_add">
                    <tr align="right"> 
                      <td colspan="8"><strong>New item/Unit</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a&item_code=$item_code" ?>">New</a> |
                        <a href="<?php echo "items.php?item_code=$itemunit_item&ty=e" ?>">Items</a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="476" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Item #:&nbsp;</td>
                  <td width="308"><?= $itemunit_item ?><INPUT TYPE="hidden" NAME="itemunit_item" VALUE="<?= $itemunit_item ?>"></td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Item Unit:&nbsp;</td>
                  <td width="308"><?= strtoupper($it_arr["item_unit"]) ?></td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Unit #:&nbsp;</td>
                  <td> 
				    <?= $f->fillSelectBox($unitbox,"itemunit_unit", "value", "name", $itemunit_unit) ?>
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
				  <INPUT TYPE="checkbox" NAME="itemunit_buy" value="t" checked>Buyable
				  <INPUT TYPE="checkbox" NAME="itemunit_sell" value="t" checked>Sellable
				  <INPUT TYPE="checkbox" NAME="itemunit_stock" value="t" checked>Stockable
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
<?php
//	include("itemunit_list.php");
?>
