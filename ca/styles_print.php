<?php
	include_once("class/class.formutils.php");
	include_once("class/class.styles.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.styldtls.php");
	include_once("class/class.items.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/register_globals.php");

//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
//-----------------------------------------------------------------------
	$d = new Datex();
	$f = new FormUtil();
	$s = new Styles();
	$y = new StylDtls();
	$t = new Items();

	if ($styl = $s->getStyles($styl_code)) foreach($styl as $k => $v) $$k = $v; 
	$recs = $y->getStylDtlsList($styl_code);
?>
<html>
<head>
<title> 
<?= $label[$lang][Styles] ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function OnPrint() {
	window.print();
}

//-->
</SCRIPT>
<style type="text/css">
<!--
.between {  border-color: white black; border-style: solid; border-top-width: auto; border-right-width: auto; border-bottom-width: auto; border-left-width: auto}
-->
</style>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  onLoad="OnPrint()"">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td><strong><?= $label[$lang][Cost_Sheet] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> 
      <table width="100%" border="1" cellspacing="0" cellpadding="1">
        <tr> 
          <td align="right" bgcolor="silver"> 
            <?= $label[$lang]["PO_no"] ?>
            :&nbsp;</td>
          <td> 
            <?= $styl_po_no ?>
          </td>
          <td width="10" class="between">&nbsp;</td>
          <td width="120" align="right" bgcolor="silver"> 
            <?= $label[$lang]["Date_1"] ?>
            :&nbsp;</td>
          <td width="265"> 
            <?= $styl_date ?>
          </td>
        </tr>
        <tr> 
          <td align="right" bgcolor="silver"> 
            <?= $label[$lang][Style_no] ?>
            :&nbsp;</td>
          <td> 
            <?= $styl_code ?>
          </td>
          <td width="10" class="between">&nbsp;</td>
          <td align="right" bgcolor="silver"> 
            <?= $label[$lang]["Description"] ?>
            :&nbsp;</td>
          <td> 
            <?= $styl_desc ?>
          </td>
        </tr>
        <tr> 
          <td width="120" align="right" bgcolor="silver"> 
            <?= $label[$lang][Work_Qty] ?>
            :&nbsp;</td>
          <td width="265"> 
            <?= $styl_qty_work ?>
          </td>
          <td width="10" class="between">&nbsp;</td>
          <td align="right" bgcolor="silver"> 
            <?= $label[$lang]["Cust_no"] ?>
            :&nbsp;</td>
          <td> 
            <?= $styl_cust_code ?>
          </td>
        </tr>
        <tr> 
          <td width="120" align="right" bgcolor="silver"> 
            <?= $label[$lang][Qty_Board] ?>
            :&nbsp;</td>
          <td width="265"> 
            <?= $styl_qty_board ?>
          </td>
          <td width="10" class="between">&nbsp;</td>
          <td width="140" align="right" bgcolor="silver"> 
            <?= $label[$lang][On_Board_Date] ?>
            :&nbsp;</td>
          <td> 
            <?= $styl_onbrd_date ?>
            &nbsp; </td>
        </tr>
        <tr> 
          <td width="120" align="right" bgcolor="silver"> 
            <?= $label[$lang][USD_Cost] ?>
            :&nbsp;</td>
          <td width="265"> 
            <?= $styl_cost_usd ?>
          </td>
          <td width="10" class="between">&nbsp;</td>
          <td align="right" bgcolor="silver"> 
            <?= $label[$lang][RMB_Cost] ?>
            :&nbsp;</td>
          <td> 
            <?= $styl_cost_rmb ?>
          </td>
        </tr>
        <tr> 
          <td align="right" bgcolor="silver"> 
            <?= $label[$lang][Status] ?>
            :&nbsp;</td>
          <td> 
<?php
				if ($styl_status=="o") echo $label[$lang][Ordered];
				else if ($styl_status=="w") echo $label[$lang][Working];
                  else if ($styl_status=="f") echo $label[$lang][Finished];
                  else if ($styl_status=="h") echo $label[$lang][Hold];
                  else if ($styl_status=="c") echo $label[$lang][Cancled];
?>
          </td>
          <td class="between">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
                    </tr>
                    <tr align="right"> 
						<td colspan="7">&nbsp</td>
                    </tr>
                    <tr align="right"> 
    <td colspan="7"> <table width="100%" border="1" cellspacing="0" cellpadding="1">
        <tr> 
          <th bgcolor="gray" width="34"><font color="white"> 
            <?= $label[$lang]["No"] ?>
            </font></th>
          <th colspan="2" bgcolor="gray"><font color="white"> 
            <?= $label[$lang]["Item"] ?>
            </font></th>
          <th bgcolor="gray" width="120"><font color="white"> 
            <?= $label[$lang][Meter] ?>
            / 
            <?= $label[$lang][Pair] ?>
            </font></th>
          <th bgcolor="gray" width="120"><font color="white"> 
            <?= $label[$lang][RMB] ?>
            / 
            <?= $label[$lang][Meter] ?>
            </font></th>
          <th bgcolor="gray" width="120"><font color="white"> 
            <?= $label[$lang][RMB] ?>
            / 
            <?= $label[$lang][Pair] ?>
            </font></th>
        </tr>
<?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i][styldtl_item_code]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
        <td align="center">
          <?= $i+1 ?>
        </td>
        <td width="68"> 
          <?= $recs[$i][styldtl_item_code] ?>
        </td>
        <td width="337"> 
          <?= $arr["item_desc"] ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][styldtl_meter_per_pair]+0 ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][styldtl_rmb_per_meter]+0 ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][styldtl_rmb_per_pair]+0 ?>
          <a href="ic_proc.php?cmd=style_detail_sess_del&did=<?= $i ?>"> </a> 
        </td>
        </tr>
<?php
		}
	}
	if (count($arr) == 0) {
?>
        <tr bgcolor="#EEEEEE"> 
          <td colspan="6" align="center"> <b> 
            <?= $label[$lang]["Empty_1"] ?>
            !</b> </td>
        </tr>
<?php
	}
?>
      </table></td>
                    </tr>
                  </table>
<?php
//	print_r($_SESSION);
?>