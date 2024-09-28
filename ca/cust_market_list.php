				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td><strong>Customer List To Be Called</strong></td>
                    </tr>
					<form>
					<tr align="right"> 
                      <td>
						<?= $f->fillTextBox("ft",$ft,16) ?>
						<a href="javascript:openCalendar('ft')">Calendar</a>
						<?php
						//echo $f->fillSelectBox($selbox,"cn", "value", "name", $cn);
						?>
						<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?>>
						Reverse
						<input type="button" name="filter" value="Filter" onClick="setFilter()">
						<?= $f->fillHidden("objname",$objname) ?>
						<?= $f->fillHidden("page",$page) ?>
					  </td>
                    </tr>
					</form>
                    <tr align="right"> 
                      <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white">#</font></th>
                            <th bgcolor="gray" width="25%"><font color="white">Name</font></th>
                            <th bgcolor="gray" width="25%"><font color="white">Address</font></th>
                            <th bgcolor="gray" width="15%"><font color="white">City</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Tel</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">SM</font></th>
                            <th bgcolor="gray" width="9%"><font color="white">Balance</font></th>
                            <th bgcolor="gray" width="1%"><font color="white">A</font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new Custs();
	$d = new DateX();
	$numrows = $c->getCustsRowsCRM($d->getWeekday($ft));
	$recs = $c->getCustsListCRM($d->getWeekday($ft), $rv, $page, $limit);
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
                            <td align="center"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&cust_code=".urlencode($recs[$i]["cust_code"]) ?>"><?= $recs[$i]["cust_code"] ?></a>
                            </td>
                            <td> 
                              <A HREF="sales.php?ty=a&sale_cust_code=<?= urlencode($recs[$i]["cust_code"]) ?>"><?= $recs[$i]["cust_name"] ?></a>
                            </td>
                            <td><?= $recs[$i]["cust_addr1"] ?></td>
                            <td><?= $recs[$i]["cust_city"] ?></td>
                            <td><?= $recs[$i]["cust_tel"] ?></td>
                            <td><?= $recs[$i]["cust_slsrep"] ?></td>
                            <td align="right"> <A HREF="sales.php?ft=<?= $recs[$i]["cust_code"] ?>&cn=cust&pg=1">
							  <?= number_format($recs[$i]["cust_balance"], 2, ".", ",") ?>
							  </A>
                            </td>
                            <td><?= ($recs[$i]["cust_active"] != "f")?"X":"&nbsp;" ?></td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="8" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
						</table></td>
                    </tr>
                    <tr align="right"> 
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&ty=l&page=1" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&ty=l&page=$prevpage" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&ty=l&page=$nextpage" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&ty=l&page=$totalpage" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
