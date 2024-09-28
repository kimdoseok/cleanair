<?php
	include_once("class/class.formutils.php");
	include_once("class/class.navigates.php");

	$f = new FormUtil();

	$selbox = array(0=>array("value"=>"code", "name"=>"Sales Number"),
					1=>array("value"=>"date", "name"=>"Sales Date")
	);

//------------------------------------------------------------------------
	
include_once("class/map.label.php");
//------------------------------------------------------------------------
include_once("class/map.lang.php");
include_once("class/register_globals.php");
//-----------------------------------------------------------------------


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Sales Batch Process</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">

<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">

<SCRIPT LANGUAGE="JavaScript">

	var clicked = false;

	function setFilter() {
		if (clicked==true) return;
		clicked = true;

		var f = document.forms[0];
		f.method = "GET" ;
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}

	function updateForm(page) {
		if (clicked==true) return;
		clicked = true;

		document.forms[0].pg.value = page;
		document.forms[0].method="POST";
		document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
		document.forms[0].submit();
	}

	function setCode(value) {
		var f = document.forms[0];

		var s = self.opener.document.forms[0];
		self.opener.document.forms[0].<?= $objname ?>.value = value;
		setClose();
	}

	function setClose() {
		self.opener.document.form1.submit();;
		self.opener.document.form1.<?= $objname ?>.focus();
		self.close();
	}

<?php
	if ($close == "t") {
		echo "	setClose()";
	}
?>
</SCRIPT>
</HEAD>

  <BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
    <table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr align="center"> 
        <td colspan="4"><strong>Sales Batch</strong></td>
      </tr>
	  <form method="post" action="sales_proc.php">
	  <tr> 
        <td width="5%" align="center">&nbsp;</td>
        <td width="30%" align="right" bgcolor="silver">From</td>
        <td width="60%"><?= $f->fillTextBox("from",$from,16) ?></td>
        <td width="5%">&nbsp;</td>
      </tr>
	  <tr>
        <td>&nbsp;</td>
        <td align="right" bgcolor="silver">To</td>
        <td><?= $f->fillTextBox("to",$to,16) ?></td>
        <td>&nbsp;</td>
      </tr>
	  <tr> 
        <td>&nbsp;</td>
        <td align="right" bgcolor="silver">Type</td>
        <td><?= $f->fillSelectBox($selbox,"codetype", "value", "name", $codetype) ?></td>
        <td>&nbsp;</td>
      </tr>
	  <tr> 
        <td>&nbsp;</td>
        <td align="right" bgcolor="silver">&nbsp;</td>
        <td>
		  <INPUT TYPE="radio" NAME="worktype" VALUE="print" checked>Print
		  <INPUT TYPE="radio" NAME="worktype" VALUE="pick">Picking Ticket
		</td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td colspan="4" align="center">
		  <?= $f->fillHidden("objname",$objname) ?>
		  <?= $f->fillHidden("cmd","sale_batch") ?>
		  <INPUT TYPE="submit" NAME="process" VALUE="Process">
		  <INPUT TYPE="button" NAME="close" VALUE="Close" onClick="setClose()">
		</td>
      </tr>
	  </form>
    </table>

 </BODY>
</HTML>
