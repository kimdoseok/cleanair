<?php
	include_once("class/class.formutils.php");
	include_once("class/class.tickets.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");
//------------------------------------------------------------------------
	include_once("class/map.default.php");
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");

	$vars = array("ty","oo","fo","ft","page","dir");
	foreach ($vars as $var) {
	  $$var = "";
	}
	$vars = array("tkt_id");
	foreach ($vars as $var) {
	  $$var = 0;
	}
  
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------


$olds = $default["comp_code"]."_olds";
?>

<html>
<head>
<title>Tickets</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

	var custBrowse;
	function openCustBrowse(objname)  {
		if (custBrowse && !custBrowse.closed) {
			custBrowse.close();
		}
		custBrowse = window.open("customers_popup.php?objname="+objname, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	var saleBrowse;
	function openSalesBrowse(objname)  {
		if (saleBrowse && !saleBrowse.closed) {
			saleBrowse.close();
		}
		saleBrowse = window.open("sales_popup.php?objname="+objname, "saleBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=550,width=650");
		saleBrowse.focus();
		saleBrowse.moveTo(100,100);
	}

//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td><?php include("top_menu.php"); ?></td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_crm.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$c = new Tickets();
	$d = new Datex();
	$ua = new UserAuths();
	if ($ty == "a") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$numrows = $c->getTicketsRows();
		if ($oldrec = $c->getTextFields($dir, $tkt_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds]=$oldrec;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "tickets_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cust_tickets_add.php");
		else include("permission.php");

	} else if ($ty == "e") {
		if ($_SESSION[$olds]) $_SESSION[$olds]=NULL;
		$numrows = $c->getTicketsRows();
		if (empty($tkt_id)) $oldrec = $c->getFirstTickets();
		else $oldrec = $c->getTextFields($dir, $tkt_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds]=$oldrec;
			if (!empty($tkt_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "tickets_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cust_tickets_edit.php");
		else include("permission.php");
		
	} else if ($ty == "v") {
		$numrows = $c->getTicketsRows();
		if (empty($tkt_id)) $oldrec = $c->getFirstTickets();
		else $oldrec = $c->getTextFields($dir, $tkt_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($tkt_id)) $cnt = 1;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "tickets_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cust_tickets_view.php");
		else include("permission.php");
	
	} else  {
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "tickets_list");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("cust_tickets_list.php");
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