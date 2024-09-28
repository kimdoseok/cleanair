<?php
	include_once("class/class.formutils.php");
	$f = new FormUtil();

	$vars = array("ft","rv","pg","cn","ln","code");
	foreach ($vars as $var) {
		if (!isset($$var)) $$var = "";
	} 
	$pend_qty = 0;

	$selbox = array(0=>array("value"=>"code", "name"=>"Sales Number"),
					1=>array("value"=>"cust", "name"=>"Customer Code"),
					2=>array("value"=>"date", "name"=>"Sales Date"),
					3=>array("value"=>"tel", "name"=>"Telephone")
			  );

	if ($cmd=="filter") {
		$_SESSION["sales_filter"] = array();	
		$_SESSION["sales_filter"]["ft"] = $ft;
		$_SESSION["sales_filter"]["rv"] = $rv;
		$_SESSION["sales_filter"]["pg"] = $pg;
		$_SESSION["sales_filter"]["cn"] = $cn;	
	} else {
		if (array_key_exists("sales_filter", $_SESSION) && isset($_SESSION["sales_filter"])) {
			if (empty($ft)) $ft = $_SESSION["sales_filter"]["ft"];
			if (empty($rv)) $rv = $_SESSION["sales_filter"]["rv"];
			if (empty($pg)) $pg = $_SESSION["sales_filter"]["pg"];
			if (empty($cn)) $cn = $_SESSION["sales_filter"]["cn"];	
		} else {
			$_SESSION["sales_filter"] = array("ft"=>"","rv"=>"","pg"=>"","cn"=>"");
		}
	}

	if (empty($pg)) $pg = 1
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
                      <td colspan="8"><strong><?= $label[$lang]["List_Sales"] ?></strong></td>
                    </tr>
	  <form name="form1" method="get" action="">
                    <tr>
    <td colspan="8" align="left">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> 
            | <a href="javascript:openBatchBrowse('ft')">Batch</a> 
             | </font></td>
            <td align="right">
				&nbsp;
            </td>
        </tr>
      </table></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="right"><font size="2">
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
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Sale_no"] ?></font></th>
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
	$limit = 100;
	if ($cn == "date") {
		$old_ft = $ft;
		$ft = $d->toIsoDate($ft);
	}
	$numrows = $c->getSalesRows($cn, $ft);
