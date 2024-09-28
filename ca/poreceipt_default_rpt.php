<?php
	include_once("class/class.formutils.php");
  include_once("class/register_globals.php");

	$f = new FormUtil();
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td><strong>Purchase Report Menu</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr height="25">
          <td width="10%">&nbsp;</td>
          <td width="39%"><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=dr" ?>">Daily Report</A></td>
          <td width="2%">&nbsp;</td>
          <td width="39%"><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=scu" ?>">Purchase By Vendor Report</A></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=si" ?>">Purchase By Item Report</A></td>
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=sc" ?>">Purchase By Category Report</A></td>
          <td>&nbsp;</td>
        </tr>
		</form>
      </table>
	</td>
  </tr>
  <tr align="right"> 
    <td>&nbsp;</td>
  </tr>
 </table>
