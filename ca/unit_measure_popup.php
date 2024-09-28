<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.unit_measures.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Unit of Measure Pop Up</title>


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
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List Unit of Measure</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">Unit#</font></th>
                            <th bgcolor="gray"><font color="white">Name</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new UnitMeasures();
	$numrows = $c->getUnitMeasuresRows();
	$recs = $c->getUnitMeasuresList($condition, $filtertext, $reverse, $page, $limit);
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
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="20%" align="center"> 
							<a href="javascript:setCode('<?= rawurlencode($recs[$i]["unit_code"]) ?>');"><?= $recs[$i]["unit_code"] ?></a>
                            </td>
                            <td width="80%"> 
                              <?= $recs[$i]["unit_name"] ?>
                            </td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red">No Data!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=1" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$prevpage" ?>">&lt;<?= Prev</a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$nextpage" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$totalpage" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
