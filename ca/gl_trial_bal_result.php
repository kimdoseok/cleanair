<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	$d = new Datex();
	$r = new Dbutils();
	$limit = 1000;
	
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td align="left" colspan="3"> 
		Beginning Date : <?= $start_date ?>
    </td>
    <td align="left" colspan="3"> 
		Ending Date : <?= $end_date ?>
    </td>
    <td align="left" colspan="3"> 
		Include Zero? : <?= ($zero_balance=="z")?"Yes":"No" ?>
    </td>
  </tr>
  <tr align="right"> 
    <td bgcolor="gray" align="center"><font color="white">
            <B>Acct #</B>
            </font></td>
    <td bgcolor="gray" align="center" colspan="2"><font color="white">
            <B>Beg. Bal.</B>
            </font></td>
    <td bgcolor="gray" align="center"><font color="white">
            <B>Total Debit</B>
            </font></td>
    <td bgcolor="gray" align="center"><font color="white">
            <B>Total Credit</B>
            </font></td>
    <td bgcolor="gray" align="center" colspan="2"><font color="white">
            <B>Net Change</B>
            </font></td>
    <td bgcolor="gray" align="center" colspan="2"><font color="white">
            <B>End. Bal.</B>
            </font></td>
  </tr>
  <tr> 
    <td bgcolor="gray" colspan="9"><font color="white">
            <B>&nbsp; &nbsp; Account Name</B>
            </font></td>
  </tr>
<?php
	$query = "SELECT acct_code, acct_desc FROM accts ORDER BY acct_code ";
	$start_date = $d->toIsoDate($start_date);
	$end_date = $d->toIsoDate($end_date);
	$rownums = $r->selectQryRaw($query);
	$total_page = 
	$j = new JrnlTrxs();
	$i=0;
	
	while ($r->fetch_row()) {
		$j_arr = $j->getJrnlTrxTrial($r->record["acct_code"], $start_date, $end_date);
		if (empty($start_date)) $begin_bal = 0;
		else $begin_bal = $j_arr[begin_debit] - $j_arr[begin_credit];
		$net_change = $j_arr[period_debit] - $j_arr[period_credit];
		$ending_bal = $begin_bal + $net_change;
		if ($ending_bal != 0 || $zero_balance == "z") {

			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
    <td align="left" width="150"> 
      <b><?= $r->record["acct_code"] ?></b>
    </td>
    <td align="right" width="150"> 
      <?= ($begin_bal<0)?sprintf("%0.2f", number_format(abs($begin_bal), 2, '.', ',')):number_format($begin_bal, 2, '.', ',') ?>
    </td>
    <td align="right" width="5"> 
      <?= ($begin_bal<0)?"CR":"&nbsp;" ?>
    </td>
    <td align="right" width="150"> 
      <?= number_format($j_arr[period_debit], 2, '.', ',') ?>
    </td>
    <td align="right" width="150"> 
      <?= number_format($j_arr[period_credit], 2, '.', ',') ?>
    </td>
    <td align="right" width="150"> 
      <?= ($net_change<0)?number_format(abs($net_change), 2, '.', ','):number_format($net_change, 2, '.', ',') ?>
    </td>
    <td align="right" width="5"> 
      <?= ($net_change<0)?"CR":"&nbsp;" ?>
    </td>
    <td align="right" width="150"> 
      <?= ($ending_bal<0)?number_format(abs($ending_bal), 2, '.', ','):number_format($ending_bal, 2, '.', ',') ?>
    </td>
    <td align="right" width="5"> 
      <?= ($ending_bal<0)?"CR":"&nbsp;" ?>
    </td>
  </tr>
<?php
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
	<td align="left" colspan="9"> 
          <?= $r->record["acct_desc"] ?>
    </td>
  </tr>
<?php
			$i++;
//			if ($i>=limit) break;
		}
	}

/*
	if ($i>=limit) {
?>
  <tr>
	<td align="left" colspan="9"> 
      <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a> &nbsp; 
	  <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage" ?>">&lt; <?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
	  <font color="gray"><?= "[$pg / $totalpage]" ?></font> &nbsp; 
	  <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage" ?>"><?= $label[$lang]["Next_1"] ?> &gt;</a> &nbsp; 
	  <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage" ?>"><?= $label[$lang]["Last"] ?> &gt;&gt;</a>
   </td>
  </tr>
<?php
	}
*/
?>
</table>