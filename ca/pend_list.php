<?php
	$f = new FormUtil();

	$selbox = array(0=>array("value"=>"code", "name"=>"pending #"),
					1=>array("value"=>"cust", "name"=>"Customer Code"),
					2=>array("value"=>"date", "name"=>"Sales Date"),
					3=>array("value"=>"tel", "name"=>"Telephone")
			  );

	if ($cmd=="filter") {
		$_SESSION[pends_filter]["ft"] = $ft;
		$_SESSION[pends_filter]["rv"] = $rv;
		$_SESSION[pends_filter]["pg"] = $pg;
		$_SESSION[pends_filter]["cn"] = $cn;
	} else {
		if (empty($ft)) $ft = $_SESSION[pends_filter]["ft"];
		if (empty($rv)) $rv = $_SESSION[pends_filter]["rv"];
		if (empty($pg)) $pg = $_SESSION[pends_filter]["pg"];
		if (empty($cn)) $cn = $_SESSION[pends_filter]["cn"];
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
		batchBrowse = window.open("pends_batch_popup.php?objname="+objname, "batchBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=150,width=320");
		batchBrowse.focus();
		batchBrowse.moveTo(100,100);
	}

//-->
</SCRIPT>

<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td colspan="8"><strong>Pending List</strong></td>
  </tr>
  <form name="form1" method="get" action="">
  <tr>
    <td colspan="8" align="right"><font size="2">
      <?= $f->fillTextBox("ft",$ft,30) ?>
      <?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
      <input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> >Reverse
      <input type="checkbox" name="ln" value="t" <?= ($ln=="t")?"checked":"" ?> >Lines
      <input type="checkbox" name="sa" value="t" <?= ($sa=="t")?"checked":"" ?> >Show All
      <input type="button" name="filter" value="Filter" onClick="javascript:setFilter()">
      <?= $f->fillHidden("pg",$pg) ?>
      <?= $f->fillHidden("cmd","") ?>
    </td>
  </tr>
  </form>
  <tr align="right"> 
    <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr> 
        <th bgcolor="gray" width="10%"><font color="white">Pending #</font></th>
        <th colspan="2" bgcolor="gray"><font color="white">Customer</font></th>
        <th bgcolor="gray" width="10%"><font color="white">Date</font></th>
        <th bgcolor="gray" width="10%"><font color="white">SubTotal</font></th>
        <th bgcolor="gray" width="10%"><font color="white">Tax</font></th>
        <th bgcolor="gray" width="10%"><font color="white">Freight</font></th>
        <th bgcolor="gray" width="10%"><font color="white">Total</font></th>
        <th bgcolor="gray" width="2%"><font color="white">St</font></th>
      </tr>
<?php
	$limit = 100;
	if ($cn == "date") {
		$old_ft = $ft;
		$ft = $d->toIsoDate($ft);
	}
	$numrows = $p->getPendsRows($cn, $ft, $sa);
	$recs = $p->getPendsList($cn, $ft, $rv, $pg, $limit, $sa);
	if ($cn == "date") $ft = $old_ft;

	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

	$linenum = 1;
	$pd = new PenDtls();
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($ln == "t") {
			echo "<tr bgcolor=\"#EEEEEE\">";
		} else {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
		}
		$arr = $c->getCusts($recs[$i][pend_cust_code]);
		$line_total = $recs[$i][pend_freight_amt]+$recs[$i][pend_tax_amt]+$recs[$i][pend_amt];

//echo "$pick_qty / $pend_qty <br>";
?>
        <td align="center" width="10%"> 
          <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&pend_id=".$recs[$i][pend_id] ?>"><?= $recs[$i][pend_id] ?></a>
        </td>
        <td align="left" width="10%"> 
          <?= $recs[$i][pend_cust_code] ?>
        </td>
        <td align="left" width="30%"> 
          <?= $arr["cust_name"] ?>
        </td>
        <td align="center" width="10%"> 
          <?= $recs[$i][pend_date] ?>
        </td>
        <td  width="10%" align="right"> 
          <?= sprintf("%0.2f", $recs[$i][pend_amt]) ?>
        </td>
        <td  width="10%" align="right"> 
          <?= sprintf("%0.2f", $recs[$i][pend_tax_amt]) ?>
        </td>
        <td  width="10%" align="right"> 
          <?= sprintf("%0.2f", $recs[$i][pend_freight_amt]) ?>
        </td>
        <td  width="10%" align="right"> 
          <?= number_format($line_total, 2, ".", ",") ?>
        </td>
        <td  width="2%" align="center"> 
<?php
	if ($recs[$i][pend_status] == 0) echo "X";
	else echo "&nbsp;";
?>
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
			$recd = $pd->getPenDtlsList($recs[$i][pend_id]);
			for ($j=0;$j<count($recd);$j++) {
				if (!empty($recd[$j])) {
?>
            <tr>
              <td width="10%" bgcolor="white"> 
                <?= $recd[$j][pendtl_item_code] ?>
              </td>
              <td width="60%" bgcolor="white"> 
                <?= $recd[$j][pendtl_item_desc] ?>
              </td>
              <td width="20%" align="right" bgcolor="white"> <?= $recd[$j][pendtl_qty]+0 ?>x<?= sprintf("%0.2f", $recd[$j][pendtl_cost]) ?>
              </td>
              <td width="10%" align="right" bgcolor="white"> 
                <?= sprintf("%0.2f", $recd[$j][pendtl_cost]*$recd[$j][pendtl_qty]) ?>
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
		<tr><td colspan="9" align="center"><font color="red">No Data!</font></td></tr>
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
