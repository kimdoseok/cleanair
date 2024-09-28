<?php
	include_once("class/class.formutils.php");

	$selbox = array(0=>array("value"=>"as", "name"=>$label[$lang]["Asset"]),
					1=>array("value"=>"li", "name"=>$label[$lang]["Liability"]), 
					2=>array("value"=>"eq", "name"=>$label[$lang]["Equity"]), 
					3=>array("value"=>"in", "name"=>$label[$lang]["Income"]),
					4=>array("value"=>"cs", "name"=>$label[$lang]["Cost_of_Sale"]),
					5=>array("value"=>"ex", "name"=>$label[$lang]["Expense"]),
					6=>array("value"=>"mi", "name"=>$label[$lang]["Misc_Income"]),
					7=>array("value"=>"me", "name"=>$label[$lang]["Misc_Expense"])
					);
	$f = new FormUtil();
	if (empty($sc)) $sc = $label[$lang]["all"];					
	if ($ch == "t") $pg = 1;
	if (empty($pg)) $pg = 1;
?>
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang]["List_Account"] ?></strong></td>
                    </tr>
                    <tr>
    <td colspan="8" align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr>
          <td><font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> | </font></td>
            <td align="right"> 
			      <?= $f->fillTextBox("ft",$ft,20) ?>
					<?= $f->fillSelectBoxWithAllRefresh($selbox,"sc", "value", "name", $sc) ?>
					<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="javascript:updateFilter()">
					<?= $f->fillHidden("ty", $ty) ?>
					<?= $f->fillHidden("pg", $pg) ?>
					<?= $f->fillHidden("ch", "f") ?>
            </td>
        </tr>
		</form>
      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Acct_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Type"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new Accts();
	$numrows = $c->getAcctsRows($sc, $ft);
	$recs = $c->getAcctsList($sc, $ft, $reverse, $pg, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";

		if ($recs[$i][acct_type] == "as") $acct_type = $label[$lang]["Asset"];
		else if ($recs[$i][acct_type] == "li") $acct_type = $label[$lang]["Liability"];
		else if ($recs[$i][acct_type] == "eq") $acct_type = $label[$lang]["Equity"];
		else if ($recs[$i][acct_type] == "in") $acct_type = $label[$lang]["Income"];
		else if ($recs[$i][acct_type] == "cs") $acct_type = $label[$lang]["Cost_of_Sale"];
		else if ($recs[$i][acct_type] == "ex") $acct_type = $label[$lang]["Expense"];
		else if ($recs[$i][acct_type] == "mi") $acct_type = $label[$lang]["Misc_Income"];
		else if ($recs[$i][acct_type] == "me") $acct_type = $label[$lang]["Misc_Expense"];
		else $acct_type = $label[$lang][Unknown];
?>
                            <td width="60" align="center"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&acct_code=".$recs[$i]["acct_code"] ?>"><?= $recs[$i]["acct_code"] ?></a>
                            </td>
                            <td width="80" align="center"> 
                              <?= $acct_type ?>
                            </td>
                            <td> 
                              <?= $recs[$i]["acct_desc"] ?>
                            </td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&sc=$sc&ft=$ft" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&sc=$sc&ft=$ft" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
									<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&sc=$sc&ft=$ft" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&sc=$sc&ft=$ft" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>