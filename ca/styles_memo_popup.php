<?php
include_once("../fns/everypage.php");
include_once("../fns/formutil.php");
include_once("../fns/class.cust_memo.php");
include_once("../fns/class.sessions.php");
include_once("class/register_globals.php");


$code = $cust_memo_id;
if (!empty($_GET[customer_id])) $cust_memo_cust_id = $_GET[customer_id];
$cm = new CustMemo();
$oldrec = $cm->getTextFields($direction, $code, $cust_memo_cust_id);
if (!empty($oldrec)) foreach ($oldrec as $k => $v) if(empty($$k)) $$k = $v;
$olds = base64_encode(serialize($oldrec));
if (!empty($code)) $cnt = 1;
echo $cust_memo_cust_id.">>>>>>";


$options = "onFocus=\"this.className='on_focus'\"  onBlur=\"this.className='out_focus'\"";
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">
<script LANGUAGE="JavaScript">

function findName() {
	document.forms[0].code.value = "";
	document.forms[0].oldcode.value = "";
	document.forms[0].method='post';
	document.forms[0].action='<?= htmlentities($_SERVER['PHP_SELF']) ?>';
	document.forms[0].submit();
}

function updateForm(form) {
	document.forms[0].method='post';
	document.forms[0].action='<?= htmlentities($_SERVER['PHP_SELF']) ?>';
	document.forms[0].submit();
}

function moveRecord(value) {
	window.location=<?= htmlentities($_SERVER['PHP_SELF']) ?>document.forms[0].cust_memo_cust_id.value = <?= $cust_memo_cust_id ?>;
	document.forms[0].direction.value = value;
	updateForm();
}

function goList() {
	document.forms[0].method="post";
	document.forms[0].action="ic_colorcod-list.php";
	document.forms[0].submit();
}

function enterCode(e) {
	var enterKey =		13; // 
	var nextKey =		62;	// ">"
	var previousKey =	60; // "<"
	var endKey =	93; // "]"
	var homeKey =	91; // "["
	var charCode = (navigator.appName == "Netscape") ? e.which : e.keyCode ;
	var itsValue = document.form1.code.value;

	if ( itsValue == "" && charCode == enterKey) {
		goList();
	} else if (charCode == previousKey) {
		moveRecord(-1)
	} else if (charCode == nextKey) {
		moveRecord(1)
	} else if (charCode == homeKey) {
		moveRecord(0)
	} else if (charCode == endKey) {
		moveRecord(-2)
	} else {
		document.forms[0].oldcode.value = "";
		document.forms[0].cursor.value = "";
	}
}

function firstWork() {
	document.forms[0].code.focus();
}

function setHelp (helptext) {
	document.forms[0].helpbox.value = helptext;
}

function checkForm(form) {
	if (form.cust_memo_title.value == "") {
		window.alert("Title Should Not Be Blank");
	} else {
		document.form1.cmd.value = 'cust_memo';
		document.form1.method='post';
		document.form1.action='index.php';
		document.form1.submit();
	}
}


</script>
</head>

<?php
//-----------------------------------------------------------------------------
// *** Section: Start of HTML body
//-----------------------------------------------------------------------------
?>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" 
      bgcolor="white" onLoad="firstWork()">
<form name="form1">
<!--
/*-----------------------------------------------------------------------------
*** Section: Enter Key-value
-----------------------------------------------------------------------------*/
//$options = "onFocus=\"this.className='on_focus'\" onBlur=\"this.className='out_focus'\"";
-->

<table width="350" border="0" cellspacing="1" cellpadding="0" bgcolor="gray">
  <tr> 
       <td class="thead" width="103" bgcolor="#EEEEEE"><p>Memo #</p></td>
       <td class="tdata"><?= fillHidden("cmd","") ?><?= fillHidden("olds",$olds) ?> 
			<input type="text" name="code" value="<?= strtoupper($cust_memo_id) ?>"
					class="inbox" size="6" maxlength="12" onChange="updateForm()" onKeyPress="enterCode(event)"
			<?= setHelpOption("Enter a color code or part of it to search for a color or range of colors.") ?>>
			<?= fillHidden("cust_memo_id", $cust_memo_id) ?>
			<?= fillHidden("cust_memo_cust_id", $cust_memo_cust_id) ?>
			<a href="javascript:updateForm()">
				<IMG SRC="../icons/lookup.gif" WIDTH="20" HEIGHT="15" BORDER=0 ALT="Look Up"></a>&nbsp;
			<a href="ic_colorcod-list.php">
				<IMG SRC="../icons/list.gif" WIDTH="20" HEIGHT="15" BORDER=0 ALT="List"></a>&nbsp; 
			<a href="<?= htmlentities($_SERVER['PHP_SELF']) ?>">
				<IMG SRC="../icons/clear.gif" WIDTH="20" HEIGHT="15" BORDER=0 ALT="Clear Form"></a>&nbsp; 
			<br>
			<a href="javascript:moveRecord(-2)">
				<IMG SRC="../icons/first.gif" WIDTH="20" HEIGHT="15" BORDER=0 ALT="First"></a>&nbsp;
			<a href="javascript:moveRecord(-1)">
				<IMG SRC="../icons/previous.gif" WIDTH="20" HEIGHT="15" BORDER=0 ALT="Previous"></a>&nbsp;
			<a href="javascript:moveRecord(1)">
				<IMG SRC="../icons/next.gif" WIDTH="20" HEIGHT="15" BORDER=0 ALT="Next"></a>&nbsp;
			<a href="javascript:moveRecord(2)">
				<IMG SRC="../icons/last.gif" WIDTH="20" HEIGHT="15" BORDER=0 ALT="Last"></a>&nbsp;
			<?= fillHidden("direction","") ?> 
			<?= fillHidden("cursor",$cursor) ?>
	  </td>
 </tr>
  <tr>
    <td class="thead" width="103" bgcolor="#EEEEEE"> 
		<p>Date</p></td>
    <td class="tdata"> 
		<?= fillTextBoxRO("cust_memo_created", (empty($cust_memo_created))?date("m/d/Y"):$cust_memo_created, 32,32,"inbox") ?>
  </tr>
  <tr>
    <td class="thead" width="103" bgcolor="#EEEEEE"> 
		<p>Title</p></td>
    <td class="tdata"> 
		<?= fillTextBox("cust_memo_title", $cust_memo_title, 32,32,"inbox") ?>
  </tr>
  <tr>
    <td class="thead" width="103" bgcolor="#EEEEEE"> 
		<p>Memo</p></td>
    <td class="tdata">
		<?=	fillTextareaBox("cust_memo_body", $cust_memo_body, 35, 10, "inbox") ?>
  </tr>
  <tr> 
	  <td class="tdata" align="center" colspan="2"> 
		<?php if ($cnt>0) { ?>
		<input type="button" name="Submit" value="Update" onClick="javascript:checkForm(this.form)">
		<?php } else { ?>
		<input type="button" name="Submit1" value="Insert" onClick="javascript:checkForm(this.form)">
		<?php } ?>
		<input type="button" name="Submit2" value="Cancel" onClick="javascript:form1.reset()">
	  </td>
  </tr>
</table>
<!-- ========================================================================== -->
</form>
</body>
</html>