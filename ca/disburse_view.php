<?php
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.disburdtls.php");
	include_once("class/class.vendors.php");
	include_once("class/class.purdtls.php");
	$x = new Datex();
	$s = new Disburse();
	$d = new DisburDtls();

	$limit = 1;
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$recs = $d->getDisburDtlsList($disbur_id);
	for ($i=0;$i<count($recs);$i++) $applied += $recs[$i][disburdtl_amt];
	$remained = $disbur_amt - $applied;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][View_Purchase] ?></strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="8"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> | 
							 <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&disbur_id=$disbur_id" ?>"><?= $label[$lang]["Edit"] ?></a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							
      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="62%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["PO_no"] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_po_no ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Vendor] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_vend_code ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Vendor_Inv_dno] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_vend_inv ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Check_no"] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_check_no ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Amount"] ?>:</td>
                            <td> 
                              <?= $disbur_amt ?>
                            </td>
                          </tr>
              </table></td>
            <td width="1%">&nbsp;</td>
            <td width="37%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Disb_no] ?>:</td>
                            <td width="308"> 
                              <?= $disbur_id ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
                            <td> 
                              <?= $disbur_date ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                            <td> 
                              <?= $disbur_user_code ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Applied] ?>:</td>
                            <td width="308"> 
                              <?= $applied ?>
                            </td>
                          </tr>
                          <tr> 
                            <td width="97" bgcolor="silver"><?= $label[$lang][Remained] ?>:</td>
                            <td> 
                              <?= $remained ?>
                            </td>
                          </tr>
              </table></td>
          </tr>
        </table> </td>
						  </tr>
						</table>
					  
					    
    </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="7"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["No"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang][Vend_Inv_no] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Acct_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
                          </tr>
<?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
                              <?= $i+1 ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i][disburdtl_vend_inv] ?>
                            </td>
                            <td width="13%"> 
                              <?= $recs[$i][disburdtl_acct_code] ?>
                            </td>
                            <td width="12%" align="right"> 
                              <?= $recs[$i][disburdtl_amt] ?>
                            </td>
                            <td width="60%"> 
                              <?= $recs[$i][disburdtl_desc] ?>
                            </td>
                          </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
						  <tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
                          </tr>
<?php
	}
?>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&purch_id=$purch_id" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&purch_id=$purch_id" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&purch_id=$purch_id" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&purch_id=$purch_id" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
