<?php
	include_once("class/class.formutils.php");
	include_once("class/class.styles.php");
	$f = new FormUtil();
	if ($ch == "t") $page = 1;
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
                      <td colspan="8"><strong><?= $label[$lang]["List_Account"] ?></strong></td>
                    </tr>
                    <tr>
    <td colspan="8" align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr>
          <td><font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
            | </font></td>
            <td align="right"><?= $label[$lang][Type_Filter] ?>: 
              <?= $f->fillSelectBoxWithAllRefresh($selbox,"sc", "value", "name", $sc) ?>
			  <?= $f->fillHidden("ty", $ty) ?><?= $f->fillHidden("page", $page) ?><?= $f->fillHidden("ch", "f") ?>
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
	$limit = 20;
	$c = new Styles();
	$numrows = $c->getStylesRows($sc);
	$recs = $c->getStylesList($sc, $filtertext, $reverse, $page, $limit);

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

?>
                            <td width="60" align="center"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&styl_code=".$recs[$i][styl_code] ?>"><?= $recs[$i][styl_code] ?></a>
                            </td>
                            <td width="80" align="center"> 
                              <?= $acct_type ?>
                            </td>
                            <td> 
                              <?= $recs[$i][styl_code] ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=1&sc=$sc" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$prevpage&sc=$sc" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$nextpage&sc=$sc" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage&sc=$sc" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
