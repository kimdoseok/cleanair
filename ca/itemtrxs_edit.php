<?php
	$iu = new ItemUnits();
	$iu_arr = $iu->getItemUnitsListByItem($invtrx_item_code);
?>
<SCRIPT LANGUAGE="JavaScript">
<!--

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
						<INPUT TYPE="hidden" name="cmd" value="itemtrxs_edit">
						<?= $f->fillHidden("invtrx_id", $invtrx_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit InvTrx</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a>  |
								<a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a>  | </font></td>
                      <td colspan="4" align="right"><font size="2">
							 | <a href="ic_proc.php?cmd=itemtrxs_del&invtrx_id=<?= $invtrx_id ?>">Del</a> | </font>
                      </td>
                    </tr>
                      <tr align="right"> 
                        <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                            <tr> 
                              <td width="120" bgcolor="silver">InvTrx #&nbsp;</td>
                              <td> 
							    <?= $f->fillHidden("invtrx_id",$invtrx_id) ?>
                                <?= $invtrx_id ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
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
			if ($value==$invtrx_unit) echo "<option value='$value' selected>$name</option>";
			else echo "<option value='$value'>$name</option>";
		}
	}
?>
								</SELECT>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="120" bgcolor="silver">Account #</td>
                              <td> 
							    <?= $f->fillHidden("invtrx_inv_acct", $invtrx_inv_acct) ?>
                                <?= $f->fillTextBox("invtrx_acct_code", $invtrx_acct_code, 32, 32, "inbox") ?>
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
                              <td bgcolor="silver">Quantity:</td>
                              <td> 
                                <?= $f->fillTextBox("invtrx_qty", $invtrx_qty, 32, 32, "inbox") ?>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td bgcolor="silver">Type:</td>
                              <td>
<?php
	if ($invtrx_type=="a") {
		echo "Adjust";
	} else if ($invtrx_type=="s") {
		echo "Sales";
	} else {
		echo "Receiving";
	}
?>
							    <?= $f->fillHidden("invtrx_type", $invtrx_type) ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&invtrx_id=$invtrx_id&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&invtrx_id=$invtrx_id&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&invtrx_id=$invtrx_id&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&invtrx_id=$invtrx_id&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="submit" name="Submit2222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
