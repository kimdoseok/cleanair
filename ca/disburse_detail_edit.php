<?php
	include_once("class/class.formutils.php");
	$f = new FormUtil();

	if ($ht == "e") $disburdtl = $_SESSION[$disburdtls_edit];
	else $disburdtl = $_SESSION[$disburdtls_add];
	if ($disburdtl[$did]) foreach ($disburdtl[$did] as $k => $v) $$k = $v;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function itemLookUp() {
		var f = document.forms[0];
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.cmd.value = "ilu";
		f.method = "get";
		f.submit();
	}

	function EditDtl() {
		var f = document.forms[0];
		f.action = "ap_proc.php";
		f.cmd.value = "disbur_detail_sess_edit";
		f.method = "post";
		f.submit();
	}
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="ap_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="">
						<INPUT TYPE="hidden" name="disburdtl_disbur_id" value="<?= $disburdtl_disbur_id ?>">
						<?= $f->fillHidden("ht", $ht) ?>						
						<?= $f->fillHidden("did", $did) ?>
						<?= $f->fillHidden("disbur_id", $disbur_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][Edit_Disbursement_Detail] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo "disburse.php?ty=$ht&disbur_id=$disbur_id" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver"><?= $label[$lang][Reference_no] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("disburdtl_ref_id", $disburdtl_ref_id, 20, 32, "inbox") ?>
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
                            <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="EditDtl()"> 
                              <input type="button" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>
                  </table>
