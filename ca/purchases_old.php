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
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_purchases.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
                  <?php
	if ($ty == "a") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>New Purchases</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> 
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97" align="right">Vendor:&nbsp;</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp; </td>
                            <td width="95" align="right">Purchase #&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td align="right">Comment:&nbsp;</td>
                            <td rowspan="2" valign="top"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td align="right">Date&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97">&nbsp;</td>
                            <td width="11">&nbsp;</td>
                            <td align="right">User #&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th colspan="2" bgcolor="gray"><font color="white">Item 
                              #</font></th>
                            <th bgcolor="gray" width="75"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="67"><font color="white">Qty</font></th>
                            <th width="90" bgcolor="gray"><font color="white">Amount</font></th>
                          </tr>
                          <tr> 
                            <td width="81"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="238"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 26, 32, "inbox") ?>
                            </td>
                            <td width="75" align="right"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="67" align="right"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="90" align="right"> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="95">Ship Via</td>
                            <td width="306"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                            <td width="12">&nbsp; </td>
                            <td width="140" align="right">Freight&nbsp;</td>
                            <td width="94"> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="95">Comment</td>
                            <td width="306"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                            <td width="12">&nbsp;</td>
                            <td width="140" align="right">Tax&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="95">&nbsp;</td>
                            <td width="306"><div align="right"> </div></td>
                            <td width="12">&nbsp;</td>
                            <td width="140" align="right">Total Amount&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                          <tr align="center"> 
                            <td colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td width="64%" align="center"><input type="button" name="Button522" value="&lt;&lt;"> 
                                    &nbsp; <input type="button" name="Button2222" value="&lt;"> 
                                    &nbsp; <input type="button" name="Button3222" value="&gt;"> 
                                    &nbsp; <input type="button" name="Button4222" value="&gt;&gt;"></td>
                                  <td width="36%" align="center"><input type="submit" name="Submit3222" value="Record"> 
                                    <input type="submit" name="Submit22222" value="Cancel"></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  <?php
	} else if ($ty == "e") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Purchases</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> 
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97">Customer:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp; </td>
                            <td width="95" align="right">&nbsp;</td>
                            <td width="136">&nbsp;</td>
                          </tr>
                          <tr> 
                            <td width="97">Ship To:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">Sales #&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97">&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">Date&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97">&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">PO #&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td>&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                              <?= $f->fillTextBox("abc", $abc, 2, 32, "inbox") ?>
                              <?= $f->fillTextBox("abc", $abc, 5, 32, "inbox") ?>
                            </td>
                            <td>&nbsp;</td>
                            <td align="right">User #&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="96"><font color="white">Style 
                              # </font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item 
                              #</font></th>
                            <th bgcolor="gray" width="75"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="67"><font color="white">Qty</font></th>
                            <th width="90" bgcolor="gray"><font color="white">Amount</font></th>
                          </tr>
                          <tr> 
                            <td width="96" align="center"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="81"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="238"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 26, 32, "inbox") ?>
                            </td>
                            <td width="75" align="right"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="67" align="right"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="90" align="right"> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="95">Ship Via</td>
                            <td width="306"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                            <td width="12">&nbsp; </td>
                            <td width="140" align="right">Freight&nbsp;</td>
                            <td width="94"> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="95">Comment</td>
                            <td width="306"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                            <td width="12">&nbsp;</td>
                            <td width="140" align="right">Tax&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="95">&nbsp;</td>
                            <td width="306"><div align="right"> </div></td>
                            <td width="12">&nbsp;</td>
                            <td width="140" align="right">Total Amount&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                          <tr align="center"> 
                            <td colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td width="64%" align="center"><input type="button" name="Button52" value="&lt;&lt;"> 
                                    &nbsp; <input type="button" name="Button222" value="&lt;"> 
                                    &nbsp; <input type="button" name="Button322" value="&gt;"> 
                                    &nbsp; <input type="button" name="Button422" value="&gt;&gt;"></td>
                                  <td width="36%" align="center"><input type="submit" name="Submit322" value="Record"> 
                                    <input type="submit" name="Submit2222" value="Cancel"></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  <?php
	} else if ($ty == "v") {
?>
                  <br> <br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>View Purchases</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> 
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97">Customer:</td>
                            <td width="308"> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp; </td>
                            <td width="95" align="right">&nbsp;</td>
                            <td width="136">&nbsp;</td>
                          </tr>
                          <tr> 
                            <td width="97">Ship To:</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">Sales #&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97">&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">Date&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97">&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">PO #&nbsp;</td>
                            <td width="136"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td>&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                              <?= $f->fillTextBox("abc", $abc, 2, 32, "inbox") ?>
                              <?= $f->fillTextBox("abc", $abc, 5, 32, "inbox") ?>
                            </td>
                            <td>&nbsp;</td>
                            <td align="right">User #&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="96"><font color="white">Style 
                              # </font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Item 
                              #</font></th>
                            <th bgcolor="gray" width="75"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="67"><font color="white">Qty</font></th>
                            <th width="90" bgcolor="gray"><font color="white">Amount</font></th>
                          </tr>
                          <tr> 
                            <td width="96" align="center"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="81"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="238"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 26, 32, "inbox") ?>
                            </td>
                            <td width="75" align="right"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="67" align="right"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="90" align="right"> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="95">Ship Via</td>
                            <td width="306"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                            <td width="12">&nbsp; </td>
                            <td width="140" align="right">Freight&nbsp;</td>
                            <td width="94"> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="95">Comment</td>
                            <td width="306"> 
                              <?= $f->fillTextBox("abc", $abc, 20, 32, "inbox") ?>
                            </td>
                            <td width="12">&nbsp;</td>
                            <td width="140" align="right">Tax&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="95">&nbsp;</td>
                            <td width="306"><div align="right"> </div></td>
                            <td width="12">&nbsp;</td>
                            <td width="140" align="right">Total Amount&nbsp;</td>
                            <td> 
                              <?= $f->fillTextBox("style_code$i", sprintf("%0.2f", $style_code.$i), 10, 16, "inbox") ?>
                            </td>
                          </tr>
                          <tr align="center"> 
                            <td colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td width="64%" align="center"><input type="button" name="Button53" value="&lt;&lt;"> 
                                    &nbsp; <input type="button" name="Button223" value="&lt;"> 
                                    &nbsp; <input type="button" name="Button323" value="&gt;"> 
                                    &nbsp; <input type="button" name="Button423" value="&gt;&gt;"></td>
                                  <td width="36%" align="center"><input type="submit" name="Submit323" value="Record"> 
                                    <input type="submit" name="Submit2223" value="Cancel"></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  <?php
	} else  {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Purchases</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>">Edit</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>">View</a> 
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> 
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="63"><font color="white">Sales 
                              # </font></th>
                            <th bgcolor="gray" width="75"><font color="white">PO#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Vendor</font></th>
                            <th bgcolor="gray" width="67"><font color="white">Date</font></th>
                            <th width="94" bgcolor="gray"><font color="white">Total</font></th>
                            <th width="94" bgcolor="gray"><font color="white">Fin</font></th>
                          </tr>
                          <tr> 
                            <td width="63" align="center"> 
                              <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                            </td>
                            <td width="75" align="right"> 
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
				                <td width="10">&nbsp;</td>

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
