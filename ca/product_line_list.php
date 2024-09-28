                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Product Lines</strong></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">Product Line#</font></th>
                            <th bgcolor="gray"><font color="white">Name</font></th>
                            <th bgcolor="gray"><font color="white">Desc</font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new ProductLine();
	$numrows = $c->getProductLineRows();
	$recs = $c->getProductLineList($condition, $filtertext, $reverse, $page, $limit);
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
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&productline_code=".urlencode($recs[$i]["productline_code"]) ?>"><?= $recs[$i]["productline_code"] ?></a>
                            </td>
                            <td width="15%" align="left">
                              <?= $recs[$i]["productline_name"] ?>
                            </td>
                            <td width="45%" align="left"> 
                              <?= $recs[$i][productline_desc] ?>
                            </td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red">No Data!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=1" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$prevpage" ?>">&lt;Prev</a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$nextpage" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
