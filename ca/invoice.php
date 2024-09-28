<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.invoices.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.customers.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------

	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------
?>
<html>
<head>
<title>Invoices</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

	var custBrowse;
	function openCustBrowse(objname)  {
		if (!custBrowse || custBrowse.closed) {
			custBrowse = window.open("picking_customers_popup.php?objname="+objname, "custBrowseWin", "height=500, width=350");
		} else {
			custBrowse.focus();
		}
		custBrowse.moveTo(100,100);
	}


	function printOpen(code, pr) {
		if (code != "" && pr == "t") {
			var url = 'invoice_print.php?invoice_id='+code;
			var printWin=window.open(url, 'pbml_win','toolbar=yes,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=yes,width=615,height=550');
			printWin.focus();
		}
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
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_sales.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$ua = new UserAuths();
	$c = new Invoices();
	if ($ty == "v") {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "invc_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("invoice_view.php");
		else include("permission.php");
		
	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "invc_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("invoice_list.php");
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
