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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Discount Calculator</title>


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
	var st = parseFloat(s.sale_tax_amt.value);
	var sr = parseFloat(s.sale_freight_amt.value);
	var ss = parseFloat(s.sale_amt.value);
	var fd = parseFloat(f.discount.value);
	s.totalamount.value = formatCurrency(Math.round((st+sr+ss-fd)*100)/100) ;
	s.sale_disc_amt.value = formatCurrency(f.discount.value);
	setClose();
}

function setClose() {
	var s = self.opener.document.forms[0];
	s.sale_disc_amt.focus();
	self.close();
}

function getDiscount() {
	var f = document.forms[0];
	var disc = formatCurrency(parseFloat(f.amt.value)*parseFloat(f.percent.value)/100);
	f.discount.value=disc;
}

function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num)) num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10) cents = "0" + cents;
//	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
//	return (((sign)?'':'-') + '$' + num + '.' + cents);
	return (((sign)?'':'-') + num + '.' + cents);
}

<?php
	if ($close == "t") {
		$value = sprintf("%0.2f", $_SESSION[sale_deposit]["rcpt_amt"]);
		echo "	setCode('$value');";
		echo "	setClose();";
	}
?>
</SCRIPT>
</HEAD>

  <BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
    <table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr align="center"> 
        <td colspan="4"><strong>Discount Caclulator</strong></td>
      </tr>
	  <form method="post">
	  <tr> 
        <td width="10%" align="center">&nbsp;</td>
        <td width="30%" align="right" bgcolor="silver">Total Amount</td>
        <td width="50%"><?= $f->fillTextBoxRO("amt", sprintf("%0.2f", $amt) , 20, 32, "inbox") ?></td>
        <td width="10%">&nbsp;</td>
      </tr>
	  <tr> 
        <td width="10%" align="center">&nbsp;</td>
        <td width="30%" align="right" bgcolor="silver">Percent</td>
        <td width="50%">
			<INPUT TYPE="text" NAME="percent" VALUE="0" CLASS="inbox" onChange="getDiscount()">%
		</td>
        <td width="10%">&nbsp;</td>
      </tr>
	  <tr> 
        <td width="10%" align="center">&nbsp;</td>
        <td width="30%" align="right" bgcolor="silver">Discount</td>
        <td width="50%"><?= $f->fillTextBox("discount", "0.00", 20, 32, "inbox") ?></td>
        <td width="10%">&nbsp;</td>
      </tr>
	  <tr> 
        <td colspan="4" align="center">
		  <INPUT TYPE="button" NAME="process" VALUE="SET" onClick="setCode()">
		  <INPUT TYPE="button" NAME="close" VALUE="Close" onClick="setClose()">
		</td>
      </tr>
	  </form>
    </table>

 </BODY>
</HTML>
