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
    <td colspan="8"><strong>Bill of Lading (Detail)</strong></td>
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
		else $selected = "";
		echo "<option $selected>".$sv_arr[$i]["pick_shipvia"]."</option>";
	}
?>
			  </SELECT>
              <input type="radio" name="bl_type" value="s">
              Summary 
              <input type="radio" name="bl_type" value="d" checked>
              Detail 
              <input type="submit" name="submit" value="Submit"> </td>
          </tr>
        </form>
      </table></td>
  </tr>
  <tr align="right"> 
    <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr> 
          <th bgcolor="gray"><font color="white">
            P#
            </font></th>
          <th bgcolor="gray"><font color="white">
            S#
            </font></th>
          <th bgcolor="gray" colspan="2"><font color="white">
            Customer
            </font></th>
          <th bgcolor="gray" colspan="2"><font color="white">
            Item
            </font></th>
          <th bgcolor="gray"><font color="white">
            Qty
            </font></th>
          <th bgcolor="gray"><font color="white">
            Price
            </font></th>
        </tr>
<?php
	$limit = 20;
	$c = new PickDtls();
	if ($tf = $x->isUsaDate($bl_date)) $bldate = $x->toIsoDate($bl_date);
	$recs = $c->getPicksListCustDate($bldate, $shipvia);

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
        <td align="center" width="6%">
          <?= $recs[$i]["pick_id"] ?>
        </td>
        <td align="center" width="6%">
          <?= $recs[$i]["slsdtl_sale_id"] ?>
        </td>
        <td align="center" width="6%"> 
          <?= $recs[$i]["pick_cust_code"] ?>
        </td>
        <td align="left"> 
          <?= $recs[$i]["cust_name"] ?>
        </td>
        <td align="center" width="7%"> 
          <?= $recs[$i]["slsdtl_item_code"] ?>
        </td>
        <td align="left"> 
          <?= $recs[$i]["slsdtl_item_desc"] ?>
        </td>
        <td align="right" width="6%"> 
          <?= $recs[$i]["pickdtl_qty"]+0 ?>
        </td>
        <td align="right" width="8%"> 
          <?= sprintf("%0.2f", $recs[$i]["pickdtl_cost"]) ?>
        </td>
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
