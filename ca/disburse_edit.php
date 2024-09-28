<?php
	include_once("class/class.datex.php");
	include_once("class/class.disburdtls.php");
	include_once("class/class.formutils.php");
	include_once("class/class.vendors.php");
	$x = new Datex();
	$f = new FormUtil();
	$s = new Disburse();
	$d = new DisburDtls();

	if ($_SESSION[$disburse_edit]) {
		$recs = $_SESSION[$disburse_edit]));
		if ($recs[disburdtl_disbur_id] != $disbur_id) $recs = $d->getDisburDtlsList($disbur_id);
		if (!empty($recs)) foreach($recs as $k => $v) $$k = $v; 
	} else {
		$recs = $d->getDisburDtlsList($disbur_id);
		if (!empty($recs)) foreach($recs as $k => $v) $$k = $v; 
	}
	$_SESSION[$disburse_edit] = $recs;

	if ($_SESSION[$disburdtls_edit]) {
		$recs = $_SESSION[$disburdtls_edit];
		if ($recs[0][disburdtl_disbur_id] != $disbur_id) $recs = $d->getDisburDtlsList($disbur_id);
	} else {
		$recs = $d->getDisburDtlsList($disbur_id);
	}
	$_SESSION[$disburdtls_edit] = $recs;

	for ($i=0;$i<count($recs);$i++) $applied += $recs[$i][disburdtl_amt];
	$remained = $disbur_amt - $applied;
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
//		if (f.disbur_id.value == "") {
//			window.alert("Disbursement number should not be blank!");
//		} else {
			f.cmd.value = "disbur_sess_add";
			f.ht.value = "<?= $ty ?>";
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
//		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "disbur_sess_del";
		<?= ($ty=="e")?"f.ht.value = 'e';":"" ?>
		f.method = "get";
		f.action = "ap_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.disbur_id.value == "") {
			window.alert("Purchase number should not be blank!");
		} else if (f.remained.value != 0) {
			window.alert("Remaining value should not be 0 !");
		} else {
			f.cmd.value = "disbur_edit";
			<?= ($ty=="e")?"f.ht.value = 'e';":"" ?>
			f.method = "post";
			f.action = "ap_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "disbur_clear_sess_edit";
		f.method = "post";
		f.action = "ap_proc.php";
		f.submit();
	}

	function UpdateForm() {
		var f = document.forms[0];
		f.ht.value = "<?= $ty ?>";
		f.action = "ap_proc.php";
		f.cmd.value = "disbur_update_sess_add";
		f.method = "post";
		f.submit();
	}

//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ap_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ht","") ?>
							<?= $f->fillHidden("disbur_id",$disbur_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][Edit_Sytle] ?></strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="8"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							
      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="62%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["PO_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_po_no", $disbur_po_no, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Vendor] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_vend_code", $disbur_vend_code, 20, 32, "inbox") ?>
							  <A HREF="javascript:openVendBrowse('disbur_vend_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                            </td>
                          </tr>
<!--
                          <tr> 
                            <td width="97" bgcolor="silver">Vendor Inv. #:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_vend_inv", $disbur_vend_inv, 20, 32, "inbox") ?>
                            </td>
                          </tr>
-->
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Check_no"] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("disbur_check_no", $disbur_check_no, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:</td>
                            <td> 
                              <?= $f->fillTextBox("disbur_amt", $disbur_amt , 20, 32, "inbox") ?>
                            </td>
                          </tr>
              </table></td>
            <td width="1%">&nbsp;</td>
            <td width="37%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Disb_no] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBoxRO("disbur_id", $disbur_id, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
                            <td> 
                              <?= $f->fillTextBox("disbur_date", empty($disbur_date)?$x->getToday():$disbur_date, 20,32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBoxRO("disbur_user_code", $_SERVER["PHP_AUTH_USER"], 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Applied] ?>:</td>
                            <td width="308"> 
                              <?= $f->fillTextBoxRO("applied", $applied, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Remained] ?>:</td>
                            <td> 
                              <?= $f->fillTextBoxRO("remained", $remained , 20, 32, "inbox") ?>
                            </td>
                          </tr>
              </table></td>
          </tr>
        </table> </td>
						  </tr>
						</table>
					  
					    
    </td>
                    </tr>
                    <tr> 
                      <td colspan="1" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()"><?= $label[$lang]["Add_Detail"] ?></A></FONT></td>
                      <td colspan="1" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="50%" align="center">&nbsp;</td>
                           <td width="50%" align="center">
                            <input type="button" name="Submit32" value="<?= $label[$lang]["Update"] ?>" onClick="UpdateForm()">
								   <input type="button" name="Submit3222" value="<?= $label[$lang]["Record"] ?>" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="<?= $label[$lang]["Clear"] ?>" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang][Vend_Inv_no] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Acct_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
                            <th bgcolor="gray"><font color="white">&nbsp;</font></th>
                          </tr>

<?php
	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              <a href="disburse_details.php?ty=e&disbur_id=<?= $disbur_id ?>&ht=e&did=<?= $i ?>"><?= $i+1 ?></a>
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
                            <td width="55%"> 
                              <?= $recs[$i][disburdtl_desc] ?>
                            </td>
                            <td width="5%"> 
                              <a href="ap_proc.php?ty=e&cmd=disbur_detail_sess_del&disbur_id=<?= $disbur_id ?>&did=<?= $i ?>">Del</a>
                            </td>
                          </tr>

<?php
		}
	}
	if (empty($recs[0])) {
?>
						<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b>Empty!</b>
                            </td>
                          </tr>
<?php
	}
?>
                        </table></td>
                    </tr>
                  </table>
<br>
<?php
//	print_r($_SESSION);
?>
