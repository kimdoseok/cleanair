
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" align="right"><strong><?= $label[$lang]["View_Item"] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a&item_code=$item_code" ?>">New</a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&item_code=$item_code" ?>">Edit</a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&item_code=$item_code" ?>">List</a> |
						<a href="javascript:openHistory('<?= $item_code ?>')">Change History</a> | 
						</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="400" align="right" bgcolor="white"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Item #:</td>
                  <td width="308"> 
                    <?= $item_code ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Description:</td>
                  <td valign="top"> 
                    <?= $item_desc ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Type:</td>
                  <td valign="top"> 
<?php
	$item_type = strtolower($item_type);
	if ($item_type=="s") $item_type_name = "Supply";
	else if ($item_type=="e") $item_type_name = "Equipment";
	else if ($item_type=="p") $item_type_name = "Part";
	else if ($item_type=="n") $item_type_name = "Note";
	else if ($item_type=="m") $item_type_name = "Service";
	else $item_type_name = "Other";
?>
					<?= $item_type_name ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Category:</td>
                  <td valign="top"> 
					<?= $item_cate_code ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">MSRP:</td>
                  <td> 
                    <?= $item_msrp ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Average_Cost"] ?>:</td>
                  <td> 
                    <?= $item_ave_cost ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Unit"] ?>:</td>
                  <td> 
<?php
	for ($i=0;$i<$u_num;$i++) {
		if ($u_arr[$i]["unit_code"] == $item_unit) {
			$unit_name = $u_arr[$i]["unit_name"];
			break;
		}
	}
?>
                    <?= $unit_name ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Qty_On_Hand"] ?>:&nbsp;</td>
                  <td> 
                    <?= $item_qty_onhnd ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Product Line:&nbsp;</td>
                  <td> 
                    <?= $item_prod_line ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Primary Vendor:&nbsp;</td>
                  <td> 
                    <?= $item_vend_code ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Material:&nbsp;</td>
                  <td> 
                    <?= $item_material ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Vendor Prod.Code:</td>
                  <td> 
                    <?= $item_vend_prod_code ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Vendor Product:</td>
                  <td> 
                    <?= $item_vend_prod_name ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Inv. Acct#:</td>
                  <td> 
                    <?= $item_inv_acct ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Taxable:&nbsp;</td>
                  <td> 
                    <?= ($item_tax=="t")?"Yes":"No" ?>
                  </td>
                </tr>
				<tr> 
				  <td align="right" bgcolor="silver">Status:</td>
				  <td> 
					<?= ($item_active!="f")?"Active":"Inactive" ?>
				  </td>
				</tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&item_code=$item_code&dir=-2" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&item_code=$item_code&dir=-1" ?>">&lt;Prev</a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&item_code=$item_code&dir=1" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&item_code=$item_code&dir=2" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
<?php
	include("itemunit_list.php");
?>