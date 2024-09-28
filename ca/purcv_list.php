                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Received Purchase</strong></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=a" ?>">New</a> | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th width="40%" bgcolor="gray"><font color="white">Item#</font></th>
                            <th width="10%" bgcolor="gray"><font color="white">Ord.Qty</font></th>
                            <th width="10%" bgcolor="gray"><font color="white">Rcv.Qty</font></th>
                            <th width="30%" bgcolor="gray"><font color="white">Date</font></th>
                            <th width="10%" bgcolor="gray"><font color="white">Inv.#</font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new Purcvds();
	$numrows = $c->getPurcvdsRows();
	$recs = $c->getPurcvdsList($purcv_purdtl_id, $filtertext, $reverse, $page, $limit);
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
                            <td align="center"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=$purcv_purdtl_id&ty=v&purcv_id=".$recs[$i][purcv_id] ?>"><?= $recs[$i]["purdtl_item_code"] ?></a>
                            </td>
                            <td align="right">
                              <?= $recs[$i]["purdtl_qty"]+0 ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][purcv_qty]+0 ?>
                            </td>
                            <td align="center"> 
                              <?= $recs[$i][purcv_date] ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][purcv_inv_no] ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=<?= $purcv_purdtl_id ?>&ty=l&page=1" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=<?= $purcv_purdtl_id ?>&ty=l&page=$prevpage" ?>">&lt;Prev</a> &nbsp; 
								<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=<?= $purcv_purdtl_id ?>&ty=l&page=$nextpage" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?purcv_purdtl_id=<?= $purcv_purdtl_id ?>&ty=l&page=$totalpage" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
