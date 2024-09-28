<?php
	include_once("class/register_globals.php");

if ($cmd =="itemunit_add") {
	if (empty($itemunit_item)) {
		$errno = 1;
		$errmsg = "Item code should't be blank";
		include("error.php");
		exit;
	}
	if (empty($itemunit_unit)) {
		$errno = 1;
		$errmsg = "unit code should't be blank";
		include("error.php");
		exit;
	}
	$iu = new ItemUnits();
	$r = new Requests();
	$arr = array();
	if ($check = $iu->getItemUnits($itemunit_item, $itemunit_unit)) {
		$errno = 1;
		$errmsg = "Item/Unit code exists already.";
		include("error.php");
		exit;
	} else {
		$oldrec = $iu->getItemUnitsFields();
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if ($arr["itemunit_buy"]!="t") $arr["itemunit_buy"]="f";
		else  $arr["itemunit_buy"]="t";
		if ($arr["itemunit_sell"]!="t") $arr["itemunit_sell"]="f";
		else $arr["itemunit_sell"]="t";
		if ($arr["itemunit_stock"]!="t") $arr["itemunit_stock"]="f";
		else $arr["itemunit_stock"]="t";
		$iu->insertItemUnits($arr); 
	}
	$loc = "Location: itemunits.php?ty=e&itemunit_item=$itemunit_item&itemunit_unit=$itemunit_unit";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="itemunit_edit") {
	if (empty($itemunit_item)) {
		$errno = 1;
		$errmsg = "Item code should't be blank";
		include("error.php");
		exit;
	}
	if (empty($itemunit_unit)) {
		$errno = 1;
		$errmsg = "Item code should't be blank";
		include("error.php");
		exit;
	}
	$iu = new ItemUnits();
	$r = new Requests();
	$arr = array();
	if ($check = $iu->getItemUnits($itemunit_item, $itemunit_unit)) {
		$oldrec = $iu->getItemUnitsFields();
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if ($arr["itemunit_buy"]!="t") $arr["itemunit_buy"]="f";
		else  $arr["itemunit_buy"]="t";
		if ($arr["itemunit_sell"]!="t") $arr["itemunit_sell"]="f";
		else $arr["itemunit_sell"]="t";
		if ($arr["itemunit_stock"]!="t") $arr["itemunit_stock"]="f";
		else $arr["itemunit_stock"]="t";
		if (!empty($arr)) $result = $iu->updateItemUnits($itemunit_item, $itemunit_unit, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find Item/Unit code entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: itemunits.php?ty=e&itemunit_item=$itemunit_item&itemunit_unit=$itemunit_unit";
	header($loc);
	exit;

} else if ($cmd =="itemunit_del") {
	if (empty($itemunit_item)) {
		$errno = 1;
		$errmsg = "Item code should't be blank";
		include("error.php");
		exit;
	}
	if (empty($itemunit_unit)) {
		$errno = 1;
		$errmsg = "Unit code should't be blank";
		include("error.php");
		exit;
	}
	$iu = new ItemUnits();
	if ($check = $iu->getItemUnits($itemunit_item, $itemunit_unit)) {
		$iu->deleteItemUnits($itemunit_item, $itemunit_unit);
	} else {
		$errno = 6;
		$errmsg = "Item/Unit Delete Failure";
		include("error.php");
		exit;
	}
	$loc = "Location: items.php?item_code=$itemunit_item&ty=e";
	if ($errno == 0) header($loc);
	exit;

}
?>