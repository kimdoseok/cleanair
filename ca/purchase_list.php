<?php
	include_once("class/class.formutils.php");
	include_once("class/register_globals.php");

	$f = new FormUtil();
	$d = new DateX();

	$selbox = array(0=>array("value"=>"code", "name"=>"Purchase Number"),
					1=>array("value"=>"cust", "name"=>"Customer Code"),
					2=>array("value"=>"vend", "name"=>"Vendor Code"),
					3=>array("value"=>"date", "name"=>"Purchase Date"),
					4=>array("value"=>"comp", "name"=>"Complete Date"),
					5=>array("value"=>"tel", "name"=>"Telephone"),
					6=>array("value"=>"sale", "name"=>"Sales #")
			  );
	$vars = array("cn","ln");
	foreach ($vars as $var) {
		$$var = "";
	} 
		  
	if (!array_key_exists("purchase_filter", $_SESSION)) {
		$_SESSION["purchase_filter"]= array("ft"=>"","rv"=>"","pg"=>"");
	}
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
                      <td colspan="8"><strong>List Purchase</strong></td>
                    </tr>
	  <form name="form1" method="get" action="">
                    <tr>
    <td colspan="8" align="left">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
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
                            <th bgcolor="gray"><font color="white">PO#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Vendor</font></th>
                            <th bgcolor="gray"><font color="white">Date</font></th>
<!--
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["SubTotal"]?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Tax"] ?></font></th>
                            <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Freight"] ?></font></th>
-->
                            <th bgcolor="gray"><font color="white">Amount</font></th>
<!--
                            <th bgcolor="gray"><font color="white">Completed</font></th>
-->
                            <th bgcolor="gray"><font color="white">St</font></th>
                            <th bgcolor="gray"><font color="white">C</font></th>
                          </tr>
<?php
	$limit = 100;
	if ($cn == "date") {
		$old_ft = $ft;
		$ft = $d->toIsoDate($ft);
	}
	$numrows = $c->getPurchaseRows($cn, $ft);
//	$recs = $c->getPurchaseListEx($cn, $ft, $rv, $pg, $limit);
	$recs = $c->getPurchaseList($cn, $ft, $rv, $pg, $limit);
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
		//print_r($recs[$i]);

		if ($ln == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
		/*
		if (!array_key_exists("purch_vend_code", $recs[$i])) {
			$recs[$i]["purch_vend_code"]="";
		}
		*/
		//print_r($recs[$i]["purch_vend_code"]);
		$arr = $v->getVends($recs[$i]["purch_vend_code"]);
		$line_total = $recs[$i]["purch_freight_amt"]+$recs[$i]["purch_tax_amt"]+$recs[$i]["purch_amt"];

//		$purch_qty = $sd->getPurDtlsHdrSum($recs[$i]["purch_id"]);
//		if ($pick_qty > $purch_qty || $purch_qty == 0) $status = "E";
//		else if ($pick_qty == $purch_qty && $pick_qty>0) $status = "F";
//		else if ($pick_qty < $purch_qty && $pick_qty>0) $status = "P";
//		else $status = "N";
?>
                            <td align="center" width="10%"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&purch_id=".$recs[$i]["purch_id"] ?>"><?= $recs[$i]["purch_id"] ?></a>
                            </td>
                            <td align="left" width="10%"> 
                              <?= $recs[$i]["purch_vend_code"] ?>
                            </td>
                            <td align="left" width="50%"> 
                              <?= $arr["vend_name"] ?>
                            </td>
                            <td align="center" width="10%"> 
                              <?= $recs[$i]["purch_date"] ?>
                            </td>
<!--
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["purch_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["purch_tax_amt"]) ?>
                            </td>
                            <td  width="10%" align="right"> 
                              <?= sprintf("%0.2f", $recs[$i]["purch_freight_amt"]) ?>
                            </td>
-->
                            <td  width="10%" align="right"> 
                              <?= number_format($line_total, 2, ".", ",") ?>
                            </td>
<?php
/*
?>
                            <td align="center" width="10%"> 
                              <?= ($recs[$i]["purch_completed_date"]!="0000/00/00")?$d->UsaDate($recs[$i]["purch_completed_date"]):"" ?>
                            </td>
<?php
*/
?>                          <td  width="2%" align="center"> 
							  <?= strtoupper($recs[$i]["purch_status"]) ?>
                            </td>
                            <td  width="2%" align="center"> 
							  <?= ($recs[$i]["purch_completed"]=="t")?"X":"&nbsp;" ?>
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
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
