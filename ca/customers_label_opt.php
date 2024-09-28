<?php
	include_once("class/class.formutils.php");
	include_once("class/register_globals.php");

	$f = new FormUtil();

	$strkeys = array("start_cust", "end_cust","begin_date");
	foreach ($strkeys as $key) {
		if (!isset($$key)) {
			$$key = "";
		}	
	}

?>
<SCRIPT LANGUAGE="JavaScript">
<!--

	function updateForm() {
		var f = document.forms[0];
		f.method = 'post';
		f.action='<?= htmlentities($_SERVER['PHP_SELF']) ?>';
		f.submit();
	}

	var custBrowse;
	function openCustBrowse(objname)  {
		if (custBrowse && !custBrowse.closed) custBrowse.close();
		custBrowse = window.open("customers_popup.php?objname="+objname, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	function openCustBrowseFilter(objname, filtertext) {
		if (custBrowse && !custBrowse.closed) custBrowse.close();
		var url = "customers_popup.php?objname="+objname+"&ft="+filtertext;
		custBrowse = window.open(url, "custBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=500,width=650");
		custBrowse.focus();
		custBrowse.moveTo(100,100);
	}

	var calBrowse;
	function openCalendar(objname)  {
		if (calBrowse && !calBrowse.closed) calBrowse.close();
		calBrowse = window.open("calendar_popup.php?objname="+objname, "calBrowseWin", "toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=auto,height=240,width=340");
		calBrowse.focus();
		calBrowse.moveTo(100,100);
	}

	function setCursor(field) {
		var f = document.forms[0];
		var fn = "document.forms[0].";
		var fv = eval(fn+field+".focus()");
	}

//-->
</SCRIPT>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
	<td><strong>Customer Label</strong></td>
  </tr>
  <tr>
	<td align="left"><font size="2">&nbsp; </font> 
	  <table width="100%" border="0" cellspacing="1" cellpadding="1">
		<FORM METHOD=POST ACTION="customers_label_pdf.php">
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">Start Customer</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%"><?= $f->fillTextBox("start_cust", $start_cust, 20, 32, "inbox") ?>
			<A HREF="javascript:openCustBrowse('start_cust')">Lookup</a></td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">End Customer</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%"><?= $f->fillTextBox("end_cust", $end_cust, 20, 32, "inbox") ?>
			  <A HREF="javascript:openCustBrowse('end_cust')">Lookup</a></td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">Beging Date</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%">
		    <?= $f->fillTextBox("begin_date", $begin_date, 20, 32, "inbox") ?>
		  </td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">Output</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%">
        <input type="radio" NAME="output" value="label" checked="checked"> Label
        <input type="radio" NAME="output" value="excel"> CSV
		  </td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">Active Only</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%">
        <input type="checkbox" NAME="activeonly" value="1" checked>
		  </td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">Sort By</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%">
			<SELECT NAME="sortby">
			  <option value="cust_code">Customer Code</option>
			  <option value="cust_zip">Zip Code</option>
			</SELECT>
		  </td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td width="10%">&nbsp;</td>
		  <td width="29%" bgcolor="silver">Avery#</td>
		  <td width="1%">&nbsp;</td>
		  <td width="50%">
			<SELECT NAME="avery">
			  <option value="5160">5160</option>
			</SELECT>
		  </td>
		  <td width="10%">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4" align="center">
        <INPUT TYPE="submit" name="process" value="Generate">
      </td>
		</tr>
		</FORM>
	  </table></td>
	</tr>
 </table>
