                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>View Unit of Measure</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&unit_code=$unit_code" ?>"><?= $label[$lang]["Edit"] ?></a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
                  <td width="20%" align="right" bgcolor="silver">Unit#:&nbsp;</td>
                  <td width="80%"> 
                    <?= $unit_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Name:&nbsp;</td>
                  <td> 
                    <?= $unit_name ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td> 
                    <?= $unit_desc ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Type&nbsp;</td>
                  <td> 
<?php
	if ($unit_type=="e") echo "Each";
	else if ($unit_type=="v") echo "Volume";
	else if ($unit_type=="l") echo "Length";
	else if ($unit_type=="a") echo "Area";
	else if ($unit_type=="w") echo "Weight";
	else echo "&nbsp;";
?>
				  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Factor&nbsp;</td>
                  <td> 
                    <?= $unit_factor ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" bgcolor="silver">Prime?&nbsp;</td>
                  <td> 
				    <?= ($unit_prime!="f")?"Yes":"No" ?>
                  </td>
                </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8">
					    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&unit_code=$unit_code" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&unit_code=$unit_code" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&unit_code=$unit_code" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&unit_code=$unit_code" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table>
					  </td>
                    </tr>
                  </table>
