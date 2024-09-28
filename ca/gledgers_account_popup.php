<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/map.label.php");
	include_once("class/register_globals.php");

	$lang = "en";
	$charsetting = "iso-8859-1";
//	if ($_SERVER["PHP_AUTH_USER"] != "admin") {
//		$lang = "ch";
//		$charsetting = "gb2312";
//	}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title><?= $label[$lang]["Popup_Account_Codes"] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">

<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
function updateForm(page) {
	document.forms[0].pg.value = page;
	document.forms[0].method="POST";
	document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
	document.forms[0].submit();
}

function setCode(value) {
	self.opener.document.forms[0].<?= $objname ?>.value = value;
	self.close();
	self.opener.document.forms[0].<?= $objname ?>.select();
}

function setClose() {
	self.close();
	self.opener.document.form1.<?= $objname ?>.select();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
<?php
	include_once("class/class.formutils.php");

	$selbox = array(0=>array("value"=>"as", "name"=>$label[$lang]["Asset"]),
					1=>array("value"=>"li", "name"=>$label[$lang]["Liability"]), 
					2=>array("value"=>"eq", "name"=>$label[$lang]["Equity"]), 
					3=>array("value"=>"in", "name"=>$label[$lang]["Income"]),
					4=>array("value"=>"cs", "name"=>$label[$lang]["Cost_of_Sale"]),
					5=>array("value"=>"ex", "name"=>$label[$lang]["Expense"]),
					6=>array("value"=>"mi", "name"=>$label[$lang]["Misc_Income"]),
					7=>array("value"=>"me", "name"=>$label[$lang]["Misc_Expense"])
					);
	$f = new FormUtil();
	if (empty($sc)) $sc = $label[$lang]["all"];					
	if ($ch == "t") $pg = 1;
	if (empty($pg)) $pg = 1;
?>
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr>
    <td colspan="8" align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr>
            <td align="right"> 
			      <?= $f->fillTextBox("ft",$ft,20) ?>
				  <?= $f->fillSelectBoxWithAllRefresh($selbox,"sc", "value", "name", $sc) ?>
					<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="javascript:updateFilter()">
					<?= $f->fillHidden("ty", $ty) ?>
					<?= $f->fillHidden("pg", $pg) ?>
					<?= $f->fillHidden("ch", "f") ?>
					<?= $f->fillHidden("objname", $objname) ?>
            </td>
        </tr>
		</form>
      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Acct_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Accts();
	$numrows = $c->getAcctsRows($sc, $ft);
	$recs = $c->getAcctsList($sc, $ft, $reverse, $pg, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";

		if ($recs[$i][acct_type] == "as") $acct_type = $label[$lang]["Asset"];
		else if ($recs[$i][acct_type] == "li") $acct_type = $label[$lang]["Liability"];
		else if ($recs[$i][acct_type] == "eq") $acct_type = $label[$lang]["Equity"];
		else if ($recs[$i][acct_type] == "in") $acct_type = $label[$lang]["Income"];
		else if ($recs[$i][acct_type] == "cs") $acct_type = $label[$lang]["Cost_of_Sale"];
		else if ($recs[$i][acct_type] == "ex") $acct_type = $label[$lang]["Expense"];
		else if ($recs[$i][acct_type] == "mi") $acct_type = $label[$lang]["Misc_Income"];
		else if ($recs[$i][acct_type] == "me") $acct_type = $label[$lang]["Misc_Expense"];
		else $acct_type = $label[$lang][Unknown];
?>
                            <td width="50" align="center"> 
							  <a href="javascript:setCode('<?= rawurlencode($recs[$i]["acct_code"]) ?>');"><?= $recs[$i]["acct_code"] ?></a>
                            </td>
                            <td> 
                              <?= $recs[$i]["acct_desc"] ?>
                            </td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&sc=$sc&ft=$ft&objname=$objname" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&sc=$sc&ft=$ft&objname=$objname" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
									<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&sc=$sc&ft=$ft&objname=$objname" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&sc=$sc&ft=$ft&objname=$objname" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>


</form>
</BODY>
</HTML>
