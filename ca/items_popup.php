<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.items.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/register_globals.php");
//------------------------------------------------------------------------

// Customer Screen Text View Language Select
	$lang = 'en';
	$charsetting = "iso-8859-1";
	if ($_SERVER["PHP_AUTH_USER"] != "admin") {
		$lang = "ch";
		$charsetting = "gb2312";
	}
//-----------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Popup_Item_Codes</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
function updateForm(page) {
	document.forms[0].pg.value = page;
	document.forms[0].method="POST";
	document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
	document.forms[0].submit();
}

function setCode(value, desc, unit) {
	var s = self.opener.document.forms[0];
	s.styldtl_item_code.value = value;
	s.styldtl_item_desc.value = desc;
	s.styldtl_unit.value = unit;
	s.styldtl_item_code.select();
//	self.opener.document.forms[0].<?= $objname ?>.value = value;
//	self.opener.document.forms[0].styldtl_item_code.value = value;
//	self.opener.document.forms[0].styldtl_item_desc.value = desc;
	self.close();
//	self.opener.document.forms[0].<?= $objname ?>.select();
//	self.opener.document.forms[0].styldtl_item_code.select();
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
                      <td colspan="8"><strong><?= $label[$lang]["List_Item"] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Item_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
                            <th bgcolor="gray"><font color="white">Status</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Items();
	$numrows = $c->getItemsRows();
	$recs = $c->getItemsList($condition, $filtertext, $reverse, $page, $limit);
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
							<a href="javascript:setCode('<?= rawurlencode($recs[$i]["item_code"]) ?>', '<?= $recs[$i]["item_desc"] ?>', '<?= $recs[$i]["item_unit"] ?>');">
								<?= $recs[$i]["item_code"] ?></a>
                            </td>
                            <td width="75%"> 
                              <?= $recs[$i]["item_desc"] ?>
                            </td>
                            <td width="5%"> 
                              <?= ($recs[$i]["item_active"]!="f")?"Active":"Inactive" ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=1" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$prevpage" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$nextpage" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$totalpage" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
