<?php
	include_once("class/class.formutils.php");
	include_once("class/class.customers.php");
	
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.custships.php");
	include_once("class/class.userauths.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");

	$vars = array("custship_id","custship_name","custship_addr1","custship_addr2",
	"custship_addr3","custship_city","custship_state","custship_zip","custship_tel",
	"custship_fax","custship_delv_week","custship_shipvia");
	foreach ($vars as $v) {
		$$v = "";
	} 

	include_once("class/register_globals.php");
//-----------------------------------------------------------------------


?>
<html>
<head>
<title>Shipping Address</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	function setFilter() {
		var f = document.forms[0];
		f.method = "GET" ;
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
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
   <td>
	<table width="800" border="0" cellspacing="0" cellpadding="0">
	 <tr valign="top">
	  <td width="110" bgcolor="#CCCCFF">
	    <?php include ("left_sales.php") ?> 
	  </td>
	  <td width="681" align="center">
	   <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
         <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
		</tr>
		<tr>
		 <td width="10">&nbsp;</td>
		 <td valign="top">
<?php

	$c = new CustShips();

	$d = new Datex();

	$ua = new UserAuths();


	if ($ty == "e") {

		$olds = $c->getCustShips($custship_id, $cust_code);
		if ($olds) {
			foreach ($olds as $k => $v) $$k = $v;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_edit");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("custships_edit.php");
		else include("permission.php");

	} else if ($ty == "v") {
		$olds = $c->getCustShips($custship_id, $cust_code);
		if ($olds) {
			foreach ($olds as $k => $v) $$k = $v;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_view");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("custships_view.php");
		else include("permission.php");

	} else {
		$olds = $c->getCustShips($custship_id, $cust_code);
		if ($olds) {
			foreach ($olds as $k => $v) $$k = $v;
		}
		$ua_arr = $ua->getUserAuthsTwoCode($_SERVER["PHP_AUTH_USER"], "cust_add");
		if ($_SERVER["PHP_AUTH_USER"]=="admin") $ua_arr["userauth_allow"]="t";
		if ($ua_arr["userauth_allow"]=="t") include("custships_add.php");
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
