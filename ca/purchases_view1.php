<?php
	include_once("class/class.datex.php");
	$d = new Datex();

	include_once("class/class.formutils.php");
	$f = new FormUtil();
		
	include_once("class/class.navigates.php");
	$limit = 1;
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();


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
							 <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&purch_id=$purch_id" ?>"><?= $label[$lang]["Edit"] ?></a> |
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
                  <td width="97" align="right" bgcolor="silver"><?= $label[$lang][Vendor] ?>:&nbsp;</td>
                  <td width="308"> 
                    <?= $purch_vend_code ?>
                  </td>
                </tr>
                <tr> 
                  <td width="97" align="right" valign="top">&nbsp;</td>
                  <td valign="top"> 
<?php
	$line1 = $varr["vend_name"];
	if (!empty($line1)) $line1 .= "<br>";
	echo $line1;
	$line2 = $varr["vend_addr1"]." ".$varr["vend_addr2"]." ".$varr["vend_addr3"];
	if (!empty($line2)) $line2 .= "<br>";
	echo $line2;
	$line3 = $varr["vend_city"]." ".$varr["vend_state"]." ".$varr["vend_zip"]." ".$varr["vend_country"];
	echo $line3;

?>
                  </td>
                </tr>
              </table></td>
            <td width="1%">&nbsp;</td>
            <td width="37%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="95" align="right" bgcolor="silver"><?= $label[$lang][Trx_no] ?>&nbsp;</td>
                  <td width="136">
                    <?= $purch_id ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang][Vendor_Inv_no] ?></td>
                  <td> 
                    <?= $purch_vend_inv ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>&nbsp;</td>
                  <td> 
                    <?= $purch_date ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                  <td> 
                    <?= $purch_user_code ?>
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
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["PO_no"] ?></font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Item_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Cost"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["qty"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                            <th bgcolor="gray"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	include_once("class/class.purdtls.php");
	include_once("class/class.items.php");

	$t = new Items();
	$d = new PurDtls();
	$recs = $d->getPurDtlsList($purch_id);
	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i]["purdtl_item_code"]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"];
?>
                            <td width="6%" align="center"> 
                              <?= $i+1 ?>
                            </td>
                            <td width="10"> 
                              <?= $recs[$i][purdtl_po_no] ?>
                            </td>
                            <td width="10"> 
                              <?= $recs[$i]["purdtl_item_code"] ?>
                            </td>
                            <td width="30%"> 
                              <?= $arr["item_desc"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= $recs[$i]["purdtl_cost"]+0 ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= $recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i]["purdtl_cost"]*$recs[$i]["purdtl_qty"]+0 ?>
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
									<tr>
  <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr width="20%"> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%" width="80%">
                <?= $purch_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $purch_comnt ?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%"><?= $label[$lang]["Sub_Total"] ?></td>
              <td width="50%">
                <?= $subtotal+0 ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td align="right">
                <?= $purch_freight_amt+0 ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= $purch_tax_amt+0 ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= $purch_tax_amt+$purch_freight_amt+$subtotal ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                          </tr>
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
