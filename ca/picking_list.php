<?php
	include_once("class/class.formutils.php");
	include_once("class/class.customers.php");
	$f = new FormUtil();

	if (!array_key_exists("picks_filter", $_SESSION)) {
		$_SESSION["picks_filter"]= array("ft"=>"","rv"=>"","pg"=>"");
	}

	if ($cmd=="filter") {
		$_SESSION["picks_filter"]["ft"] = $ft;
		$_SESSION["picks_filter"]["rv"] = $rv;
		$_SESSION["picks_filter"]["pg"] = $pg;
	} else {
		if (empty($ft)) $ft = $_SESSION["picks_filter"]["ft"];
		if (empty($rv)) $rv = $_SESSION["picks_filter"]["rv"];
		if (empty($pg)) $pg = $_SESSION["picks_filter"]["pg"];
	}

	if (empty($pg)) $pg = 1;

	$selbox = array(0=>array("value"=>"code", "name"=>"Picking Number"),
					1=>array("value"=>"cust", "name"=>"Customer Code"),
					2=>array("value"=>"date", "name"=>"Picked Date"),
					3=>array("value"=>"pdate", "name"=>"Promised Date"),
					4=>array("value"=>"ddate", "name"=>"Delivery Date"),
					5=>array("value"=>"tel", "name"=>"Telephone"),
					6=>array("value"=>"total", "name"=>"Total")
			  );

	$limit = 100;
	$c = new Picks();
	$v = new Custs();
	$in = new Invoices();

	if ($cn == "date" || $cn == "pdate" || $cn == "ddate") {
		$old_ft = $ft;
		$ft = $d->toIsoDate($ft);
	}
	$numrows = $c->getPicksRows($cn, $ft);
	$recs = $c->getPicksList($cn, $ft, $rv, $pg, $limit);
	if ($cn == "date" || $cn == "pdate" || $cn == "ddate") $ft = $old_ft;

?>
	<script language="JavaScript" type="text/JavaScript">
		function updateForm() {
			document.form1.pg.value = <?=$pg ?>;
			document.form1.ch.value = "t";
			document.form1.action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
			document.form1.submit();
		}

	function setFilter() {
		var f = document.forms[0];
		f.method = "POST" ;
		f.pg.value = 1;
		f.cmd.value = "filter";
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}

	var batchBrowse;
	function openBatchBrowse(objname)  {
		if (batchBrowse && !batchBrowse.closed) batchBrowse.close();
		batchBrowse = window.open("picking_batch_popup.php?objname="+objname, "batchBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=150,width=320");
		batchBrowse.focus();
		batchBrowse.moveTo(100,100);
	}
	</script>

                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List of Picking Ticket</strong></td>
                    </tr>
                    <tr>
    <td colspan="8" align="left">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
           | <a href="javascript:openBatchBrowse('ft')">Batch</a> 
             | </font></td>
            <td align="right">
				&nbsp;
            </td>
        </tr>
      </table></td>
                    </tr>
                    <tr>
					<form name="form1" method="get" action="">
                      <td colspan="8" align="right"><font size="2">
								<?= $f->fillTextBox("ft",$ft,30) ?>
								<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
								<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> ><?= $label[$lang]["Reverse"] ?>
								<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="javascript:setFilter()">
								<?= $f->fillHidden("pg",$pg) ?>
								<?= $f->fillHidden("cmd","") ?>
							 </td>
					</form>
					</tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white">#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Customer"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Date_1"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["SubTotal"]?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Tax"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Freight"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Total"] ?></font></th>
                            <th bgcolor="gray" width="2%"><font color="white">St</font></th>
                            <th bgcolor="gray" width="2%"><font color="white">Pr</font></th>
                          </tr>
<?php
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
		$arr = $v->getCusts($recs[$i]["pick_cust_code"]);
		$inv_arr = $in->getInvoicesPick($recs[$i]["pick_id"]);
		if ($inv_arr) {
			$status = "X";
			$st_code = "I";
		} else {
			$status = "&nbsp;";
			$st_code = "N";
		}
?>
                            <td align="center" width="10%"> 
<?php
	if ($st_code == "I") {
?>
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&pick_id=".$recs[$i]["pick_id"] ?>"><?= $recs[$i]["pick_id"] ?></a>
<?php
	} else {
?>
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&pick_id=".$recs[$i]["pick_id"] ?>"><?= $recs[$i]["pick_id"] ?></a>
<?php
	}
?>
                            </td>
                            <td align="center" width="10%"> 
                              <?= $recs[$i]["pick_cust_code"] ?>
                            </td>
                            <td align="left" width="30%"> 
                              <?= $arr["cust_name"] ?>
                            </td>
                            <td align="center" width="10%"> 
                              <?= $recs[$i]["pick_date"] ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["pick_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["pick_tax_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["pick_freight_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["pick_freight_amt"]+$recs[$i]["pick_tax_amt"]+$recs[$i]["pick_amt"]) ?>
                            </td>
							<td  width="2%" align="center"> 
                              <?= $status ?>
                            </td>
							<td  width="2%" align="center"> 
							  <a href="ar_proc.php?cmd=pick_print&ty=l&pg=<?= $pg ?>&pick_id=<?= $recs[$i]["pick_id"] ?>"><?= $recs[$i]["pick_print"] ?></a>
                            </td>
                          </tr>

<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="9" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&sc=$sc" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&sc=$sc" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&sc=$sc" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&sc=$sc" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
