<?php
	include_once("class/class.formutils.php");
	include_once("class/class.customers.php");
	$f = new FormUtil();

	$selbox = array(0=>array("value"=>"code", "name"=>"CR Memo Number"),
					1=>array("value"=>"cust", "name"=>"Customer Code"),
					2=>array("value"=>"tel", "name"=>"Telephone")
			  );

	if ($cmd=="filter") {
		$_SESSION[$cmemo_filter]["ft"] = $ft;
		$_SESSION[$cmemo_filter]["rv"] = $rv;
		$_SESSION[$cmemo_filter]["pg"] = $pg;
	} else {
		if (empty($ft)) $ft = $_SESSION[$cmemo_filter]["ft"];
		if (empty($rv)) $rv = $_SESSION[$cmemo_filter]["rv"];
		if (empty($pg)) $pg = $_SESSION[$cmemo_filter]["pg"];
	}

	if (empty($pg)) $pg = 1;

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function updateForm() {
		document.form1.pg.value = <?=$pg ?>;
		document.form1.ch.value = "t";
		document.form1.action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
		document.form1.submit();
	}

	function setFilter() {
		var f = document.forms[0];
		f.method = "POST" ;
		f.pg.value = 1;
		f.cmd.value = "filter";
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}
//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Credit Memo</strong></td>
                    </tr>
	  <form name="form1" method="get" action="">
                    <tr>
    <td colspan="8" align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
            | </font></td>
            <td align="right">
				&nbsp;
            </td>
        </tr>
      </table></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="right"><font size="2">
								<?= $f->fillTextBox("ft",$ft,30) ?>
								<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
								<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> ><?= $label[$lang]["Reverse"] ?>
								<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="javascript:setFilter()">
								<?= $f->fillHidden("pg",$pg) ?>
								<?= $f->fillHidden("cmd","") ?>
							 </td>
                    </tr>
		</form>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white">CM #</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Customer"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Date_1"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["SubTotal"]?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Tax"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Freight"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Total"] ?></font></th>
                            <th bgcolor="gray" width="2%"><font color="white">Pr</font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new Cmemo();
	$v = new Custs();
	$numrows = $c->getCmemoRows($cn, $ft);
	$recs = $c->getCmemoList($cn, $ft, $rv, $pg, $limit);

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
		$arr = $v->getCusts($recs[$i]["cmemo_cust_code"]);
		$status = "&nbsp;";
		$line_total = $recs[$i]["cmemo_amt"] + $recs[$i]["cmemo_tax_amt"] + $recs[$i]["cmemo_freight_amt"] ;
?>
                            <td align="center" width="10%"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&cmemo_id=".$recs[$i]["cmemo_id"] ?>"><?= $recs[$i]["cmemo_id"] ?></a>
                            </td>
                            <td align="left" width="10%"> 
                              <?= $recs[$i]["cmemo_cust_code"] ?>
                            </td>
                            <td align="left" width="30%"> 
                              <?= $arr["cust_name"] ?>
                            </td>
                            <td align="center" width="10%"> 
                              <?= $recs[$i]["cmemo_date"] ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["cmemo_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["cmemo_tax_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["cmemo_freight_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= number_format($line_total, 2, ".", ",") ?>
                            </td>
                            <td  width="2%" align="center"> 
							  <a href="ar_proc.php?cmd=cmemo_print&ty=l&pg=<?= $pg ?>&cmemo_id=<?= $recs[$i]["cmemo_id"] ?>"><?= $recs[$i]["cmemo_print"] ?></a>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&cn=$cn&ft=$ft" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&cn=$cn&ft=$ft" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&cn=$cn&ft=$ft" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&cn=$cn&ft=$ft" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
