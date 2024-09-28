<?php
	$u = new Unit();
	$u_num = $u->getUnitRows();
	$u_arr = $u->getUnitList("name","","f",1,$u_num);
	$unitbox = array();
	for ($i=0;$i<$u_num;$i++) {
		$tmp = array("value"=>"", "name"=>"");
		$tmp["value"] = $u_arr[$i]["unit_code"];
		$tmp["Name"] = $u_arr[$i]["unit_name"];
		array_push($unitbox, $tmp);
	}

	$v = new Vends();
	if (!empty($vend_code)) {
		if ($varr = $v->getVends($vend_code)) {
			$item_vend_code	= strtoupper($vend_code);
			$not_found_vend = 0;
		} else {
			$not_found_vend = 1;
		}
	} else {
		$not_found_vend = 0;
	}
	$m = new Material();
	if (!empty($mate_code)) {
		if ($marr = $m->getMaterial($mate_code)) {
			$item_material	= strtoupper($mate_code);
			$not_found_mate = 0;
		} else {
			$not_found_mate = 1;
		}
	} else {
		$not_found_mate = 0;
	}
	$p = new ProductLine();
	if (!empty($pline_code)) {
		if ($parr = $p->getProductLine($pline_code)) {
			$item_prod_line	= strtoupper($pline_code);
			$not_found_pline = 0;
		} else {
			$not_found_pline = 1;
		}
	} else {
		$not_found_pline = 0;
	}
	if (empty($item_inv_acct)) $item_inv_acct = "12000";
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
<?php
	if ($not_found_vend == 1) {
		echo "	openVendBrowseFilter('item_vend_code', '$vend_code');\n";
	}
	if ($not_found_mate == 1) {
		echo "	openMaterialBrowseFilter('item_material', '$mate_code');\n";
	}
	if ($not_found_pline == 1) {
		echo "	openProductLineBrowseFilter('item_prod_line', '$pline_code');\n";
	}
?>

//-->
</SCRIPT>
				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
  <form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="item_add">
						<?= $f->fillHidden("ty","a") ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>New Item</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="475" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Item #:</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("item_code", $item_code, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Description:</td>
                  <td valign="top"> 
                    <?= $f->fillTextBox("item_desc", $item_desc, 32, 250, "inbox") ?>
                  </td>
                </tr>
				<tr> 
                  <td align="right" bgcolor="silver">Type:</td>
                  <td valign="top"> 
					<?= $f->fillSelectBox($typebox,"item_type", "value", "name", $item_type) ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Category:</td>
                  <td valign="top"> 
					<?= $f->fillSelectBox($catbox,"item_cate_code", "value", "Name", $item_cate_code) ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">MSRP:</td>
                  <td> 
                    <?= $f->fillTextBox("item_msrp", $item_msrp, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Average Cost:</td>
                  <td> 
                    <?= $f->fillTextBox("item_ave_cost", $item_ave_cost, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Unit:</td>
                  <td> 
				    <?= $f->fillSelectBox($unitbox,"item_unit", "value", "Name", $item_unit) ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Qty On Hand:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBoxRO("item_qty_onhnd", $item_qty_onhnd, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><A HREF="javascript:openProductLineBrowse('item_prod_line')">Product Line</A>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("item_prod_line", $item_prod_line, 8, 16, "inbox", "onChange='updateField(\"item_prod_line\")'") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><A HREF="javascript:openVendBrowse('item_vend_code')">Primary Vendor</A>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("item_vend_code", $item_vend_code, 8, 16, "inbox", "onChange='updateField(\"item_vend_code\")'") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><A HREF="javascript:openMaterialBrowse('item_material')">Material</A>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("item_material", $item_material, 8, 16, "inbox", "onChange='updateField(\"item_material\")'") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Vendor Prod.Code:</td>
                  <td> 
                    <?= $f->fillTextBox("item_vend_prod_code", $item_vend_prod_code, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Vendor Product:</td>
                  <td> 
                    <?= $f->fillTextBox("item_vend_prod_name", $item_vend_prod_name, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><A HREF="javascript:openAcctBrowse('item_inv_acct')">Inv. Acct#:</A></td>
                  <td> 
                    <?= $f->fillTextBox("item_inv_acct", $item_inv_acct, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="110" align="right" bgcolor="silver">Taxable:&nbsp;</td>
                  <td> 
                    <INPUT TYPE="checkbox" NAME="item_tax" value="t" <?= ($item_tax=="t")?"checked":"" ?>>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Status:</td>
                  <td> 
				    <INPUT TYPE="radio" NAME="item_active" VALUE="t" <?= ($item_active!="f")?"CHECKED":"" ?>>Active
					<INPUT TYPE="radio" NAME="item_active" VALUE="f" <?= ($item_active=="f")?"CHECKED":"" ?>>Inactive
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
                              <input type="submit" name="Submit222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>
                  </table>
