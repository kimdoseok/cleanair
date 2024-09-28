<?php
	include_once("class/class.formutils.php");
	$f = new FormUtil();
?>
<html>
<head>
<title>Accounts</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0" height="20">
        <tr> 
          <td>&nbsp;| <a href="sales.php">Sales </a> | <a href="purchases.php">Purchases 
            </a> | <a href="items.php">Inventory</a> | <a href="accounts.php">General_Ledger</a> 
            |</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_generalledgers.php") ?> </td>
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
                    <form name="form1" method="post" action="">
                      <tr align="right"> 
                        <td colspan="8"><strong><?= $label[$lang][New_Account] ?></strong></td>
                      </tr>
                      <tr align="right">
                        <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
                          <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>"><?= $label[$lang]["Edit"] ?></a>
						  <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>"><?= $label[$lang]["View"] ?></a> 
                          <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> </td>
                      </tr>
                      <tr align="right"> 
                        <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td width="97"><?= $label[$lang][Account_no] ?>:</td>
                              <td width="308"> 
                                <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="97"><?= $label[$lang]["Type"] ?>:</td>
                              <td colspan="2"> <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Asset"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Liability"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Equity"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Income"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Cost_of_Sale"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Expense"] ?> </td>
                            </tr>
                            <tr> 
                              <td><?= $label[$lang]["Description"] ?>:</td>
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
                              <td width="36%" align="center"><input type="submit" name="Submit32" value=<?= $label[$lang]["Record"] ?>> 
                                <input type="submit" name="Submit222" value=<?= $label[$lang]["Cancel"] ?>></td>
                            </tr>
                          </table></td>
                      </tr>
                    </form>
                  </table>
                  <?php
	} else if ($ty == "e") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <form name="form1" method="post" action="">
                      <tr align="right"> 
                        <td colspan="8"><strong><?= $label[$lang][Edit_Account] ?></strong></td>
                      </tr>
                      <tr align="right">
                        <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
                          <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>"><?= $label[$lang]["Edit"] ?></a> 
						  <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>"><?= $label[$lang]["View"] ?></a> 
                          <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> </td>
                      </tr>
                      <tr align="right"> 
                        <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td width="97"><?= $label[$lang][Account_no] ?>:</td>
                              <td width="308"> 
                                <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="97"><?= $label[$lang]["Type"] ?>:</td>
                              <td colspan="2"> <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Asset"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Liability"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Equity"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Income"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Cost_of_Sale"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Expense"] ?> </td>
                            </tr>
                            <tr> 
                              <td><?= $label[$lang]["Description"] ?>:</td>
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
                              <td width="36%" align="center"><input type="submit" name="Submit322" value=<?= $label[$lang]["Record"] ?>> 
                                <input type="submit" name="Submit2222" value=<?= $label[$lang]["Cancel"] ?>></td>
                            </tr>
                          </table></td>
                      </tr>
                    </form>
                  </table>
                  <?php
	} else if ($ty == "v") {
?>
                  <br>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <form name="form1" method="post" action="">
                      <tr align="right"> 
                        <td colspan="8"><strong><?= $label[$lang]["View_Account"] ?></strong></td>
                      </tr>
                      <tr align="right">
                        <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
                          <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>"><?= $label[$lang]["Edit"] ?></a> 
						  <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>"><?= $label[$lang]["View"] ?></a> 
                          <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> </td>
                      </tr>
                      <tr align="right"> 
                        <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr> 
                              <td width="97"><?= $label[$lang][Account_no] ?>:</td>
                              <td width="308"> 
                                <?= $f->fillTextBox("abc", $abc, 32, 32, "inbox") ?>
                              </td>
                              <td>&nbsp; </td>
                            </tr>
                            <tr> 
                              <td width="97"><?= $label[$lang]["Type"] ?>:</td>
                              <td colspan="2"> <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Asset"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Liability"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Equity"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Income"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Cost_of_Sale"] ?> 
                                <input type="radio" name="radiobutton" value="radiobutton">
                                <?= $label[$lang]["Expense"] ?> </td>
                            </tr>
                            <tr> 
                              <td><?= $label[$lang]["Description"] ?>:</td>
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
                              <td width="36%" align="center"><input type="submit" name="Submit323" value=<?= $label[$lang]["Record"] ?>> 
                                <input type="submit" name="Submit2223" value=<?= $label[$lang]["Cancel"] ?>></td>
                            </tr>
                          </table></td>
                      </tr>
                    </form>
                  </table> 
                  <?php
	} else  {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <form name="form1" method="post" action="">
                      <tr align="right"> 
                        <td colspan="8"><strong><?= $label[$lang]["List_Account"] ?></strong></td>
                      </tr>
                      <tr align="right">
                        <td colspan="8" align="center"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
                          <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e" ?>"><?= $label[$lang]["Edit"] ?></a> 
						  <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v" ?>"><?= $label[$lang]["View"] ?></a> 
                          <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> </td>
                      </tr>
                      <tr align="right"> 
                        <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                            <tr> 
                              <th bgcolor="gray" width="83"><font color="white"><?= $label[$lang]["Acct_no"] ?></font></th>
                              <th width="92" bgcolor="gray"><font color="white"><?= $label[$lang]["Type"] ?></font></th>
                              <th bgcolor="gray"><font color="white"><?= $label[$lang][Discription] ?></font></th>
                            </tr>
                            <tr> 
                              <td width="83" align="center"> 
                                <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                              </td>
                              <td width="92"> 
                                <?= $f->fillTextBox("style_code$i", $style_code.$i, 10, 16, "inbox") ?>
                              </td>
                              <td> 
                                <?= $f->fillTextBox("style_code$i", $style_code.$i, 26, 32, "inbox") ?>
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
                              <td width="36%" align="center"><input type="submit" name="Submit324" value=<?= $label[$lang]["Record"] ?>> 
                                <input type="submit" name="Submit2224" value=<?= $label[$lang]["Cancel"] ?>></td>
                            </tr>
                          </table></td>
                      </tr>
                    </form>
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
