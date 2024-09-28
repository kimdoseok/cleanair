<?php
	include_once("class/class.vendors.php");

	$selbox = array(0=>array("value"=>"dat", "name"=>"Date"),
					1=>array("value"=>"itm", "name"=>"Component"),
					2=>array("value"=>"doc", "name"=>"Doc no")
					);
	$selbox1 = array(
				0=>array("value"=>"all", "name"=>"All"),
				1=>array("value"=>"rec", "name"=>"Receiving"),
				2=>array("value"=>"adj", "name"=>"Adjustment"),
				3=>array("value"=>"sal", "name"=>"Sales")
				); 
?>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>Item Transaction</strong></td>
                    </tr>
                    <tr>
                      <td align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> | </font></td>
                    </tr>
					<form>
					
					<tr>
					  <td align="right">
					    <?= $f->fillHidden("page",$page) ?>
						<?= $f->fillHidden("ty","l") ?>
						<?= $f->fillTextBox("filtertext",$filtertext,33, 33) ?>
						<?= $f->fillSelectBox($selbox,"condition", "value", "name", $condition) ?>
						<?= $f->fillSelectBox($selbox1,"condition1", "value", "name", $condition1) ?>
						<input type="checkbox" name="reverse" value="t" <?= (!empty($reverse) && $reverse == "t")? "checked" : "" ?>>
						<?= $label[$lang]["Reverse"] ?>
						<input type="button" name="filter" value="<?= $label[$lang]["Filter"]?>" onClick="javascript:updateForm(1)">
					  </td>
					</tr>
					</form>
					<tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white">#</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Type</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Ref#</font></th>
                            <th bgcolor="gray" width="30%"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Date</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Amount</font></th>
                          </tr>
<?php
	$limit = 100;
	$c = new ItemTrxs();
	$numrows = $c->getItemTrxsRows();
	$recs = $c->getItemTrxsList($condition, $condition1, $filtertext, $reverse, $page, $limit);
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
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&invtrx_id=".$recs[$i][invtrx_id] ?>"><?= $recs[$i][invtrx_id] ?></a>
                            </td>
                            <td align="left"> 
							<?php
								  if ($recs[$i][invtrx_type]=="s") echo "Sales";
								  else if ($recs[$i][invtrx_type]=="r") echo "Receiving";
								  else if ($recs[$i][invtrx_type]=="a") echo "Adjust";
							  ?>
                            </td>
                            <td align="center"> 
                              <?= $recs[$i][invtrx_ref_code] ?>
                            </td>
                            <td align="left"> 
                              <?= $recs[$i][invtrx_item_code] ?>
                            </td>
                            <td align="center"> 
                              <?= $recs[$i][invtrx_date] ?>
                            </td>
                            <td align="right"> 
                              <?= number_format($recs[$i][invtrx_cost],2,".",",") ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][invtrx_qty]+0 ?>
                            </td>
                            <td align="right"> 
                              <?= number_format($recs[$i][invtrx_cost]*$recs[$i][invtrx_qty],2,".",",") ?>
                            </td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td align="center" colspan="8"><font color="red">No Data!</font></td></tr>
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
