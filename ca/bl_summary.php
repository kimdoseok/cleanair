<?php
	$f = new FormUtil();
	$x = new Datex();
	if (empty($page)) $page = 1;
	if (empty($bl_date)) $bl_date = $x->getToday();

	$p = new Picks();
	$sv_arr = $p->getPickShipViaList();
	$sv_num = count($sv_arr);
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td colspan="8"><strong>Bill of Lading (Summary)</strong></td>
  </tr>
  <tr> 
    <td colspan="8" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <form name="form1" method="get" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>">
          <tr> 
            <td align="left"> Date: 
              <?= $f->fillTextBox("bl_date", $bl_date, 12, 32, "inbox") ?>
			  <a href="javascript:openCalendar('bl_date')">C</a>
			  <SELECT NAME="shipvia">
			  <option value="all" <?= ($shipvia == "all")?"selected":"" ?>>All</option>
<?php
	for ($i=0;$i<$sv_num;$i++) {
		if ($sv_arr[$i]["pick_shipvia"]==$shipvia) $selected = "selected";
		echo "<option $selected>".$sv_arr[$i]["pick_shipvia"]."</option>";
	}
?>
			  </SELECT>
              <input type="radio" name="bl_type" value="s" checked>
              Summary 
              <input type="radio" name="bl_type" value="d">
              Detail 
              <input type="submit" name="submit" value="Submit"> </td>
          </tr>
        </form>
      </table></td>
  </tr>
  <tr align="right"> 
    <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr> 
          <th bgcolor="gray" width="20%"><font color="white">Item Code</font></th>
          <th bgcolor="gray" width="65%"><font color="white">Item Description</font></th>
          <th bgcolor="gray" width="15%"><font color="white">Quantity</font></th>
        </tr>
<?php
	$limit = 20;
	$c = new PickDtls();
	if ($tf = $x->isUsaDate($bl_date)) $bl_date = $x->toIsoDate($bl_date);
	$recs = $c->getPicksListDate($bl_date, $shipvia);

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
        <td align="center" width="10%">
          <?= $recs[$i]["slsdtl_item_code"] ?>
          </td>
        <td align="left" width="10%"> 
          <?= $recs[$i]["item_desc"] ?>
        </td>
        <td align="right" width="30%"> 
          <?= $recs[$i]["pickdtl_qty_sum"]+0 ?>
        </td>
<?php
	}
	if ($numrecs == 0) {
?>
        <tr>
          <td colspan="9" align="center"><font color="red">
            <?= $label[$lang]["No_Data"] ?>
            !</font></td>
        </tr>
        <?php
	}
?>
      </table></td>
  </tr>
<!--
  <tr align="right"> 
    <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=1&sc=$sc" ?>">&lt;&lt;
            <?= $label[$lang]["First"] ?>
            </a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$prevpage&sc=$sc" ?>">&lt;
            <?= $label[$lang]["Prev_1"] ?>
            </a> &nbsp; <font color="gray">
            <?= "[$page / $totalpage]" ?>
            </font> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$nextpage&sc=$sc" ?>">
            <?= $label[$lang]["Next_1"] ?>
            &gt;</a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage&sc=$sc" ?>">
            <?= $label[$lang]["Last"] ?>
            &gt;&gt;</a></td>
        </tr>
      </table></td>
  </tr>
-->
</table>
