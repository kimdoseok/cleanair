<?php
include_once("class/register_globals.php");

	$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
					1=>array("value"=>"name", "name"=>"Name"),
					2=>array("value"=>"addr", "name"=>"Address"),
					3=>array("value"=>"city", "name"=>"City"),
					4=>array("value"=>"tel", "name"=>"Tel"),
					5=>array("value"=>"slsrep", "name"=>"Sales Rep."),
					6=>array("value"=>"points", "name"=>"Points")
	);


?>
				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Customer</strong></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=b" ?>">Label</a> | </font></td>
                    </tr>
					<form>
					<tr align="right"> 
                      <td colspan="8">
						<?= $f->fillTextBox("ft",$ft,30) ?>
						<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
						<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?>>
						<?= $label[$lang]["Reverse"] ?>
						<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="setFilter()">
						<?= $f->fillHidden("objname",$objname) ?>
						<?= $f->fillHidden("page",$page) ?>
					  </td>
                    </tr>
					</form>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
					    <font size="2">
                          <tr> 
                            <th width="10%" bgcolor="gray"><font color="white"><?= $label[$lang]["Cust_no"] ?></font></th>
                            <th width="24%" bgcolor="gray"><font color="white">Name</font></th>
                            <th width="24%" bgcolor="gray"><font color="white">Address</font></th>
                            <th width="14%" bgcolor="gray"><font color="white">City</font></th>
                            <th width="12%" bgcolor="gray"><font color="white">Tel</font></th>
                            <th width="3%" bgcolor="gray"><font color="white">SM</font></th>
                            <th width="9%" bgcolor="gray"><font color="white">Balance</font></th>
                            <th width="5%" bgcolor="gray"><font color="white">Point</font></th>
                            <th width="1%" bgcolor="gray"><font color="white">A</font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new Custs();
	$numrows = $c->getCustsRows($cn, $ft);
	$recs = $c->getCustsList($cn, $ft, $rv, $page, $limit);
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
                            <td align="right"><?= $recs[$i]["cust_points"] ?></td>
                            <td><?= ($recs[$i]["cust_active"] != "f")?"X":"&nbsp;" ?></td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
						</font>
						</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
