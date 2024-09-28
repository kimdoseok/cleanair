<?php
	include_once("class/register_globals.php");
	
	$selbox = array(0=>array("value"=>"code", "name"=>$label[$lang][Code]),
						1=>array("value"=>"desc", "name"=>$label[$lang]["Description"])
					);

	$limit = 20;
	$f = new FormUtil();
	$c = new Items();
	$numrows = $c->getItemsRows();
	$recs = $c->getItemsList($cn, $ft, $rv, $pg, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?= $label[$lang][Item_Popup_List] ?></TITLE>
</HEAD>
<BODY BGCOLOR="#FFFFFF">

<SCRIPT LANGUAGE="JavaScript">
<!--
	function setFilter() {
		var f = document.forms[0];
		f.method = "GET" ;
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang]["List_Item"] ?></strong></td>
                    </tr>
						  <form name="form1">
                    <tr>
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> | </font></td>
                      <td colspan="4" align="right"><font size="2">
								<?= $f->fillTextBox("ft",$ft,30) ?>
								<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
								<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> ><?= $label[$lang]["Reverse"] ?>
								<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="javascript:setFilter()">
								<?= $f->fillHidden("pg",$pg) ?>
							 </td>
                    </tr>
						  </form>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="100"><font color="white"><?= $label[$lang]["Item_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
                            <th bgcolor="gray" width="80"><font color="white"><?= $label[$lang]["Cost"] ?></font></th>
                            <th width="80" bgcolor="gray"><font color="white"><?= $label[$lang][On_Hand] ?></font></th>
                          </tr>
<?php

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td align="left"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&item_code=".$recs[$i]["item_code"] ?>"><?= $recs[$i]["item_code"] ?></a>
                            </td>
                            <td> 
                              <a href="itemtrxs.php?ty=a&invtrx_item_code=<?= $recs[$i]["item_code"] ?>"><?= $recs[$i]["item_desc"] ?></a>
                            </td>
                            <td align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["item_ave_cost"]) ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i]["item_qty_onhnd"]+0 ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=1" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=$prevpage" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=$nextpage" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=$totalpage" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
</BODY>
</HTML>
