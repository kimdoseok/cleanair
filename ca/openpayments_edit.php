<?php
	include_once("class/class.datex.php");
	include_once("class/class.receipt.php");
	include_once("class/class.rcptdtls.php");
	include_once("class/class.formutils.php");
	include_once("class/class.vendors.php");
	$x = new Datex();
	$f = new FormUtil();
	$s = new Receipt();
	$d = new RcptDtls();
	$o = new OpenPay();

	if ($sls = $s->getReceipt($rcpt_id)) if (!empty($sls)) foreach($sls as $k => $v) $$k = $v; 

	$recs = $o->getOpenPayDtlsList($rcpt_id);

//	for ($i=0;$i<count($recs);$i++) $applied += $recs[$i]["rcptdtl_amt"];
	$applied = 0;
	$rcptdtl_disc_amt = 0;
	$rcptdtl_op_amt = 0;
	$rcptdtl_cm_amt = 0;
	$rcptdtl_aw_amt = 0;
	for ($i=0;$i<count($recs);$i++) {
		if ($recs[$i]["rcptdtl_type"]=="ar") $applied += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="op") $applied += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="cm") $rcptdtl_cm_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="dc") $rcptdtl_dc_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="aw") $rcptdtl_aw_amt += $recs[$i]["rcptdtl_amt"];
		else if ($recs[$i]["rcptdtl_type"]=="fc") $applied += $recs[$i]["rcptdtl_amt"];
		else $applied += $recs[$i]["rcptdtl_amt"];
	}
	$remained = $rcpt_amt - $applied;
	$rcpt_disc_amt = $rcptdtl_dc_amt;

	$not_found_cust = 0;
	$rcpt_cust_code_old = $rcpt_cust_code;
	if (!empty($_POST["rcpt_cust_code"])) $sale_cust_code = $_POST["rcpt_cust_code"];
	if (!empty($_GET["rcpt_cust_code"])) $sale_cust_code = $_GET["rcpt_cust_code"];
	if (!empty($rcpt_cust_code)) {
		$v = new Custs();
		if ($varr = $v->getCusts($rcpt_cust_code)) {
			$rcpt_cust_code	= strtoupper($rcpt_cust_code);
			$cust_name = $varr["cust_name"];
			$cust_addr1 = $varr["cust_addr1"];
			$cust_addr2 = $varr["cust_addr2"];
			$cust_addr3 = $varr["cust_addr3"];
			$cust_city = $varr["cust_city"];
			$cust_state = $varr["cust_state"];
			$cust_zip = $varr["cust_zip"];
			$cust_balance = $varr["cust_balance"];
			$rcpt_cust_code_old = $rcpt_cust_code;
			$not_found_cust = 0;
		} else {
			$not_found_cust = 1;
			$rcpt_cust_code	= $rcpt_cust_code_old;
		}
	}
?>
<SCRIPT LANGUAGE="JavaScript">
<!--


//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="openpayments_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ht","e") ?>
							<?= $f->fillHidden("rcpt_id",$rcpt_id) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Open Payment Edit</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="8"><font size="2">
						| <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							
      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <td width="97" bgcolor="silver">Customer:</td>
              <td width="308"> 
                <?= $rcpt_cust_code ?>
              </td>
            </tr>
            <tr> 
              <td height="20" align="right" bgcolor="silver">Name:</td>
              <td> 
                <?= $cust_name ?>
              </td>
            </tr>
            <tr> 
              <td align="right" bgcolor="silver">Address</td>
              <td> 
                <?= $cust_addr1 ?>
              </td>
            </tr>
            <tr> 
              <td align="right" bgcolor="silver">&nbsp;</td>
              <td> 
                <?= $cust_addr2 ?>
              </td>
            </tr>
            <tr> 
              <td align="right" bgcolor="silver">&nbsp;</td>
              <td> 
                <?= $cust_addr3 ?>
              </td>
            </tr>
            <tr> 
              <td align="right" bgcolor="silver">C/S/Z/C</td>
              <td> 
                <?= $cust_city ?>
                <?= $cust_state ?>
                <?= $cust_zip ?>
              </td>
            </tr>
            <tr> 
              <td align="right" bgcolor="silver">Balance:</td>
              <td> 
                <?= number_format($cust_balance,2,",",".") ?>
              </td>
            </tr>
            <tr> 
              <td align="right" bgcolor="silver">Comment:</td>
              <td> 
                <?= $rcpt_comment ?>
              </td>
            </tr>
               </table></td>
             <td width="1%">&nbsp;</td>
             <td width="37%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
               <tr> 
                 <td width="97" align="right" bgcolor="silver">Check_no:</td>
                 <td width="308"> 
                   <?= $rcpt_check_no ?>
                 </td>
               </tr>
               <tr> 
                 <td width="97" align="right" bgcolor="silver">Pay Type:</td>
                 <td width="308"> 
