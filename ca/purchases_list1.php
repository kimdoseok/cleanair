<?php
	include_once("class/class.formutils.php");
	include_once("class/class.vendors.php");
	$f = new FormUtil();
	if (empty($page)) $page = 1;

?>
	<script language="JavaScript" type="text/JavaScript">
		function updateForm() {
			document.form1.page.value = <?=$page ?>;
			document.form1.ch.value = "t";
			document.form1.action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
			document.form1.submit();
		}
	</script>

                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang]["Purchase_List"] ?></strong></td>
                    </tr>
                    <tr>
    <td colspan="8" align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr>
          <td><font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
            | </font></td>
            <td align="right">
				&nbsp;
            </td>
        </tr>
		</form>
      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang][Pur_no] ?></font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang][Vendor] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Date_1"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["SubTotal"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Tax"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Freight"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Total"] ?></font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new Purchases();
	$v = new Vends();
	$numrows = $c->getPurchasesRows();
	$recs = $c->getPurchasesList("", "", "f", 1, $numrows);

	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
		$arr = $v->getVends($recs[$i]["purch_vend_code"]);

?>
                            <td align="center" width="10%"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&purch_id=".$recs[$i]["purch_id"] ?>"><?= $recs[$i]["purch_id"] ?></a>
                            </td>
                            <td align="left" width="10%"> 
                              <?= $recs[$i]["purch_vend_code"] ?>
                            </td>
                            <td align="left" width="30%"> 
                              <?= $recs[$i][styl_date] ?>
                            </td>
                            <td align="left" width="10%"> 
                              <?= $recs[$i]["purch_date"] ?>
                            </td>
                            <td  width="10%"> 
                              <?= $recs[$i]["purch_amt"]+0 ?>
                            </td>
                            <td  width="10%"> 
                              <?= $recs[$i]["purch_tax_amt"]+0 ?>
                            </td>
                            <td  width="10%"> 
                              <?= $recs[$i]["purch_freight_amt"]+0 ?>
                            </td>
                            <td  width="10%"> 
                              <?= $recs[$i]["purch_freight_amt"]+$recs[$i]["purch_tax_amt"]+$recs[$i]["purch_amt"] ?>
                            </td>
                          </tr>

<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="9" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=1&sc=$sc" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$prevpage&sc=$sc" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$nextpage&sc=$sc" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage&sc=$sc" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
