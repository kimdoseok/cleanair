<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/map.label.php");
	include_once("class/register_globals.php");

	$lang = "en";
	$charsetting = "iso-8859-1";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Account Popup</title>
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
	self.opener.document.forms[0].<?= $objname ?>.select();
	self.close();

}

function setClose() {
	self.opener.document.form1.<?= $objname ?>.select();
	self.close();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
<?php
	include_once("class/class.formutils.php");

	$selbox = array(0=>array("value"=>"as", "name"=>"Asset"),
					1=>array("value"=>"li", "name"=>"Liability"), 
					2=>array("value"=>"eq", "name"=>"Equity"), 
					3=>array("value"=>"in", "name"=>"Income"),
					4=>array("value"=>"cs", "name"=>"Cost of Sale"),
					5=>array("value"=>"ex", "name"=>"Expense"),
					6=>array("value"=>"mi", "name"=>"Misc Income"),
					7=>array("value"=>"me", "name"=>"Misc Expense")
					);
	$f = new FormUtil();
	if (empty($sc)) $sc = "all";					
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
					<input type="button" name="filter" value="Filter" onClick="javascript:updateFilter()">
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
                            <th bgcolor="gray"><font color="white">Acct #</font></th>
                            <th bgcolor="gray"><font color="white">Description</font></th>
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

		if ($recs[$i][acct_type] == "as") $acct_type = "Asset";
		else if ($recs[$i][acct_type] == "li") $acct_type = "Liability";
		else if ($recs[$i][acct_type] == "eq") $acct_type = "Equity";
		else if ($recs[$i][acct_type] == "in") $acct_type = "Income";
		else if ($recs[$i][acct_type] == "cs") $acct_type = "Cost of Sale";
		else if ($recs[$i][acct_type] == "ex") $acct_type = "Expense";
		else if ($recs[$i][acct_type] == "mi") $acct_type = "Misc_Income";
		else if ($recs[$i][acct_type] == "me") $acct_type = "Misc_Expense";
		else $acct_type = "Unknown";
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
		<tr><td colspan="5" align="center"><font color="red">"No_Data!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=1&sc=$sc&ft=$ft&objname=$objname" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$prevpage&sc=$sc&ft=$ft&objname=$objname" ?>">&lt;Prev</a> &nbsp; 
									<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$nextpage&sc=$sc&ft=$ft&objname=$objname" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&pg=$totalpage&sc=$sc&ft=$ft&objname=$objname" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>


</form>
</BODY>
</HTML>
