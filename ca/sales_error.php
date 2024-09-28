<?php
	$sd = new SaleDtls();

	if (empty($pg)) $pg = 1
?>

                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>Sales Errors</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Sale_no"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Customer</font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Date_1"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white">LineTotal</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">LineCount</font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["SubTotal"]?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Tax"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Freight"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Total"] ?></font></th>
                          </tr>
<?php
	$limit = 10000;
	$numrows = $c->getSalesRows($cn, $ft);
	$recs = $c->getSalesList($cn, $ft, $rv, $pg, $limit);
	if($recs) $numrecs = count($recs);    
    $oddnum = 1;
    for ($i=0; $i<$numrecs; $i++) {
    	$recd = $sd->getSaleDtlsList($recs[$i]["sale_id"]);
        $lineamt = 0;
        $linecount = count($recd);
		for ($j=0;$j<$linecount;$j++) $lineamt += $recd[$j]["slsdtl_cost"]*$recd[$j]["slsdtl_qty"];
        if (round($lineamt*100)==round($recs[$i]["sale_amt"]*100)) continue;
        if ($oddnum == 1) {
            echo "<tr>";
            $oddnum = 0;
		} else {
            echo "<tr bgcolor=\"#EEEEEE\">";
            $oddnum = 1;
        }
		$line_total = $recs[$i]["sale_freight_amt"]+$recs[$i]["sale_tax_amt"]+$recs[$i]["sale_amt"];
?>
                            <td align="center" width="10%"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&sale_id=".$recs[$i]["sale_id"] ?>"><?= $recs[$i]["sale_id"] ?></a>
                            </td>
                            <td align="left" width="10%"> 
                              <?= $recs[$i]["sale_cust_code"] ?>
                            </td>
                            <td align="left" width="10%"> 
                              <?= $recs[$i]["sale_date"] ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $lineamt) ?>
                            </td>
                            <td align="center" width="10%"> 
                              <?= $linecount ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["sale_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["sale_tax_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["sale_freight_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= number_format($line_total, 2, ".", ",") ?>
                            </td>
                            <td  width="2%" align="center"> 
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="9" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
					    </table>
					  </td>
					</tr>
                  </table>
