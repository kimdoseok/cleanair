<?php
	include_once("class/class.formutils.php");
	include_once("class/class.items.php");
	include_once("class/class.itemunits.php");
	include_once("class/class.category.php");
	include_once("class/class.unit.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.userauths.php");
 	include_once("class/class.vendors.php");
	include_once("class/class.materials.php");
	include_once("class/class.productlines.php");
  //include_once("class/class.dbconfig.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/map.default.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------

$olds = $default["comp_code"]."_olds";


$dbcfg = new DbConfig();
$conx = odbc_pconnect($dbcfg->dbname,$dbcfg->username,$dbcfg->password);
odbc_exec($conx, "use ".$dbcfg->dbname);

$cols = 4;
$rows = 5;
$width = 680;

?>
<html>
<head>
<title>Item Catalog</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">

</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td><?php include("top_menu.php") ?></td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_items.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "s") { // selected items
		include("lst_itemselected.php");
	} else if ($ty == "p") { // view pdf
		include("pdf_itemcatalog.php");
	} else if ($ty == "v") { // view catalog
		include("view_itemcatalog.php");
	} else  {
		include("lst_itemcatalog.php");
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
