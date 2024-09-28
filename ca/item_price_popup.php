<?php
//------------------------------------------------------------------------
	
	include_once("class/map.label.php");
	include_once("class/register_globals.php");

//------------------------------------------------------------------------
	

// Customer Screen Text View Language Select
	
	$lang = 'en';
	
	$charsetting = "iso-8859-1";

//-----------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Price Adjustment</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
<table width="100%" border="0" cellspacing="1" cellpadding="0">
<FORM METHOD="POST" ACTION="ic_proc.php">
<INPUT TYPE="hidden" NAME="cmd" VALUE="item_price">
<tr align="center"> 
  <td colspan="2" height="35"><strong>Price Adjustment of "<?= $item_code ?>"</strong></td>
</tr>
<tr> 
  <td width="40%" align="right" bgcolor="silver"> 
	Adjust Price &nbsp;
  </td>
  <td width="60%" align="left">
	<INPUT TYPE="text" NAME="adj_pct" VALUE="0" SIZE="10">
	<INPUT TYPE="hidden" NAME="item_code" VALUE="<?= $item_code ?>">
	<INPUT TYPE="hidden" NAME="ft" VALUE="<?= $ft ?>">
	<INPUT TYPE="hidden" NAME="cn" VALUE="<?= $cn ?>">
	<INPUT TYPE="hidden" NAME="pg" VALUE="<?= $pg ?>">
	<INPUT TYPE="hidden" NAME="rv" VALUE="<?= $rv ?>">
  </td>
</tr>
<tr> 
  <td width="40%" align="right" bgcolor="silver"> 
	Adjust Type &nbsp;
  </td>
  <td width="60%" align="left"> 
    <INPUT TYPE="radio" NAME="adj_type" VALUE="a" checked>Amount
    <INPUT TYPE="radio" NAME="adj_type" VALUE="p">Percent
  </td>
</tr>
<tr align="center"> 
  <td colspan="2" align="center" height="35">
    <INPUT TYPE="submit" NAME="submit" VALUE="Process"> <INPUT TYPE="button" NAME="close" VALUE="Close" onClick="javascript:self.close()">
  </td>
</tr>
</FORM>
</table>

</BODY>
</HTML>
