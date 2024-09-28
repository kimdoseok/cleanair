<?php

	$selbox = array(0=>array("value"=>"code", "name"=>"Purchase Number"),
					1=>array("value"=>"item", "name"=>"Customer Code"),
					2=>array("value"=>"vend", "name"=>"Vendor Code"),
					3=>array("value"=>"date", "name"=>"Purchase Date"),
					4=>array("value"=>"comp", "name"=>"Complete Date"),
					5=>array("value"=>"tel", "name"=>"Telephone"),
					6=>array("value"=>"sale", "name"=>"Sales #")
			  );

	if ($cmd=="filter") {
		$_SESSION["purchase_filter"]["ft"] = $ft;
		$_SESSION["purchase_filter"]["rv"] = $rv;
		$_SESSION["purchase_filter"]["pg"] = $pg;
	} else {
		if (empty($ft)) $ft = $_SESSION["purchase_filter"]["ft"];
		if (empty($rv)) $rv = $_SESSION["purchase_filter"]["rv"];
		if (empty($pg)) $pg = $_SESSION["purchase_filter"]["pg"];
	}

	if (empty($pg)) $pg = 1;

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function updateForm() {
		document.form1.pg.value = <?=$pg ?>;
		document.form1.ch.value = "t";
		document.form1.action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
		document.form1.submit();
	}

	function setFilter() {
		var f = document.forms[0];
		f.method = "GET" ;
		f.pg.value = 1;
		f.cmd.value = 'filter';
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}

	var batchBrowse;
	function openBatchBrowse(objname)  {
		if (batchBrowse && !batchBrowse.closed) batchBrowse.close();
		batchBrowse = window.open("sales_batch_popup.php?objname="+objname, "batchBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=150,width=320");
		batchBrowse.focus();
		batchBrowse.moveTo(100,100);
	}

//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td><strong>Open PO Receipt List</strong></td>
                    </tr>
	  <form name="form1" method="get" action="">
                    <tr>
    <td align="left">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
            | </font></td>
            <td align="right">
				&nbsp;
            </td>
        </tr>
      </table></td>
                    </tr>
                    <tr>
                      <td align="right"><font size="2">
								<?= $f->fillTextBox("ft",$ft,30) ?>
								<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
								<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> >Reverse
								<input type="checkbox" name="ln" value="t" <?= ($ln=="t")?"checked":"" ?> >Lines
								<input type="button" name="filter" value="Filter" onClick="javascript:setFilter()">
								<?= $f->fillHidden("pg",$pg) ?>
								<?= $f->fillHidden("cmd","") ?>
							 </td>
                    </tr>
		</form>
                    <tr align="right"> 
                      <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="4%"><font color="white">#</font></th>
                            <th bgcolor="gray" width="15%"><font color="white">Item#</font></th>
                            <th bgcolor="gray" width="15%"><font color="white">Vendor#</font></th>
                            <th bgcolor="gray" width="30%"><font color="white">Vendor</font></th>
                            <th bgcolor="gray" width="9%"><font color="white">Ord.</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Rcvd.</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Cncl.</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">ToBe.</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Count</font></th>
                          </tr>
<?php
	$limit = 100;
	if ($cn == "date") {
		$old_ft = $ft;
		$ft = $d->toIsoDate($ft);
	}
	$recs = $c->getPurDtlOpenList($cn, $ft, $rv, $pg, $limit);
	if ($cn == "date") $ft = $old_ft;

	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

	$linenum = 1;
	$sd = new PurDtls();
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($ln == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
		$tobe_qty = $recs[$i][pur_qty]-$recs[$i][rcv_qty]-$recs[$i][csl_qty]
?>
                            <td align="center"> 
                              <?= $i+1 ?>
                            </td>
                            <td align="left"> 
                              <?= $recs[$i]["purdtl_item_code"] ?>
                            </td>
                            <td align="left"> 
                              <?= $recs[$i]["purch_vend_code"] ?>
                            </td>
                            <td align="left"> 
                              <?= $recs[$i]["purch_vend_name"] ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][pur_qty]+0 ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][rcv_qty]+0 ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][csl_qty]+0 ?>
                            </td>
                            <td align="right"> 
                              <?= $tobe_qty ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][cnt] ?>
                            </td>
                          </tr>
<?php
		if ($ln == "t") {
?>
						  <tr>
						    <td>&nbsp;</td>
						    <td colspan="7">
							 <table width="100%" bgcolor="#EEEEEE" border="0" cellspacing="1" cellpadding="0">
<?php
			$recd = $sd->getPurDtlsList($recs[$i]["purch_id"]);
			if ($recd) $recnum = count($recd);
			else $recnum = 0;
			for ($j=0;$j<$recnum;$j++) {
				if (!empty($recd[$j])) {
?>
						  </tr>
                            <td width="10%" bgcolor="white"> 
                              <?= $recd[$j]["purdtl_item_code"] ?>
                            </td>
                            <td width="60%" bgcolor="white"> 
                              <?= $recd[$j]["purdtl_item_desc"] ?>
                            </td>
                            <td width="20%" align="right" bgcolor="white"> 
							  <?= $recd[$j]["purdtl_qty"]+0 ?>x<?= sprintf("%0.2f", $recd[$j]["purdtl_cost"]) ?>
                            </td>
                            <td width="10%" align="right" bgcolor="white"> 
                              <?= sprintf("%0.2f", $recd[$j]["purdtl_cost"]*$recd[$j]["purdtl_qty"]) ?>
                            </td>
                          </tr>
<?php
				}
			}
?>
							 </table>
						    </td>
						    <td colspan="2">
						  </tr>
<?php
		}
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="9" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
					    </table>
					  </td>
					</tr>
					<tr align="right"> 
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&cn=$cn&ft=$ft" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&cn=$cn&ft=$ft" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&cn=$cn&ft=$ft" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&cn=$cn&ft=$ft" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
