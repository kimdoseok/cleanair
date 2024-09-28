<?php
	include_once("class/class.formutils.php");
	include_once("class/class.navigates.php");

	$f = new FormUtil();

//------------------------------------------------------------------------
	
include_once("class/map.label.php");
//------------------------------------------------------------------------
include_once("class/map.lang.php");
include_once("class/register_globals.php");
//-----------------------------------------------------------------------


	foreach (array('_GET', '_POST', '_COOKIE', '_SERVER') as $_SG) {
		foreach ($$_SG as $_SGK => $_SGV) {
			$$_SGK = $_SGV;
		}
	}


if (!empty($_SESSION[sale_deposit])) {
	$rcpt_amt = $_SESSION[sale_deposit]["rcpt_amt"];
	$rcpt_type = $_SESSION[sale_deposit]["rcpt_type"];
	$rcpt_check_no = $_SESSION[sale_deposit]["rcpt_check_no"];
	$rcpt_comment = $_SESSION[sale_deposit][rcpt_comment];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Deposit Entry</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">

<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">

<SCRIPT LANGUAGE="JavaScript">

function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num)) num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10) cents = "0" + cents;
	//for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
//	return (((sign)?'':'-') + '$' + num + '.' + cents);
	return (((sign)?'':'-') + num + '.' + cents);
}

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
	var s = self.opener.document.forms[0];
	var f = document.forms[0];
	var deposit = parseFloat(value);
	var discount = parseFloat(s.sale_disc_amt.value);
	var tax = parseFloat(s.sale_tax_amt.value);
	var subtotal = parseFloat(s.sale_amt.value);
	var freight = parseFloat(s.sale_freight_amt.value);
	s.sale_deposit_amt.value = formatCurrency(value);
	s.totalamount.value = formatCurrency(Math.round((subtotal+tax+freight-discount-deposit)*100)/100);
	setClose();
}

function setClose() {
	var s = self.opener.document.forms[0];
	s.sale_deposit_amt.focus();
	self.close();
}

<?php
	if ($close == "t") {
		$value = sprintf("%0.2f", $_SESSION[sale_deposit]["rcpt_amt"]);
		echo "	setCode('$value');\n";
		echo "	setClose();\n";
	}
?>
</SCRIPT>
</HEAD>

  <BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
    <table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr align="center"> 
        <td colspan="4"><strong>Deposit Entry</strong></td>
      </tr>
	  <form method="post" action="sales_proc.php">
		<?= $f->fillHidden("cmd", "sale_deposit") ?>
	  <tr> 
        <td width="10%" align="center">&nbsp;</td>
        <td width="30%" align="right" bgcolor="silver">Amount</td>
        <td width="50%"><?= $f->fillTextBox("rcpt_amt", sprintf("%0.2f",$rcpt_amt) , 20, 32, "inbox") ?></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr> 
        <td width="10%" align="center">&nbsp;</td>
        <td width="30%" align="right" bgcolor="silver">Pay Type:</td>
        <td width="50%">
		  <SELECT NAME="rcpt_type">
			<OPTION VALUE="ch" <?= ($rcpt_type=="ch")?"SELECTED":"" ?>>Check
		    <OPTION VALUE="ca" <?= ($rcpt_type=="ca")?"SELECTED":"" ?>>Cash
			<OPTION VALUE="cc" <?= ($rcpt_type=="cc")?"SELECTED":"" ?>>Credit Card
			<OPTION VALUE="dc" <?= ($rcpt_type=="dc")?"SELECTED":"" ?>>Discount Apply
			<OPTION VALUE="bc" <?= ($rcpt_type=="bc")?"SELECTED":"" ?>>Bounced Check
			<OPTION VALUE="ot" <?= ($rcpt_type=="ot")?"SELECTED":"" ?>>Other
		  </SELECT>
		</td>
        <td width="10%">&nbsp;</td>
      </tr>
	  <tr> 
        <td width="10%" align="center">&nbsp;</td>
        <td width="30%" align="right" bgcolor="silver">Check#</td>
        <td width="50%"><?= $f->fillTextBox("rcpt_check_no", $rcpt_check_no, 20, 32, "inbox") ?></td>
        <td width="10%">&nbsp;</td>
      </tr>
      <tr> 
        <td width="10%" align="center">&nbsp;</td>
        <td width="30%" align="right" bgcolor="silver">Comment:</td>
        <td width="50%"><?= $f->fillTextBox("rcpt_comment", $rcpt_comment, 32, 250, "inbox") ?></td>
        <td width="10%">&nbsp;</td>
      </tr>
	  <tr> 
        <td colspan="4" align="center">
		  <INPUT TYPE="submit" NAME="process" VALUE="Save">
		  <INPUT TYPE="button" NAME="close" VALUE="Close" onClick="setClose()">
		</td>
      </tr>
	  </form>
    </table>

 </BODY>
</HTML>
