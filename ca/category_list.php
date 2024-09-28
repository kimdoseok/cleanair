<SCRIPT LANGUAGE="JavaScript">
<!--
	var prcBrowse;
	function adjPrice(code) {
		var f = document.forms[0];
		if (prcBrowse && !prcBrowse.closed) prcBrowse.close();
		prcBrowse = window.open("cate_price_popup.php?cate_code="+code, "prcBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=130,width=300");
		prcBrowse.focus();
		prcBrowse.moveTo(100,100);
	}

//-->
</SCRIPT>
 <table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td colspan="8"><strong>List Category</strong></td>
  </tr>
  <tr> 
    <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">
      <?= $label[$lang]["New_1"] ?>
      </a> | </font></td>
  </tr>
  <tr align="right"> 
    <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr> 
          <th bgcolor="gray"><font color="white">Code</font></th>
          <th bgcolor="gray"><font color="white">Name</font></th>
          <th bgcolor="gray"><font color="white">&nbsp;</font></th>
        </tr>
        <?php
	$limit = 100;
	$c = new Category();
	$numrows = $c->getCategoryRows();
	$recs = $c->getCategoryList($condition, $filtertext, $reverse, $page, $limit);
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
        <td width="20%" align="center"> <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&cate_code=".urlencode($recs[$i]["cate_code"]) ?>">
          <?= $recs[$i]["cate_code"] ?>
          </a> </td>
        <td width="70%" align="left"> 
		  <a href="<?= "items.php?ft=".$recs[$i]["cate_code"]."&cn=cat" ?>">
          <?= $recs[$i]["cate_name1"] ?>
		  <?= (!empty($recs[$i]["cate_name2"]))?":".$recs[$i]["cate_name2"]:"" ?>
		  <?= (!empty($recs[$i]["cate_name3"]))?":".$recs[$i]["cate_name3"]:"" ?>
		  <?= (!empty($recs[$i]["cate_name4"]))?":".$recs[$i]["cate_name4"]:"" ?>
		  </a>
        </td>
        <td width="10%" align="left"> 
		<A HREF="javascript:adjPrice('<?= $recs[$i]["cate_code"] ?>')">Adj Price</A>
        </td>
        </tr>
<?php
	}
	if ($numrecs == 0) {
?>
        <tr>
          <td colspan="3" align="center"><font color="red">
            <?= $label[$lang]["No_Data"] ?>!</font></td>
        </tr>
<?php
	}
?>
      </table></td>
  </tr>
  <tr align="right"> 
    <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=1" ?>">&lt;&lt;
            <?= $label[$lang]["First"] ?>
            </a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$prevpage" ?>">&lt;
            <?= $label[$lang]["Prev_1"] ?>
            </a> &nbsp; <font color="gray">
            <?= "[$page / $totalpage]" ?>
            </font> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$nextpage" ?>">
            <?= $label[$lang]["Next_1"] ?>
            &gt;</a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage" ?>">
            <?= $label[$lang]["Last"] ?>
            &gt;&gt;</a></td>
        </tr>
      </table></td>
  </tr>
</table>

