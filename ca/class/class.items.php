<?php
include_once("class.ic.php");

class Items extends IC {

	var $active;
	var $start_item;
	var $end_item;
	var $start_vendor;
	var $end_vendor;
	var $start_prodline;
	var $end_prodline;
	var $start_material;
	var $end_material;
	var $sortby;

	function insertItems($arr) {
		if ($lastid = $this->insertIC("items", $arr)) return $lastid;
		return false;
	}

	function insertItemHists($arr) {
		if ($lastid = $this->insertIC("itemhists", $arr)) return true;
		return false;
	}

	function updateItems($code, $arr) {
		if ($this->updateIC("items", "item_code", $code, $arr)) return true;
		return false;
	}

	function updateItemsQty($code, $newqty, $oldqty) {
		$qty = $newqty - $oldqty;
		$this->query = "UPDATE items SET item_qty_onhnd = item_qty_onhnd + $qty WHERE item_code='$code' ";
//echo $this->query."<br>";
		if ($this->updateICRaw($this->query)) return true;
		else return false;
	}

	function updateItemsAvgCost($code) {
		$this->query = "SELECT sum(purdtl_cost) as avgcost FROM purdtls WHERE purdtl_item_code = '$code' LIMIT 1 ";
		$arr = $this->getIC();
		$cost = $arr[0][avgcost];
		$this->query = "UPDATE items SET item_avg_cost = $cost WHERE item_code='$code' ";
//echo $this->query;
		if ($this->updateICRaw($this->query)) return true;
		else return false;
	}

	function updateItemsQtyCost($code, $cost, $oldqty, $newqty) { //$qty is positive on porcpt/negative on sales
		$this->query = "SELECT * FROM items WHERE item_code = '$code' LIMIT 1 ";
		$arr = $this->getIC();
		$onhand = $arr[0]["item_qty_onhnd"];
		$avgcost = $arr[0]["item_ave_cost"];
		if ($onhand-$oldqty+$newqty == 0) {
			$this->query = "UPDATE items SET item_last_cost=$cost, item_qty_onhnd=0, item_ave_cost=0 WHERE item_code='$code' ";
		} else {
			$this->query = "UPDATE items SET item_last_cost = $cost, item_qty_onhnd = item_qty_onhnd - $oldqty + $newqty, item_ave_cost = ((item_qty_onhnd-$oldqty)*item_ave_cost+$newqty*$cost)/(item_qty_onhnd-$oldqty+$newqty) WHERE item_code='$code' ";
		}
//echo $this->query;
		if ($this->updateICRaw($this->query)) return true;
		else return false;
	}

	function deleteItems($code) {
		$query = "delete from items where item_code='$code'";
		if ($this->updateICRaw($query)) return true;
		return false;
	}

	function getItems($code="") {
		$code = trim($code);
		$aw = "";
		if (empty($code)) return false;
		if ($this->active == "t") $aw = "AND item_active = 't' ";
		$this->query = "SELECT * FROM items WHERE item_code = '$code' $aw LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function getItemsFields() {
		$this->query = "SELECT * FROM items LIMIT 0 ";
		if ($arr = $this->getICFields($this->query)) return $arr;
		return false;
	}

