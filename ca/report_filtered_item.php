<?php
	include_once("class/class.formutils.php");
  include_once("class/register_globals.php");

	$f = new FormUtil();
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right"> 
    <td><strong>Filtered Item Report</strong></td>
  </tr>
  <tr>
    <td align="left"><font size="2">&nbsp; </font> 
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
	    <FORM METHOD=POST ACTION="report_filtered_item_out.php">
		<INPUT TYPE="hidden" name="ty" value="r">
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Item</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_item", $start_item, 20, 32, "inbox") ?>
			<A HREF="javascript:openItemBrowse('start_item')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Item</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_item", $end_item, 20, 32, "inbox") ?>
			  <A HREF="javascript:openItemBrowse('end_item')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Vendor</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_vendor", $start_vendor, 20, 32, "inbox") ?>
			<A HREF="javascript:openVendorBrowse('start_vendor')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Vendor</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_vendor", $end_vendor, 20, 32, "inbox") ?>
			  <A HREF="javascript:openVendorBrowse('end_vendor')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Product Line</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_prodline", $start_prodline, 20, 32, "inbox") ?>
			<A HREF="javascript:openProdLineBrowse('start_prodline')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Product Line</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_prodline", $end_prodline, 20, 32, "inbox") ?>
			  <A HREF="javascript:openProdLineBrowse('end_prodline')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Start Material</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("start_material", $start_material, 20, 32, "inbox") ?>
			<A HREF="javascript:openMaterialBrowse('start_material')">Lookup</a></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">End Material</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><?= $f->fillTextBox("end_material", $end_material, 20, 32, "inbox") ?>
			  <A HREF="javascript:openMaterialBrowse('end_material')">Lookup</a></td>
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
			  <OPTION VALUE="item">Item</OPTION>
			  <OPTION VALUE="vendor">Vendor</OPTION>
			  <OPTION VALUE="prodline">Product Line</OPTION>
			  <OPTION VALUE="material">Material</OPTION>
			</SELECT>
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Show Inactive</td>
          <td width="1%">&nbsp;</td>
          <td width="50%"><INPUT TYPE="checkbox" NAME="show_inactive" value="t"></td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td width="10%">&nbsp;</td>
          <td width="29%" bgcolor="silver">Output Method</td>
          <td width="1%">&nbsp;</td>
          <td width="50%">
		    <SELECT NAME="method">
			  <option value="html">HTML</option>
			  <option value="pdf">PDF</option>
<!--
			  <option value="text">TEXT</option>
			  <option value="print">Printer</option>
-->
			</SELECT>
		  </td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><INPUT TYPE="submit" name="process" value="Generate Report"></td>
        </tr>
	    </FORM>
      </table></td>
    </tr>
 </table>
