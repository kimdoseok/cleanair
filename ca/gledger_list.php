<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td colspan="8"><strong>
      List General Ledger
      </strong></td>
  </tr>
  <tr> 
    <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">
      <?= $label[$lang]["New_1"] ?>
      </a> | </font></td>
  </tr>
  <tr align="right"> 
    <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr> 
          <th bgcolor="gray" width="12%"><font color="white">
            <?= $label[en]["No"] ?>
            </font></th>
          <th bgcolor="gray" width="12%"><font color="white">
            <?= $label[$lang]["Date_1"] ?>
            </font></th>
          <th bgcolor="gray" width="12%"><font color="white">
            <?= $label[$lang]["Amount"] ?>
            </font></th>
          <th bgcolor="gray" width="60%"><font color="white">
            <?= $label[$lang]["Comment"] ?>
            </font></th>
          <th bgcolor="gray" width="4%"><font color="white">&nbsp;</font></th>
        </tr>
        <?php
	$limit = 20;
	$c = new GLedger();
	$numrows = $c->getGLedgerRows($condition, $filtertext);
	$recs = $c->getGLedgerList($condition, $filtertext, $reverse, $page, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	$totalpage = $n->getTotalPage();
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
        <td align="center"> <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&gldgr_id=".$recs[$i][gldgr_id] ?>">
          <?= $recs[$i][gldgr_id] ?>
          </a> </td>
        <td align="center"> 
          <?= $recs[$i][gldgr_date] ?>
        </td>
        <td align="right"> 
          <?= sprintf("%0.2f", $recs[$i][gldgr_amt]) ?>
        </td>
        <td align="left"> 
          <?= $recs[$i][gldgr_cmnt] ?>
        </td>
        <td align="center"> <a href="gl_proc.php?cmd=gldgr_del&gldgr_id=<?= $recs[$i][gldgr_id] ?>">
          <?= $label[$lang]["Del"] ?>
          </a> </td>
        </tr>
<?php
	}
	if ($numrecs == 0) {
?>
        <tr>
          <td colspan="5" align="center"><font color="red">
            <?= $label[$lang]["No_Data"] ?>
            !</font></td>
        </tr>
        <?php
	}
?>
      </table></td>
  </tr>
  <tr align="right"> 
    <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="64%" align="center">
		    <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=1" ?>">&lt;&lt;
            <?= $label[$lang]["First"] ?>
            </a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$prevpage" ?>">&lt;
            <?= $label[$lang]["Prev_1"] ?>
            </a> &nbsp; <font color="gray">
            <?= "[$page / $totalpage]" ?>
            </font> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$nextpage" ?>">
            <?= $label[$lang]["Next_1"] ?>
            &gt;</a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage" ?>">
            <?= $label[$lang]["Last"] ?>
            t&gt;&gt;</a>
		  </td>
        </tr>
      </table></td>
  </tr>
</table>
