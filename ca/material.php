<?php
	include_once("class/class.formutils.php");
	include_once("class/class.materials.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
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
?>

<html>
<head>
<title>Materials</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
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
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_items.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "a") {
		$_SESSION[$olds] = NULL;
		$c = new Material();
		$numrows = $c->getMaterialRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $cust_code)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds] = $oldrec;
		}
		include("material_add.php");

	} else if ($ty == "e") {
		$_SESSION[$olds] = NULL;
		$c = new Material();
		$numrows = $c->getMaterialRows();
		$d = new Datex();
		if (empty($material_code)) $oldrec = $c->getMaterial();
		else $oldrec = $c->getTextFields($dir, $material_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION[$olds] = $oldrec;
			if (!empty($material_code)) $cnt = 1;
		}
		include("material_edit.php");
	} else if ($ty == "v") {
		$c = new Material();
		$numrows = $c->getMaterialRows();
		$d = new Datex();
		if (empty($material_code)) $oldrec = $c->getFirstMaterial();
		else $oldrec = $c->getTextFields($dir, $material_code);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($material_code)) $cnt = 1;
		}
		include("material_view.php");
	} else  {
		include("material_list.php");
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