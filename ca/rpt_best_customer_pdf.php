<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.customers.php");
	include_once("class/class.picks.php");
	include_once("class/class.receipt.php");
	include_once("class/class.cmemo.php");
	include_once("class/register_globals.php");

	$dx = new Datex();
	$cu = new Custs();
	$pk = new Picks();
	$cm = new Cmemo();
	$rc = new Receipt();

	if ($sortby=="sale") { // sales
		$arr = $pk->getPicksSumBest($dx->toIsoDate($start_date), $dx->toIsoDate($end_date), $sort, $num_disp);
	} else if ($sortby=="rcpt") { // cash receipt
		$arr = $rc->getReceiptSumBest($dx->toIsoDate($start_date), $dx->toIsoDate($end_date), $sort, $num_disp);
	} else if ($sortby=="cmemo") { // credit memo
		$arr = $cm->getCmemoSumBest($dx->toIsoDate($start_date), $dx->toIsoDate($end_date), $sort, $num_disp);
	}
	if ($arr) $num = count($arr);
	else $num = 0;

	$pdf->ezText($sub,11, array("justification"=>"center"));
	$pdf->ezSetDy(-10);


	if ($sortby=="sale") {
		$cols = array('num'=>"#"
					 ,'code'=>'Code'
					 ,'name'=>'Name'
					 ,'city'=>'City'
					 ,'state'=>'State'
					 ,'amount'=>'Amount'
					 ,'freight'=>'Freight'
					 ,'tax'=>'Tax'
					 ,'total'=>'Total'
				   );
		$opt = array('xPos'=>"center", 'xOrientation'=>'center', 'width'=>750, 'fontSize'=>8, 'textcol'=>'(1,1,1)'
				,'cols'=>array('num'=>array('width'=>30, 'justification'=>'left')
							  ,'code'=>array('width'=>60, 'justification'=>'left')
							  ,'name'=>array('width'=>200, 'justification'=>'left')
							  ,'city'=>array('width'=>60, 'justification'=>'left')
							  ,'state'=>array('width'=>50, 'justification'=>'left')
							  ,'amount'=>array('width'=>50, 'justification'=>'right')
							  ,'freight'=>array('width'=>30, 'justification'=>'right')
							  ,'tax'=>array('width'=>40, 'justification'=>'right')
							  ,'total'=>array('width'=>40, 'justification'=>'right')
							  )
				);
		$data = array();
		$time_limit = 5 * $it_num + 30;
		set_time_limit($time_limit);
		$x = 1;
		for ($i=0;$i<$it_num;$i++) {
			$tmp = array();
			$tmp['num'] = $x;
			$tmp['code'] = $it_arr[$i]["item_code"];
			$tmp['name'] = $it_arr[$i]["item_desc"];
			$tmp['city'] = $it_arr[$i]["item_vend_code"];
			$tmp['state'] = $it_arr[$i]["item_prod_line"];
			$tmp['amount'] = $it_arr[$i]["item_material"];
			$tmp['freight'] = strtoupper($it_arr[$i]["item_unit"]);
			$tmp['tax'] = $it_arr[$i]["item_msrp"];
			$tmp['total'] = $it_arr[$i]["item_qty_onhnd"]+0;
			array_push($data, $tmp);
			$x++;
		}

	} else if ($sortby=="rcpt") {
		$cols = array('num'=>"#"
					 ,'code'=>'Code'
					 ,'name'=>'Name'
					 ,'city'=>'City'
					 ,'state'=>'State'
					 ,'amount'=>'Amount'
					 ,'disc'=>'Discount'
					 ,'total'=>'Total'
				   );
		$opt = array('xPos'=>"center", 'xOrientation'=>'center', 'width'=>750, 'fontSize'=>8, 'textcol'=>'(1,1,1)'
				,'cols'=>array('num'=>array('width'=>30, 'justification'=>'left')
							  ,'code'=>array('width'=>60, 'justification'=>'left')
							  ,'name'=>array('width'=>200, 'justification'=>'left')
							  ,'city'=>array('width'=>60, 'justification'=>'left')
							  ,'state'=>array('width'=>50, 'justification'=>'left')
							  ,'amount'=>array('width'=>60, 'justification'=>'right')
							  ,'disc'=>array('width'=>60, 'justification'=>'right')
							  ,'total'=>array('width'=>40, 'justification'=>'right')
							  )
				);
		$data = array();
		$time_limit = 5 * $it_num + 30;
		set_time_limit($time_limit);
		$x = 1;
		for ($i=0;$i<$it_num;$i++) {
			$tmp = array();
			$tmp['num'] = $x;
			$tmp['code'] = $arr[$i]["cust_code"];
			$tmp['name'] = $arr[$i][cust_desc];
			$tmp['city'] = $arr[$i]["cust_city"];
			$tmp['state'] = $arr[$i]["cust_state"];
			$tmp['amount'] = $arr[$i]["total_rcpt"];
			$tmp['disc'] = $arr[$i]["total_disc"];
			$tmp['total'] = $arr[$i]["Total"];
			array_push($data, $tmp);
			$x++;
		}
	} else if ($sortby=="cmemo") {
		$cols = array('num'=>"#"
					 ,'code'=>'Code'
					 ,'name'=>'Name'
					 ,'city'=>'City'
					 ,'state'=>'State'
					 ,'amount'=>'Amount'
					 ,'freight'=>'Freight'
					 ,'tax'=>'Tax'
					 ,'total'=>'Total'
				   );
		$opt = array('xPos'=>"center", 'xOrientation'=>'center', 'width'=>750, 'fontSize'=>8, 'textcol'=>'(1,1,1)'
				,'cols'=>array('num'=>array('width'=>30, 'justification'=>'left')
							  ,'code'=>array('width'=>60, 'justification'=>'left')
							  ,'name'=>array('width'=>200, 'justification'=>'left')
							  ,'city'=>array('width'=>60, 'justification'=>'left')
							  ,'state'=>array('width'=>50, 'justification'=>'left')
							  ,'amount'=>array('width'=>50, 'justification'=>'right')
							  ,'freight'=>array('width'=>30, 'justification'=>'right')
							  ,'tax'=>array('width'=>40, 'justification'=>'right')
							  ,'total'=>array('width'=>40, 'justification'=>'right')
							  )
				);
		$data = array();
		$time_limit = 5 * $it_num + 30;
		set_time_limit($time_limit);
		$x = 1;
		for ($i=0;$i<$it_num;$i++) {
			$tmp = array();
			$tmp['num'] = $x;
			$tmp['code'] = $arr[$i]["cust_code"];
			$tmp['name'] = $arr[$i]["cust_name"];
			$tmp['city'] = $arr[$i]["cust_city"];
			$tmp['state'] = $arr[$i]["cust_state"];
			$tmp['amount'] = $arr[$i]["total_cmemo"];
			$tmp['freight'] = $arr[$i]["total_freight"];
			$tmp['tax'] = $arr[$i]["total_tax"];
			$tmp['total'] = $arr[$i]["Total"];
			array_push($data, $tmp);
			$x++;
		}
	}


$pdf->ezTable($data,$cols,"",$opt); 
$pdf->ezStream();

?>