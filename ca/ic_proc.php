<?php
//error_reporting(0);
include_once("class/map.default.php");
include_once("class/class.items.php");
include_once("class/class.itemunits.php");
include_once("class/class.itemtrxs.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.requests.php");
include_once("class/class.category.php");
include_once("class/class.saledtls.php");
include_once("class/class.unit_measures.php");
include_once("class/class.materials.php");
include_once("class/class.productlines.php");
include_once("class/class.itemunits.php");
include_once("class/class.itemhists.php");
include_once("class/class.accounts.php");
include_once("class/register_globals.php");


$errno = 0;
$olds = $default["comp_code"]."_olds";

include_once("common_proc.php");

if ($cmd =="item_edit") {
	$c = new Items();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getItems($item_code)) {
		$oldrec = $_SESSION[$olds];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if (!empty($oldrec)) $result = $c->updateItems($item_code, $arr); 
		if ($result && ($check["item_msrp"]!=$arr["item_unit"])) {
			$ih = new ItemHists();
			$ih_arr = array();
			$ih_arr[itemhist_item_code] = $item_code;
			$ih_arr[itemhist_type] = "p";
			$ih_arr["itemhist_prc_old"] = $check["item_msrp"];
			$ih_arr["itemhist_prc_new"] = $_POST["item_msrp"];
			$ih_arr[itemhist_unit] = $_POST["item_unit"];
			$ih_arr["itemhist_user"] = $_SERVER["PHP_AUTH_USER"];
			$ih->insertItemHists($ih_arr);
		}
		$iu = new ItemUnits();
		if (!$iu->getItemUnits($item_code, $item_unit)) {
			$iu_arr = array();
			$iu_arr[itemunit_item] = $item_code;
			$iu_arr["itemunit_unit"] = $item_unit;
			$iu_arr["itemunit_cost"] = $item_msrp;
			$iu_arr["itemunit_cost"] = $item_ave_cost;
			$iu->insertItemUnits($iu_arr);
		}
	} else {
		$errno = 2;
		$errmsg = "Couldn't find item code entered.";
		include("error.php");
		exit;
	}
	//$loc = "Location: http://" . $_SERVER['HTTP_HOST'] . dirname(htmlentities($_SERVER['PHP_SELF'])) . "/items.php?ty=e&item_code=$item_code";
	$loc = "Location: items.php?ty=e&item_code=$item_code";
	header($loc);
	exit;

} else if ($cmd =="item_add") {
		if (empty($item_code)) {
			$errno = 1;
			$errmsg = "Item code should't be blank";
			include("error.php");
			exit;
		}
		if (empty($item_unit)) {
			$errno = 1;
			$errmsg = "Item unit code should be selected";
			include("error.php");
			exit;
		}
		$c = new Items();
		$r = new Requests();
		$arr = array();
		if ($check = $c->getItems($item_code)) {
			$errno = 1;
			$errmsg = "Item code should be unique";
			include("error.php");
			exit;
		} else {
			$oldrec = $_SESSION[$olds];
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			if ($c->insertItems($arr)) {
				$iu = new ItemUnits();
				$iu_arr = array();
				$iu_arr[itemunit_item] = $item_code;
				$iu_arr["itemunit_unit"] = $item_unit;
				$iu_arr["itemunit_cost"] = $item_msrp;
				$iu_arr["itemunit_cost"] = $item_ave_cost;
				$iu->insertItemUnits($iu_arr);
			}

		}
		$loc = "Location: items.php?ty=e&item_code=$item_code";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="item_del") {
	$iu = new ItemUnits();
	$c = new Items();
	if ($check = $c->getItems($item_code)) { 
		$sd = new SaleDtls();
		$num = $sd->getSaleDtlsRowsItem($item_code);
		if ($num<=0 || !$num) {
			$c->deleteItems($item_code);
			$iu->deleteItemUnitsByItem($item_code);

		}
	} else {
		$errno = 6;
		$errmsg = "Item Delete Failure";
		include("error.php");
		exit;
	}
	$loc = "Location: items.php?ty=l";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="item_price") {
	$c = new Items();
	$arr = $c->getItems($item_code);
	$updarr = array();
	if ($adj_type == "p") $updarr["item_msrp"] = sprintf("%0.2f",$arr["item_msrp"]+($arr["item_msrp"]*$adj_pct/100));
	//else $updarr["item_msrp"] = sprintf("%0.2f",$arr["item_msrp"]+$adj_pct);
	else $updarr["item_msrp"] = sprintf("%0.2f",$adj_pct);
	$c->updateItems($item_code, $updarr);
	$histarr = array();
	$histarr[itemhist_item_code] = $item_code;
	$histarr[itemhist_type] = "p";
	$histarr[itemhist_qty_old] = 0;
	$histarr[itemhist_qty_new] = 0;
	$histarr["itemhist_prc_old"] = $arr["item_msrp"];
	$histarr["itemhist_prc_new"] = $updarr["item_msrp"];
	$histarr["itemhist_user"] = $_SERVER["PHP_AUTH_USER"];
	$ih = new ItemHists();
	$ih->insertItemHists($histarr);
	echo "<script>";
	echo "self.opener.location='items.php?ft=$ft&cn=$cn&rv=&rv=&pg=&pg';";
	echo "self.close();";
	echo "</script>";

} else if ($cmd =="cate_edit") {
	$c = new Category();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getCategory($cate_code)) {
		$oldrec = $_SESSION[$olds];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if (isset($arr[cate_tax]) && $arr[cate_tax]!='t') $arr[cate_tax] = 'f';
		if (!empty($oldrec)) $result = $c->updateCategory($cate_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find category code entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: category.php?ty=e&cate_code=$cate_code";
	header($loc);
	exit;

} else if ($cmd =="cate_add") {
		if (empty($cate_code)) {
			$errno = 1;
			$errmsg = "Category code should't be blank";
			include("error.php");
			exit;
		}
		$c = new Category();
		$r = new Requests();
		$arr = array();
		if ($check = $c->getCategory($cate_code)) {
			$errno = 1;
			$errmsg = "Category code should be unique";
			include("error.php");
			exit;
		} else {
			$oldrec = $_SESSION[$olds];
			$arr = $r->getAlteredArray($oldrec, $_POST); 
			$c->insertCategory($arr); 
		}
		$loc = "Location: category.php?ty=e&cate_code=$cate_code";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="cate_price") {
	$c = new Items();
	$arr = $c->getCatItems($cate_code);
	if ($arr) $cnt = count($arr);
	else $cnt = 0;
	for ($i=0;$i<$cnt;$i++) {
		$updarr = array();
		if ($adj_type == "p") $updarr["item_msrp"] = sprintf("%0.2f",$arr[$i]["item_msrp"]+($arr[$i]["item_msrp"]*$adj_pct/100));
		else $updarr["item_msrp"] = sprintf("%0.2f",$arr[$i]["item_msrp"]+$adj_pct);
		$c->updateItems($arr[$i]["item_code"], $updarr);
		$histarr = array();
		$histarr[itemhist_item_code] = $arr[$i]["item_code"];
		$histarr[itemhist_type] = "p";
		$histarr[itemhist_qty_old] = 0;
		$histarr[itemhist_qty_new] = 0;
		$histarr[itemhist_amt_old] = $arr[$i]["item_msrp"];
		$histarr[itemhist_amt_new] = $updarr["item_msrp"];
		$histarr["itemhist_user"] = $_SERVER["PHP_AUTH_USER"];
		$c->insertItemHists($histarr);
	}
	echo "<script>self.close()</script>";

} else if ($cmd =="cate_del") {
	if (empty($unit_code)) {
		$errno = 1;
		$errmsg = "Unit code should't be blank";
		include("error.php");
		exit;
	}
	$c = new Category();
	if ($check = $c->getCategory($cate_code)) { 
		$c->deleteCategory($cate_code);
	} else {
		$errno = 6;
		$errmsg = "Category Delete Failure";
		include("error.php");
		exit;
	}
	$loc = "Location: category.php?ty=l";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="unit_add") {
	if (empty($unit_code)) {
		$errno = 1;
		$errmsg = "Unit code should't be blank";
		include("error.php");
		exit;
	}
	$c = new UnitMeasures();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getUnitMeasures($unit_code)) {
		$errno = 1;
		$errmsg = "Unit code should be unique";
		include("error.php");
		exit;
	} else {
		$oldrec = $_SESSION[$olds];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		$c->insertUnitMeasures($arr); 
	}
	$loc = "Location: unit_measure.php?ty=e&unit_code=$unit_code";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="unit_edit") {
	if (empty($unit_code)) {
		$errno = 1;
		$errmsg = "Unit code should't be blank";
		include("error.php");
		exit;
	}
	$c = new UnitMeasures();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getUnitMeasures($unit_code)) {
		$oldrec = $_SESSION[$olds];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if (isset($arr[unit_prime]) && $arr[unit_prime]!='t') $arr[unit_prime] = 'f';
		if (!empty($oldrec)) $result = $c->updateUnitMeasures($unit_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find unit code entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: unit_measure.php?ty=e&unit_code=$unit_code";
	header($loc);
	exit;

} else if ($cmd =="unit_del") {
	if (empty($unit_code)) {
		$errno = 1;
		$errmsg = "Unit code should't be blank";
		include("error.php");
		exit;
	}
	$c = new UnitMeasures();
	if ($check = $c->getUnitMeasures($unit_code)) { 
		$c->deleteUnitMeasures($unit_code);
	} else {
		$errno = 6;
		$errmsg = "Unit of Measure Delete Failure";
		include("error.php");
		exit;
	}
	$loc = "Location: unit_measure.php?ty=l";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="material_add") {
	if (empty($material_code)) {
		$errno = 1;
		$errmsg = "Material code should't be blank";
		include("error.php");
		exit;
	}
	$c = new Material();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getMaterial($material_code)) {
		$errno = 1;
		$errmsg = "Material code should be unique";
		include("error.php");
		exit;
	} else {
		$oldrec = $_SESSION[$olds];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		$c->insertMaterial($arr); 
	}
	$loc = "Location: material.php?ty=e&material_code=$material_code";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="material_edit") {
	if (empty($material_code)) {
		$errno = 1;
		$errmsg = "Material code should't be blank";
		include("error.php");
		exit;
	}
	$c = new Material();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getMaterial($material_code)) {
		$oldrec = $_SESSION[$olds];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if (!empty($oldrec)) $result = $c->updateMaterial($material_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find material code entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: material.php?ty=e&material_code=$material_code";
	header($loc);
	exit;

} else if ($cmd =="material_del") {
	if (empty($material_code)) {
		$errno = 1;
		$errmsg = "Material code should't be blank";
		include("error.php");
		exit;
	}
	$c = new Material();
	if ($check = $c->getMaterial($material_code)) { 
		$c->deleteMaterial($material_code);
	} else {
		$errno = 6;
		$errmsg = "Material Delete Failure";
		include("error.php");
		exit;
	}
	$loc = "Location: material.php?ty=l";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="product_line_add") {
	if (empty($productline_code)) {
		$errno = 1;
		$errmsg = "Product line code should't be blank";
		include("error.php");
		exit;
	}
	$c = new ProductLine();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getProductLine($productline_code)) {
		$errno = 1;
		$errmsg = "Product line code should be unique";
		include("error.php");
		exit;
	} else {
		$oldrec = $_SESSION[$olds];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		$c->insertProductLine($arr); 
	}
	$loc = "Location: product_line.php?ty=e&productline_code=$productline_code";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="product_line_edit") {
	if (empty($productline_code)) {
		$errno = 1;
		$errmsg = "Product line code should't be blank";
		include("error.php");
		exit;
	}
	$c = new ProductLine();
	$r = new Requests();
	$arr = array();
	if ($check = $c->getProductLine($productline_code)) {
		$oldrec = $_SESSION[$olds];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if (!empty($oldrec)) $result = $c->updateProductLine($productline_code, $arr); 
	} else {
		$errno = 2;
		$errmsg = "Couldn't find product line code entered.";
		include("error.php");
		exit;
	}
	$loc = "Location: product_line.php?ty=e&productline_code=$productline_code";
	header($loc);
	exit;

} else if ($cmd =="product_line_del") {
	if (empty($productline_code)) {
		$errno = 1;
		$errmsg = "Product line code should't be blank";
		include("error.php");
		exit;
	}
	$c = new ProductLine();
	if ($check = $c->getProductLine($productline_code)) { 
		$c->deleteProductLine($productline_code);
	} else {
		$errno = 6;
		$errmsg = "Product Line Delete Failure";
		include("error.php");
		exit;
	}
	$loc = "Location: product_line.php?ty=l";
	if ($errno == 0) header($loc);
	exit;

} else if (substr($cmd, 0, 9) == "itemunit_") {
	include("itemunit_proc.php");

} else if (substr($cmd, 0, 6) == "style_") {
	include("styles_proc.php");

} else if (substr($cmd, 0, 9) == "itemtrxs_") {
	include("itemtrxs_proc.php");

} else {
	header("Location: $HTTP_REFERER");
	exit;

}
?>