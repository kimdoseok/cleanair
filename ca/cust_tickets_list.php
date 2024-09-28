<?php
  //$oo = $_GET["oo"]+0;

?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right">
    <td colspan="8"><strong>List Tickets</strong></td>
  </tr>
  <form method="GET" action="<?= $PHPSELF ?>">
  <tr>
    <td colspan="1" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"> New
      </a> | </font></td>
    <td colspan="7" align="right">
      <input type="text" name="ft" size="30" value="<?= $ft ?>">
      <SELECT name="fo">
        <OPTION VALUE="ti" <?= ($fo=="ti")?"SELECTED":"" ?>>Title</OPTION>
        <OPTION VALUE="cu" <?= ($fo=="cu")?"SELECTED":"" ?>>Customer</OPTION>
        <OPTION VALUE="rf" <?= ($fo=="rf")?"SELECTED":"" ?>>Reference</OPTION>
        <OPTION VALUE="us" <?= ($fo=="us")?"SELECTED":"" ?>>User</OPTION>
      </SELECT>
      <input type="checkbox" name="oo" value="1" <?= ($oo==1)?"CHECKED":"" ?>>Open Only
      <input type="submit" name="Filter" value="Filter">
    </td>
  </tr>
  </form>
  <tr align="right">
    <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <th width="8%" bgcolor="gray"><font color="white">#</font></th>
          <th width="40%" bgcolor="gray"><font color="white">Title</font></th>
          <th width="10%" bgcolor="gray"><font color="white">Customer</font></th>
          <th width="8%" bgcolor="gray"><font color="white">Ref#</font></th>
          <th width="10%" bgcolor="gray"><font color="white">User</font></th>
          <th width="23%" bgcolor="gray"><font color="white">Date/Time</font></th>
          <th width="3%" bgcolor="gray"><font color="white">S</font></th>
        </tr>
        <?php
	$limit = 100;
	$numrows = $c->getTicketsRows($fo, $ft, $oo);
	$recs = $c->getTicketsList($fo, $ft, $oo, $page, $limit);
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
        <td align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&tkt_id=".$recs[$i]["tkt_id"] ?>">
            <?= $recs[$i]["tkt_id"] ?>
            </a> </td>
          <td align="left"><?= $recs[$i]["tkt_title"] ?>
          </td>
          <td align="center"><?= $recs[$i]["tkt_cust_code"] ?>
          </td>
          <td align="center"><?= $recs[$i]["tkt_refnum"] ?>
          </td>
          <td align="center"><?= $recs[$i]["tkt_user_code"] ?>
          </td>
          <td align="center"><?= $recs[$i]["tkt_ts"] ?></td>
          <td align="center"><?php if ($recs[$i]["tkt_status"]==10) echo "X"; else echo "O"; ?></td>
        </tr>
        <?php
	}
	if ($numrecs == 0) {
?>
        <tr>
          <td colspan="6" align="center"><font color="red">No Data!</font></td>
        </tr>
        <?php
	}
?>
      </table></td>
  </tr>
  <tr align="right">
    <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=1" ?>">&lt;&lt;First</a> &nbsp; 
		  <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$prevpage" ?>">&lt;Prev</a> &nbsp; <font color="gray">
            <?= "[$page / $totalpage]" ?>
            </font> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$nextpage" ?>">Next&gt;</a> &nbsp; 
			<a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage" ?>">Last&gt;&gt;</a></td>
        </tr>
      </table></td>
  </tr>
</table>
