<?php
	include_once("class/register_globals.php");
?>
<html>
<head>
<title>Vouchers Detail</title>
<meta http-equiv="Content-Type" content="text/html; charset=">
<link rel="StyleSheet" href="../fns/css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
	function updateForm() {
		var f = document.forms[0];
		f.action = '/00wpi/ap/ap_voucher_details.php';
		f.method = 'post';
		f.submit();
	}

	var acctBrowse;
	function openAcctBrowse(objname)  {
		if (acctBrowse && !acctBrowse.closed) acctBrowse.close();
		acctBrowse = window.open("../ap/ap_voucher_account_popup.php?objname="+objname, "acctBrowseWin", "height=500, width=350");
		acctBrowse.focus();
		acctBrowse.moveTo(100,100);
	}

	function calcAmt() {
		var f = document.forms[0];
		var q = Math.round(parseFloat(f.vouchdtl_qty.value)*100)/100;
		var c = Math.round(parseFloat(f.vouchdtl_cost.value)*1000)/1000;
		f.amount.value = Math.round(q * c * 100)/100;
	}

	function calcPrc() {
		var f = document.forms[0];
		var a = Math.round(parseFloat(f.amount.value)*100)/100;
		var q = Math.round(parseFloat(f.vouchdtl_qty.value)*100)/100;
		f.vouchdtl_cost.value = Math.round(a / q * 100)/100;
	}

	function firstWork() {
		var f = document.forms[0];
//		if (f.ty.value=='a' || f.ty.value=='e') f.vouchdtl_cust_code.focus();
	}

	function setCursor(field) {
		var f = document.forms[0];
		var fn = "document.forms[0].";
		var fv = eval(fn+field+".focus()");
	}

//-->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="30" marginwidth="0" marginheight="0" onLoad="firstWork()">
<div align='center'>
<table width="780" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td> <table width="780" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="10" bgcolor="#CCCCFF"> &nbsp; </td>
          <td width="780" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		f.action = "../ap/ap_voucher_proc.php";
		f.cmd.value = "voucher_detail_sess_add";
		f.method = "post";
		f.submit();
	}
	
	function ClearVoucher() {
		var f = document.forms[0];
		f.action = "../ap/ap_voucher_proc.php";
		f.cmd.value = "voucher_clear_sess_add";
		f.method = "post";
		f.submit();
	}
//-->
</SCRIPT>

						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="../ap/ap_voucher_proc.php">
							<INPUT TYPE="hidden" name="cmd" value="">
							<input type="hidden" name="ht" value="a">							<input type="hidden" name="ty" value="a">							<input type="hidden" name="voucher_id" value="">							<input type="hidden" name="thisfocus" value="">                    <tr align="right"> 
                      <td colspan="8"><strong>New Voucher Detail</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="/00wpi/ap/ap_voucher_details.php?ty=a&ht=a">New</a> |
                        <a href="../ap/ap_voucher.php?ty=a&voucher_id=">Header</a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Amount</td>
                  <td width="308"> 
                    <input class="inbox" type="text" name="vouchdtl_amt" size="20" maxlength="32" value=""  >                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Exp. Main Acct</td>
                  <td> 
                    <input class="inbox" type="text" name="vouchdtl_exp_main_acct" size="20" maxlength="32" value=""  onChange='' >					<A HREF="javascript:openAcctBrowse('vouchdtl_exp_main_acct','vouchdtl_exp_sub_acct')"><font size="2">Lookup</font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Exp. Sub Acct</td>
                  <td> 
                    <input class="inbox" type="text" name="vouchdtl_exp_sub_acct" size="20" maxlength="32" value=""  onChange='' >                  </td>
                </tr>
                <tr> 
                  <td width="150" align="right" bgcolor="silver">Description</td>
                  <td> 
                    <input class="inbox" type="text" name="vouchdtl_acct_desc" size="40" maxlength="250" value=""  >                  </td>
                </tr>

				<tr> 
                  <td width="150" align="right" bgcolor="silver">Reason Code</td>
                  <td> 
                    <input class="inbox" type="text" name="vouchdtl_reason_code" size="20" maxlength="32" value=""  >                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
									 <td width="100%" align="center"><input type="button" name="Submit32" value="Record" onClick="AddDtl()"> 
                              <input type="reset" name="Submit222" value="Cancel"></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8">&nbsp;</td>
                    </tr>

						  </form>
                  </table>
			
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
</div>
<script language="javascript1.2" src="../menud/menu.js" type="text/javascript"></script></body>
</html>
