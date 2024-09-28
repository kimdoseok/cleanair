<?php
	$selbox = array(0=>array("value"=>"r", "name"=>$label[$lang]["Sales"]),
					1=>array("value"=>"p", "name"=>$label[$lang][Purchase]), 
					2=>array("value"=>"i", "name"=>$label[$lang]["Inventory"]), 
					3=>array("value"=>"g", "name"=>$label[$lang]["General_Ledger"]),
					4=>array("value"=>"c", "name"=>$label[$lang]["Cash_Receipt"]),
					5=>array("value"=>"d", "name"=>$label[$lang][Disbursement])
					);
?>
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=post action="gl_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="jrnltrx_add">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][New_Account] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left">
<!--
						<font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> |</font>
-->
						</td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang][Account_no] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $f->fillTextBox("jrnltrx_id", $jrnltrx_id, 32, 32, "inbox") ?>
						  <a href="javascript:setPaste('jrnltrx_id')"><?= $label[$lang][Paste] ?></a>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Type"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillSelectBox($selbox,"jrnltrx_type", "value", "name", $jrnltrx_type) ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Description"] ?>:&nbsp;</td>
                  <td> 
                    <?= $f->fillTextBox("jrnltrx_desc", $jrnltrx_desc, 32, 32, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
            <td width="13">&nbsp; </td>
            <td width="275" align="right" ><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="95" align="right" >&nbsp;</td>
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
                            <td width="100%" align="center"><input type="submit" name="Submit32" value="<?= $label[$lang]["Record"] ?>"> 
                              <input type="reset" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>
                  </table>
