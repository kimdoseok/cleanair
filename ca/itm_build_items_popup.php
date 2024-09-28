<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.items.php");

//------------------------------------------------------------------------
	
include_once("class/map.label.php");

//------------------------------------------------------------------------
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
<title>Popup_Item_Codes</title>


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
	
//	window.alert(value+desc+price);
	var s = self.opener.document.forms[0];
	
	s.slsdtl_item_code.value = value;
	
	s.slsdtl_item_desc.value = desc;
	
	s.slsdtl_cost.value = price;
	s.slsdtl_unit.value = unit;
	s.slsdtl_qty.value = 1;
	s.amount.value = Math.round(parseFloat(s.slsdtl_qty.value)*parseFloat(price)*100)/100;
	if (tax == 't') s.slsdtl_taxable.checked;

	s.slsdtl_item_code.focus();

	self.close();
	s.slsdtl_item_code.focus();
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
                      <td colspan="1"><strong><?= $label[$lang]["List_Item"] ?></strong></td>
                    </tr>
                    <tr align="right"> 
					<FORM>
                      <td>
						<?= $f->fillTextBox("ft",$ft,30) ?>
						<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
						<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> ><?= $label[$lang]["Reverse"] ?>
						<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="javascript:setFilter()">
						<?= $f->fillHidden("pg",$pg) ?>
						<?= $f->fillHidden("objname",$objname) ?>
					  </td>
					  </form>
                    </tr>


                    <tr align="right"> 
                      <td colspan="1"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Item_no"] ?></font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Description"] ?></font></th>
                            <th bgcolor="gray"><font color="white">MSRP</font></th>
                            <th bgcolor="gray"><font color="white">Unit</font></th>
                            <th bgcolor="gray"><font color="white">A</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Items();
	$c->active = "t";
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
							<a href="javascript:setCode('<?= addslashes($recs[$i]["item_code"]) ?>', '<?= addslashes($recs[$i]["item_desc"]) ?>', '<?= addslashes($recs[$i]["item_msrp"]) ?>', '<?= addslashes($recs[$i]["item_tax"]) ?>', '<?= addslashes($recs[$i]["item_unit"]) ?>')">
							<?= $recs[$i]["item_code"] ?></a></td>
                            <td width="80%"> 
                              <?= $recs[$i]["item_desc"] ?>
                            </td>
                            <td width="10%"> 
                              <?= sprintf("%0.2f", $recs[$i]["item_msrp"]) ?>
                            </td>
                            <td width="1%"> 
                              <?= strtoupper($recs[$i]["item_unit"]) ?>
                            </td>
                            <td width="1%"> 
                              <?= ($recs[$i]["item_active"]!="f")?"X":"&nbsp;" ?>
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
                      <td colspan="1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=1&ft=$ft" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$prevpage&ft=$ft" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$nextpage&ft=$ft" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$totalpage&ft=$ft" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
