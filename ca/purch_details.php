<?php
	include_once("class/class.formutils.php");
	include_once("class/class.purdtls.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------

	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------

if (!empty($purdtl_po_no)) 
$po_no = $purdtl_po_no;
?>
<html>
<head>
<title><?= $label[$lang][Purchase_Detail] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">

<!--
	
	function firstWork() {

<?php
	
	if ($ty == "a") echo "document.forms[0].purdtl_po_no.focus();";
	else if ($ty == "e") echo "document.forms[0].purdtl_po_no.select();";

?>
	}
//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="firstWork()">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td><?php include("top_menu.php") ?> </td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_purchases.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	if ($ty == "a") {
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new PurDtls();
		$numrows = $c->getPurDtlsRows();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $purdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = base64_encode(serialize($oldrec));
			$_SESSION["olds"] = $olds;
		}
		include("purch_detail_add.php");
	} else if ($ty == "e") {
		if (session_is_registered("olds")) session_unregister("olds");
		$c = new PurDtls();
		$numrows = $c->getPurDtlsRows($purch_id);
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $purdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$olds = base64_encode(serialize($oldrec));
			$_SESSION["olds"] = $olds;
			if (!empty($purdtl_id)) $cnt = 1;
		}

		include("purch_detail_edit.php");
	} else if ($ty == "v") {
		$c = new PurDtls();
		$numrows = $c->getPurDtlsRows();
		$d = new Datex();
		$oldrec = $c->getTextFields($dir, $purdtl_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($purdtl_id)) $cnt = 1;
		}
		include("purch_detail_view.php");
	} else  {
		include("purch_detail_list.php");
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
