<?php
	$d = new Datex();
	$f = new FormUtil();
	$a = new Accts();
	$applied = 0;
	$remained = 0;
	$gldgr_amt = 0;

	if ($_SESSION[$gledger_add]) if ($gldg = $_SESSION[$gledger_add]) foreach($gldg as $k => $v) $$k = $v;
	$recs = $_SESSION[$jrnltrxs_add];
	$balance = 0;
	for ($i=0;$i<count($recs);$i++) {
		if ($recs[$i]["jrnltrx_dc"] == 'd') $balance += $recs[$i]["jrnltrx_amt"];
		else if ($recs[$i]["jrnltrx_dc"] == 'c')  $balance -= $recs[$i]["jrnltrx_amt"];
	}
	if (empty($gldgr_date)) $gldgr_date = $d->getToday();
	if (!empty($did) || "$did" == 0) if (isset($recs[$did])) foreach ($recs[$did] as $k => $v) if (empty($$k)) $$k = $v;
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <form method=post action="gl_proc.php">
    <?= $f->fillHidden("cmd","") ?>
    <?= $f->fillHidden("ty","a") ?>
    <tr> 
      <td align="right"><strong>
        New General Ledger
        </strong></td>
    </tr>
    <tr> 
      <td align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">
        <?= $label[$lang]["New_1"] ?>
        </a> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">
        <?= $label[$lang]["List_1"] ?>
        </a> | </font> </td>
    </tr>
    <tr> <td width="49%">
      <table width="100%" border="0" cellspacing="1" cellpadding="0"><tr><td width="62%" valign="top">
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
<!--
		  <tr>
            <td width="97" bgcolor="silver">
              GL ID
              :</td>
            <td width="308"> 
              <?= $f->fillTextBoxRO("gldgr_id", $gldgr_id, 20, 32, "inbox") ?>
            </td>
          </tr>
-->
		  <tr>
            <td width="97" bgcolor="silver">
              <?= $label[$lang]["Date_1"] ?>
              :</td>
            <td width="308"> 
              <?= $f->fillTextBox("gldgr_date", $gldgr_date, 20, 32, "inbox") ?>
			  <a href="javascript:openCalendar('gldgr_date')">C</a>
            </td>
          </tr>
          <tr> 
            <td width="97" bgcolor="silver">
              <?= $label[$lang]["Comment"] ?>
              :</td>
            <td> 
              <?= $f->fillTextBox("gldgr_cmnt", $gldgr_cmnt , 30, 250, "inbox") ?>
            </td>
          </tr>
        </table></td>
        <td width="2%">&nbsp;</td><td width="49%">
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td width="97" bgcolor="silver">
              <?= $label[$lang]["User_no"] ?>
              &nbsp;</td>
            <td> 
              <?= $f->fillTextBoxRO("gldgr_user_code", $_SERVER["PHP_AUTH_USER"], 20, 32, "inbox") ?>
            </td>
          </tr>
          <tr> 
            <td width="97" bgcolor="silver">
              <?= $label[$lang][Balance] ?>
              :</td>
            <td> 
              <?= $f->fillTextBoxRO("balance", $balance , 20, 32, "inbox") ?>
            </td>
          </tr>
        </table></td>
        </tr>
      </table></td>
    </tr>
    <tr> 
      <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="64%" align="center">&nbsp;</td>
            <td width="36%" align="center"> <input type="button" name="Submit32" value="<?= $label[$lang]["Update"] ?>" onClick="updateHead()"> 
              <input type="button" name="Submit3222" value="<?= $label[$lang]["Record"] ?>" onClick="SaveToDB()"> 
              <input type="button" name="Submit22222" value="<?= $label[$lang]["Clear"] ?>" onClick="clearSess()"></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td align="left">
		<TABLE width="100%" bgcolor="silver" border="0" cellspacing="1" cellpadding="0">
		<TR>
			<Th bgcolor="gray" align="center" width="5%"><font color="white">#</font></TD>
			<Th bgcolor="gray" align="center" width="35%"><font color="white">Account #</font></TD>
			<Th bgcolor="gray" align="center" width="20%"><font color="white">Amount</font></TD>
			<Th bgcolor="gray" align="center" width="20%"><font color="white">Debit/Credit</font></TD>
			<Th bgcolor="gray" align="center" width="20%">&nbsp;</TD>
		</TR>
		<TR>
			<TD bgcolor="white" align="center"><?= ($did == "" || $did < 0)?"New":$did+1 ?><?= $f->fillHidden("did",$did) ?></TD>
			<TD bgcolor="white" align="center"><?= $f->fillHidden("jrnltrx_ref_id", $jrnltrx_ref_id) ?><?= $f->fillTextBox("jrnltrx_acct_code", $jrnltrx_acct_code , 16, 16, "inbox") ?> <b><font size="2"><a href="javascript:openAcctBrowse('jrnltrx_acct_code')">Lookup</a></font></b></TD>
			<TD bgcolor="white" align="center"><?= $f->fillTextBox("jrnltrx_amt", $jrnltrx_amt , 10, 32, "inbox") ?></TD>
<?php
	$debit = "";
	$credit = "";
	if ($jrnltrx_dc == "c") $credit = "checked";
	else $debit = "checked";
?>
			<TD bgcolor="white" align="center"><INPUT TYPE="radio" NAME="jrnltrx_dc" value="d" <?= $debit ?>>Debit <INPUT TYPE="radio" NAME="jrnltrx_dc" value="c" <?= $credit ?>>Credit</TD>
			<TD bgcolor="white" align="center"><INPUT TYPE="button" name="apply" value="Apply" onClick="applDtl()"> <INPUT TYPE="button" name="clear" value="New" onClick="clearDtl()"> </TD>
		</TR>
		</TABLE>
	  </td>
    </tr>
    <tr align="right"> 
      <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
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
          <th bgcolor="gray" width="5%"><font color="white">&nbsp;</font></th>
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
        <td width="5%" align="center"> <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=a&did=$i" ?>">
          <?= $i+1 ?>
          </a> </td>
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
        <td width="5%" align="center"> <a href="gl_proc.php?cmd=gldgr_detail_sess_del&did=<?= $i ?>">
          <?= $label[$lang]["Del"] ?>
          </a> </td>
        </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
          <tr bgcolor="#EEEEEE"> 
            <td colspan="8" align="center"> <b>
              <?= $label[$lang]["Empty_1"] ?>
              !</b> </td>
          </tr>
          <?php
	}
?>
        </table></td>
    </tr>
  </form>
</table>
<br>