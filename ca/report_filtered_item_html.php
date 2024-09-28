<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.items.php");

  $vars = array("show_inactive","show_amount");
  foreach ($vars as $var) {
  $$var = "";
  } 
  $vars = array("pg","cn");
  foreach ($vars as $var) {
  $$var = 0;
  } 

	include_once("class/register_globals.php");


	$dx = new Datex();
	$it = new Items();

	$it->start_item = $start_item;
	$it->end_item = $end_item;
	$it->start_vendor = $start_vendor;
	$it->end_vendor = $end_vendor;
	$it->start_prodline = $start_prodline;
	$it->end_prodline = $end_prodline;
	$it->start_material = $start_material;
	$it->end_material = $end_material;
	if ($show_inactive!='t') $it->active = 't';

	$it_arr = $it->getItemsListRange();
	if ($it_arr) $it_num = count($it_arr);
	else $it_num = 0;
?>
<HTML>
<HEAD>
<TITLE>Filtered Item Report</TITLE>
</HEAD>
<BODY>
   <table width="100%" border="0" cellspacing="1" cellpadding="0">
	  <tr> 
		<td align="center"><strong><FONT SIZE="5" COLOR="red">Filtered Item Report</strong></FONT></td>
	  </tr>
	  <tr>
		<td align="center">
		  <font size="3">
			Item from "<?= (empty($start_item))?"First":$start_item ?>" to "<?= (empty($end_item))?"Last":$end_item ?>", 
			Vendor from "<?= (empty($start_vendor))?"First":$start_vendor ?>" to "<?= (empty($end_vendor))?"Last":$end_vendor ?>", 
			Product Line from "<?= (empty($start_prodline))?"First":$start_prodline ?>" to "<?= (empty($end_prodline))?"Last":$end_prodline ?>", 
			Material from "<?= (empty($start_material))?"First":$start_material ?>" to "<?= (empty($end_material))?"Last":$end_material ?>"
		  </font> 
		</td>
	  </tr>
      <tr align="right"> 
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr> 
              <th bgcolor="gray" width="3%"><font color="white">#</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Code</font></th>
              <th bgcolor="gray" width="45%"><font color="white">Desciption</font></th>
              <th bgcolor="gray" width="10%"><font color="white">Vendor</font></th>
              <th bgcolor="gray" width="5%"><font color="white">Prod.Line</font></th>
              <th bgcolor="gray" width="8%"><font color="white">Material</font></th>
              <th bgcolor="gray" width="5%"><font color="white">Unit</font></th>
              <th bgcolor="gray" width="8%"><font color="white">Price</font></th>
              <th bgcolor="gray" width="8%"><font color="white">OnHand</font></th>
            </tr>
<?php
	$x = 0;
	for ($i=0; $i<$it_num; $i++) {	
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
       <td align="center"> 
         <?= $i+1 ?>
       </td>
       <td align="left"> 
         <?= $it_arr[$i]["item_code"] ?>
       </td>
       <td align="left"> 
         <?= $it_arr[$i]["item_desc"] ?>
       </td>
       <td align="left"> 
         <?= $it_arr[$i]["item_vend_code"] ?>
       </td>
       <td align="left"> 
         <?= $it_arr[$i]["item_prod_line"] ?>
       </td>
       <td align="left"> 
         <?= $it_arr[$i]["item_material"] ?>
       </td>
       <td align="left"> 
         <?= strtoupper($it_arr[$i]["item_unit"]) ?>
       </td>
       <td align="right"> 
         <?= $it_arr[$i]["item_msrp"] ?>
       </td>
       <td align="right"> 
         <?= $it_arr[$i]["item_qty_onhnd"]+0 ?>
       </td>
<?php
	if ($show_amount == "t") {
?>
       <td align="right"> 
         <?= number_format($it_arr[$i]["prc"], 2, ".", ",") ?>
       </td>
       <td align="right"> 
         <?= number_format($it_arr[$i]["amt"], 2, ".", ",") ?>
       </td>
<?php
	}
?>
     </tr>
<?php
	}
?>
	</table></td>
   </tr>
 </table>
</BODY>
</HTML>