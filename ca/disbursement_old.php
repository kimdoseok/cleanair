<?php
	include_once("class/class.formutils.php");
	include_once("class/register_globals.php");
	$f = new FormUtil();
?>
<html>
<head>
<title>Sales</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td>&nbsp;| <a href="sales.php">Sales </a> | <a href="purchases.php">Purchases 
            </a> | <a href="items.php">Inventory</a> | <a href="accounts.php">General Ledger</a> 
            |</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_purchases.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="2" bgcolor="#CCFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "a") {
?>			
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>New Disbursement</strong></td>
                    </tr>
                    <tr align="right">
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97">Vendor:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97">Date:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97">User #&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97">Amount:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td>Description:</td>
                            <td colspan="2"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><input type="button" name="Button5" value="&lt;&lt;"> 
                              &nbsp; <input type="button" name="Button22" value="&lt; "> 
                              &nbsp; <input type="button" name="Button32" value=" &gt;"> 
                              &nbsp; <input type="button" name="Button42" value="&gt;&gt;"></td>
                            <td width="36%" align="center"><input type="submit" name="Submit32" value="Record"> 
                              <input type="submit" name="Submit222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  <?php
	} else if ($ty == "e") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Distbursement</strong></td>
                    </tr>
                    <tr align="right">
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97">Vendor:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97">Date:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97">User #&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97">Amount:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td>Description:</td>
                            <td colspan="2"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><input type="button" name="Button52" value="&lt;&lt;"> 
                              &nbsp; <input type="button" name="Button222" value="&lt; "> 
                              &nbsp; <input type="button" name="Button322" value=" &gt;"> 
                              &nbsp; <input type="button" name="Button422" value="&gt;&gt;"></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="Record"> 
                              <input type="submit" name="Submit2222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  <?php
	} else if ($ty == "v") {
?>
                  <br>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>View Disbursement</strong></td>
                    </tr>
                    <tr align="right">
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97">Vendor:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97">Date:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97">User #&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td width="97">Amount:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td>&nbsp; </td>
                          </tr>
                          <tr> 
                            <td>Description:</td>
                            <td colspan="2"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><input type="button" name="Button53" value="&lt;&lt;"> 
                              &nbsp; <input type="button" name="Button223" value="&lt; "> 
                              &nbsp; <input type="button" name="Button323" value=" &gt;"> 
                              &nbsp; <input type="button" name="Button423" value="&gt;&gt;"></td>
                            <td width="36%" align="center"><input type="submit" name="Submit323" value="Record"> 
                              <input type="submit" name="Submit2223" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  <?php
	} else  {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Disbursement</strong></td>
                    </tr>
                    <tr align="right">
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="63"><font color="white">DIS 
                              # </font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Vendor</font></th>
                            <th bgcolor="gray" width="67"><font color="white">Date</font></th>
                            <th width="94" bgcolor="gray"><font color="white">Amount</font></th>
                            <th width="94" bgcolor="gray"><font color="white">Fin</font></th>
                          </tr>
                          <tr> 
                            <td width="63" align="center"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="114"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="240"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 26, 32, "inbox") ?>
                            </td>
                            <td width="67" align="right"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="94" align="right"> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                            <td width="94" align="right"> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><input type="button" name="Button54" value="&lt;&lt;"> 
                              &nbsp; <input type="button" name="Button224" value="&lt;"> 
                              &nbsp; <input type="button" name="Button324" value="&gt;"> 
                              &nbsp; <input type="button" name="Button424" value="&gt;&gt;"></td>
                            <td width="36%" align="center"><input type="submit" name="Submit324" value="Record"> 
                              <input type="submit" name="Submit2224" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
<?php	}
?>			
                  </td>
              </tr>
            </table></td>
          <td width="10" bgcolor="#CCCCFF">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr bgcolor="#6666FF"> 
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
