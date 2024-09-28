<?php
	include_once("class/register_globals.php");
?>
<html>
<head>
<title>Vouchers</title>
<meta http-equiv="Content-Type" content="text/html; charset=">
</head>
<!-------------------------------------------------------------------------------------->
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="30" marginwidth="0" marginheight="0">
<div align='center'>
<!-------------------------------------------------------------------------------------->
<table width="780" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="ttop" width="10%" align="left">
		<font color=#ff2f2f>AP</font></td>
    <td class="ttop" width="45%" align="center">
		<font color=orange><u>AP Vouchers</u></font></td>
    <td class="ttop" width="30%" align="left">
		<font color="#ffffff">Company:Wiesner Products, Inc.</font></td>
	<td class="ttop" width="15%" align="left">
		<font color="#ffffff">User:ADMIN</font></td>
  </tr>
</table>
<table width="780" border="0" cellspacing="0" cellpadding="0">
<!--
  <tr bgcolor="#6666FF">
    <td>&nbsp;</td>
  </tr>
-->
  <tr> 
    <td>
	  <table width="780" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="10" bgcolor="#CCCCFF"> &nbsp; </td>
          <td width="780" align="center">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
<!--
			  <tr> 
                <td colspan="3" bgcolor="#CCFFFF" align="right">&nbsp;</td>
              </tr>
-->
			  <tr> 
<!--                <td width="10">&nbsp;</td> -->
                <td valign="top"> 
