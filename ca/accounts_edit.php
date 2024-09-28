<?php

	$selbox = array(0=>array("value"=>"as", "name"=>$label[$lang]["Asset"]),
					1=>array("value"=>"li", "name"=>$label[$lang]["Liability"]), 
					2=>array("value"=>"eq", "name"=>$label[$lang]["Equity"]), 
					3=>array("value"=>"in", "name"=>$label[$lang]["Income"]),
					4=>array("value"=>"cs", "name"=>$label[$lang]["Cost_of_Sale"]),
					5=>array("value"=>"ex", "name"=>$label[$lang]["Expense"]),
					6=>array("value"=>"mi", "name"=>$label[$lang]["Misc_Income"]),
					7=>array("value"=>"me", "name"=>$label[$lang]["Misc_Expense"])
					);
?>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="gl_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="acct_edit">
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit_Account</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New_1</a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code" ?>">View</a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List_1</a>  | </font></td>
                      <td colspan="4" align="right"><font size="2"> | 
					    <a href="<?php echo "gl_proc.php?cmd=acct_del&acct_code=$acct_code" ?>">Del</a>  |</font></td>
                    </tr>
                    <tr align="right"> 
                      
      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Account_no:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("acct_code", $acct_code, 32, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Type:&nbsp;</td>
                  <td> 
                    <?= $f->fillSelectBox($selbox,"acct_type", "value", "name", $acct_type) ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("acct_desc", $acct_desc, 64, 64, "inbox") ?>
                  </td>
                </tr>
              </table></td>
            <td width="12">&nbsp; </td>
            <td width="276" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="95" align="right">&nbsp;</td>
                  <td width="136">&nbsp; </td>
                </tr>
                <tr> 
                  <td width="95" align="right">&nbsp;</td>
                  <td width="136">&nbsp; </td>
                </tr>
                <tr> 
                  <td width="95" align="right">&nbsp;</td>
                  <td width="136">&nbsp; </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=-2" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=-1" ?>">&lt;Prev_1</a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=1" ?>">Next_1&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=$acct_code&dir=2" ?>">Last&gt;&gt;</a></td>
                            <td width="36%" align="center"><input type="submit" name="Submit322" value="Record"> 
                              <input type="reset" name="Submit2222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
						</form>
                  </table>
