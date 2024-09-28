<?php
	include_once("class/class.disburdtls.php");
	include_once("class/class.formutils.php");
	include_once("class/class.disburse.php");


	$f = new FormUtil();
	$last = $_SESSION[$lastdisb];
	if (!empty($last)) {
		foreach($last as $k => $v) $$k = $v;
		$_SESSION[$lastdisb]=NULL;
	}

	$c = new Disburse();
	$d = new DisburDtls();

	if ($ht == "e") $recs = $_SESSION[$disburse_edit]));
	else $recs = $_SESSION[$disburse_add];
	if (!empty($recs)) foreach($recs as $k => $v) $$k = $v; 

	if ($ht == "e") $recs = $_SESSION[$disburdtls_edit]));
	else $recs = $_SESSION[$disburdtls_add];
	
	for ($i=0;$i<count($recs);$i++) $applied += $recs[$i][disburdtl_amt];
	$remained = $disbur_amt - $applied;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		f.action = "ap_proc.php";
		f.cmd.value = "disbur_detail_sess_add";
		f.method = "post";
		f.submit();
	}
	
	function ClearDisburse() {
		var f = document.forms[0];
		f.action = "ap_proc.php";
		f.cmd.value = "disbur_clear_sess_add";
		f.method = "post";
		f.submit();
	}

//-->
</SCRIPT>

						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="ap_proc.php">
							<INPUT TYPE="hidden" name="cmd" value="">
							<?= $f->fillHidden("ht", $ht) ?>
							<?= $f->fillHidden("disbur_id", $disbur_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][New_Disbursease_Detail] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=a&ht=$ht&disbur_id=$disbur_id" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo "disburse.php?ty=$ht&disbur_id=$disbur_id" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang][Vendor_Inv_dno] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("disburdtl_vend_inv", $disburdtl_vend_inv, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Acct_no"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("disburdtl_acct_code", $disburdtl_acct_code, 20, 32, "inbox") ?>
					<A HREF="javascript:openAcctBrowse('disburdtl_acct_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("disburdtl_amt", $disburdtl_amt, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang]["Description"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("disburdtl_desc", $disburdtl_desc, 20, 32, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
									 <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="AddDtl()"> 
                              <input type="button" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>" onClick="ClearDisburse()"></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8">&nbsp;</td>
                    </tr>
					<tr>
					  <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="62%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr> 
									<td width="97" bgcolor="silver"><?= $label[$lang]["PO_no"] ?>:</td>
									<td width="308"> <?= $disbur_po_no ?> </td>
								</tr>
							  <tr> 
								<td width="97" bgcolor="silver"><?= $label[$lang][Vendor] ?>:</td>
								<td width="308"> 
								  <?= $disbur_vend_code ?>
								</td>
							  </tr>
<!--
							  <tr> 
								<td width="97" bgcolor="silver">Vendor Inv. #:</td>
								<td width="308"> 
								  <?= $disbur_vend_inv ?>
								</td>
							  </tr>
-->
							  <tr> 
								<td width="97" bgcolor="silver"><?= $label[$lang][Check] ?>:</td>
								<td width="308"> 
								  <?= $disbur_check_no ?>
								</td>
							  </tr>
							  <tr> 
								<td width="97" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:</td>
								<td> 
								  <?= $disbur_amt ?>
								</td>
							  </tr>
			              </table></td>
						<td width="1%">&nbsp;</td>
						<td width="37%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<!--
						  <tr> 
                            <td width="97" bgcolor="silver">Disb #:</td>
                            <td width="308"> 
                              <?= $disbur_id ?>
                            </td>
                          </tr>
-->
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
                            <td> 
                              <?= $disbur_date ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $disbur_user_code ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Applied] ?>:</td>
                            <td width="308"> 
                              <?= $applied ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Remained] ?>:</td>
                            <td> 
                              <?= $remained ?>
                            </td>
                          </tr>
              </table></td>
						  </tr>
						</table>
					  
					    
    </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang][Vend_Inv_no] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Acct_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
                          </tr>
<?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              <?= $i+1 ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i][disburdtl_vend_inv] ?>
                            </td>
                            <td width="13%"> 
                              <?= $recs[$i][disburdtl_acct_code] ?>
                            </td>
                            <td width="12%" align="right"> 
                              <?= $recs[$i][disburdtl_amt] ?>
                            </td>
                            <td width="60%"> 
                              <?= $recs[$i][disburdtl_desc] ?>
                            </td>
                          </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
						  <tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
                          </tr>
<?php
	}
?>
                        </table></td>
                    </tr>

						  </form>
                  </table>
				  <br>