	function getLastItems($filter="") {
		if ($this->active == "t") $aw = "WHERE item_active = 't' ";
		$this->query = "SELECT * FROM items $aw ORDER BY item_code DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstItems($filter="") {
		if ($this->active == "t") $aw = "WHERE item_active = 't' ";
		$this->query = "SELECT * FROM items $aw ORDER BY item_code LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextItems($code, $filter="") {
		if ($this->active == "t") $aw = "AND item_active = 't' ";
		$this->query = "SELECT * FROM items WHERE item_code > '$code' $aw ORDER BY item_code LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevItems($code, $filter="") {
		if ($this->active == "t") $aw = "AND item_active = 't' ";
		$this->query = "SELECT * FROM items WHERE item_code  < '$code' $aw ORDER BY item_code DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getItemsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i]->name;
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getItems($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevItems($code, $filter);
					if (!$rec) $rec = $this->getFirstItems($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextItems($code, $filter);
					if (!$rec) $rec = $this->getLastItems($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstItems($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastItems($filter);
				} else {
					$rec = $this->getItems($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastItems($filter);
				} else {
					$rec = $this->getFirstItems($filter);
				}
			}
		}
		return $rec;
	}

	function getCatItems($cate_code) {
		$this->query = "SELECT * FROM items WHERE item_cate_code = '$cate_code' ORDER BY item_code";
		return $this->getIC();
	}

	function getItemsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		$wh = "";
		if ($page < 1) $page = 0;
		else $page--;
		if ($this->active == "t") {
			$wh = "WHERE item_active = 't' ";
			$aw = "AND item_active = 't' ";
		}

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM items $wh ORDER BY item_code  ";
			else if ($condition == "desc") $this->query = "SELECT * FROM items $wh ORDER BY item_desc  ";
			else if ($condition == "cat") $this->query = "SELECT * FROM items $wh ORDER BY item_cate_code  ";
			else if ($condition == "catdesc") $this->query = "SELECT * FROM items i, cates c $wh ORDER BY cate_name1, cate_name2, cate_name3, cate_name4  ";
			else if ($condition == "vendor") $this->query = "SELECT * FROM items $wh ORDER BY item_vend_code  ";
			else $this->query = "SELECT * FROM items $wh ORDER BY item_code ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM items WHERE item_code LIKE '$filtertext%' $aw ORDER BY item_code ";
			else if ($condition == "desc") $this->query = "SELECT * FROM items WHERE item_desc LIKE '%$filtertext%' $aw ORDER BY item_desc  ";
			else if ($condition == "cat") $this->query = "SELECT * FROM items WHERE item_cate_code LIKE '$filtertext%' $aw ORDER BY item_cate_code, item_code  ";
			else if ($condition == "catdesc") $this->query = "SELECT * FROM items i, cates c WHERE i.item_cate_code=c.cate_code AND (c.cate_name1 LIKE '$filtertext%' OR c.cate_name2 LIKE '$filtertext%' OR c.cate_name3 LIKE '$filtertext%' OR c.cate_name4 LIKE '$filtertext%') $aw ORDER BY cate_name1, cate_name2, cate_name3, cate_name4, item_code  ";
			else if ($condition == "vendor") $this->query = "SELECT * FROM items WHERE item_vend_code LIKE '$filtertext%' $aw ORDER BY item_vend_code, item_code  ";
			else $this->query = "SELECT * FROM items WHERE item_code LIKE '$filtertext%' $aw ORDER BY item_code ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query;
		return $this->getIC();
	}

	function getItemsRows($filtertext="") {
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(item_code) AS numrows FROM items ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(item_code) AS numrows FROM items WHERE item_code LIKE '$filtertext%' ";
			else if ($condition == "desc") $this->query = "SELECT count(item_code) AS numrows FROM items WHERE item_desc  LIKE '$filtertext%' ";
			else if ($condition == "cat") $this->query = "SELECT count(item_code) AS numrows FROM items WHERE item_cate_code  LIKE '$filtertext%' ";
			else if ($condition == "catdesc") $this->query = "SELECT count(item_code) AS numrows FROM items i, cates c WHERE i.item_cate_code=c.cate_code AND (c.cate_name1 LIKE '$filtertext%' OR c.cate_name2 LIKE '$filtertext%' OR c.cate_name3 LIKE '$filtertext%' OR c.cate_name4 LIKE '$filtertext%') ";
			else $this->query = "SELECT count(item_code) AS numrows FROM items WHERE item_code LIKE '$filtertext%' ";
		}
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

	function getItemsListPick($cust_code, $condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		$select = "SELECT i.*, sum(p.pickdtl_qty) AS pickdtl_qty_sum ";
		$where = "WHERE i.item_code=s.slsdtl_item_code AND s.slsdtl_id=p.pickdtl_slsdtl_id AND p.pickdtl_pick_id=h.pick_id AND h.pick_cust_code = '$cust_code' ";
		$from = "FROM items i, slsdtls s, pickdtls p, picks h ";
		$orderby = "ORDER BY ";
		$groupby = "GROUP BY i.item_code ";

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") {
				$orderby .= " item_code  ";
			} else if ($condition == "desc") {
				$orderby .= " item_desc  ";
			} else if ($condition == "cat") {
				$orderby .= " item_cate_code, item_code ";
			} else if ($condition == "catdesc") {
				$from .= ", cates c ";
				$where .= "AND i.item_cate_code=c.cate_code ";
				$orderby .= "cate_name1, cate_name2, cate_name3, cate_name4  ";
			} else {
				$orderby .= "item_code ";
			}
		} else {
			if ($condition == "code") {
				$where .= "AND item_code LIKE '$filtertext%' ";
				$orderby .= " item_code  ";
			} else if ($condition == "desc") {
				$where .= "AND item_desc  LIKE '%$filtertext%' ";
				$orderby .= " item_desc  ";
			} else if ($condition == "cat") {
				$where .= "AND item_cate_code  LIKE '$filtertext%' ";
				$orderby .= " item_cate_code, item_code ";
			} else if ($condition == "catdesc") {
				$where .= "AND i.item_cate_code=c.cate_code AND (c.cate_name1 LIKE '$filtertext%' OR c.cate_name2 LIKE '$filtertext%' OR c.cate_name3 LIKE '$filtertext%' OR c.cate_name4 LIKE '$filtertext%') ";
				$from .= ", cates c ";
				$orderby .= "cate_name1, cate_name2, cate_name3, cate_name4  ";
			} else {
				$where .= " AND item_code LIKE '$filtertext%' ";
				$orderby .= "item_code ";
			}
		}

