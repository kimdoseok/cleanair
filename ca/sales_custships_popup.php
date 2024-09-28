<?php
	include_once("class/class.formutils.php");
	include_once("class/class.navigates.php");
	include_once("class/class.custships.php");
	include_once("class/class.datex.php");

	$f = new FormUtil();
	$d = new Datex();


	$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
					1=>array("value"=>"name", "name"=>"Name"),
					2=>array("value"=>"addr", "name"=>"Address"),
					3=>array("value"=>"city", "name"=>"City"),
					4=>array("value"=>"tel", "name"=>"Tel")
	);

//------------------------------------------------------------------------
	
include_once("class/map.label.php");
//------------------------------------------------------------------------
include_once("class/map.lang.php");

$vars = array("ft","rv","aw");
foreach ($vars as $v) {
	$$v = "";
} 
$vars = array("pg","cn");
foreach ($vars as $v) {
	$$v = 0;
} 


include_once("class/register_globals.php");
//-----------------------------------------------------------------------


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Popup ShipTos</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">

<LINK REL="StyleSheet" HREF="css.txt" type="text/css">

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

function setCode(name, addr1, addr2, addr3, city, state, zip, wday, tel, svia) {
	var f = document.forms[0];

	var s = self.opener.document.forms[0];
	self.opener.document.forms[0].sale_name.value = name;
	self.opener.document.forms[0].sale_addr1.value = addr1;
	self.opener.document.forms[0].sale_addr2.value = addr2;
	
	self.opener.document.forms[0].sale_addr3.value = addr3;
	
	self.opener.document.forms[0].sale_city.value = city;
	
	self.opener.document.forms[0].sale_state.value = state;
	
	self.opener.document.forms[0].sale_zip.value = zip;
	self.opener.document.forms[0].sale_prom_date.value = wday;
	self.opener.document.forms[0].sale_tel.value = tel;
	self.opener.document.forms[0].sale_shipvia.value = svia;
	self.opener.document.forms[0].<?= $objname ?>.focus();
	self.close();
}

function setClose() {
	self.opener.document.form1.<?= $objname ?>.focus();
	self.close();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>List ShipTo List</strong></td>
                    </tr>
					<form>
					<tr align="right"> 
                      <td colspan="8">
						<?= $f->fillTextBox("ft",$ft,30) ?>
						<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
						<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?>>
						<?= $label[$lang]["Reverse"] ?>
						<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="setFilter()">
						<?= $f->fillHidden("objname",$objname) ?>
						<?= $f->fillHidden("pg",$pg) ?>
						<?= $f->fillHidden("cust_code",$cust_code) ?>
					  </td>
                    </tr>
					</form>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">#</font></th>
                            <th bgcolor="gray"><font color="white">Name</font></th>
                            <th bgcolor="gray"><font color="white">Address</font></th>
                            <th bgcolor="gray"><font color="white">City</font></th>
                            <th bgcolor="gray"><font color="white">Tel</font></th>
                            <th bgcolor="gray"><font color="white">A</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new CustShips();
	$c->active = "t";
	$numrows = $c->getCustShipsRows($cust_code, $cn,$ft);
	$recs = $c->getCustShipsList($cust_code, $cn, $ft, $rv, $pg, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	$numrecs = 0;
	if($recs) $numrecs = count($recs);

	$tday = date("m/d/Y");
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
		$wday = $d->nextWeekDay($recs[$i]["custship_delv_week"], $tday);
?>
                            <td width="10%" align="center"> 
							<a href="javascript:setCode('<?= addslashes($recs[$i]["custship_name"]) ?>','<?= addslashes($recs[$i]["custship_addr1"]) ?>','<?= addslashes($recs[$i][custship_addr2]) ?>','<?= addslashes($recs[$i][custship_addr3]) ?>','<?= addslashes($recs[$i]["custship_city"]) ?>','<?= addslashes($recs[$i][custship_state]) ?>','<?= addslashes($recs[$i][custship_zip]) ?>','<?= addslashes($wday) ?>','<?= addslashes($recs[$i]["custship_tel"]) ?>','<?= addslashes($recs[$i][custship_shipvia]) ?>');"><?= $i+1 ?></a></td>
                            <td width="30%"><?= $recs[$i]["custship_name"] ?></td>
                            <td width="30%"><?= $recs[$i]["custship_addr1"] ?></td>
                            <td width="15%"><?= $recs[$i]["custship_city"] ?></td>
                            <td width="15%"><?= $recs[$i]["custship_tel"] ?></td>
                            <td width="1%"><?= ($recs[$i]["custship_active"]!="t")?"X":"&nbsp;" ?></td>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=1&cn=$cn&ft=$ft&cust_code=$cust_code" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$prevpage&cn=$cn&ft=$ft&cust_code=$cust_code" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$nextpage&cn=$cn&ft=$ft&cust_code=$cust_code" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$totalpage&cn=$cn&ft=$ft&cust_code=$cust_code" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
