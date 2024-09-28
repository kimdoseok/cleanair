<?php
	include_once("class/class.formutils.php");
	$f = new FormUtil();
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td><strong><?= "General Ledger Report Menu" ?></strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <form name="form1" method="get" action="">
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="39%"><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?report_page=gl_trial_bal_rpt.php" ?>">Trial Banance</A></td>
          <td width="2%">&nbsp;</td>
          <td width="39%"><A HREF="<?= htmlentities($_SERVER['PHP_SELF'])."?report_page=gl_comntd_acct_rpt.php" ?>">Commented Accts</A></td>
          <td width="10%">&nbsp;</td>
        </tr>
		</form>
      </table>
	</td>
  </tr>
  <tr align="right"> 
    <td>&nbsp;</td>
  </tr>
 </table>
