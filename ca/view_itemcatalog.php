<?php
	include_once("class/register_globals.php");

$cols = 4;
$rows = 5;

$ft = $_GET["ft"];

if ($_GET["pg"]>0) $curpage = $_GET["pg"];
else $curpage = 1;
if ($curpage) $startp = ($curpage-1)*$cols*$rows;
else $startp = 0;

function IsChecked($itemno) {
    for ($i=0;$i<count($_SESSION["selections"]);$i++) {
        if (strtolower(trim($_SESSION["selections"][$i][itemno]))==trim($itemno)) {
            echo trim($_SESSION["selections"][$i][itemno]).",".trim($itemno);
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
    window.location="./?cmd=icat&pg=<?= $curpage ?>&c=<?= $cols ?>&r=<?= $rows ?>&ft=<?= $ft ?>&scm=f";
}

function selectFiltered() {
    window.location="./?cmd=icat&pg=<?= $curpage ?>&ft=<?= $ft ?>&scm=f";
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
if (count($_SESSION["selections"])) echo "<A HREF='./?p=icats'>View Selection</A>";
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
<?php
    $item_code = $_GET["id"];

    $query = "SELECT * FROM items i WHERE i.item_code LIKE '$item_code' ";
    
    $res = odbc_exec($conx, $query);
    if ($row = odbc_fetch_row($res)) {
        $item_name = odbc_result($res, "item_name");
        $item_desc = odbc_result($res, "item_desc");
        $item_unit = odbc_result($res, "item_unit");
        $item_qty_onhnd = odbc_result($res, "item_qty_onhnd");
        $item_msrp = odbc_result($res, "item_msrp");
        //$cbarcode1 = odbc_result($res, "cbarcode1");
    }
    odbc_free_result($res);
    
?>
<TR>
    <TD colspan="2" align="center">
        <TABLE align="center" width="100%" cellspacing="8" cellpadding="5" border="0" bgcolor="#EEEEEE">
            <TR>
                <TD align="center" colspan="2">
                    <b><?= strtoupper($item_code) ?></b>
                </TD>
            </TR>
            <TR>
                <TD align="left" bgcolor="white" width="80%" valign="top">
                  <!--<div class='engdesc'><?= stripslashes($item_desc) ?></div>-->
                  <div class='kordesc'><?= stripslashes($item_desc) ?></div>
                  <div class='kordesc'>($<?= number_format($item_msrp,2,".",",") ?>/<?= strtoupper($item_unit) ?>)</div>
                </TD>
                <TD align="center" bgcolor="white" width="20%" valign="top">
                    <IMG SRC='ean128.php?bc=<?= $item_code ?>' BORDER='0' ALT='' align='middle'>
                </TD>
            </TR>
            <TR bgcolor="white">
                <TD align="center" colspan="2">
<?php
//echo $filename;
$imgfile = $_SERVER["DOCUMENT_ROOT"]."item_images/".strtolower(trim($item_code)).".jpg";
if (file_exists($imgfile)) echo "<IMG SRC='itemimage.php?id=$item_code&width=600' BORDER='0' ALT='' align='middle'>";
?>
                </TD>
            </TR>
            <TR bgcolor="white">
                <TD align="center" colspan="2">
                    <form name="imgupload" enctype="multipart/form-data" method="POST" action="catalog_proc.php">
                    <input type="file" name="upload">
                    <input type="hidden" name="id" value="<?= $item_code ?>">
                    <input type="hidden" name="pg" value="<?= $_GET["pg"] ?>">
                    <input type="hidden" name="ft" value="<?= $_GET["ft"] ?>">
                    <input type="hidden" name="ty" value="<?= $_GET["ty"] ?>">
                    <input type="hidden" name="cmd" value="catalog_picture"> &nbsp;
                    <input type="submit">
                    </form>
                </TD>
            </TR>
        </table>
    </TD>
</TR>
</TABLE>
</BODY>
</HTML>
<?php
?>
