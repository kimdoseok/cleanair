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
            </a> | <a href="items.php">Inventory</a> | <a href="accounts.php">General 
            Ledger</a> |</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_items.php") ?> </td>
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
                      <td colspan="8"><strong>New Item</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97" align="right">Item #:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp; </td>
                            <td width="95" align="right">Inv. Acct&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right">Description:</td>
                            <td rowspan="4" valign="top"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">Exp. Acct&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right">&nbsp;</td>
                            <td width="11">&nbsp;</td>
                            <td align="right">Sales Acct&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right">&nbsp;</td>
                            <td width="11">&nbsp;</td>
                            <td width="97" align="right">Average Cost:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="97" align="right">Qty On Hand:&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><input type="button" name="Button522" value="&lt;&lt;"> 
                              &nbsp; <input type="button" name="Button2222" value="&lt;"> 
                              &nbsp; <input type="button" name="Button3222" value="&gt;"> 
                              &nbsp; <input type="button" name="Button4222" value="&gt;&gt;"></td>
                            <td width="36%" align="center"><input type="submit" name="Submit3222" value="Record"> 
                              <input type="submit" name="Submit22222" value="Cancel"></td>
                          </tr>
                        </table> </td>
                    </tr>
                  </table>
                  <?php
	} else if ($ty == "e") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Item</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97" align="right">Item #:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp; </td>
                            <td width="95" align="right">Inv. Acct&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right">Description:</td>
                            <td rowspan="4" valign="top"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">Exp. Acct&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right">&nbsp;</td>
                            <td width="11">&nbsp;</td>
                            <td align="right">Sales Acct&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right">&nbsp;</td>
                            <td width="11">&nbsp;</td>
                            <td width="97" align="right">Average Cost:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="97" align="right">Qty On Hand:&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><input type="button" name="Button5222" value="&lt;&lt;"> 
                              &nbsp; <input type="button" name="Button22222" value="&lt;"> 
                              &nbsp; <input type="button" name="Button32222" value="&gt;"> 
                              &nbsp; <input type="button" name="Button42222" value="&gt;&gt;"></td>
                            <td width="36%" align="center"><input type="submit" name="Submit32222" value="Record"> 
                              <input type="submit" name="Submit222222" value="Cancel"></td>
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
                      <td colspan="8"><strong>View Item</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97" align="right">Item #:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp; </td>
                            <td width="95" align="right">Inv. Acct&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right">Description:</td>
                            <td rowspan="4" valign="top"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">Exp. Acct&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right">&nbsp;</td>
                            <td width="11">&nbsp;</td>
                            <td align="right">Sales Acct&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right">&nbsp;</td>
                            <td width="11">&nbsp;</td>
                            <td width="97" align="right">Average Cost:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="97" align="right">Qty On Hand:&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><input type="button" name="Button52222" value="&lt;&lt;"> 
                              &nbsp; <input type="button" name="Button222222" value="&lt;"> 
                              &nbsp; <input type="button" name="Button322222" value="&gt;"> 
                              &nbsp; <input type="button" name="Button422222" value="&gt;&gt;"></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322222" value="Record"> 
                              <input type="submit" name="Submit2222222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table> 
                  <?php
	} else  {
?>
                  item_qty_onhnd 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Sales</strong></td>
                    </tr>
                    <tr align="right">
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="63"><font color="white">Item 
                              # </font></th>
                            <th width="412" bgcolor="gray"><font color="white">Description</font></th>
                            <th bgcolor="gray" width="86"><font color="white">Cost</font></th>
                            <th width="94" bgcolor="gray"><font color="white">On 
                              Hand </font></th>
                          </tr>
                          <tr> 
                            <td width="63" align="center"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="86" align="right"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
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
