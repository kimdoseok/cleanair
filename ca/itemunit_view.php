				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td><strong>View Item/Unit</strong></td>
                    </tr>
                    <tr> 
                      <td align="left"><font size="2"> | <a href="<?php echo "itemunits.php?ty=a&itemunit_item=$itemunit_item" ?>">New</a>  |
						<a href="<?php echo "itemunits.php?ty=e&itemunit_item=$itemunit_item&itemunit_unit=$itemunit_unit" ?>">Edit</a>  |
                        <a href="<?php echo "items.php?item_code=$itemunit_item&ty=$ty" ?>">Item</a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="476" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="25%" align="right" bgcolor="silver">Item #:&nbsp;</td>
                  <td width="75%"><?= $itemunit_item ?></td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Item Unit:&nbsp;</td>
                  <td><?= strtoupper($it_arr["item_unit"]) ?></td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Unit #:&nbsp;</td>
                  <td> 
				    <?= strtoupper($itemunit_unit) ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Factor:&nbsp;</td>
                  <td> 
                    <?= $itemunit_factor+0 ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Qty:&nbsp;</td>
                  <td> 
                    <?= $itemunit_qty+0 ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Cost:&nbsp;</td>
                  <td> 
                    <?= $itemunit_cost ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Type:&nbsp;</td>
                  <td> 
				    <?= ($itemunit_buy!="f")?"Buyable":"Non-buyable" ?> /
				    <?= ($itemunit_sell!="f")?"Sellable":"Non-sellable" ?> /
				    <?= ($itemunit_stock!="f")?"Sellable":"Non-stockable" ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Status:</td>
                  <td> 
				    <?= ($itemunit_active!="f")?"Active":"Inactive" ?>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                  </table>