<!-------------------------------------------------------------------------------------->
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.voucher_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
		} else {
			f.cmd.value = "voucher_sess_add";
			f.method = "post";
			f.action = "../ap/ap_voucher_proc.php";
			f.submit();
		}
	}

	function DelDtl(did) {
		var f = document.forms[0];
		f.cmd.value = "voucher_sess_del";
		f.method = "get";
		f.action = "../ap/ap_voucher_proc.php";
		f.submit();
	}

	function SaveToDB() {
		var f = document.forms[0];
		if (f.voucher_vend_code.value == "") {
			window.alert("Vendor Code should not be blank!");
		} else {
			if (f.voucher_amt.value != f.voucher_inv_amt.value) {
				if (window.confirm("Amounts dismatch! Wanna process?")) {
					f.ht.value = "a";
					f.cmd.value = "voucher_add";
					f.method = "post";
					f.action = "ap_voucher_proc.php";
					f.submit();
				}
			} else {
				if (f.voucher_amt.value != 0) {
					f.ht.value = "a";
					f.cmd.value = "voucher_add";
					f.method = "post";
					f.action = "ap_voucher_proc.php";
					f.submit();
				}
			}
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "voucher_clear_sess_add";
		f.method = "post";
		f.action = "../ap/ap_voucher_proc.php";
		f.submit();
	}

	function UpdateForm() {
		var f = document.forms[0];
		f.action = "../ap/ap_voucher_proc.php";
		f.cmd.value = "voucher_update_sess_add";
		f.method = "post";
		f.submit();
	}

	function calcTotal() {
		var f = document.forms[0];
		var t = parseFloat(f.voucher_tax_amt.value);
		var r = parseFloat(f.voucher_freight_amt.value);
		var s = parseFloat(f.voucher_amt.value);
		f.totalamount.value = Math.round((t+r+s)*100)/100 ;
	}

	function calcTax() {
		var f = document.forms[0];
		var x = parseFloat(f.taxtotal.value);
		var a = parseFloat(f.voucher_taxrate.value);
		f.voucher_tax_amt.value = Math.round(x*a)/100;
		var t = parseFloat(f.voucher_tax_amt.value);
		calcTotal();
	}

	var f = document.forms[0];//-->
</SCRIPT>
<!-------------------------------------------------------------------------------------->
<form method=post action="../ap/ap_voucher_proc.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<input type="hidden" name="cmd" value="">		<input type="hidden" name="ty" value="a">		<input type="hidden" name="ht" value="a">		<input type="hidden" name="voucher_vend_code_old" value="">  <tr> 
    <td class="tlist" colspan="8" align="right"><strong>New Voucher</strong></td>
  </tr>
  <tr> 
    <td class="tlist" colspan="8" align="left">| 
		<a href="/00wpi/ap/ap_voucher.php?ty=a">New</a> |
		<a href="/00wpi/ap/ap_voucher.php?ty=l">List</a> |
    </td>
  </tr>
</table>
<!-------------------------------------------------------------------------------------->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="right"> 
    <td colspan="8" valign="top">
	  <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%" valign="top">
		    <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr> 
                <td class="thead" width="25%">Vendor No</td>
                <td class="tdata" width="75%"> 
					<input class="inbox" type="text" name="voucher_vend_code" size="20" maxlength="32" value="" onChange='updateForm()' >					<a HREF="javascript:openVendBrowse('voucher_vend_code')">Lookup</a>
                </td>
              </tr>
<script LANGUAGE="JavaScript">
<!--
	setCursor('voucher_vend_code');
//-->
</script>
			  <tr> 
                <td class="thead">Date&nbsp;</td>
                <td class="tdata"> 
					<input class="inbox" type="text" name="voucher_date" size="20" maxlength="32" value="12/13/2003"  >					<a href="javascript:openCalendar('voucher_date')">C</a>
					<input type="hidden" name="voucher_ap_type" value="AP">                </td>
              </tr>
              <tr> 
                <td class="thead">User #&nbsp;</td>
                <td class="tdata"> 
                   <input class="inbox" type="text" name="voucher_user_code" size="20" maxlength="32" value="ADMIN"  readonly>                </td>
              </tr>
              <tr> 
                <td class="thead">PO #&nbsp;</td>
                <td class="tdata"> 
                   <input class="inbox" type="text" name="voucher_po_no" size="20" maxlength="32" value=""  >                </td>
              </tr>
              <tr> 
                <td class="thead">Amount&nbsp;</td>
                <td class="tdata"> 
                   <input class="inbox" type="text" name="voucher_amt" size="20" maxlength="32" value="0.00"  readonly>                </td>
              </tr>
              <tr> 
                <td class="thead">Ref #&nbsp;</td>
                <td class="tdata"> 
                   <input class="inbox" type="text" name="voucher_ref" size="20" maxlength="32" value=""  >                </td>
              </tr>
              <tr> 
                <td class="thead">Due Days</td>
                <td class="tdata"> 
                   <input class="inbox" type="text" name="voucher_due_days" size="20" maxlength="32" value=""  >                </td>
              </tr>
              <tr> 
                <td class="thead">Due Date</td>
                <td class="tdata"> 
					<input class="inbox" type="text" name="voucher_due_date" size="20" maxlength="32" value=""  >					<a href="javascript:openCalendar('voucher_due_date')">C</a>
                </td>
              </tr>
              <tr> 
                <td class="thead">GL Date</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_gl_date" size="20" maxlength="32" value=""  >				  <a href="javascript:openCalendar('voucher_gl_date')">C</a>
                </td>
              </tr>
              <tr> 
                <td class="thead">Check #</td>
                <td class="tdata"> 
                   <input class="inbox" type="text" name="voucher_check_no" size="20" maxlength="32" value=""  >                </td>
              </tr>
              <tr> 
                <td class="thead">Check Date</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_check_date" size="20" maxlength="32" value=""  >				  <a href="javascript:openCalendar('voucher_check_date')">C</a>
                </td>
              </tr>
            </table>
		  </td>
<!--      <td width="2%">&nbsp;</td> -->
          <td width="50%" valign="top">
		    <table width="100%" border="0" cellspacing="1" cellpadding="0">
<SCRIPT LANGUAGE="JavaScript">
<!--
	setCursor('voucher_vend_code');
//-->
</SCRIPT>
              <tr> 
                <td class="thead">Vend Name</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_name" size="32" maxlength="32" value=""  readonly>                </td>
              </tr>
              <tr> 
                <td class="thead">Address</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_addr1" size="32" maxlength="32" value=""  readonly>                </td>
              </tr>
              <tr> 
                <td class="thead">&nbsp;</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_addr2" size="32" maxlength="32" value=""  readonly>                </td>
              </tr>
              <tr> 
                <td class="thead">&nbsp;</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_addr3" size="32" maxlength="32" value=""  readonly>                </td>
              </tr>
              <tr> 
                <td class="thead">City/S/Z/C</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_city" size="10" maxlength="15" value=""  readonly>                  <input class="inbox" type="text" name="voucher_state" size="2" maxlength="2" value=""  readonly>                  <input class="inbox" type="text" name="voucher_zip" size="5" maxlength="10" value=""  readonly>                  <input class="inbox" type="text" name="voucher_country" size="3" maxlength="3" value=""  readonly>                </td>
              </tr>
              <tr> 
                <td class="thead">Vend Inv.#&nbsp;</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_vend_inv" size="20" maxlength="32" value=""  >                </td>
              </tr>
              <tr> 
                <td class="thead">Inv Date</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_vend_inv_date" size="20" maxlength="32" value=""  >				  <a href="javascript:openCalendar('voucher_vend_inv_date')">C</a>
                </td>
              </tr>
              <tr> 
                <td class="thead">Inv Amt</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_inv_amt" size="20" maxlength="32" value="0.00"  >                </td>
              </tr>
              <tr> 
                <td class="thead">Disc Days</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_disc_days" size="20" maxlength="32" value=""  >                </td>
              </tr>
              <tr> 
                <td class="thead">Disc Date</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_disc_date" size="20" maxlength="32" value=""  >				  <a href="javascript:openCalendar('voucher_disc_date')">C</a>
                </td>
              </tr>
              <tr> 
                <td class="thead"">Disc %</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_disc_pct" size="20" maxlength="32" value=""  >                </td>
              </tr>
              <tr> 
                <td class="thead">Disc Amt</td>
                <td class="tdata"> 
                  <input class="inbox" type="text" name="voucher_disc_amt" size="20" maxlength="32" value="0.00"  >                </td>
              </tr>
            </table>
	      </td>
        </tr>
      </table>
    </td>
  </tr>
  <!--                  </tr> -->
  <tr> 
    <td colspan="8" align="center">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td class="tlist" width="50%" align="left">
			<a HREF="javascript:AddDtl()">Add Detail</a>
	      </td>
          <td width="50%" align="center">
			<input type="button" name="Submit32" value="Update" onClick="UpdateForm()">
		    <input type="button" name="Submit3222" value="Record" onClick="SaveToDB()"> 
            <input type="button" name="Submit22222" value="Clear" onClick="clearSess()">
	      </td>
        </tr>
      </table>
	</td>
  </tr>
</table>
<!-------------------------------------------------------------------------------------->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="right"> 
    <td colspan="8">
	  <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr> 
          <th class="tlist" bgcolor="gray" width="5%"><font color="white">No</font></th>
          <th class="tlist" bgcolor="gray" width="15%"><font color="white">Account</font></th>
          <th class="tlist" bgcolor="gray" width="35%"><font color="white">Desc</font></th>
          <th class="tlist" bgcolor="gray" width="10%"><font color="white">Reason</font></th>
          <th class="tlist" bgcolor="gray" width="10%"><font color="white">Amount</font></th>
          <th class="tlist" bgcolor="gray" width="2%"><font color="white">&nbsp;</font></th>
        </tr>
		<tr bgcolor="#EEEEEE">
          <td class="tlist" colspan="8" align="center"><b>Empty!</b></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<!-------------------------------------------------------------------------------------->			
<!-------------------------------------------------------------------------------------->
                </td>
                <td width="10">&nbsp;</td>
              </tr>
            </table>
		  </td>
          <td width="10" bgcolor="#CCCCFF">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr bgcolor="#6666FF"> 
    <td>&nbsp;</td>
  </tr>
</table>
<!-------------------------------------------------------------------------------------->
</div>
<script language="javascript1.2" src="../menud/menu.js" type="text/javascript"></script></body>
</html>