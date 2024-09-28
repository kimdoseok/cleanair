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
	include_once("class/class.pends.php");
	include_once("class/class.pendtls.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/register_globals.php");
//------------------------------------------------------------------------

	$f = new FormUtil();
	$cust_code = $pend_cust_code;
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
//-----------------------------------------------------------------------
//echo $_SESSION[saledtls_edit];

?>
<html>
<head>
<title>Pending Sales</title>
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
		custBrowse = window.open("sales_customers_popup.php?objname="+objname, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	var shipBrowse;
	function openShipBrowse(objname)  {
		var f = document.forms[0];
		if (shipBrowse && !shipBrowse.closed) {
			shipBrowse.close();
		}
		shipBrowse = window.open("sales_custships_popup.php?cust_code="+f.pend_cust_code.value+"&objname="+objname, "shipBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		shipBrowse.focus();
		shipBrowse.moveTo(100,100);
	}

	function openCustBrowseFilter(objname, filtertext) {
		if (custBrowse && !custBrowse.closed) custBrowse.close();
		var url = "sales_customers_popup.php?objname="+objname+"&ft="+filtertext;
		custBrowse = window.open(url, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
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
		proposalBrowse = window.open("sales_proposal_print_popup.php?pend_id="+id, "proposalBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=650,width=650");
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
		if (f.pend_cust_code.value == "") {
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
		var amt = f.pend_amt.value;
		if (discEntry && !discEntry.closed) discEntry.close();
		discEntry = window.open("sales_discount_calculator_popup.php?amt="+amt, "discEntryWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=150,width=350");
		discEntry.focus();
		discEntry.moveTo(30,30);
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
	$c = new Custs();
	$p = new Pends();
	if ($ty == "a") {
		if ($_SESSION["olds"]) $_SESSION["olds"]=NULL;
		$numrows = $p->getPendsRows();
		if ($oldrec = $p->getTextFields($dir, $pend_id)) {
			foreach ($oldrec as $k => $v) if (empty($$k)) $$k = $v;
			$_SESSION["olds"] = $oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("pend_add.php");
		else include("permission.php");
	
	} else if ($ty == "e") {
		if ($_SESSION["olds"]) $_SESSION["olds"]=NULL;
		$numrows = $p->getPendsRows();
		if ($oldrec = $p->getTextFields($dir, $pend_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
			if (!empty($pend_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("pend_edit.php");
		else include("permission.php");

	} else if ($ty == "c") { // Database edit conflict...
		$numrows = $p->getPendsRows();
		$sales = $p->getTextFields($dir, $pend_id);
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("sales_conflict.php");
		else include("permission.php");

	} else if ($ty == "v") {
		$numrows = $p->getPendsRows();
		$oldrec = $p->getTextFields($dir, $pend_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($pend_id)) $cnt = 1;
		}
		$varr = $c->getCusts($pend_cust_code);

		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("pend_view.php");
		else include("permission.php");

	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "sale_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("pend_list.php");
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
