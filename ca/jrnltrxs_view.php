                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" align="right"><strong><?= $label[$lang]["View_Account"] ?></strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2">
							    | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&jrnltrx_id=$jrnltrx_id" ?>"><?= $label[$lang]["Edit"] ?></a> |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l&jrnltrx_id=$jrnltrx_id" ?>"><?= $label[$lang]["List_1"] ?></a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <td width="97" align="right" bgcolor="silver"><?= $label[$lang][jrnltrx_no] ?>:&nbsp;</td>
                            <td width="308"> 
                              <?= $jrnltrx_id  ?>
										<a href="gl_proc.php?cmd=copy&v=<?= $jrnltrx_id ?>"><font size="2"><?= $label[$lang][Copy_1] ?></font></a>
                            </td>
                            <td width="11">&nbsp; </td>
                            <td width="95" align="right">&nbsp;</td>
                            <td width="136"> 
                              &nbsp;
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Type"] ?>:&nbsp;</td>
                            <td> 
<?php
		if ($jrnltrx_type == "r") $type = $label[$lang]["Sales"]";
		else if ($jrnltrx_type == "p") $type = $label[$lang][Purchase]";
		else if ($jrnltrx_type == "i") $type = $label[$lang]["Inventory"]";      
		else if ($jrnltrx_type == "g") $type = $label[$lang]["General_Ledger"]";      
		else if ($jrnltrx_type == "c") $type = $label[$lang]["Cash_Receipt"]";
		else if ($jrnltrx_type == "d") $type = $label[$lang][Disbursement]";     
		else $type = $label[$lang][Unknown];
?>
                              <?= $type ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">&nbsp;</td>
                            <td width="136"> 
                              &nbsp;
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" align="right" bgcolor="silver"><?= $label[$lang]["Description"] ?>:&nbsp;</td>
                            <td> 
                              <?= $jrnltrx_desc ?>
                            </td>
                            <td width="11">&nbsp;</td>
                            <td width="95" align="right">&nbsp;</td>
                            <td width="136"> 
                              &nbsp;
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&jrnltrx_id=$jrnltrx_id&dir=-2" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&jrnltrx_id=$jrnltrx_id&dir=-1" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
										&nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&jrnltrx_id=$jrnltrx_id&dir=1" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&jrnltrx_id=$jrnltrx_id&dir=2" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
