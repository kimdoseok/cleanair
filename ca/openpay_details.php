<?php
	include_once("class/class.formutils.php");
	include_once("class/class.rcptdtls.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.rcptdtls.php");
	include_once("class/class.receipt.php");
	include_once("class/class.customers.php");
	include_once("class/class.openpay.php");
	include_once("class/class.accounts.php");

	include_once("class/map.label.php");
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------
$f = new FormUtil();
$c = new Receipt();
	$d = new RcptDtls();

	$rec = $c->getReceipt($rcpt_id);
	if (!empty($rec)) foreach($rec as $k => $v) $$k = $v; 

//	for ($i=0;$i<count($recs);$i++) $applied += $recs[$i]["rcptdtl_amt"];
	$applied = 0;
	$rcptdtl_disc_amt = 0;
	$rcptdtl_op_amt = 0;
	$rcptdtl_cm_amt = 0;
	$rcptdtl_aw_amt = 0;
	for ($i=0;$i<count($recs);$i++) {
		if ($recs[$i]["rcptdtl_type"]=="ar") $applied += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="op") $rcptdtl_op_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $recs[$i]["rcptdtl_amt"];
		else $applied += $recs[$i]["rcptdtl_amt"];
	}
	$remained = $rcpt_amt - $applied;


?>
<html>
<head>
<title>Open Payment Detail</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	var acctBrowse;
	function openAcctBrowse(objname)  {
		if (acctBrowse && !acctBrowse.closed) acctBrowse.close();
		acctBrowse = window.open("rcptdtl_accts_popup.php?objname="+objname, "acctBrowseWin", "menubar=no,resizable=yes,scrollbars=auto,height=500,width=450");
		acctBrowse.focus();
		acctBrowse.moveTo(100,100);
	}

	var pickBrowse;
	function openPickBrowse(objname)  {
		if (pickBrowse && !pickBrowse.closed) pickBrowse.close();
		pickBrowse = window.open("rcptdtl_picking_popup.php?remained=<?= $remained ?>&cust_code=<?= $cust_code ?>&objname="+objname, "pickBrowseWin", "menubar=no,resizable=yes,scrollbars=auto,height=500,width=450");
		pickBrowse.focus();
		pickBrowse.moveTo(100,100);
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
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<?php
	$c = new RcptDtls();
	$o = new OpenPay();
	$op_arr = $o->getOpenPay($rcpt_id);
	if ($op_arr) foreach ($op_arr as $k=>$v) $$k=$v;

	if ($ty == "e") {
		if ($_SESSION["olds"]) $_SESSION["olds"]=NULL;
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $rcptdtl_id)) {
			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
			if (!empty($rcptdtl_id)) $cnt = 1;
		}

		include("openpay_detail_edit.php");

	} else if ($ty == "a") {
		if ($_SESSION["olds"]) $_SESSION["olds"]=NULL;
		$d = new Datex();
		if ($oldrec = $c->getTextFields($dir, $rcptdtl_id)) {
//			foreach ($oldrec as $k => $v) $$k = $v;
			$_SESSION["olds"] = $oldrec;
		}
		include("openpay_detail_add.php");
	} else {
		echo "&nbsp;";
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
