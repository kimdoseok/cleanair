<?php
	include_once("class/class.formutils.php");
	$f = new FormUtil();
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td><strong>Sales Report Menu</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr height="25">
          <td width="10%">&nbsp;</td>
          <td width="39%"><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=dr" ?>">Daily Report</A></td>
          <td width="2%">&nbsp;</td>
          <td width="39%"><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=scu" ?>">Sales By Customer Report</A></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=si" ?>">Sales By Item Report</A></td>
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=sc" ?>">Sales By Category Report</A></td>
          <td>&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=oi" ?>">Orders By Item Report</A></td>
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=stny" ?>">Sales Tax Report</A></td>
          <td>&nbsp;</td>
        </tr>
        <tr height="25">
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=rcpt" ?>">Cash Receipt Report</A></td>
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=srsr" ?>">Sales Rep. Status Reports</A></td>
          <td>&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=arstatus" ?>">AR Status Report</A></td>
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=salerep" ?>">Sales By Sales Rep. Report</A></td>
          <td>&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=boi" ?>">Back Order By Item Report</A></td>
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=sahist" ?>">Sales History Report</A></td>
          <td>&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="report_customer_list.php">Customer List Raw</A></td>
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=custrpt" ?>">Customer Report</A></td>
          <td>&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="report_customer_email.php">Customer Email List</A></td>
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=custlist" ?>">Zero Balance Active Customer List</A></td>
          <td>&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=mr" ?>">Monthly Report</A></td>
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=activecust" ?>">Inactive Transaction Customers</A></td>
          <td>&nbsp;</td>
        </tr>
        <tr height="25">
          <td>&nbsp;</td>
          <td><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=acy" ?>">Active Customers for this year</A></td>
          <td>&nbsp;</td>
          <td></td>
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
