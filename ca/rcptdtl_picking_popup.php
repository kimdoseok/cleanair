<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.items.php");
	include_once("class/class.picks.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	include("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Unpaid Picking Tickets</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
function updateForm(page) {
	document.forms[0].pg.value = page;
	document.forms[0].method="POST";
	document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
	document.forms[0].submit();
}

function setCode(pid, amt) {
	var s = self.opener.document.forms[0];
//	s.rcptdtl_ref_code.value = pid;
	s.rcptdtl_pick_id.value = pid;
	s.rcptdtl_amt.value = amt;
//	s.rcptdtl_ref_code.focus();
	s.rcptdtl_pick_id.focus();
	self.close();
}

function setClose() {
//	self.opener.document.form1.rcptdtl_ref_code.select();
	self.opener.document.form1.rcptdtl_pick_id.focus();
	self.close();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>Unpaid Picking Tickets</strong></td>
                    </tr>
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">Pick #</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Date</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Amount</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Paid</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Balance</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Picks();
	$numrows = $c->getPicksRowsUnpaid($cust_code);
	$recs = $c->getPicksListUnpaid($cust_code, $page, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr bgcolor=\"WHITE\">"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
		$balance = $recs[$i]["pick_amt"] - $recs[$i][pick_paid];
		if ($remained < $balance) $avl_amt = $remained;
		else $avl_amt = $balance;
?>
							<td width="5%" align="center"> 
                              <a href="javascript:setCode('<?= $recs[$i]["pick_id"] ?>', '<?= sprintf("%0.2f",$avl_amt) ?>');">
							    <?= $recs[$i]["pick_id"] ?>
							  </a>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["pick_date"] ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= number_format($recs[$i]["pick_amt"], 2, '.', ',') ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= number_format($recs[$i][pick_paid], 2, '.', ',') ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= number_format($balance, 2, '.', ',') ?>
                            </td>
                        </tr>
<?php
	}
	if ($numrecs == 0) {
?>
						  <tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
                          </tr>

<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=1&pick_cust_code=$pick_cust_code&slsdtl_item_code=$slsdtl_item_code" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$prevpage&pick_cust_code=$pick_cust_code&slsdtl_item_code=$slsdtl_item_code" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$nextpage&pick_cust_code=$pick_cust_code&slsdtl_item_code=$slsdtl_item_code" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$totalpage&pick_cust_code=$pick_cust_code&slsdtl_item_code=$slsdtl_item_code" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