<?php
	if ($rcpt_type=="ch") echo "Check";
	else if ($rcpt_type=="ca") echo "Cash";
	else if ($rcpt_type=="cc") echo "Credit Card";
	else if ($rcpt_type=="dc") echo "Discount Apply";
	else if ($rcpt_type=="bc") echo "Bounced Check";
	else if ($rcpt_type=="ot") echo "Other Payment";
	else echo "N/A";
?>
                 </td>
               </tr>
               <tr> 
                 <td width="97" align="right" bgcolor="silver">Amount:</td>
                 <td> 
                   <?= number_format($rcpt_amt,2,",",".") ?>
                 </td>
               </tr>
               <tr> 
                 <td width="97" align="right" bgcolor="silver">Discount:</td>
                 <td> 
                   <?= number_format($rcpt_disc_amt,2,",",".") ?>
                 </td>
               </tr>
               <tr> 
                 <td width="97" align="right" bgcolor="silver">Date_1:</td>
                 <td> 
                   <?= $rcpt_date ?>
                 </td>
               </tr>
               <tr> 
                 <td width="97" align="right" bgcolor="silver">User_no&nbsp;</td>
                 <td> 
                   <?= $_SERVER["PHP_AUTH_USER"] ?>
                 </td>
               </tr>
               <tr> 
                 <td width="97" align="right" bgcolor="silver">Applied:</td>
                 <td width="308"> 
                   <?= number_format($applied,2,",",".") ?>
                 </td>
               </tr>
               <tr> 
                 <td width="97" align="right" bgcolor="silver">Remained:</td>
                 <td> 
                   <?= number_format($remained,2,",",".") ?>
                 </td>
               </tr>
              </table></td>
            </tr>
          </table> </td>
	    </tr>
	  </table>
    </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">No</font></th>
                            <th bgcolor="gray"><font color="white">Inv #</font></th>
                            <th bgcolor="gray"><font color="white">Acct #</font></th>
                            <th bgcolor="gray"><font color="white">Type</font></th>
                            <th bgcolor="gray"><font color="white">Amount</font></th>
                            <th bgcolor="gray"><font color="white">Description</font></th>
                            <th bgcolor="gray"><font color="white">&nbsp;</font></th>
                          </tr>

<?php
	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="5%" align="center"> 
<?php
	if (strtoupper($recs[$i]["rcptdtl_type"])=="OP") {
		echo $i+1;
	} else {
?>
                              <a href="openpay_details.php?ty=e&rcpt_id=<?= $rcpt_id ?>&ht=e&rcptdtl_id=<?= $recs[$i][rcptdtl_id] ?>"><?= $i+1 ?></a>
<?php
	}
?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["rcptdtl_pick_id"] ?>
                            </td>
                            <td width="13%"> 
                              <?= $recs[$i][rcptdtl_acct_code] ?>
                            </td>
                            <td width="10%" align="center"> 
							  <?= strtoupper($recs[$i]["rcptdtl_type"]) ?>
                            </td>
                            <td width="12%" align="right"> 
                              <?= $recs[$i]["rcptdtl_amt"] ?>
                            </td>
                            <td width="55%"> 
                              <?= $recs[$i]["rcptdtl_desc"] ?>
                            </td>
                            <td width="5%"> 
<?php
	if (strtoupper($recs[$i]["rcptdtl_type"])=="OP") {
?>
                              <a href="openpay_details.php?ty=a&rcpt_id=<?= $rcpt_id ?>&rcptdtl_id=<?= $recs[$i][rcptdtl_id] ?>">Apply</a>
<?php
	} else {
		echo "&nbsp;";
	}
?>
                            </td>
                          </tr>
<?php
		}
	}
	if (empty($recs[0])) {
?>
						<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b>Empty_1!</b>
                            </td>
                          </tr>
<?php
	}
?>
                        </table></td>
                    </tr>
                  </table>
<br>
<?php
//	print_r($_SESSION);
?>
