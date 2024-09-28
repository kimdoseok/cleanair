<?php
	include_once("class/register_globals.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Item Catalogue</TITLE>
<style type="text/css">
<!--
.engdesc {
	font-family: "Arial", "Helvetica", "sans-serif";
	font-size: 14px;
    line-height: 20px;
	font-weight: bold;
}
.kordesc {
	font-family: "Arial", "Helvetica", "sans-serif";
	font-size: 12px;
    line-height: 14px;
	font-weight: normal;
}
-->
</style>
</HEAD>
<BODY>
<TABLE align="center" width="680">
<TR>
	<TD colspan="1" align="left" class="engdesc">
        <A HREF="item_catalog.php?pg=<?php echo $_GET["pg"]; ?>&ft=<?php echo $_GET["ft"]; ?>">To List</A>

    </TD>
	<TD colspan="1" align="right" class="engdesc">
<?php
if (count($_SESSION["selections"])>0) {
?>
        <A HREF="pdf_itemcatalog.php">Generate PDF</A> &nbsp;
        <A HREF="catalog_proc.php?cmd=catalog_clear">Clear Selected</A>
<?php
}
?>
         &nbsp;
    </TD>
</TR>
<TR>
	<TD colspan="2" align="center">
        <TABLE align="center" width="680" cellspacing="2" cellpadding="1" border="0" bgcolor="#EEEEEE">
<?php

for ($i=0;$i<count($_SESSION["selections"]);$i++) {
    $item_code=$_SESSION["selections"][$i]["item_code"];
    echo "<tr bgcolor='#EEEEEE'>";
    echo "<TD bgcolor='white' width='110' valign='top' align='center' rowspan='3'><a name='$item_code'><IMG SRC='itemimage.php?id=$item_code&width=100' BORDER='0' ALT='' align='middle'></a></TD>";
    echo "<TD bgcolor='white' width='440' height='20' valign='top' class='engdesc'>".stripslashes($_SESSION["selections"][$i]["item_desc"])."</TD>";
    echo "<TD bgcolor='white' width='130' height='20' valign='top' class='engdesc' align='right'>";
    echo "<A HREF='catalog_proc.php?cmd=catalog_up&id=$item_code'>UP</A> / <A HREF='catalog_proc.php?cmd=catalog_down&id=$item_code'>DOWN</A> / <A HREF='catalog_proc.php?cmd=catalog_delete&id=$item_code'>DEL</A>";
    echo "</TD>";
    echo "</tr>\n";
    echo "<tr bgcolor='#EEEEEE'>";
    echo "<TD bgcolor='white' width='570' height='20' valign='top' class='kordesc' colspan='2'>";
    echo stripslashes($_SESSION["selections"][$i]["item_desc"])."<sup>[$item_code]</sup>";
    echo "</TD>";
    echo "</tr>\n";
    echo "<tr bgcolor='#EEEEEE'>";
    echo "<TD bgcolor='white' width='570' valign='top' class='kordesc' colspan='2'>";
    echo "($".number_format($_SESSION["selections"][$i]["item_msrp"],2,".",",")."/".strtoupper($_SESSION["selections"][$i]["item_unit"]).")";
    echo "</TD>";
    echo "</tr>\n";
}

?>
        </table>
    </TD>
</TR>

</TABLE>
</BODY>
</HTML>
<?php
//print_r($_SESSION["selections"]);
?>
