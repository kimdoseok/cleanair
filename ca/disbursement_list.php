<?php
	include_once("class/class.vendors.php");
?>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][List_Disbursements] ?></strong></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang][DIS_no] ?></font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang][Vendor] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Date_1"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
                          </tr>
<?php
	$limit = 100;
	$v = new Vends();
	$c = new Disburs();
	$numrows = $c->getDisbursRows();
	$recs = $c->getDisbursList($condition, $filtertext, $reverse, $page, $limit);
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
		$arr = $v->getVends($recs[$i][disbur_vend_code]);
?>
                            <td width="10%" align="center"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&disbur_id=".$recs[$i][disbur_id] ?>"><?= $recs[$i][disbur_id] ?></a>
                            </td>
                            <td width="20%"> 
                              <?= $recs[$i][disbur_vend_code] ?>
                            </td>
                            <td width="40%"> 
                              <?= $arr["vend_name"] ?>
                            </td>
                            <td width="15%" align="center"> 
                              <?= $recs[$i][disbur_date] ?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i][disbur_amt]) ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=1" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$prevpage" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$nextpage" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage" ?>"><?= $label[$lang]["Last"] ?>t&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
