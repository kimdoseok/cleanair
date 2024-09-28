<?php
	include_once("class/register_globals.php");

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function goSales() {
		var f = document.forms[0];
		window.location= 'sales.php?ty=a&sale_cust_code='+f.cust_code.value;
	}

//-->
</SCRIPT>
				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="ar_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="cust_edit">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang]["Edit_Customer"] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&cust_code=$cust_code" ?>"><?= $label[$lang]["View"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                      <td colspan="4" align="right"><font size="2">|
                        <a href="<?php echo "ar_proc.php?cmd=cust_del&cust_code=$cust_code" ?>">Delete</a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="465" align="right" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Customer_no"] ?>:&nbsp;</td>
                  <td width="308">
                    <?= $f->fillTextBox("cust_code", $cust_code, 20, 32, "inbox") ?>
					<A HREF="javascript:goSales()"><FONT SIZE="2">New Sales</FONT></A>	  
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Name"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("cust_name", $cust_name, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Address"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("cust_addr1", $cust_addr1, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("cust_addr2", $cust_addr2, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("cust_addr3", $cust_addr3, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["City_State_Zip"] ?></td>
                  <td> 
                    <?= $f->fillTextBox("cust_city", $cust_city, 20, 32, "inbox") ?>
                    <?= $f->fillTextBox("cust_state", $cust_state, 2, 32, "inbox") ?>
                    <?= $f->fillTextBox("cust_zip", $cust_zip, 5, 32, "inbox") ?>
                  </td>
                </tr>
                <tr>
                  <td align="right" bgcolor="silver">Tax</td>
                  <td> 
                    <?= $f->fillSelectBox($taxbox,"cust_tax_code", "value", "name", $cust_tax_code) ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Terms:</td>
                  <td> 
                    <?= $f->fillSelectBox($termbox, "cust_term", "value", "Name", $cust_term) ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Status:</td>
                  <td> 
				    <INPUT TYPE="radio" NAME="cust_active" VALUE="t" <?= ($cust_active!="f")?"CHECKED":"" ?>>Active
					<INPUT TYPE="radio" NAME="cust_active" VALUE="f" <?= ($cust_active=="f")?"CHECKED":"" ?>>Inactive
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Need Mkting:</td>
                  <td> 
				    <INPUT TYPE="checkbox" NAME="cust_marketing" VALUE="t" <?= ($cust_marketing=="t")?"checked":"" ?>>
                  </td>
                </tr>
                <tr>
                  <td align="right" bgcolor="silver">AR Info:</td>
                  <td>
				    <INPUT TYPE="checkbox" NAME="cust_arinfo" VALUE="t" <?= ($cust_arinfo=="t")?"checked":"" ?>>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Email</td>
                  <td> 
                    <?= $f->fillTextBox("cust_email", $cust_email, 30, 256, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Website</td>
                  <td> 
                    <?= $f->fillTextBox("cust_www", $cust_www, 30, 256, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Memo</td>
                  <td> 
                    <?= $f->fillTextBox("cust_memo", $cust_memo, 20, 256, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
            <td width="14">&nbsp; </td>
            <td width="269" align="right" valign="top">
			<table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr>
                  <td width="95" align="right" bgcolor="silver">Tel</td>
                  <td width="136">
                    <?= $f->fillTextBox("cust_tel", $cust_tel, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr>
                  <td width="95" align="right" bgcolor="silver">Cell</td>
                  <td width="136">
                    <?= $f->fillTextBox("cust_cell", $cust_cell, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr>
                  <td width="95" align="right" bgcolor="silver">Fax</td>
                  <td width="136">
                    <?= $f->fillTextBox("cust_fax", $cust_fax, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr>
                  <td width="95" align="right" bgcolor="silver">Sale Acct</td>
                  <td width="136">
                    <?= $f->fillTextBox("cust_sls_acct", $cust_sls_acct, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr>
                  <td align="right" bgcolor="silver">AR Acct</td>
                  <td>
                    <?= $f->fillTextBox("cust_ar_acct", $cust_ar_acct, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr>
                  <td width="95" align="right" bgcolor="silver">init.Balance</td>
                  <td width="136">
                    <?= $f->fillTextBox("cust_init_bal", sprintf("%0.2f", $cust_init_bal), 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr>
                  <td width="95" align="right" bgcolor="silver">Balance</td>
                  <td width="136">
                    <?= $f->fillTextBoxRO("cust_balance", sprintf("%0.2f", $cust_balance), 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr>
                  <td align="right" bgcolor="silver">Sales Rep.</td>
                  <td>
                    <?= $f->fillSelectBox($slsrepbox,"cust_slsrep", "value", "name", $cust_slsrep) ?>
                  </td>
                </tr>
                <tr>
                  <td align="right" bgcolor="silver">Delvery</td>
                  <td> 
                    <?= $f->fillSelectBox($f->weekbox,"cust_delv_week", "value", "name", $cust_delv_week) ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Ship Via</td>
                  <td> 
                    <?= $f->fillTextBox("cust_shipvia", $cust_shipvia, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Credit Limit</td>
                  <td> 
                    <?= $f->fillTextBox("cust_cr_limit", $cust_cr_limit, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Points</td>
                  <td> 
                    <?= $f->fillTextBox("cust_points", $cust_points, 20, 32, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&cust_code=$cust_code&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&cust_code=$cust_code&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&cust_code=$cust_code&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&cust_code=$cust_code&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="reset" name="Submit2222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
<?php
	include("custships_list.php"); 
?>
