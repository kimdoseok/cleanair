<?php
	include_once("class/map.label.php");
	include_once("class/map.lang.php");
	include_once("class/class.users.php");
	include_once("class/register_globals.php");
  include_once("class/class.formutils.php");
  $f = new FormUtil();
  $u = new Users();
  
if (!isset($_SERVER['PHP_AUTH_USER'])) {
  header('WWW-Authenticate: Basic realm="My Realm"');
  header('HTTP/1.0 401 Unauthorized');
  echo "You must enter a valid login ID and password to access this resource\n";
  exit;
} else {
  if (!$u->checkUser($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
	  header('HTTP/1.0 401 Unauthorized');
	  echo "You must enter a valid login ID and password to access this resource\n";
	  exit;
  }
}
/*
*/

//echo $_SERVER['PHP_AUTH_USER'];
//echo $_SERVER['PHP_AUTH_PW'];
?>
<html>
<head>
<title><?= $label[$lang]["Sales"] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td><?php include("top_menu.php") ?> </td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <table width="120" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
            </table></td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="2" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td width="10">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td bgcolor="silver" colspan="2"><p><strong><font face="Arial, Helvetica, sans-serif"><?= $label[$lang]["Sales"] ?></font></strong></p></td>
                      <td width="10">&nbsp;</td>
                      <td bgcolor="silver" colspan="2"><strong><font face="Arial, Helvetica, sans-serif"><?= $label[$lang]["Inventory"] ?></font></strong></td>
                    </tr>
                    <tr valign="top"> 
                      <td width="20">&nbsp;</td>
                      <td><?php include ("left_sales.php") ?></td>
                      <td width="10">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td><?php include ("left_items.php") ?></td>
                    </tr>
                    <tr> 
                      <td bgcolor="silver" colspan="2"><font face="Arial, Helvetica, sans-serif"><strong><?= $label[$lang]["Purchases"] ?></strong></font></td>
                      <td><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
                      <td bgcolor="silver" colspan="2"><font face="Arial, Helvetica, sans-serif"><strong><?= $label[$lang]["General_Ledger"] ?></strong></font></td>
                    </tr>
                    <tr valign="top"> 
                      <td width="20">&nbsp;</td>
                      <td><?php include ("left_purchases.php") ?></td>
                      <td width="10">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td><?php include ("left_generalledgers.php") ?></td>
                    </tr>
                    <tr> 
                      <td bgcolor="silver" colspan="2"><font face="Arial, Helvetica, sans-serif"><strong>System</strong></font></td>
                      <td><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
                      <td bgcolor="silver" colspan="2"><font face="Arial, Helvetica, sans-serif"><strong> CRM</strong></font></td>
                    </tr>
                    <tr valign="top"> 
                      <td width="20">&nbsp;</td>
                      <td><?php include ("left_systems.php") ?></td>
                      <td width="10">&nbsp;</td>
                      <td width="20">&nbsp;</td>
                      <td><?php include ("left_crm.php") ?></td>
                    </tr>
                  </table>
                </td>
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
