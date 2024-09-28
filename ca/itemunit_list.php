  <table width="100%" border="0" cellspacing="1" cellpadding="0">
  <?php


	if ($ty=="e") {
  ?>
    <tr>
      <td colspan="7" align="left"><font size="2"> | <a href="<?php echo "itemunits.php?ty=a&itemunit_item=$item_code" ?>">New Item/Unit</a> | </font></td>
    </tr>
  <?php
	}
  ?>
	<tr> 
       <th bgcolor="silver" width="10%">#</th>
       <th bgcolor="silver" width="15%">Unit</th>
       <th bgcolor="silver" width="10%">Factor</th>
       <th bgcolor="silver" width="10%">Qty</th>
       <th bgcolor="silver" width="10%">Cost</th>
       <th bgcolor="silver" width="10%">Sellable</th>
       <th bgcolor="silver" width="10%">Buyable</th>
       <th bgcolor="silver" width="10%">Stockable</th>
       <th bgcolor="silver" width="10%">Active</th>
       <th bgcolor="silver" width="5%">Del</th>
	</tr>
<?php
  	include_once("class/class.itemunits.php");
	$iu = new ItemUnits();
	$iu_arr = $iu->getItemUnitsListByItem($item_code);
	if ($iu_arr) {
		$iu_num = count($iu_arr);
		for ($i=0;$i<$iu_num;$i++) {
?>
	<tr> 
       <td align="center"><a href="<?= "itemunits.php?ty=$ty&itemunit_item=$item_code&itemunit_unit=".$iu_arr[$i]["itemunit_unit"] ?>"><?= $i+1 ?></a></td>
       <td align="center"><?= strtoupper($iu_arr[$i]["itemunit_unit"]) ?></td>
       <td align="right"><?= $iu_arr[$i]["itemunit_factor"]+0 ?></td>
       <td align="right"><?= $iu_arr[$i]["itemunit_qty"]+0 ?></td>
       <td align="right"><?= $iu_arr[$i]["itemunit_cost"] ?></td>
       <td align="center"><?= ($iu_arr[$i]["itemunit_sell"]=="t")?"True":"False" ?></td>
       <td align="center"><?= ($iu_arr[$i]["itemunit_buy"]=="t")?"True":"False" ?></td>
       <td align="center"><?= ($iu_arr[$i]["itemunit_stock"]=="t")?"True":"False" ?></td>
       <td align="center"><?= ($iu_arr[$i]["itemunit_active"]=="t")?"True":"False" ?></td>
<?php
	if ($ty=="e") {
?>
       <td align="center"><a href="<?= "ic_proc.php?cmd=itemunit_del&itemunit_item=$item_code&itemunit_unit=".$iu_arr[$i]["itemunit_unit"] ?>">X</a></td>
<?php
	} else {
?>
       <td align="center">X</td>
<?php
	}
?>
	</tr>
<?php
		}
	} else {
?>
	<tr> 
       <td colspan="7" align="center"><font color="red"><b>No Data!</b></font></td>
	</tr>
<?php
	}
?>
  </table>