		if ($reverse == "t") $orderby .= " DESC ";
		$this->query = "$select $from $where $groupby $orderby ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query;
//echo "<br>";
		return $this->getIC();
	}

	function getItemsRowsPick($cust_code, $condition, $filtertext="", $extended=False) {
		$select = "SELECT count(item_code)  AS numrows ";
		$where = "WHERE i.item_code=s.slsdtl_item_code AND s.slsdtl_id=p.pickdtl_slsdtl_id AND p.pickdtl_pick_id=h.pick_id AND h.pick_cust_code = '$cust_code' ";
		$from = "FROM items i, slsdtls s, pickdtls p, picks h ";
		$groupby = "GROUP BY i.item_code ";

		if ($this->active == "t") $where .= "AND item_active = 't' ";

		if (!empty($filtertext)) {
			if ($condition == "code") {
				$where .= "AND item_code LIKE '$filtertext%' ";
			} else if ($condition == "desc") {
				$where = "AND item_desc  LIKE '$filtertext%' ";
			} else if ($condition == "cat") {
				$where = "AND item_cate_code  LIKE '$filtertext%' ";
			} else if ($condition == "catdesc") {
				$where .= "AND i.item_cate_code=c.cate_code AND (c.cate_name1 LIKE '$filtertext%' OR c.cate_name2 LIKE '$filtertext%' OR c.cate_name3 LIKE '$filtertext%' OR c.cate_name4 LIKE '$filtertext%') ";
				$from .= ", cates c ";
			} else {
				$where .= "AND item_code LIKE '$filtertext%' ";
			}
		}
		$this->query = "$select $from $where $groupby ";
//echo $this->query;
//echo "<br>";

		$arr = $this->getIC();
		if ($arr) {
			if ($extended) return count($arr);
			else return $arr[0]["numrows"];
		} else {
			return false;
		}
	}

	function getItemsListRange() {
		$where = "WHERE 1 ";
		if ($this->active == "t") $where .= "AND item_active = 't' ";
		if (!empty($this->start_item)) $where .= "AND item_code >= '".$this->start_item."' ";
		if (!empty($this->end_item)) $where .= "AND item_code <= '".$this->end_item."' ";
		if (!empty($this->start_vendor)) $where .= "AND item_vend_code >= '".$this->start_vendor."' ";
		if (!empty($this->end_vendor)) $where .= "AND item_vend_code <= '".$this->end_vendor."' ";
		if (!empty($this->start_prodline)) $where .= "AND item_prod_line >= '".$this->start_prodline."' ";
		if (!empty($this->end_prodline)) $where .= "AND item_prod_line <= '".$this->end_prodline."' ";
		if (!empty($this->start_material)) $where .= "AND item_material >= '".$this->start_material."' ";
		if (!empty($this->end_material)) $where .= "AND item_material <= '".$this->end_material."' ";


		$select = "SELECT * ";
		$from = "FROM items ";

		if ($this->sortby=="item") $orderby = "ORDER BY item_code, item_vend_code, item_prod_line, item_material, item_active ";
		else if ($this->sortby=="vendor") $orderby = "ORDER BY item_vend_code, item_code, item_prod_line, item_material, item_active ";
		else if ($this->sortby=="prodline") $orderby = "ORDER BY item_prod_line, item_code, item_vend_code, item_material, item_active ";
		else if ($this->sortby=="material") $orderby = "ORDER BY item_material, item_code, item_vend_code, item_prod_line, item_active ";
		else if ($this->sortby=="active") $orderby = "ORDER BY item_active, item_code, item_vend_code, item_prod_line, item_material ";
		else $orderby = "ORDER BY item_code, item_vend_code, item_prod_line, item_material, item_active ";
		$this->query = "$select $from $where $orderby ";
//echo $this->query;
		return $this->getICRaw();
	}

}
?>