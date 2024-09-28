<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.items.php");

//------------------------------------------------------------------------
	
include_once("class/map.label.php");

include_once("class/map.default.php");
include_once("class/map.lang.php");
include_once("class/register_globals.php");

//-----------------------------------------------------------------------

$f = new FormUtil();

$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
				1=>array("value"=>"desc", "name"=>"Description")
);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Items Popup</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">

function setFilter() {
	var f = document.forms[0];
	f.method = "GET" ;
	f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
	f.submit();
}

function updateForm(page) {
	document.forms[0].pg.value = page;
	document.forms[0].method="POST";
	document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
	document.forms[0].submit();
}

function setCode(value, desc, price, tax, unit) {
	
	var s = self.opener.document.forms[0];
	
	s.porcptdtl_item_code.value = value;
	
	s.porcptdtl_item_desc.value = desc;
	
//	s.porcptdtl_cost.value = price;
	
	s.porcptdtl_unit.value = unit;
	s.porcptdtl_qty.value = 1;
//	s.amount.value = Math.round(parseFloat(s.porcptdtl_qty.value)*parseFloat(price)*100)/100;
//	if (tax == 't') s.porcptdtl_taxable.checked;

	s.porcptdtl_item_code.focus();
	self.close();
}



function setClose() {
	self.opener.document.form1.<?= $objname ?>.select();
	self.close();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="1"><strong>List Item</strong></td>
                    </tr>
                    <tr align="right"> 
					<FORM>
                      <td>
						<?= $f->fillTextBox("ft",$ft,30) ?>
						<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
						<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> ><?= $label[$lang]["Reverse"] ?>
						<input type="button" name="filter" value="Filter" onClick="javascript:setFilter()">
						<?= $f->fillHidden("pg",$pg) ?>
						<?= $f->fillHidden("objname",$objname) ?>
					  </td>
					  </form>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">Item#</font></th>
                            <th bgcolor="gray"><font color="white">Description</font></th>
                            <th bgcolor="gray"><font color="white">Last Prc</font></th>
                            <th bgcolor="gray"><font color="white">Avg. Prc</font></th>
                            <th bgcolor="gray"><font color="white">A</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Items();
	$numrows = $c->getItemsRows($ft);
	$recs = $c->getItemsList($cn, $ft, $rv, $pg, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";

?>
                            <td width="20%" align="center"> 
							<a href="javascript:setCode('<?= addslashes($recs[$i]["item_code"]) ?>', '<?= addslashes($recs[$i]["item_desc"]) ?>', '<?= addslashes($recs[$i]["item_last_cost"]) ?>', '<?= addslashes($recs[$i]["item_tax"]) ?>', '<?= addslashes($recs[$i]["item_unit"]) ?>')">
							<?= $recs[$i]["item_code"] ?></a></td>
                            <td width="49%"> 
                              <?= $recs[$i]["item_desc"] ?>
                            </td>
                            <td width="15%" align="right"> 
                              <?= number_format($recs[$i]["item_last_cost"],2,".",",") ?>
                            </td>
                            <td width="15%" align="right"> 
                              <?= number_format($recs[$i]["item_avg_cost"],2,".",",") ?>
                            </td>
                            <td width="1%"> 
                              <?= ($recs[$i]["item_active"]!="f")?"X":"&nbsp;" ?>
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
                    <tr align="center"> 
                      <td colspan="1">
					    <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=1&ft=$ft" ?>">&lt;&lt;First</a>
                        &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$prevpage&ft=$ft" ?>">&lt;Prev</a> &nbsp; 
						<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                        &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$nextpage&ft=$ft" ?>">Next&gt;</a>
                        &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$totalpage&ft=$ft" ?>">Last&gt;&gt;</a>
					  </td>
                    </tr>
                  </table>

</BODY>
</HTML>
