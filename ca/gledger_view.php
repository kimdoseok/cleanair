<?php
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.disburdtls.php");
	include_once("class/class.vendors.php");
	include_once("class/class.purdtls.php");
	$x = new Datex();
	$s = new GLedger();
	$d = new JrnlTrxs();
	$a = new Accts();
	$applied = 0;
	$remained = 0;
	$gldgr_amt = 0;
 
	$recs = $c->getGLedger($gldgr_id);
	if (!empty($recs)) foreach($recs as $k => $v) $$k = $v;

	$limit = 1;
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();


	$recs = $j->getJrnlTrxsList("g", $gldgr_id);
	$balance = 0;
	for ($i=0;$i<count($recs);$i++) {
		if ($recs[$i]["jrnltrx_dc"] == 'd') $balance += $recs[$i]["jrnltrx_amt"];
		else if ($recs[$i]["jrnltrx_dc"] == 'c')  $balance -= $recs[$i]["jrnltrx_amt"];
	}
	if (empty($gldgr_date)) $gldgr_date = $d->getToday();
	if (!empty($did) || "$did" == 0) if (isset($recs[$did])) foreach ($recs[$did] as $k => $v) if (empty($$k)) $$k = $v;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
//-->
</SCRIPT>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="right"> 
    <td colspan="8"><strong>
      <?= $label[$lang][View_Purchase] ?>
      </strong></td>
  </tr>
  <tr align="left"> 
    <td colspan="8"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">
      <?= $label[$lang]["New_1"] ?>
      </a> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&gldgr_id=$gldgr_id" ?>">
      <?= $label[$lang]["Edit"] ?>
      </a> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">
      <?= $label[$lang]["List_1"] ?>
      </a> | </font> </td>
  </tr>
  <tr align="right"> 
    <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="97" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>:</td>
            <td width="308"><?= $gldgr_date ?></td>
          </tr>
          <tr> 
            <td width="97" bgcolor="silver"><?= $label[$lang]["Comment"] ?>:</td>
            <td><?= $gldgr_cmnt  ?></td>
          </tr>
                  </table></td>
                <td width="1%">&nbsp;</td>
                <td width="37%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
		  <tr>
            <td width="97" bgcolor="silver">GL ID :</td>
            <td width="308"><?= $gldgr_id ?></td>
		  </tr> 
            <tr> 
              <td width="97" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
              <td><?= $gldgr_user_code ?></td>
            </tr>
            <tr> 
              <td width="97" bgcolor="silver"><?= $label[$lang][Balance] ?>:</td>
              <td><?= sprintf("%0.2f", $balance) ?></td>
            </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr align="right"> 
    <td colspan="7"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <th bgcolor="gray" width="5%"><font color="white"> 
              <?= $label[en]["No"] ?>
              </font></th>
            <th bgcolor="gray" width="60%" colspan="2"><font color="white"> 
              <?= $label[$lang][Account_no] ?>
              </font></th>
            <th bgcolor="gray" width="15%"><font color="white"> 
              <?= $label[$lang][Debit] ?>
              </font></th>
            <th bgcolor="gray" width="15%"><font color="white"> 
              <?= $label[$lang][Credit] ?>
              </font></th>
          </tr>
<?php
	$balance = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			if ($recs[$i]["jrnltrx_dc"]=="d") {
				$credit = 0;
				$debit = $recs[$i]["jrnltrx_amt"];
				$balance += $recs[$i]["jrnltrx_amt"];
			} else if ($recs[$i]["jrnltrx_dc"]=="c") {
				$debit = 0;
				$credit = $recs[$i]["jrnltrx_amt"];
				$balance -= $recs[$i]["jrnltrx_amt"];
			}
			$aarr = $a->getAccts($recs[$i]["jrnltrx_acct_code"]);
?>
          <td width="5%" align="center"><?= $i+1 ?></a> </td>
          <td width="10%" align="center"> 
            <?= $recs[$i]["jrnltrx_acct_code"] ?>
          </td>
          <td width="50%" align="left"> 
            <?= $aarr["acct_desc"] ?>
          </td>
          <td width="15%" align="right"> 
            <?= sprintf("%0.2f", $debit) ?>
          </td>
          <td width="15%" align="right"> 
            <?= sprintf("%0.2f", $credit) ?>
          </td>
          </tr>
<?php
		}
	}
?>
      </table></td>
  </tr>
  <tr align="right"> 
    <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&gldgr_id=$gldgr_id" ?>">&lt;&lt;
            <?= $label[$lang]["First"] ?>
            </a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&gldgr_id=$gldgr_id" ?>">&lt;
            <?= $label[$lang]["Prev_1"] ?>
            </a> &nbsp; &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&gldgr_id=$gldgr_id" ?>">
            <?= $label[$lang]["Next_1"] ?>
            &gt;</a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&gldgr_id=$gldgr_id" ?>">
            <?= $label[$lang]["Last"] ?>
            &gt;&gt;</a></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>