<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.customers.php");
	include_once("class/class.taxrates.php");
	include_once("class/class.datex.php");
	include_once("class/class.receipt.php");
	include_once("class/class.tickets.php");

	$f = new FormUtil();
	$d = new Datex();

	$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
					1=>array("value"=>"name", "name"=>"Name"),
					2=>array("value"=>"addr", "name"=>"Address"),
					3=>array("value"=>"city", "name"=>"City"),
					4=>array("value"=>"tel", "name"=>"Tel"),
					5=>array("value"=>"points", "name"=>"Points")
	);

//----------------------------------------------------------------------	
include_once("class/map.label.php");
//----------------------------------------------------------------------
include_once("class/map.lang.php");

$vars = array("ft","rv");
foreach ($vars as $v) {
	$$v = "";
} 
$vars = array("pg","cn");
foreach ($vars as $v) {
	$$v = 0;
} 

include_once("class/register_globals.php");
//----------------------------------------------------------------------

	$tk = new Tickets();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title><?= $label[$lang]["Popup_Customer_Codes"] ?></title>


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

function setCode(value, name, addr1, addr2, addr3, city, state, zip, taxrate, bal, wday, tel, cell, rep, svia, rdate, ramt, term, memo, email,lasticket) {
	var f = document.forms[0];

	var s = self.opener.document.forms[0];
	self.opener.document.forms[0].<?= $objname ?>.value = value;
	self.opener.document.forms[0].sale_name.value = name;
	self.opener.document.forms[0].sale_addr1.value = addr1;
	self.opener.document.forms[0].sale_addr2.value = addr2;
	
	self.opener.document.forms[0].sale_addr3.value = addr3;
	
	self.opener.document.forms[0].sale_city.value = city;
	
	self.opener.document.forms[0].sale_state.value = state;
	
	self.opener.document.forms[0].sale_zip.value = zip;
	self.opener.document.forms[0].sale_taxrate.value = taxrate;
	self.opener.document.forms[0].sale_prom_date.value = wday;
	self.opener.document.forms[0].sale_tel.value = tel;
	self.opener.document.forms[0].sale_cell.value = cell;
	self.opener.document.forms[0].sale_slsrep.value = rep;
	self.opener.document.forms[0].sale_term.value = term;
	self.opener.document.forms[0].sale_shipvia.value = svia;
	self.opener.document.forms[0].cust_balance.value = bal;
	self.opener.document.forms[0].last_payment.value = ramt;
	self.opener.document.forms[0].last_payday.value = rdate;
	self.opener.document.forms[0].cust_memo.value = memo;
	self.opener.document.forms[0].cust_email.value = email;
	if (lasticket>0) self.opener.document.forms[0].checkticket.disabled=false;
	else self.opener.document.forms[0].checkticket.disabled=true;
	self.opener.document.forms[0].lasticket.value = lasticket;
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
                            <th width="27%" bgcolor="gray"><font color="white"><?= $label[$lang]["Name"] ?></font></th>
                            <th width="30%" bgcolor="gray"><font color="white">Address</font></th>
                            <th width="15%" bgcolor="gray"><font color="white">City</font></th>
                            <th width="15%" bgcolor="gray"><font color="white">Tel</font></th>
                            <th width="3%" bgcolor="gray"><font color="white">P</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new Custs();
	$c->active = "t";
	$numrows = $c->getCustsRows($cn,$ft);
	$recs = $c->getCustsList($cn, $ft, $rv, $pg, $limit);

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
	$x = new TaxRates();
	$ca = new Receipt();
	$ca_defaults = array("rcpt_amt"=>0,"rcpt_disc_amt"=>0);
	$tday = date("m/d/Y");
	for ($i=0; $i<$numrecs; $i++) {		
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
		
		$xarr = $x->getTaxRates($recs[$i]["cust_tax_code"]);
		$wday = $d->nextWeekDay($recs[$i]["cust_delv_week"], $tday);
		$ca_arr = $ca->getReceiptLast($recs[$i]["cust_code"]);
		$ca_arr = array_merge($ca_defaults, $ca_arr);
		$ramt = number_format($ca_arr["rcpt_amt"],2,".",",")."(".$ca_arr["rcpt_disc_amt"].")";
		$tkt_arr = $tk->getTicketsCust($recs[$i]["cust_code"]);
		
		if ($tkt_arr) {
			$numarr = count($tkt_arr);
			$lasticket = $tkt_arr[$numarr-1]["tkt_id"];
      //print_r($tkt_arr);
		} else {
			$lasticket = 0;
		}
//echo $lasticket;
//print_r($recs);

		settype($recs[$i]["cust_balance"],"float");

?>
                            <td align="center"> 
							<a href="javascript:setCode('<?= addslashes($recs[$i]["cust_code"] ?? "") ?>','<?= addslashes($recs[$i]["cust_name"] ?? "") ?>','<?= addslashes($recs[$i]["cust_addr1"] ?? "") ?>','<?= addslashes($recs[$i]["cust_addr2"] ?? "") ?>','<?= addslashes($recs[$i]["cust_addr3"] ?? "") ?>','<?= addslashes($recs[$i]["cust_city"] ?? "") ?>','<?= addslashes($recs[$i]["cust_state"] ?? "") ?>','<?= addslashes($recs[$i]["cust_zip"] ?? "") ?>','<?= addslashes($xarr["taxrate_pct"] ?? "") ?>','<?= addslashes($recs[$i]["cust_balance"] ?? "") ?>','<?= addslashes($wday ?? "") ?>','<?= addslashes($recs[$i]["cust_tel"] ?? "") ?>','<?= addslashes($recs[$i]["cust_cell"] ?? "") ?>','<?= addslashes($recs[$i]["cust_slsrep"] ?? "") ?>','<?= addslashes($recs[$i]["cust_shipvia"] ?? "") ?>','<?= addslashes($ca_arr["rcpt_date"] ?? "") ?>','<?= addslashes($ramt) ?>','<?= addslashes($recs[$i]["cust_term"] ?? "") ?>','<?= addslashes($recs[$i]["cust_memo"] ?? "") ?>','<?= addslashes($recs[$i]["cust_email"] ?? "") ?>', <?= $lasticket ?>);"><?= $recs[$i]["cust_code"] ?></a></td>
                            <td><?= $recs[$i]["cust_name"] ?>
<?php
	if ($lasticket>0) echo "<super><font color='red'>*$lasticket*</font></super>";
?>
							</td>
                            <td><?= $recs[$i]["cust_addr1"] ?></td>
                            <td><?= $recs[$i]["cust_city"] ?></td>
                            <td><?= $recs[$i]["cust_tel"] ?></td>
                            <td align="right"><?= $recs[$i]["cust_points"] ?></td>
                            <!--<td><?= ($recs[$i]["cust_active"]!="t")?"X":"&nbsp;" ?></td>-->
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
