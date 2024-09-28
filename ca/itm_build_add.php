<?php
	include_once("class/class.itm_buildtls.php");
	include_once("class/class.datex.php");
	include_once("class/class.formutils.php");
	$d = new Datex();
	$f = new FormUtil();

	if ($_SESSION[itmbld_add]) foreach($_SESSION[itmbld_add] as $k => $v) $$k = $v;

	$recs = $_SESSION[itmbldtls_add];
	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) if (!empty($recs[$i])) $subtotal += sprintf("%0.2f",$recs[$i][itmbldtl_cost]*$recs[$i][itmbldtl_qty]);

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.itmbuild_name.value == "") {
			window.alert("The name should not be blank!");
		} else {
			f.cmd.value = "itm_build_sess_add";
			f.method = "post";
			f.action = "ibm_build_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "itm_build_sess_del";
		f.method = "get";
		f.action = "ibm_build_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.itmbuild_name.value == "") {
			window.alert("The name should not be blank!");
		} else {
			f.cmd.value = "itm_build_add";
			f.method = "post";
			f.action = "ibm_build_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "itm_build_clear_sess_add";
		f.method = "post";
		f.action = "ibm_build_proc.php";
		f.submit();
	}

	function UpdateForm() {
		var f = document.forms[0];
		f.action = "ibm_build_proc.php";
		f.cmd.value = "itm_build_update_sess_add";
		f.method = "post";
		f.submit();
	}


//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ibm_build_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ty","a") ?>
                    <tr> 
                      <td colspan="1" align="right"><strong>New Item Build</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="1" align="left"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="100%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Name</td>
                                  <td> 
                                    <?= $f->fillTextBox("itmbuild_name", stripslashes($itmbuild_name), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Description</td>
                                  <td> 
                                    <?= $f->fillTextBox("itmbuild_desc", stripslashes($itmbuild_desc), 64, 250, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">User</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("itmbuild_user_code", date("m/d/Y"), 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Sub Total</td>
                                  <td> 
                                    <?= $f->fillTextBoxRO("subtotal", $subtotal, 32, 32, "inbox") ?>
                                  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr> 
                      <td colspan="1" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="50%" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add Detail</A></FONT></td>
                           <td width="50%" align="center">
                            <input type="button" name="Submit32" value="Update" onClick="UpdateForm()">
								   <input type="button" name="Submit3222" value="Record" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="Clear" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">#</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Item Code</font></th>
                            <th bgcolor="gray" width="45%"><font color="white">Description</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Unit</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td align="center"> 
                              <a href="itm_build_details.php?ty=e&ht=a&did=<?= $i ?>"><?= $i+1 ?></a>
                            </td>
                            <td> 
                              <?= $recs[$i][itmbldtl_item_code] ?>
                            </td>
                            <td> 
                              <?= stripslashes($recs[$i][itmbldtl_desc]) ?>
                            </td>
                            <td align="center"> 
                              <?= strtoupper($recs[$i][itmbldtl_unit_code]) ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][itmbldtl_qty]+0 ?>
                            </td>
                            <td align="right"> 
                              <?= number_format($recs[$i][itmbldtl_cost],2,".",",") ?>
                            </td>
                            <td align="right"> 
                              <?= number_format($recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"],2,".",",") ?>
                            </td>
                            <td width="5%" align="center"> 
                              <a href="ibm_build_proc.php?cmd=itm_build_detail_sess_del&ty=a&did=<?= $i ?>"><?= $label[$lang]["Del"] ?></a>
                            </td>
                          </tr>

<?php
		}
	}
	if (empty($recs[0])) {
?>
						  <tr bgcolor="#EEEEEE">
                            <td colspan="1" align="center"> 
                              <b>Empty!</b>
                            </td>
                          </tr>
<?php
	}
?>
                    </table></td></tr>
				  </form>
                  </table>
