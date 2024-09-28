<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.invoices.php");
	include_once("class/class.picks.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.customers.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.taxrates.php");
	include_once("class/class.userauths.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include("class/map.lang.php");

	$vars = array("ty","cmd","cn","dir","page","rv");
	foreach ($vars as $var) {
		$$var = "";
	} 

	include_once("class/register_globals.php");

//-----------------------------------------------------------------------

?>
<html>
<head>
<title>Picking Tickets</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

	var custBrowse;
	function openCustBrowse(objname)  {
		if (!custBrowse || custBrowse.closed) {
			custBrowse = window.open("picking_customers_popup.php?objname="+objname, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=yes,height=500,width=650");
		} else {
			custBrowse.focus();
		}
		custBrowse.moveTo(100,100);
	}

	function openCustBrowseFilter(objname, filtertext) {
		if (custBrowse && !custBrowse.closed) custBrowse.close();
		var url = "picking_customers_popup.php?objname="+objname+"&ft="+filtertext;
		custBrowse = window.open(url, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	function printOpen(code, pr) {
		if (code != "" && pr == "t") {
			var url = 'picking_print.php?pick_id='+code;
			var printWin=window.open(url, 'pbml_win','toolbar=yes,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=yes,width=615,height=550');
			printWin.focus();
		}
	}

	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=yes,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
	}

	function calcTotal() {
		var f = document.forms[0];
		var t = parseFloat(f.pick_tax_amt.value);
		var r = parseFloat(f.pick_freight_amt.value);
		var s = parseFloat(f.pick_amt.value);
		var d = parseFloat(f.pick_deposit_amt.value);
		f.totalamount.value = Math.round((t+r+s-d)*100)/100 ;
	}

	function calcTax() {
		var f = document.forms[0];
		var x = parseFloat(f.taxtotal.value);
		var a = parseFloat(f.pick_taxrate.value);
		f.pick_tax_amt.value = Math.round(x*a)/100;
		var t = parseFloat(f.pick_tax_amt.value);
		calcTotal();
	}

	function makeInvoice() {
		if (window.confirm("Are you SURE to make an invoice with this picking ticket?")) {
			//var f = document.forms[0];
			//f.pick_id.value = '<?= $pick_id ?>';
			//f.cmd.value= 'pick_make_invoice';
			//f.action = 'picking_proc.php';
			//f.method = 'post';
			//f.submit();
			document.location='picking_proc.php?cmd=pick_make_invoice&ty=e&pick_id=<?= $pick_id ?>';
		}
	}

	var invoiceBrowse;
	function openInvoiceBrowse(id)  {
		if (invoiceBrowse && !invoiceBrowse.closed) invoiceBrowse.close();
		invoiceBrowse = window.open("invoice_proposal_print_popup.php?pick_id="+id, "invoiceBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=650,width=650");
		invoiceBrowse.focus();
		invoiceBrowse.moveTo(10,10);
	}


//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td>
	 <?php include("top_menu.php")  ?>
	</td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_sales.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$c = new Picks();
	$d = new Datex();
	$ua = new UserAuths();
	if ($ty == "a") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$numrows = $c->getPicksRows();
		if ($oldrec = $c->getTextFields($dir, $pick_id)) {
//			foreach ($oldrec as $k => $v) if (empty($$k)) $$k = $v;
			$olds = $oldrec;
      $_SESSION["olds"]=$olds;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "pick_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("picking_add.php");
		else include("permission.php");
		
	} else if ($ty == "e") {
		if (isset($_SESSION["olds"])) unset($_SESSION["olds"]);
		$numrows = $c->getPicksRows();
		if ($oldrec = $c->getTextFields($dir, $pick_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = $oldrec;
      $_SESSION["olds"]=$olds;
			if (!empty($pick_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "pick_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("picking_edit.php");
		else include("permission.php");
		
	} else if ($ty == "v") {
		$numrows = $c->getPicksRows();
		$oldrec = $c->getTextFields($dir, $pick_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($pick_id)) $cnt = 1;
		}
		$v = new Custs();
		$varr = $v->getCusts($pick_cust_code);
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "pick_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("picking_view.php");
		else include("permission.php");

	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "pick_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("picking_list.php");
		else include("permission.php");
		
	}
?>			
                  </td>
                <td width="10">&nbsp;</td>
              </tr>
            </table></td>
          <td width="10" bgcolor="#CCCCFF">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr bgcolor="#6666FF"> 
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
