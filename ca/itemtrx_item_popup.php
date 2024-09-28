<?php
	include_once("class/class.itemunits.php");
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
<title>Popup Item Codes</title>


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

function setCode(value, desc, unit, cost, inv, unam, uval) {
	
	var isIE = (navigator.appVersion.indexOf("MSIE")!=-1)
	var s = self.opener.document.forms[0];
	
	s.invtrx_item_code.value = value;	
	s.invtrx_item_desc.value = desc;
	s.invtrx_cost.value = cost;
	s.invtrx_unit.value = unit;
	s.invtrx_inv_acct.value = inv;
	s.invtrx_unit.length = 0;
	var selected = 0;
	for (i=0;i<uval.length;i++) {
		if (isIE) {
			newOpt = self.opener.document.createElement("OPTION");
			newOpt.value = uval[i];
			newOpt.text = unam[i];
			s.invtrx_unit.options.add(newOpt, i)
		} else {
			s.invtrx_unit.options[i] = new Option(uval[i]);
			s.invtrx_unit.options[i].text = unam[i];
			s.invtrx_unit.options[i].value = uval[i];
		}
		if (uval[i]==unit) selected = i;
	}
	s.invtrx_unit.selectedIndex = selected;

	s.invtrx_item_code.focus();
	self.close();
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
                      <td colspan="8"><strong>List Item</strong></td>
                    </tr>
                    <tr align="right"> 
					<FORM>
                      <td>
						<?= $f->fillTextBox("ft",$ft,30) ?>
						<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
						<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> >Reverse
						<input type="button" name="filter" value="Filter" onClick="javascript:setFilter()">
						<?= $f->fillHidden("pg",$pg) ?>
						<?= $f->fillHidden("objname",$objname) ?>
					  </td>
					  </form>
                    </tr>


                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">Item #</font></th>
                            <th bgcolor="gray"><font color="white">Description</font></th>
                            <th bgcolor="gray"><font color="white">MSRP</font></th>
                            <th bgcolor="gray"><font color="white">Unit</font></th>
                            <th bgcolor="gray"><font color="white">A</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Items();
	$iu = new ItemUnits();
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
		if ($recs[$i]["item_code"]) {
			$iu_arr = $iu->getItemUnitsListByItem($recs[$i]["item_code"]);
			if ($iu_arr) {
				$iu_num = count($iu_arr);
				$iu_val = "new Array(";
				$iu_nam = "new Array(";
				for ($j=0;$j<$iu_num;$j++) {
					$value = strtoupper($iu_arr[$j]["itemunit_unit"]);
					$name = $iu_arr[$j]["unit_name"];
					if ($j!=0) {
						$iu_val = ",";
						$iu_nam = ",";
					}
					$iu_val .= "'".$value."'";
					$iu_nam .= "'".$name."'";
				}
				$iu_val .= ")";
				$iu_nam .= ")";
			} else {
				$iu_val = "new Array('EA')";
				$iu_nam = "new Array('Each')";
			}
		}

?>
                            <td width="20%" align="center"> 
							<a href="javascript:setCode('<?= addslashes($recs[$i]["item_code"]) ?>','<?= addslashes($recs[$i]["item_desc"]) ?>','<?= addslashes($recs[$i]["item_unit"]) ?>','<?= $recs[$i][item_user_cost] ?>','<?= $recs[$i][item_inv_acct] ?>',<?= $iu_nam ?>,<?= $iu_val ?>)">
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
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
