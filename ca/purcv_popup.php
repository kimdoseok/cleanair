<?php
	include_once("class/class.formutils.php");
	include_once("class/class.purcvds.php");
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

?>

<html>
<head>
<title>Received Purchase</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
	}
-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
if ($purcv_purdtl_id) {
	if ($ty == "a") {
		$_SESSION["olds"] = NULL;
		$c = new Purcvds();
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $purcv_purdtl_id, $purcv_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
		}
		include("purcv_add.php");

	} else if ($ty == "e") {
		$_SESSION["olds"] = NULL;
		$c = new Purcvds();
		$d = new Datex();
		if (!empty($purcv_id)) $oldrec = $c->getPurcvds($purcv_id);
		else $oldrec = $c->getTextFields($dir, $purcv_purdtl_id, $purcv_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
			if (!empty($unit_code)) $cnt = 1;
		}
		include("purcv_edit.php");
	} else if ($ty == "v") {
		$c = new Purcvds();
		$d = new Datex();
		if (!empty($purcv_id)) $oldrec = $c->getPurcvds($purcv_id);
		else $oldrec = $c->getTextFields($dir, $purcv_purdtl_id);
		if ($oldrec) {
			foreach ($oldrec as $k => $v) $$k = $v;
			if (!empty($purcv_id)) $cnt = 1;
		}
		include("purcv_view.php");
	} else  {
		include("purcv_list.php");
	}
}
?>			
<p>&nbsp;</p>
</body>
</html>