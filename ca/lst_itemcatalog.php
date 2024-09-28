<?php
include_once("class/register_globals.php");

$ft = $_GET["ft"];

if ($_GET["pg"]>0) $curpage = $_GET["pg"];
else $curpage = 1;
if ($curpage) $startp = ($curpage-1)*$cols*$rows;
else $startp = 0;

function IsChecked($item_code) {
    for ($i=0;$i<count($_SESSION["selections"]);$i++) {
        if (strtolower(trim($_SESSION["selections"][$i]["item_code"]))==trim($item_code)) {
            echo trim($_SESSION["selections"][$i]["item_code"]).",".trim($item_code);
            return true;
        }
    }
    return false;
}

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

<SCRIPT LANGUAGE="JavaScript">
<!--
function selectPage() {
    window.location="catalog_proc.php?cmd=catalog_page&pg=<?= $curpage ?>&c=<?= $cols ?>&r=<?= $rows ?>&ft=<?= $ft ?>";
}

function selectFiltered() {
    window.location="catalog_proc.php?cmd=catalog_filter&pg=<?= $curpage ?>&ft=<?= $ft ?>";
}

function selectChecked() {
    var form = document.forms['checked'];
    form.submit();
}


//-->
</SCRIPT>
</HEAD>
<BODY>
<TABLE align="center" width="<?= $width ?>">
<FORM METHOD="GET" ACTION="<?= $_SERVER[PHP_SELF] ?>">
<INPUT type="hidden" NAME="p" VALUE="<?= $authcode ?>">
<TR>
	<TD colspan="1" width="50%" align="left" class="engdesc">
<?php
if (count($_SESSION["selections"])) echo "<A HREF='item_catalog.php?ty=s&pg=$curpage&ft=".$_GET["ft"]."'>View Selection</A>";
echo " &nbsp; ";
if (!empty($_GET["ty"])) echo "<A HREF='item_catalog.php?pg=$curpage&ft=$ft'>To List</A>";
?>
    </TD>
	<TD colspan="1" width="50%" align="right">
        Item#:
        <input type="text" name="ft" value="<?= $_GET["ft"] ?>">
        <input type="submit" value="Filter">
    </TD>
</TR>
</form>

<TR>
	<TD colspan="2" align="center">
        <TABLE align="center" width="100%" cellspacing="8" cellpadding="5" border="0" bgcolor="#EEEEEE">
            <FORM name="checked" method="POST" action="catalog_proc.php">
                <input type="hidden" name="cmd" value="catalog_checked">
                <input type="hidden" name="pg" value="<?= $_GET["pg"] ?>">
                <input type="hidden" name="ft" value="<?= $_GET["ft"] ?>">
<?php

$numrows = 0;
$query = "SELECT count(i.item_code) as numrows FROM items i WHERE i.item_active<>'f' AND i.item_code LIKE '$ft%' ";
$res = odbc_exec($conx, $query);
if ($row = odbc_fetch_row($res)) {
    $numrows = odbc_result($res, "numrows");
}
odbc_free_result($res);

$lastpage = ceil($numrows/($cols*$rows)*1.0);
if ($lastpage<1) $lastpage = 1;
if ($curpage<=1) $prevpage = 1;
else $prevpage = $curpage-1;
if ($curpage>=$lastpage) $nextpage=$lastpage;
else $nextpage=$curpage+1;

$query = "SELECT i.item_code, i.item_name, i.item_desc, i.item_unit, i.item_qty_onhnd, i.item_msrp ";
$query .= "FROM items i ";
$query .= "WHERE i.item_active<>'f' AND i.item_code LIKE '$ft%' ";
$query .= "ORDER BY i.item_code ";
$res = odbc_exec($conx,$query);

$fetchrec = true;
if ($numrows<$startp) $fetchrec = false;

for ($i=0;$i<$rows;$i++) {
    echo "<tr bgcolor='#EEEEEE'>";
    
    for ($j=0;$j<$cols;$j++) {
        $idx = $startp+$i*$cols+$j+1;
        if ($fetchrec) {
            $line = odbc_fetch_row($res, $idx);
            if (!$line) $fetchrec = false;
        }
        echo "<TD bgcolor='white' width='25%' height='180' valign='top'>";
        if ($fetchrec) {
            $item_code = strtolower(trim(odbc_result($res, "item_code")));
            $item_name = trim(odbc_result($res, "item_name"));
            $item_desc = trim(odbc_result($res, "item_desc"));
            $item_unit = trim(odbc_result($res, "item_unit"));
            $item_qty_onhnd = odbc_result($res, "item_qty_onhnd");
            $item_msrp = odbc_result($res, "item_msrp");
            echo "<A HREF='".$_SERVER[PHP_SELF]."?p=$authcode&pg=$curpage&ft=$ft&ty=v&id=$item_code'>";
            //echo "<div class='engdesc'>$item_name</div>";
            echo "<div class='kordesc'>".stripslashes($item_desc)." <sup>[".strtoupper($item_code)."]</sup></div>";
            echo "</A>";
            echo "<div class='kordesc'>($";
            echo number_format($item_msrp,2,".",",");
            //echo "/".($item_qty_onhnd+0);
            echo "/".strtoupper($item_unit);
            echo ")</div>";
            $imgfile = $_SERVER["DOCUMENT_ROOT"]."item_images/".strtolower(trim($item_code)).".jpg";
            //echo $imgfile;
            if (file_exists($imgfile)) {
                echo "<A HREF='".$_SERVER[PHP_SELF]."?pg=$curpage&ft=$ft&ty=v&id=$item_code'>";
                echo "<IMG SRC='itemimage.php?id=$item_code&width=70' BORDER='0' ALT='' align='middle'>";
                echo "</A>";
                echo "<INPUT TYPE='checkbox' NAME='cboxes[]' VALUE='$item_code'";
                if (IsChecked($item_code)) echo " CHECKED";
                echo ">";
                echo "<A HREF='catalog_proc.php?cmd=catalog_select&pg=$curpage&ft=$ft&id=".urlencode($item_code)."'>SELECT</A>";
            }
        } else {
            echo "&nbsp;";
            $fetchrec = false;
        }
        echo "</TD>\n";
    }
    echo "</tr>\n";
}
odbc_free_result($res);
odbc_close($conx);

?>
            </FORM>
        </table>
    </TD>
</TR>
<TR>
	<TD colspan="1" bgcolor="white" align="center">
        <A HREF="<?= $_SERVER[PHP_SELF] ?>?pg=1&ft=<?= $ft ?>">&lt;&lt;</A> &nbsp;
        <A HREF="<?= $_SERVER[PHP_SELF] ?>?<?= "pg=$prevpage&ft=$ft" ?>">&lt;</A> &nbsp;
        <?= "$curpage/$lastpage" ?> &nbsp; 
        <A HREF="<?= $_SERVER[PHP_SELF] ?>?<?= "pg=$nextpage&ft=$ft" ?>">&gt;</A> &nbsp;
        <A HREF="<?= $_SERVER[PHP_SELF] ?>?<?= "pg=$lastpage&ft=$ft" ?>">&gt;&gt;</A>
    </TD>
	<TD colspan="1" bgcolor="white" align="right">
      <input type="button" NAME="selectchecked" VALUE="Select Checked" onClick="selectChecked()">
      <input type="button" NAME="selectpage" VALUE="Select Page" onClick="selectPage()">
      <input type="button" NAME="selectfiltered" VALUE="Select Filtered" onClick="selectFiltered()">
    </TD>
</TR>
</TABLE>
</BODY>
</HTML>
<?php
?>
