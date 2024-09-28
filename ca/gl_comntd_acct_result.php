<?php
	include_once("class/class.formutils.php");
	include_once("class/class.gledger.php");
	include_once("class/class.jrnltrxs.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.accounts.php");
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="center"> 
    <td colspan="5" width="100%">
	  <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="black">
		<tr align="center"> 
			<td width="25%" bgcolor="gray" align="center" colspan="1"> 
				Beg. Date
			</td>
			<td width="25%" bgcolor="gray" align="center" colspan="1"> 
				End. Date
			</td>
			<td width="25%" bgcolor="gray" align="center" colspan="1"> 
				Beg. Acct
			</td>
			<td width="25%" bgcolor="gray" align="center" colspan="1"> 
				End. Acct
			</td>
		</tr> 
	  <tr align="center"> 
		<td bgcolor="white" align="center" colspan="1"> 
			<?= $start_date ?>&nbsp;
		</td>
		<td bgcolor="white" align="center" colspan="1"> 
			<?= $end_date ?>&nbsp;
		</td>
		<td bgcolor="white" align="center" colspan="1"> 
			<?= $start_acct ?>&nbsp;
		</td>
		<td bgcolor="white" align="center" colspan="1"> 
			<?= $end_acct ?>&nbsp;
		</td>
	  </tr>
	  </table>
    </td>
  </tr>
  <tr align="center"> 
    <th bgcolor="gray" width="10%"><font color="white">
            <?= $label[en]["No"] ?>
            </font></th>
    <th bgcolor="gray" width="10%"><font color="white">
            <?= $label[$lang]["Acct_no"] ?>
            </font></th>
    <th bgcolor="gray" width="13%"><font color="white">
            <?= $label[$lang]["Date_1"] ?>
            </font></th>
    <th bgcolor="gray" width="15%"><font color="white">
            <?= $label[$lang]["Amount"] ?>
            </font></th>
    <th bgcolor="gray" width="60%"><font color="white">
            <?= $label[$lang]["Comment"] ?>
            </font></th>
  </tr>
<?php
	$limit = 100;
	$c = new GLedger();
	$d = new Datex();
	$ft_arr = array();
	$ft = array();

	$ft[l] = "gldgr_date";
	$ft[r] = $d->toIsoDate($start_date);
	$ft[o] = "ge";
	array_push($ft_arr, $ft);
	$ft[l] = "gldgr_date";
	$ft[r] = $d->toIsoDate($end_date);
	$ft[o] = "le";
	array_push($ft_arr, $ft);
	$ft[l] = "jrnltrx_acct_code";
	$ft[r] = $start_acct;
	$ft[o] = "ge";
	array_push($ft_arr, $ft);
	$ft[l] = "jrnltrx_acct_code ";
	$ft[r] = $end_acct;
	$ft[o] = "le";
	array_push($ft_arr, $ft);

	$numrows = $c->getGLedgerRowsEx($condition, $filtertext, $ft_arr);
	$recs = $c->getGLedgerListEx($condition, $filtertext, $ft_arr, $reverse, $page, $limit);

	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	if (empty($page)) $page = 1;
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
          <?= $recs[$i][gldgr_id] ?>
        </td>
        <td align="center"> 
          <?= $recs[$i]["jrnltrx_acct_code"] ?>
        </td>
        <td align="center"> 
          <?= $recs[$i][gldgr_date] ?>
        </td>
        <td align="right"> 
          <?= number_format($recs[$i][gldgr_amt], 2, '.', ',') ?>
        </td>
        <td align="left"> 
          <?= $recs[$i][gldgr_cmnt] ?>
        </td>
        </tr>
<?php
	}
	if ($numrows == 0) {
?>
  <tr>
          <td colspan="5" align="center"><font color="red">
            <?= $label[$lang]["No_Data"] ?>
            !</font></td>
  </tr>
<?php
	}
?>
  <tr align="right"> 
          <td colspan="5" align="center">
		    <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?result=t&report_page=gl_comntd_acct_rpt.php&start_date=$start_date&end_date=$end_date&start_acct=$start_acct&end_acct=$end_acct&page=1" ?>">&lt;&lt;
            <?= $label[$lang]["First"] ?>
            </a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?result=t&report_page=gl_comntd_acct_rpt.php&start_date=$start_date&end_date=$end_date&start_acct=$start_acct&end_acct=$end_acct&page=$prevpage" ?>">&lt;
            <?= $label[$lang]["Prev_1"] ?>
            </a> &nbsp; <font color="gray">
            <?= "[$page / $totalpage]" ?>
            </font> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?result=t&report_page=gl_comntd_acct_rpt.php&start_date=$start_date&end_date=$end_date&start_acct=$start_acct&end_acct=$end_acct&page=$nextpage" ?>">
            <?= $label[$lang]["Next_1"] ?>
            &gt;</a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?result=t&report_page=gl_comntd_acct_rpt.php&start_date=$start_date&end_date=$end_date&start_acct=$start_acct&end_acct=$end_acct&page=$totalpage" ?>">
            <?= $label[$lang]["Last"] ?>
            t&gt;&gt;</a>
		  </td>
  </tr>
</table>
