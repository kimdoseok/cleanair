<?php
	$iu = new ItemUnits();
	$iu_arr = $iu->getItemUnitsListByItem($item_code);
	if ($iu_arr) $iu_num = count($iu_arr);
	else $iu_num = 0;
	$unitbox = array();
	for ($i=0;$i<$iu_num;$i++) {
		$tmp = array("value"=>"", "name"=>"");
		$tmp["value"] = $iu_arr[$i]["itemunit_unit"];
		$tmp["Name"] = $iu_arr[$i]["unit_name"];
		array_push($unitbox, $tmp);
	}

	if ($iu_num == 0) {
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

	function deleteItem() {
		if (confirm("Are you sure to delete?")) window.location="ic_proc.php?cmd=item_del&item_code=<?= $item_code ?>";
	}


//-->
</SCRIPT>
				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="item_edit">
						<?= $f->fillHidden("ty","e") ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Item</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a>  |
						<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&item_code=$item_code" ?>">View</a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a>  |
						<a href="<?php echo "sales.php?ty=a" ?>">New Sales</a> | 
						<a href="javascript:openHistory('<?= $item_code ?>')">Change History</a> | 
					  </font></td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="javascript:deleteItem()">Del</a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="100%" align="right" bgcolor="white"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="20%" align="right" bgcolor="silver"><?= $label[$lang]["Item_no"] ?>:</td>
                  <td width="80%"> 
                    <?= $f->fillHidden("item_code", $item_code) ?>
                    <?= strtoupper($item_code) ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Description"] ?>:</td>
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
                  <td align="right" bgcolor="silver">Category:</td>
                  <td valign="top"> 
					<?= $f->fillSelectBox($catbox,"item_cate_code", "value", "Name", $item_cate_code) ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">MSRP:</td>
                  <td> 
                    <?= $f->fillTextBox("item_msrp", $item_msrp, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Average_Cost:</td>
                  <td> 
                    <?= $f->fillTextBox("item_ave_cost", $item_ave_cost, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Unit:</td>
                  <td> 
				    <?= $f->fillSelectBox($unitbox,"item_unit", "value", "Name", $item_unit) ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">OnHand Qty:&nbsp;</td>
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
                  <td align="right" bgcolor="silver">Taxable:&nbsp;</td>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&item_code=$item_code&dir=-2" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&item_code=$item_code&dir=-1" ?>">&lt;Prev</a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&item_code=$item_code&dir=1" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&item_code=$item_code&dir=2" ?>">Last&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="Record"> 
                              <input type="submit" name="Submit2222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
<?php
	include("itemunit_list.php");
?>