//	$recs = $c->getSalesListEx($cn, $ft, $rv, $pg, $limit);
	$recs = $c->getSalesList($cn, $ft, $rv, $pg, $limit);
	if ($cn == "date") $ft = $old_ft;

	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

	$linenum = 1;
	$pd = new PickDtls();
	$sd = new SaleDtls();
	$pn = new Pends();
	
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($ln == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
		$arr = $v->getCusts($recs[$i]["sale_cust_code"]);
		$line_total = $recs[$i]["sale_freight_amt"]+$recs[$i]["sale_tax_amt"]+$recs[$i]["sale_amt"];

		$pick_qty = $pd->getPickDtlsSlsHdrSum($recs[$i]["sale_id"]);
		$sale_qty = $sd->getSaleDtlsHdrSum($recs[$i]["sale_id"]);

		$sd_arr = $sd->getSaleDtlsListPicksEx($recs[$i]["sale_id"]);
		if ($sd_arr) {
			$num = count($sd_arr)-1;
			$delivery_date = $sd_arr[$num]["pick_date"];
		} else {
			$delivery_date = "<font color='green'>".$recs[$i]["sale_date"]."</font>";
		}
	    $pend_ids = $pn->getPendsIds($recs[$i]["sale_id"]);

//echo "$pick_qty / $sale_qty <br>";
		if ($pick_qty > $sale_qty || ($sale_qty == 0 && $pend_qty==0 && !$pend_ids)) $status = "<font color='red'>E</font>";
		else if ($pick_qty == $sale_qty && $pick_qty>0) $status = "F";
		else if ($pick_qty < $sale_qty && $pick_qty>0) $status = "P";
		else if (($pend_qty>0 && $sale_qty==0) || $pend_ids) $status = "B";
		else $status = "N";
?>
                            <td align="center" width="10%"> 
<?php
	if ($status == "F") {
?>
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&sale_id=".$recs[$i]["sale_id"] ?>"><?= $recs[$i]["sale_id"] ?></a>
<?php
	} else {
?>
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&sale_id=".$recs[$i]["sale_id"] ?>"><?= $recs[$i]["sale_id"] ?></a>
<?php
	}
?>
                            </td>
                            <td align="left" width="10%"> 
                              <?= $recs[$i]["sale_cust_code"] ?>
                            </td>
                            <td align="left" width="30%"> 
                              <?= $arr["cust_name"] ?>
<?php
	$tk_arr = $tk->getTicketsTwo($recs[$i]["sale_cust_code"],$recs[$i]["sale_id"]);
	if ($tk_arr) {
		echo "<font size='2'>(";
		for ($j=0;$j<count($tk_arr);$j++) {
			$tkt_id=$tk_arr[$j]["tkt_id"];
			if ($j>0) echo ",";
			echo "<A class='tktid' HREF='cust_tickets.php?ty=v&tkt_id=$tkt_id'>$tkt_id</A>";
		}
		echo ")</font>";
	}
?>
							</td>
                            <td align="center" width="10%"> 
                              <?= $delivery_date ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["sale_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["sale_tax_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["sale_freight_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= number_format($line_total, 2, ".", ",") ?>
                            </td>
                            <td  width="2%" align="center"> 
<?php
	if ($status == "N" || $status == "P") {
		if ($sd->getSaleDtlBoQty($recs[$i]["sale_id"])>0) {
?>
							  <a href="javascript:window.alert('Back order quantity should be cleared first!\nPlease may create pending sales.');"><?= $status ?></a>
<?php
		} else {
?>
							  <a href="ar_proc.php?cmd=sale_to_pick_add&pg=<?= $pg ?>&sale_id=<?= $recs[$i]["sale_id"] ?>"><?= $status ?></a>
<?php
		}
} else  {
								echo $status;
}
?>
                            </td>
                            <td  width="2%" align="center"> 
							  <a href="ar_proc.php?cmd=sale_print&ty=l&pg=<?= $pg ?>&sale_id=<?= $recs[$i]["sale_id"] ?>"><?= $recs[$i]["sale_print"] ?></a>
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
			$d = new SaleDtls();
			$recd = $d->getSaleDtlsList($recs[$i]["sale_id"]);
			for ($j=0;$j<count($recd);$j++) {
				if (!empty($recd[$j])) {
					$pickdtl_qty = $pd->getPickDtlsSlsSum($recd[$j]["slsdtl_id"]);
					$up_qty = $recd[$j]["slsdtl_qty"]-$recd[$j]["slsdtl_qty_cancel"]-$pickdtl_qty;
?>
						  </tr>
                            <td width="10%" bgcolor="white"> 
                              <?= $recd[$j]["slsdtl_item_code"] ?>
                            </td>
                            <td width="60%" bgcolor="white"> 
                              <?= $recd[$j]["slsdtl_item_desc"] ?>
                            </td>
                            <td width="20%" align="right" bgcolor="white"> 
							  (<?= $pickdtl_qty+0 ?>/<?= $recd[$j]["slsdtl_qty"]+0 ?>,<?= $up_qty+0 ?>)x<?= sprintf("%0.2f", $recd[$j]["slsdtl_cost"]) ?>
                            </td>
                            <td width="10%" align="right" bgcolor="white"> 
                              <?= sprintf("%0.2f", $recd[$j]["slsdtl_cost"]*$recd[$j]["slsdtl_qty"]) ?>
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
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&cn=$cn&ft=$ft&ln=$ln" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&cn=$cn&ft=$ft&ln=$ln" ?>">&lt;Prev</a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&cn=$cn&ft=$ft&ln=$ln" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&cn=$cn&ft=$ft&ln=$ln" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
