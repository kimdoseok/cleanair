<?php
	include_once("class/register_globals.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Style Memo</title>
<SCRIPT LANGUAGE="JavaScript">

function updateForm(page) {
	document.forms[0].page.value = page;
	document.forms[0].method="POST";
	document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
	document.forms[0].submit();
}

function setCode(value) {
	self.opener.document.form1.new_ordhdrs_cust_no.value = value;

	self.opener.document.form1.new_ordhdrs_cust_no.select();
	self.close();
}

function setClose() {
	self.close();
//	self.opener.document.form1.new_ordhdrs_cust_no.select();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
<form name="form1">

<?php
	include_once("class/class.formutils.php");
	$f = new FormUtil();
	if (empty($page)) $page = 1;
?>
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][List_Styles] ?></strong></td>
                    </tr>
                    <tr>
    <td colspan="8" align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr>
          <td><font size="2">| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> 
            | </font></td>
            <td align="right">
				&nbsp;
            </td>
        </tr>
		</form>
      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">#</font></th>
                            <th width="70%" bgcolor="gray"><font color="white">Memo</font></th>
                            <th bgcolor="gray" width="25%"><font color="white">Date</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Memos();
	$numrows = $c->getMemosRows();
	$recs = $c->getMemosList($condition, $filtertext, $reverse, $page, $limit);

	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
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
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&styl_code=".$recs[$i][styl_code] ?>"><?= $recs[$i][styl_code] ?></a>
                            </td>
                            <td align="center"> 
                              <?= $recs[$i][styl_po_no] ?>
                            </td>
                            <td align="right"> 
                              <?= $recs[$i][styl_date] ?>
                            </td>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="9" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=1&sc=$sc" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$prevpage&sc=$sc" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$nextpage&sc=$sc" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&page=$totalpage&sc=$sc" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
