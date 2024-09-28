<?php
//htmlentities($_SERVER['PHP_SELF'])htmlentities($_SERVER['PHP_SELF'])htmlentities($_SERVER['PHP_SELF'])htmlentities($_SERVER['PHP_SELF'])htmlentities($_SERVER['PHP_SELF'])htmlentities($_SERVER['PHP_SELF'])htmlentities($_SERVER['PHP_SELF'])
	$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
					1=>array("value"=>"desc", "name"=>"Description"),
					2=>array("value"=>"cat", "name"=>"Category"),
					3=>array("value"=>"catdesc", "name"=>"Cate. Desc."),
					4=>array("value"=>"vendor", "name"=>"Vendors")
					);
	$limit = 100;
	$f = new FormUtil();
	$c = new Items();
	$numrows = $c->getItemsRows($ft);
	$recs = $c->getItemsList($cn, $ft, $rv, $pg, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function setFilter() {
		var f = document.forms[0];
		f.method = "GET" ;
		f.pg.value = 1;
		f.action = "<?= $_SERVER['PHP_SELF'] ?>";
		f.submit();
	}

	var prcBrowse;
	function adjPrice(code) {
		var f = document.forms[0];
		if (prcBrowse && !prcBrowse.closed) prcBrowse.close();
		prcBrowse = window.open("item_price_popup.php?cn=<?= $cn ?>&ft=<?= $ft ?>&rv=<?= $rv ?>&pg=<?= $pg ?>&item_code="+code, "prcBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=130,width=300");
		prcBrowse.focus();
		prcBrowse.moveTo(100,100);
	}

//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Item</strong></td>
                    </tr>
						  <form name="form1">
                    <tr>
                      <td colspan="4" align="left"><font size="2"> | <a href="<?php echo $_SERVER['PHP_SELF']."?ty=a" ?>">New</a> | </font></td>
                      <td colspan="4" align="right"><font size="2">
								<?= $f->fillTextBox("ft",stripslashes($ft),30) ?>
								<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
								<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> >Reverse
								<input type="button" name="filter" value="Filter" onClick="javascript:setFilter()">
								<?= $f->fillHidden("pg",$pg) ?>
							 </td>
                    </tr>
						  </form>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="150"><font color="white">Item #</font></th>
                            <th bgcolor="gray"><font color="white">Description</font></th>
                            <th bgcolor="gray" width="60"><font color="white">MSRP</font></th>
                            <th bgcolor="gray" width="60"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="5"><font color="white">V.Code</font></th>
                            <th bgcolor="gray" width="5"><font color="white">Unit</font></th>
                            <th width="60" bgcolor="gray"><font color="white">OnHnd</font></th>
                            <th bgcolor="gray"><font color="white">A</font></th>
                          </tr>
<?php
	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
		if (empty($recs[$i]["item_vend_prod_code"])) $recs[$i]["item_vend_prod_code"] = "";
?>
                            <td align="left"> 
                              <a href="<?= $_SERVER['PHP_SELF']."?ty=v&item_code=".$recs[$i]["item_code"] ?>"><?= $recs[$i]["item_code"] ?></a>
                            </td>
                            <td> 
                              <?= $recs[$i]["item_desc"] ?>
                            </td>
                            <td align="right"> 
							  <A HREF="javascript:adjPrice('<?= $recs[$i]["item_code"] ?>')">
                              <?= sprintf("%0.2f", $recs[$i]["item_msrp"]) ?>
							  </A>
                            </td>
                            <td align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["item_ave_cost"]) ?>
                            </td>
                            <td align="left"> 
                              <?= strtoupper($recs[$i]["item_vend_prod_code"]) ?>
                            </td>
                            <td align="right"> 
                              <?= strtoupper($recs[$i]["item_unit"]) ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i]["item_qty_onhnd"]+0 ?>
                            </td>
                            <td width="1%"><?= ($recs[$i]["item_active"] != "f")?"X":"&nbsp;" ?></td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red">No Data !</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= $_SERVER['PHP_SELF']."?cn=$cn&ft=$ft&pg=1" ?>">&lt;&lt;First</a>

                              &nbsp; <a href="<?= $_SERVER['PHP_SELF']."?cn=$cn&ft=$ft&pg=$prevpage" ?>">&lt;Prev</a> &nbsp; 

										<font color="gray"><?= "[$pg / $totalpage]" ?></font>

                              &nbsp; <a href="<?= $_SERVER['PHP_SELF']."?cn=$cn&ft=$ft&pg=$nextpage" ?>">Next&gt;</a>

                              &nbsp; <a href="<?= $_SERVER['PHP_SELF']."?cn=$cn&ft=$ft&pg=$totalpage" ?>">Last&gt;&gt;</a></td>

                          </tr>

                        </table></td>

                    </tr>

                  </table>
