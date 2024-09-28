<?php
include_once("class/map.default.php");
include_once("class/class.items.php");
include_once("class/class.itemunits.php");
include_once("class/class.itemtrxs.php");
include_once("class/class.jrnltrxs.php");
include_once("class/class.requests.php");
include_once("class/class.unit_measures.php");
include_once("class/class.accounts.php");
include_once("class/class.unit.php");
include_once("class/register_globals.php");

if ($cmd =="itemtrxs_del") {
	$c = new ItemTrxs();
	$r = new Requests();
	$i = new Items();
	$u = new Unit();
	$iu = new ItemUnits();
	$j = new JrnlTrxs();
	$arr = array();
	if ($itx = $c->getItemTrxs($invtrx_id)) {
		$it_arr =  $i->getItems($itx[invtrx_item_code]);
		$iu_arr_fr = $iu->getItemUnits($itx[invtrx_item_code], $it_arr["item_unit"]);
		$iu_arr_to = $iu->getItemUnits($itx[invtrx_item_code], $itx[invtrx_unit]);
		if ($iu_arr_fr["itemunit_factor"]!=0) $ratio = $iu_arr_to["itemunit_factor"]/$iu_arr_fr["itemunit_factor"];
		if (round($ratio)>0) $item_qty = ceil($itx[invtrx_qty]*$ratio);
		else $item_qty = floor($itx[invtrx_qty]*$ratio);

		if ($itx[invtrx_type] == "s") {
			$res = $iu->updateItemUnitsAmt($itx[invtrx_item_code], $it_arr["item_unit"], "itemunit_qty", $item_qty);
			if (!$res) {
				$errno = 5;
				$errmsg = "Failure of Item/Unit Quantity Update!";
				include("error.php");
				exit;
			}
			if (!$i->updateItemsQty($itx[invtrx_item_code], 0, $item_qty)) {
				$errno = 4;
				$errmsg = "Failure of Item On-Hand Quantity Update!";
				include("error.php");
				exit;
			}
			$c->deleteItemTrxs($invtrx_id);
			$j->deleteJrnlTrxRefs($invtrx_id, "i");
		} else if ($itx[invtrx_type] == "r") {
			$res = $iu->updateItemUnitsAmt($itx[invtrx_item_code], $it_arr["item_unit"], "itemunit_qty", $item_qty*-1);
			if (!$res) {
				$errno = 5;
				$errmsg = "Failure of Item/Unit Quantity Update!";
				include("error.php");
				exit;
			}
			if (!$i->updateItemsQty($itx[invtrx_item_code], $item_qty, 0)) {
				$errno = 4;
				$errmsg = "Failure of Item On-Hand Quantity!";
				include("error.php");
				exit;
			}
			$c->deleteItemTrxs($invtrx_id);
			$j->deleteJrnlTrxRefs($invtrx_id, "i");
		} else if ($itx[invtrx_type] == "a") {
			$res = $iu->updateItemUnitsAmt($itx[invtrx_item_code], $it_arr["item_unit"], "itemunit_qty", $item_qty*-1);
			if (!$res) {
				$errno = 5;
				$errmsg = "Failure of Item/Unit Quantity Update!";
				include("error.php");
				exit;
			}
			if (!$i->updateItemsQty($itx[invtrx_item_code], $item_qty, 0)) {
				$errno = 4;
				$errmsg = "Failure of Item On-Hand Quantity!";
				include("error.php");
				exit;
			}
			$c->deleteItemTrxs($invtrx_id);
			$j->deleteJrnlTrxRefs($invtrx_id, "i");
		} else {
			$errno = 1;
			$errmsg = "There is no such a Transaction Type in this Server!";
			include("error.php");
			exit;
		}
	}
	$loc = "Location: itemtrxs.php?ty=l";
	if ($errno == 0) header($loc);
	exit;

} else if ($cmd =="itemtrxs_edit") {
	$a = new Accts();
	$c = new ItemTrxs();
	$r = new Requests();
	$i = new Items();
	$u = new Unit();
	$iu = new ItemUnits();
	$j = new JrnlTrxs();

	if (!$a->getAccts($invtrx_inv_acct)) {
		$errno = 2;
		$errmsg = "Inventory Account has not been Found!";
		include("error.php");
		exit;
	}
	if (!$a->getAccts($invtrx_acct_code)) {
		$errno = 2;
		$errmsg = "Applied Account has not been Found!";
		include("error.php");
		exit;
	}
	$arr = array();
	if ($itx = $c->getItemTrxs($invtrx_id)) {
		$iu_arr_fr = $iu->getItemUnits($itx[invtrx_item_code], $itx["item_unit"]);
		$iu_arr_to = $iu->getItemUnits($invtrx_item_code, $invtrx_unit);

		if (round($iu_arr_to["itemunit_factor"])==0) {
			$ration = 0;
		} else {
			$ratio = $iu_arr_to["itemunit_factor"]/$iu_arr_to["itemunit_factor"];
		}
		if (round($ratio)>0) {
			$item_qty_old = ceil($itx[invtrx_qty]*$ratio);
			$item_qty_new = ceil($invtrx_qty*$ratio);
		} else {
			$item_qty_old = floor($itx[invtrx_qty]*$ratio);
			$item_qty_new = floor($invtrx_qty*$ratio);
		}
		$jrnltrx_amt = $item_qty_new * $invtrx_cost;

		$oldrec = $_SESSION["olds"];
		$arr = $r->getAlteredArray($oldrec, $_POST); 
		if ($invtrx_type == "s") {
			if ($arr) {
				if (!$c->updateItemTrxs($invtrx_id, $arr)) {
					$errno = 2;
					$errmsg = "Item Transaction Update Error!";
					include("error.php");
					exit;
				}
				if (!$i->updateItemsQty($invtrx_item_code, $item_qty_old, $item_qty_new)) {
						$errno = 4;
					$errmsg = "Failure of Item On-Hand Quantity Update!";
					include("error.php");
					exit;
				}
				$res = $iu->updateItemUnitsAmt($itx[invtrx_item_code], $it_arr["item_unit"], "itemunit_qty", $item_qty_old);
				$res = $iu->updateItemUnitsAmt($invtrx_item_code, $invtrx_unit, "itemunit_qty", $item_qty_new*-1);
				$j->deleteJrnlTrxRefs($invtrx_id,"i");
				$j->insertJrnlTrxExs($invtrx_id, $_SERVER["PHP_AUTH_USER"], $invtrx_inv_acct, "i", "c", $jrnltrx_amt, $invtrx_date);
				$j->insertJrnlTrxExs($invtrx_id, $_SERVER["PHP_AUTH_USER"], $invtrx_acct_code, "i", "d", $jrnltrx_amt, $invtrx_date);
			}
		} else if ($invtrx_type == "r") {
			if ($arr) {
				if (!$c->updateItemTrxs($invtrx_id, $arr)) {
					$errno = 2;
					$errmsg = "Item Transaction Insert Error!";
					include("error.php");
					exit;
				}
				if (!$i->updateItemsQty($invtrx_item_code, $item_qty_new, $item_qty_old)) {
					$errno = 4;
					$errmsg = "Failure of Item On-Hand Quantity Update!";
					include("error.php");
					exit;
				}
				$res = $iu->updateItemUnitsAmt($itx[invtrx_item_code], $it_arr["item_unit"], "itemunit_qty", $item_qty_old*-1);
				$res = $iu->updateItemUnitsAmt($invtrx_item_code, $invtrx_unit, "itemunit_qty", $item_qty_new);
				$j->deleteJrnlTrxRefs($invtrx_id,"i");
				$j->insertJrnlTrxExs($invtrx_id, $_SERVER["PHP_AUTH_USER"], $invtrx_inv_acct, "i", "d", $jrnltrx_amt, $invtrx_date);
				$j->insertJrnlTrxExs($invtrx_id, $_SERVER["PHP_AUTH_USER"], $invtrx_acct_code, "i", "c", $jrnltrx_amt, $invtrx_date);
			}
		} else if ($invtrx_type == "a") {
			if ($arr) {
				if (!$c->updateItemTrxs($invtrx_id, $arr)) {
					$errno = 2;
					$errmsg = "Item Transaction Update Error!";
					include("error.php");
					exit;
				}
				if (!$i->updateItemsQty($invtrx_item_code, $item_qty_new, $item_qty_old)) {
					$errno = 4;
					$errmsg = "Failure of Item On-Hand Quantity Update!";
					include("error.php");
					exit;
				}
				$res = $iu->updateItemUnitsAmt($itx[invtrx_item_code], $it_arr["item_unit"], "itemunit_qty", $item_qty_old*-1);
				$res = $iu->updateItemUnitsAmt($invtrx_item_code, $invtrx_unit, "itemunit_qty", $item_qty_new);

				$j->deleteJrnlTrxRefs($jrnltrx_ref_id,"i");
				if ($jrnltrx_amt > 0) {
					$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_inv_acct, "i", "d", $jrnltrx_amt, $invtrx_date);
					$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_acct_code, "i", "c", $jrnltrx_amt, $invtrx_date);
				} else {
					$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_inv_acct, "i", "c", abs($jrnltrx_amt), $invtrx_date);
					$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_acct_code, "i", "d", abs($jrnltrx_amt), $invtrx_date);
				}
			}
		} else {
			$errno = 1;
			$errmsg = "There is no such a Transaction Type in this Server!";
			include("error.php");
			exit;
		}
	} else {
		$errno = 2;
		$errmsg = "Couldn't find item transction id.";
		include("error.php");
		exit;
	}
	$loc = "Location: itemtrxs.php?ty=e&invtrx_id=$invtrx_id";
	if ($errno == 0) header($loc);

} else if ($cmd =="itemtrxs_add") {
	$a = new Accts();
	$c = new ItemTrxs();
	$r = new Requests();
	$i = new Items();
	$u = new Unit();
	$iu = new ItemUnits();
	$j = new JrnlTrxs();
	if (!$a->getAccts($invtrx_inv_acct)) {
		$errno = 2;
		$errmsg = "Inventory Account has not been Found!";
		include("error.php");
		exit;
	}
	if (!$a->getAccts($invtrx_acct_code)) {
		$errno = 2;
		$errmsg = "Applied Account has not been Found!";
		include("error.php");
		exit;
	}

	$arr = array();
	$jrnltrx_amt = $invtrx_qty * $invtrx_cost;
	if (!empty($invtrx_id)) {
		$errno = 1;
		$errmsg = "Inventory Transaction Id should be assigned by Server!";
		include("error.php");
		exit;
	} else {
		$oldrec = $_SESSION["olds"];
		$arr = $r->getAlteredArray($oldrec, $_POST); 

		$it_arr =  $i->getItems($invtrx_item_code);
		$iu_arr_fr = $iu->getItemUnits($invtrx_item_code, $it_arr["item_unit"]);
		$iu_arr_to = $iu->getItemUnits($invtrx_item_code, $invtrx_unit);
		if ($iu_arr_fr["itemunit_factor"]!=0) $ratio = $iu_arr_to["itemunit_factor"]/$iu_arr_fr["itemunit_factor"];
		if (round($ratio)>0) {
			$item_qty = ceil($invtrx_qty*$ratio);
		} else {
			$item_qty = floor($invtrx_qty*$ratio);
		}
		$jrnltrx_amt = $item_qty * $invtrx_cost;

		if ($invtrx_type == "s") {
			if (!$last_id = $c->insertItemTrxs($arr)) {
				$errno = 2;
				$errmsg = "Item Transaction Insert Error!";
				include("error.php");
				exit;
			}
			if (!$i->updateItemsQty($invtrx_item_code, 0, $item_qty)) {
				$errno = 4;
				$errmsg = "Failure of Item On-Hand Quantity Update!";
				include("error.php");
				exit;
			}
			$res = $iu->updateItemUnitsAmt($invtrx_item_code, $it_arr["item_unit"], "itemunit_qty", $item_qty*-1);
			echo "$last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_inv_acct $jrnltrx_amt, $invtrx_date";
			$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_inv_acct, "i", "c", $jrnltrx_amt, $invtrx_date);
			$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_acct_code, "i", "d", $jrnltrx_amt, $invtrx_date);
		} else if ($invtrx_type == "r") {
			if (!$last_id = $c->insertItemTrxs($arr)) {
				$errno = 2;
				$errmsg = "Item Transaction Insert Error!";
				include("error.php");
				exit;
			}
			if (!$i->updateItemsQty($invtrx_item_code, $invtrx_qty, 0)) {
				$errno = 4;
				$errmsg = "Failure of Item On-Hand Quantity!";
				include("error.php");
				exit;
			}
			$res = $iu->updateItemUnitsAmt($invtrx_item_code, $it_arr["item_unit"], "itemunit_qty", $item_qty);
			$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_inv_acct, "i", "d", $jrnltrx_amt, $invtrx_date);
			$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_acct_code, "i", "c", $jrnltrx_amt, $invtrx_date);

		} else if ($invtrx_type == "a") {
			if (!$last_id = $c->insertItemTrxs($arr)) {
				$errno = 2;
				$errmsg = "Item Transaction Insert Error!";
				include("error.php");
				exit;
			}
			if (!$i->updateItemsQty($item_id, $invtrx_qty, 0)) {
				$errno = 4;
				$errmsg = "Failure of Item On-Hand Quantity!";
				include("error.php");
				exit;
			}
			$res = $iu->updateItemUnitsAmt($itx[invtrx_item_code], $it_arr["item_unit"], "itemunit_qty", $item_qty);
			if ($jrnltrx_amt > 0) {
				$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_inv_acct, "i", "d", $jrnltrx_amt, $invtrx_date);
				$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_acct_code, "i", "c", $jrnltrx_amt, $invtrx_date);
			} else {
				$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_inv_acct, "i", "c", abs($jrnltrx_amt), $invtrx_date);
				$j->insertJrnlTrxExs($last_id, $_SERVER["PHP_AUTH_USER"], $invtrx_acct_code, "i", "d", abs($jrnltrx_amt), $invtrx_date);
			}

		} else {
			$errno = 1;
			$errmsg = "There is no such a Transaction Type in this Server!";
			include("error.php");
			exit;
		}
	}
	$loc = "Location: itemtrxs.php?ty=a&invtrx_type=$invtrx_type";
	if ($errno == 0) header($loc);
}
?>