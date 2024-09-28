<?php
	include_once("class/class.formutils.php");

	$selbox = array(0=>array("value"=>"r", "name"=>$label[$lang]["Sales"]),
					1=>array("value"=>"p", "name"=>$label[$lang][Purchase]), 
					2=>array("value"=>"i", "name"=>$label[$lang]["Inventory"]), 
					3=>array("value"=>"g", "name"=>$label[$lang]["General_Ledger"]),
					4=>array("value"=>"c", "name"=>$label[$lang]["Cash_Receipt"]),
					5=>array("value"=>"d", "name"=>$label[$lang][Disbursement])
					);
	$f = new FormUtil();
	if (empty($sc)) $sc = $label[$lang]["all"];					
	if ($ch == "t") $pg = 1;
	if (empty($pg)) $pg = 1;

?>
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List_Transactions</strong></td>
                    </tr>
                    <tr>
    <td colspan="8" align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr>
          <td>
<!--
		  <font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New_1</a> 
            | </font>
-->
			</td>
            <td align="right"> 
			      <?= $f->fillTextBox("ft",$ft,20) ?>
					<?= $f->fillSelectBoxWithAllRefresh($selbox,"sc", "value", "name", $sc) ?>
					<input type="button" name="ft" value="Filter" onClick="updateFilter()">
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
                            <th bgcolor="gray"><font color="white">#</font></th>
                            <th bgcolor="gray"><font color="white">Ref.</font></th>
                            <th bgcolor="gray"><font color="white">Date</font></th>
                            <th bgcolor="gray"><font color="white">Type</font></th>
                            <th bgcolor="gray" colspan="2"><font color="white">Acct #</font></th>
                            <th bgcolor="gray"><font color="white">Debit</font></th>
                            <th bgcolor="gray"><font color="white">Credit</font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new JrnlTrxs();
	$numrows = $c->getJrnlTrxsRows($sc, $ft);
	$recs = $c->getJrnlTrxsList($sc, $ft, $reverse, $pg, $limit);
	$a = new Accts();

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

		if ($recs[$i]["jrnltrx_type"] == "r") $type = "Sales";
		else if ($recs[$i]["jrnltrx_type"] == "p") $type = "Purchase";
		else if ($recs[$i]["jrnltrx_type"] == "i") $type = "Inventory";
		else if ($recs[$i]["jrnltrx_type"] == "g") $type = "General Ledger";
		else if ($recs[$i]["jrnltrx_type"] == "c") $type = "Cash Receipt";
		else if ($recs[$i]["jrnltrx_type"] == "d") $type = "Disbursement";
		else $type = $label[$lang][Unknown];
		$aarr = $a->getAccts($recs[$i]["jrnltrx_acct_code"]);

?>
                            <td width="10%" align="center"> 
                              <?= $recs[$i][jrnltrx_id] ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["jrnltrx_ref_id"] ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["jrnltrx_date"] ?>
                            </td>
                            <td width="15%" align="center"> 
                              <?= $type ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["jrnltrx_acct_code"] ?>
                            </td>
                            <td width="30%" align="left"> 
                              <?= $aarr["acct_desc"] ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= ($recs[$i]["jrnltrx_dc"]=="d")?$recs[$i]["jrnltrx_amt"]+0:"&nbsp;" ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= ($recs[$i]["jrnltrx_dc"]=="c")?$recs[$i]["jrnltrx_amt"]+0:"&nbsp;" ?>
                            </td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red">No_Data!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&sc=$sc&ft=$ft" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&sc=$sc&ft=$ft" ?>">&lt;Prev_1</a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&sc=$sc&ft=$ft" ?>">Next_1&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&sc=$sc&ft=$ft" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
