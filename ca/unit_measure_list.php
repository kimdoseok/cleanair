                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Unit of Measure</strong></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">Unit#</font></th>
                            <th bgcolor="gray"><font color="white">Name</font></th>
                            <th bgcolor="gray"><font color="white">Desc</font></th>
                            <th bgcolor="gray"><font color="white">Type</font></th>
                            <th bgcolor="gray"><font color="white">Factor</font></th>
                            <th bgcolor="gray"><font color="white">Prime</font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new UnitMeasures();
	$numrows = $c->getUnitMeasuresRows();
	$recs = $c->getUnitMeasuresList($condition, $filtertext, $reverse, $page, $limit);
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
                            <td width="10%" align="center"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&unit_code=".urlencode($recs[$i]["unit_code"]) ?>"><?= $recs[$i]["unit_code"] ?></a>
                            </td>
                            <td width="15%" align="left">
                              <?= $recs[$i]["unit_name"] ?>
                            </td>
                            <td width="45%" align="left"> 
                              <?= $recs[$i][unit_desc] ?>
                            </td>
                            <td width="10%" align="center"> 
<?php
	if ($recs[$i][unit_type]=="e") echo "Each";
	else if ($recs[$i][unit_type]=="v") echo "Volume";
	else if ($recs[$i][unit_type]=="l") echo "Length";
	else if ($recs[$i][unit_type]=="a") echo "Area";
	else if ($recs[$i][unit_type]=="w") echo "Weight";
?>
                            </td>
                            <td width="10%" align="right"> 
                              <?= $recs[$i][unit_factor] ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= ($recs[$i][unit_prime]=="t")?"Yes":"" ?>
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
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
