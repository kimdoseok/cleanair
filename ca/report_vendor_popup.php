<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.vendors.php");
	include_once("class/class.datex.php");
	include_once("class/register_globals.php");

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
//-----------------------------------------------------------------------


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Popup Vendor Codes</title>


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

function setCode(value) {
	var f = document.forms[0];

	var s = self.opener.document.forms[0];
	self.opener.document.forms[0].<?= $objname ?>.value = value;
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
                      <td colspan="8"><strong>List Vendors</strong></td>
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
					  </td>
                    </tr>
					</form>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">Vendor Code</font></th>
                            <th bgcolor="gray"><font color="white"><?= $label[$lang]["Name"] ?></font></th>
                            <th bgcolor="gray"><font color="white">Address</font></th>
                            <th bgcolor="gray"><font color="white">City</font></th>
                            <th bgcolor="gray"><font color="white">Tel</font></th>
                            <th bgcolor="gray"><font color="white">A</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Vends();
	$numrows = $c->getVendsRows($ft);
	$recs = $c->getVendsList($cn, $ft, $rv, $pg, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	else $numrecs = 0;

	$tday = date("m/d/Y");
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="10%" align="center"> 
							<a href="javascript:setCode('<?= addslashes($recs[$i]["vend_code"]) ?>');"><?= $recs[$i]["vend_code"] ?></a></td>
                            <td width="25%"><?= $recs[$i]["vend_name"] ?></td>
                            <td width="30%"><?= $recs[$i]["vend_addr1"] ?></td>
                            <td width="20%"><?= $recs[$i]["vend_city"] ?></td>
                            <td width="15%"><?= $recs[$i]["vend_tel"] ?></td>
                            <td width="1%"><?= ($recs[$i]["vend_active"]!="t")?"X":"&nbsp;" ?></td>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=1&cn=$cn&ft=$ft" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$prevpage&cn=$cn&ft=$ft" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$nextpage&cn=$cn&ft=$ft" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?objname=$objname&pg=$totalpage&cn=$cn&ft=$ft" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
