<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.sales.php");
	include_once("class/class.saledtls.php");
	include_once("class/class.customers.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.taxrates.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.userauths.php");
	include_once("class/class.receipt.php");
	include_once("class/class.purchase.php");
	include_once("class/class.tickets.php");
	include_once("class/class.pends.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.lang.php");
	
	$vars = array("ty","code","cmd","dir","code","cust_email","cust_memo","sale_cust_code_old","cust_tax_code");
	foreach ($vars as $v) {
		$$v = "";
	} 
	$vars = array("sale_id","cust_cr_limit","cust_balance","sale_taxrate","rcpt_amt");
	foreach ($vars as $v) {
		$$v = 0;
	} 

	include_once("class/register_globals.php");

//------------------------------------------------------------------------
	$f = new FormUtil();
	if (array_key_exists("sale_cust_code", $_SESSION)) {
		$cust_code = $_SESSION["sale_cust_code"];
	}
//echo $_SESSION[saledtls_edit];
//echo $sale_id;
//echo $ty;
?>
<!doctype html>
<html lang="en">
<head>
<title>Sales</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="cleanair.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

	function updateForm() {
		var f = document.forms[0];
		f.method = 'post';
		f.action='<?= htmlentities($_SERVER['PHP_SELF']) ?>';
		f.submit();
	}

	var histBrowse;
	function openHistoryBrowse(cust) {
		var cust_code = eval("document.forms[0]."+cust+".value");
		if (histBrowse && !histBrowse.closed) histBrowse.close();
		histBrowse = window.open("sales_history_popup.php?cust_code="+cust_code, "histBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=yes,height=600,width=800");
		histBrowse.focus();
		histBrowse.moveTo(100,100);
	}

	var custBrowse;
	function openCustBrowse(objname)  {
		if (custBrowse && !custBrowse.closed) {
			custBrowse.close();
		}
		custBrowse = window.open("sales_customers_popup.php?objname="+objname, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=700");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	function openCustBrowseFilter(objname, filtertext) {
		if (custBrowse && !custBrowse.closed) custBrowse.close();
		//window.alert(objname+filtertext);
		//window.open("sales_customers_popup.php","custBrowseWin");
    custBrowse = window.open("sales_customers_popup.php?objname="+objname+"&ft="+filtertext, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		//custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	var shipBrowse;
	function openShipBrowse(objname)  {
		var f = document.forms[0];
		if (shipBrowse && !shipBrowse.closed) {
			shipBrowse.close();
		}
		shipBrowse = window.open("sales_custships_popup.php?cust_code="+f.sale_cust_code.value+"&objname="+objname, "shipBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		shipBrowse.focus();
		shipBrowse.moveTo(100,100);
	}

	var slsrepBrowse;
	function openSlsRepBrowse(objname)  {
		if (slsrepBrowse && !slsrepBrowse.closed) {
			slsrepBrowse.close();
		}
		slsrepBrowse = window.open("sales_slsreps_popup.php?objname="+objname, "slsrepBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		slsrepBrowse.focus();
		slsrepBrowse.moveTo(100,100);
	}

	var termBrowse;
	function openTermBrowse(objname)  {
		if (termBrowse && !termBrowse.closed) termBrowse.close();
		termBrowse = window.open("sales_term_popup.php?objname="+objname, "termBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		termBrowse.focus();
		termBrowse.moveTo(100,100);
	}

	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
	}

	var proposalBrowse;
	function openProposalBrowse(id)  {
		if (proposalBrowse && !proposalBrowse.closed) proposalBrowse.close();
		proposalBrowse = window.open("sales_proposal_print_popup.php?sale_id="+id, "proposalBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=650,width=650");
		proposalBrowse.focus();
		proposalBrowse.moveTo(10,10);
	}

	function setCursor(field) {
		var f = document.forms[0];
		var fn = "document.forms[0].";
		var fv = eval(fn+field+".focus()");
	}

	var depositEntry;
	function openDepositEntry()  {
		var f = document.forms[0];
		if (f.sale_cust_code.value == "") {
			window.alert("Customer code shouldn't be blank!");
		} else {
			if (depositEntry && !depositEntry.closed) depositEntry.close();
			depositEntry = window.open("sales_deposit_entry_popup.php", "depositEntryWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=150,width=350");
			depositEntry.focus();
			depositEntry.moveTo(30,30);
		}
	}

	var discEntry;
	function discCalculator() {
		var f = document.forms[0];
		var amt = f.sale_amt.value;
		if (discEntry && !discEntry.closed) discEntry.close();
		discEntry = window.open("sales_discount_calculator_popup.php?amt="+amt, "discEntryWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=150,width=350");
		discEntry.focus();
		discEntry.moveTo(30,30);
	}

	function viewTicket(tkt) {
    if (!tkt) tkt = document.forms[0].lasticket.value;
		window.location= "cust_tickets.php?ty=v&tkt_id="+tkt;
	}

//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF">
    <td><?php include("top_menu.php") ?> </td>
  </tr>
  <tr>
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top">
          <td width="110" bgcolor="#CCCCFF">&nbsp;<br> <?php include ("left_sales.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
                <td colspan="3" bgcolor="#CCFFFF" align="right">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$ua = new UserAuths();
	$d = new Datex();
	$tk = new Tickets();


	if ($ty == "a") {
		if (!array_key_exists("olds",$_SESSION) || $_SESSION["olds"]) $_SESSION["olds"]=NULL;
		$c = new Sales();
		$numrows = $c->getSalesRows();
		if ($oldrec = $c->getTextFields($dir, $sale_id)) {
			foreach ($oldrec as $k => $v) if (empty($$k)) $$k = $v;
			$oldrec["cust_email"]=$cust_email;
			$oldrec["cust_memo"]=$cust_memo;
			$_SESSION["olds"] = $oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";

		if ($ua_arr["userauth_allow"]=="t") include("sales_add.php");
		else include("permission.php");
	
	} else if ($ty == "e") {
    	$olds_edit = "olds_".$sale_id;
		if (array_key_exists( $olds_edit, $_SESSION )) {
			if ($_SESSION[$olds_edit]) unset($_SESSION[$olds_edit]);
		}
		$c = new Sales();
		$numrows = $c->getSalesRows();
		if ($oldrec = $c->getTextFields($dir, $sale_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (array_key_exists("cust_email", $_SESSION)) {
				$oldrec["cust_email"]=$_SESSION["cust_email"]; //$cust_email
			} else {
				$oldrec["cust_email"]=""; //$cust_email
			}
			if (array_key_exists("cust_memo", $_SESSION)) {
				$oldrec["cust_memo"]=$_SESSION["cust_memo"]; //$cust_email
			} else {
				$oldrec["cust_memo"]=""; //$cust_email
			}
			$_SESSION[$olds_edit] = $oldrec;
			if (!empty($sale_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("sales_edit.php");
		else include("permission.php");

	} else if ($ty == "c") { // Database edit conflict...
		$c = new Sales();
		$numrows = $c->getSalesRows();
		$sales = $c->getTextFields($dir, $sale_id);
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("sales_conflict.php");
		else include("permission.php");

	} else if ($ty == "v") {
		$c = new Sales();
		$numrows = $c->getSalesRows();
		$oldrec = $c->getTextFields($dir, $sale_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($sale_id)) $cnt = 1;
		}
		$v = new Custs();
		$varr = $v->getCusts($sale_cust_code);

		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("sales_view.php");
		else include("permission.php");

	} else if ($ty == "r") { // to find error
		$c = new Sales();
		$v = new Custs();
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("sales_error.php");
		else include("permission.php");

    } else  {
		$c = new Sales();
		$v = new Custs();
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("sales_list.php");
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
<?php 
//print_r($_SESSION); 
?>
</body>
</html>
