<?php
	include_once("class/class.formutils.php");
	$f = new FormUtil();

	$selbox = array(0=>array("value"=>"code", "name"=>"Sales Number"),
					1=>array("value"=>"cust", "name"=>"Customer Code"),
					2=>array("value"=>"date", "name"=>"Sales Date"),
					3=>array("value"=>"tel", "name"=>"Telephone")
			  );

	if ($cmd=="filter") {
		$_SESSION[itm_build_filter]["ft"] = $ft;
		$_SESSION[itm_build_filter]["rv"] = $rv;
		$_SESSION[itm_build_filter]["pg"] = $pg;
	} else {
		if (empty($ft)) $ft = $_SESSION[itm_build_filter]["ft"];
		if (empty($rv)) $rv = $_SESSION[itm_build_filter]["rv"];
		if (empty($pg)) $pg = $_SESSION[itm_build_filter]["pg"];
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

//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="1"><strong>List Item Builds</strong></td>
                    </tr>
	  <form name="form1" method="get" action="">
                    <tr>
    <td colspan="1" align="left">
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
                      <td colspan="1" align="right"><font size="2">
								<?= $f->fillTextBox("ft",$ft,30) ?>
								<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
								<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> ><?= $label[$lang]["Reverse"] ?>
								<input type="checkbox" name="ln" value="t" <?= ($ln=="t")?"checked":"" ?> >Lines
								<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="javascript:setFilter()">
								<?= $f->fillHidden("pg",$pg) ?>
								<?= $f->fillHidden("cmd","") ?>
							 </td>
                    </tr>
		</form>
                    <tr align="right"> 
                      <td colspan="1"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white">#</font></th>
                            <th colspan="2" bgcolor="gray"><font color="white">Name</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Desc</font></th>
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
	$numrows = $c->getItemBuildsRows($cn, $ft);
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
	$sd = new ItmBuilDtls();
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
//echo "$pick_qty / $sale_qty <br>";
		if ($pick_qty > $sale_qty || $sale_qty == 0) $status = "E";
		else if ($pick_qty == $sale_qty && $pick_qty>0) $status = "F";
		else if ($pick_qty < $sale_qty && $pick_qty>0) $status = "P";
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
                            </td>
                            <td align="center" width="10%"> 
                              <?= $recs[$i]["sale_date"] ?>
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
?>
							  <a href="ibm_build_proc.php?cmd=sale_to_pick_add&pg=<?= $pg ?>&sale_id=<?= $recs[$i]["sale_id"] ?>"><?= $status ?></a>
<?php
} else  {
								echo $status;
}
?>
                            </td>
                            <td  width="2%" align="center"> 
							  <a href="ibm_build_proc.php?cmd=sale_print&ty=l&pg=<?= $pg ?>&sale_id=<?= $recs[$i]["sale_id"] ?>"><?= $recs[$i]["sale_print"] ?></a>
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
			$d = new ItmBuilDtls();
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
                      <td colspan="1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&cn=$cn&ft=$ft&ln=$ln" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&cn=$cn&ft=$ft&ln=$ln" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&cn=$cn&ft=$ft&ln=$ln" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&cn=$cn&ft=$ft&ln=$ln" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
