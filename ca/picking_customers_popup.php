<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.customers.php");
//------------------------------------------------------------------------

include_once("class/map.label.php");

//------------------------------------------------------------------------
	include("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------

	$f = new FormUtil();

	$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
					1=>array("value"=>"name", "name"=>"Name"),
					2=>array("value"=>"addr", "name"=>"Address"),
					3=>array("value"=>"city", "name"=>"City"),
					4=>array("value"=>"tel", "name"=>"Tel")
	);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Popup_Customer_Codes</title>


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

function setCode(value, name, addr1, addr2, addr3, city, state, zip, tax, bal, tel) {
	var f = document.forms[0];
	
	var s = self.opener.document.forms[0];
	self.opener.document.forms[0].<?= $objname ?>.value = value;
	self.opener.document.forms[0].pick_name.value = name;
	self.opener.document.forms[0].pick_addr1.value = addr1;
	self.opener.document.forms[0].pick_addr2.value = addr2;
	self.opener.document.forms[0].pick_addr3.value = addr3;
	
	self.opener.document.forms[0].pick_city.value = city;
	
	self.opener.document.forms[0].pick_state.value = state;
	
	self.opener.document.forms[0].pick_zip.value = zip;
	self.opener.document.forms[0].pick_tel.value = tel;
	self.opener.document.forms[0].pick_taxrate.value = tax;
	self.opener.document.forms[0].cust_balance.value = bal;
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
                      <td colspan="8"><strong><?= $label[$lang]["List_Customer"] ?></strong></td>
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
                            <th width="10%" bgcolor="gray"><font color="white"><?= $label[$lang]["Cust_no"] ?></font></th>
                            <th width="24%" bgcolor="gray"><font color="white"><?= $label[$lang]["Name"] ?></font></th>
                            <th width="25%" bgcolor="gray"><font color="white">Address</font></th>
                            <th width="15%" bgcolor="gray"><font color="white">City</font></th>
                            <th width="15%" bgcolor="gray"><font color="white">Tel</font></th>
                            <th width="10%" bgcolor="gray"><font color="white">Balance</font></th>
                            <th width="1%" bgcolor="gray"><font color="white">A</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Custs();
	$numrows = $c->getCustsRows();
	$recs = $c->getCustsListEx($cn, $ft, $rv, $pg, $limit);
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
                            <td align="center"> 
							<a href="javascript:setCode('<?= rawurlencode($recs[$i]["cust_code"]) ?>','<?= $recs[$i]["cust_name"] ?>','<?= $recs[$i]["cust_addr1"] ?>','<?= $recs[$i]["cust_addr2"] ?>','<?= $recs[$i]["cust_addr3"] ?>','<?= $recs[$i]["cust_city"] ?>','<?= $recs[$i]["cust_state"] ?>','<?= $recs[$i]["cust_zip"] ?>','<?= $recs[$i]["taxrate_pct"] ?>','<?= $recs[$i]["cust_balance"] ?>','<?= $recs[$i]["cust_tel"] ?>');"><?= $recs[$i]["cust_code"] ?></a>
                            </td>
                            <td><?= $recs[$i]["cust_name"] ?></td>
                            <td><?= $recs[$i]["cust_addr1"] ?></td>
                            <td><?= $recs[$i]["cust_city"] ?></td>
                            <td><?= $recs[$i]["cust_tel"] ?></td>
                            <td align="right"><?= $recs[$i]["cust_balance"] ?></td>
                            <td><?= ($recs[$i]["cust_active"]!="f")?"Active":"Inactive" ?></td>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=1" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=$prevpage" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=$nextpage" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=$totalpage" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
