<?php
	include_once("class/register_globals.php");

?>
<html>
<head>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function fillRow() {
		var f = documnet.forms[0];
		window.alert(f.hello.value);
		var a[] = [1,5,8,12,15,19,21,51];
		for (i=0;i<a.length;i++) {
			fldstr = "documnet.forms[0]"+a[i];
			fld  = eval(fldstr);
			fld.checked = true;
		}
	}

	function xfCol() {
		window.alert('H');
		//var f = documnet.forms[0];
		//f.hello.value = "true";
	}
//-->
</SCRIPT>

</head>
<body>
<FORM METHOD=POST ACTION="">
<INPUT TYPE="checkbox" NAME="ua_1" value="t">1
<INPUT TYPE="checkbox" NAME="ua_5" value="t">2
<INPUT TYPE="checkbox" NAME="ua_8" value="t">3
<INPUT TYPE="checkbox" NAME="ua_12" value="t">4
<INPUT TYPE="checkbox" NAME="ua_15" value="t">5
<INPUT TYPE="checkbox" NAME="ua_19" value="t">6
<INPUT TYPE="checkbox" NAME="ua_21" value="t">7
<INPUT TYPE="checkbox" NAME="ua_51" value="t">8
<a href="javascript:xfCol()">Here</a>
</FORM>
</body>
</html>