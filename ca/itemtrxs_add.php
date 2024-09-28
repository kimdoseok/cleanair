<?php
	if ($_POST) foreach ($_POST as $k=>$v) $$k=$v;
	if (empty($invtrx_type)) $invtrx_type = "s";
	if ($_POST[invtrx_item_code]) {
		$iu = new ItemUnits();
		$iu_arr = $iu->getItemUnitsListByItem($_POST[invtrx_item_code]);
		$it = new Items();
		$it_arr = $it->getItems($_POST[invtrx_item_code]);
		if (empty($invtrx_inv_acct)) $invtrx_inv_acct = $it_arr[item_inv_acct];
		if (empty($invtrx_cost)) $invtrx_cost = $it_arr[item_user_cost];
	}

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function AddTrx() {
	var f = document.forms[0];
	if (f.invtrx_item_code.value == "") {
		window.alert("Item code should not be blank!");
	} else if (f.invtrx_date.value == "") {
		window.alert("Date should not be blank!");
	} else if (f.invtrx_date.value == "") {
		window.alert("Date should not be blank!");
	} else if (f.invtrx_cost.value == "") {
		window.alert("Cost/Price should not be blank!");
	} else if (f.invtrx_qty.value == "") {
		window.alert("Quantity should not be blank!");
	} else {
		f.cmd.value = "itemtrxs_add";
		f.method = "post";
		f.action = "ic_proc.php";
		f.submit();
	}
}

	var acctBrowse;

	function openAcctBrowse(objname)  {
		if (!acctBrowse || acctBrowse.closed) {

			acctBrowse = window.open("accounts_popup.php?objname="+objname, "acctBrowseWin", "height=450, width=350");

		} else {

			acctBrowse.focus();

		}

		acctBrowse.moveTo(100,100);

	}


//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ic_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="">
						<INPUT TYPE="hidden" name="ty" value="<?= $ty ?>">
                    <tr align="right"> 
                      <td colspan="8"><strong>New Item Transaction</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> |</font></td>
                    </tr>
                      <tr align="right"> 
                        <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                            <tr> 
                              <td bgcolor="silver">Date</td>
                              <td> 
                                <?= $f->fillTextBox("invtrx_date", (empty($invtrx_date))?$d->getToday():$invtrx_date, 32, 32, "inbox") ?>
								<a href="javascript:openCalendar('invtrx_date')">C</a>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">Item Code</td>
                              <td> 
								<?= $f->fillTextBoxRefresh("invtrx_item_code", $invtrx_item_code, 32, 32, "inbox") ?>
								<A HREF="javascript:openItemBrowse('invtrx_item_code')"><font size="2">Lookup</font></A> 

                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">Description</td>
                              <td> 
                                <?= $f->fillTextBox("invtrx_item_desc", $invtrx_item_desc, 32, 32, "inbox") ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">Unit</td>
                              <td> 
							    <SELECT NAME="invtrx_unit">
<?php
	if ($iu_arr) {
		$iu_num = count($iu_arr);
		for ($i=0;$i<$iu_num;$i++) {
			$value=strtoupper($iu_arr[$i]["itemunit_unit"]);
			$name=$iu_arr[$i]["unit_name"];
			echo "<option value='$value' >$name</option>";
		}
	}
?>
								</SELECT>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">Acct #</td>
                              <td> 
							    <?= $f->fillHidden("invtrx_inv_acct", $invtrx_inv_acct) ?>
                                <?= $f->fillTextBox("invtrx_acct_code", "52000", 32, 32, "inbox") ?>
								<A HREF="javascript:openAcctBrowse('invtrx_acct_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">Ref #</td>
                              <td> 
                                <?= $f->fillTextBox("invtrx_ref_code", $invtrx_ref_code, 32, 32, "inbox") ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">Cost:</td>
                              <td> 
                                <?= $f->fillTextBox("invtrx_cost", $invtrx_cost, 32, 32, "inbox") ?>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td bgcolor="silver">Qty:</td>
                              <td> 
                                <?= $f->fillTextBox("invtrx_qty", $invtrx_qty, 32, 32, "inbox") ?>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td bgcolor="silver">Type:</td>
                              <td> 
                                <input type="radio" name="invtrx_type" value="r" checked>
                                Receiving
							    <input type="radio" name="invtrx_type" value="s">
                                Sales
                                <input type="radio" name="invtrx_type" value="a">
                                Adjust</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td bgcolor="silver">Comment:</td>
                              <td colspan="2"> 
                                <?= $f->fillTextBox("invtrx_desc", $invtrx_desc, 20, 32, "inbox") ?>
                              </td>
                            </tr>
                          </table></td>
                      </tr>

                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="AddTrx()"> 
                              <input type="reset" name="Submit222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>
                  </table>
