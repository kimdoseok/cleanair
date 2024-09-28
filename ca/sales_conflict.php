<?php
	include_once("class/map.default.php");

	include_once("class/class.datex.php");
	$d = new Datex();

	include_once("class/class.formutils.php");
	$f = new FormUtil();

	include_once("class/class.navigates.php");
	$limit = 1;
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$sd = new SaleDtls();
//echo $sale_id."/".$diff_qty."/".$sale_qty."/".$pick_qty."<br>";

    $sale_id=$sales["sale_id"];
    $sh_edit = "sales_edit_".$sale_id;
    $sd_edit = "saledtls_edit_".$sale_id;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--

//-->
</SCRIPT>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="right"> 
    <td colspan="2"><strong>Conflict on Sales Edit</strong></td>
  </tr>
  <tr align="left"> 
    <td colspan="1"><font size="2">
	  | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> | 
	  <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | 
	  </font>
    </td>
    <td colspan="1" align="right"><font size="2">
	  | <a href="sales_proc.php?cmd=sale_clear_sess_edit&sale_id=<?= $sales["sale_id"] ?>">Go Back to Edit</a> | </font>
    </td>
  </tr>
  <tr align="right"> 
    <td colspan="2" valign="top">
      <table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor='gray'>
        <tr>
		  <td bgcolor='silver' width='20%'>
		    &nbsp;
		  </td>
		  <td bgcolor='silver' width='40%'>
		    OLD
		  </td>
		  <td bgcolor='silver' width='40%'>
		    NEW
		  </td>
		</tr>
        <tr>
		  <td bgcolor='silver'>Sales#</td>
		  <td bgcolor='white'><?= $sales["sale_id"] ?></td>
		  <td bgcolor='white'><?= $_SESSION[$sh_edit]["sale_id"] ?></td>
		</tr>
        <tr>
		  <td bgcolor='silver'>User#</td>
		  <td bgcolor='white'><b><?= $sales[sale_user_code] ?></b></td>
		  <td bgcolor='white'><b><?= $_SERVER["PHP_AUTH_USER"] ?></b></td>
		</tr>
        <tr>
		  <td bgcolor='silver'>Customer</td>
		  <td bgcolor='white'>
		  <?= $sales["sale_cust_code"] ?><br>
		  <?= $sales["sale_name"] ?><br>
		  <?= $sales["sale_addr1"] ?><br>
		  <?= $sales["sale_addr2"] ?><br>
  		  <?= $sales["sale_city"].", ".$sales["sale_state"]." ".$sales["sale_zip"] ?><br>
		  </td>
		  <td bgcolor='white'>
		  <?= $_SESSION[$sh_edit]["sale_cust_code"] ?><br>
		  <?= $_SESSION[$sh_edit]["sale_name"] ?><br>
		  <?= $_SESSION[$sh_edit]["sale_addr1"] ?><br>
		  <?= $_SESSION[$sh_edit]["sale_addr2"] ?><br>
  		  <?= $_SESSION[$sh_edit]["sale_city"].", ".$_SESSION[$sh_edit]["sale_state"]." ".$_SESSION[$sh_edit]["sale_zip"] ?><br>
		  </td>
		</tr>
        <tr>
		  <td bgcolor='silver'>Subtotal</td>
		  <td bgcolor='white' align='right'><?= number_format($sales["sale_amt"],2,".",",") ?></td>
		  <td bgcolor='white' align='right'><?= number_format($_SESSION[$sh_edit]["sale_amt"],2,".",",") ?></td>
		</tr>
        <tr>
		  <td bgcolor='silver'>Tax</td>
		  <td bgcolor='white' align='right'><?= number_format($sales["sale_tax_amt"],2,".",",") ?></td>
		  <td bgcolor='white' align='right'><?= number_format($_SESSION[$sh_edit]["sale_tax_amt"],2,".",",") ?></td>
		</tr>
        <tr>
		  <td bgcolor='silver'>Freight</td>
		  <td bgcolor='white' align='right'><?= number_format($sales["sale_freight_amt"],2,".",",") ?></td>
		  <td bgcolor='white' align='right'><?= number_format($_SESSION[$sh_edit]["sale_freight_amt"],2,".",",") ?></td>
		</tr>
        <tr>
		  <td bgcolor='silver'>Timestamp</td>
		  <td bgcolor='white'><b><?= $sales[sale_log] ?></b></td>
		  <td bgcolor='white'><b><?= date("Y-m-d H:i:s") ?></b></td>
		</tr>
		<FORM METHOD=POST ACTION="sales_proc.php">
		<INPUT TYPE="hidden" name='cmd' value='sale_edit'>
		<INPUT TYPE="hidden" name='sale_id' value='<?= $sales["sale_id"] ?>'>
		<INPUT TYPE="hidden" name='conflict' value='1'>
        <tr>
		  <td bgcolor='silver'>
		    Continue?
		  </td>
		  <td bgcolor='white' colspan='2'>
		    Please enter edit password to continue.
		    <input type='text' name='edit_overwrite' size='6' value='<?= $default[overwrite] ?>'>
			<input type='submit' value='Save'>
			<input type='reset' value='Cancel'>
			<a href="sales_proc.php?cmd=sale_clear_sess_edit&sale_id=<?= $sales["sale_id"] ?>">Clear&Back</a>
		  </td>
		</tr>
		</FORM>
	  </table>
    </td>
  </tr>
  <tr>
  <td colspan='2'>&nbsp;</td>
  </tr>
</table>
