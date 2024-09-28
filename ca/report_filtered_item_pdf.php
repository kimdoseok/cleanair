<?php
	include_once("class/class.dbutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.items.php");
	//include_once("class/class.ezpdf.php");
	include_once("class/fpdf.php");

	$vars = array("show_inactive","show_amount");
	foreach ($vars as $var) {
		$$var = "";
	} 
	$vars = array("pg","cn");
	foreach ($vars as $var) {
		$$var = 0;
	} 
  
	include_once("class/register_globals.php");

	function drawForm(&$pdf,$subt,$lineapage,$xarr) {
		$pdf->AddPage("L","Letter");
		$pdf->SetFont("Helvetica","b",18);
		$title = 'Filtered Item Report';
		$width = $pdf->GetStringWidth($title);
		$pdf->Text( ($pdf->GetPageWidth()-$width)/2, 22,$title);
		$pdf->SetFont("Helvetica","",11);
		$width = $pdf->GetStringWidth($subt);
		$pdf->Text( ($pdf->GetPageWidth()-$width)/2, 38,$subt);
		
		$y = 46;
		$h = 22;
		for ($i=0;$i<$lineapage+1;$i++) {
			$x = 20;
			for ($j=0;$j<count($xarr);$j++) {
				if ($i==0) {
					$pdf->SetFillColor(210);
					$pdf->Rect($x, $y,$xarr[$j][1],$h,"DF");
					$pdf->Text($x+($xarr[$j][1]-$pdf->GetStringWidth($xarr[$j][0]))/2 ,$y+15,$xarr[$j][0]);
				} else if ($i%2==0 && $i>0) {
					$pdf->SetFillColor(235);
					$pdf->Rect($x, $y,$xarr[$j][1],$h,"DF");
				} else {
					$pdf->Rect($x, $y,$xarr[$j][1],$h);
				}
				$x += $xarr[$j][1];
			} 
			$y += $h;
		}
	}
	
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

	$pdf = new FPDF("L","pt");

	$sub = "Item from ";
	if (empty($start_item)) $sub .= "First";
	else $sub .= $start_item;
	$sub .= " to ";
	if (empty($end_item)) $sub .= "Last";
	else $sub .= $end_item;

	$sub .= ", Vendor from ";
	if (empty($start_vendor)) $sub .= "First";
	else $sub .= $start_vendor;
	$sub .= " to ";
	if (empty($end_vendor)) $sub .= "Last";
	else $sub .= $end_vendor;

	$sub .= ", Product Line from ";
	if (empty($start_prodline)) $sub .= "First";
	else $sub .= $start_prodline;
	$sub .= " to ";
	if (empty($end_prodline)) $sub .= "Last";
	else $sub .= $end_prodline;
	$sub .= ", Material from ";
	if (empty($start_material)) $sub .= "First";
	else $sub .= $start_material;
	$sub .= " to ";
	if (empty($end_material)) $sub .= "Last";
	else $sub .= $end_material;

	
	$d = new DateX();

	$xarr = array(array("#",30),
				  array("Code",110),
				  array("Description",300),
				  array("Vendor",80),
				  array("Prod.Line",50),
				  array("Material",50),
				  array("Unit",30),
				  array("Price",60),
				  array("OnHand",50));

	$lineapage = 24;
	$page_num = ceil($it_num / $lineapage);
	$it_idx = 0;
	for ($i=0;$i<$page_num;$i++) {
		drawForm($pdf,$sub,$lineapage, $xarr);
		$y = 66;
		$h = 22;
		$dy = 15;
		$dx = 3;
		for ($j=0;$j<$lineapage;$j++) {
			$x = 20;
			for ($k=0;$k<count($xarr);$k++) {
				if ($k==0) {
					$atext = strval($it_idx+1);
					$pdf->Text($x+($xarr[$k][1]-$pdf->GetStringWidth($atext))/2 ,$y+$dy,$atext);
				} else if ($k==1) {
					$pdf->Text($x+$dx ,$y+$dy,$it_arr[$it_idx]["item_code"]);
				} else if ($k==2) {
					$pdf->Text($x+$dx ,$y+$dy,$it_arr[$it_idx]["item_desc"]);				
				} else if ($k==3) {
					$pdf->Text($x+$dx ,$y+$dy,$it_arr[$it_idx]["item_vend_code"]);								
				} else if ($k==4) {
					$pdf->Text($x+$dx ,$y+$dy,$it_arr[$it_idx]["item_prod_line"]);								
				} else if ($k==5) {
					$pdf->Text($x+$dx ,$y+$dy,$it_arr[$it_idx]["item_material"]);				
				} else if ($k==6) {
					$pdf->Text($x+$dx ,$y+$dy,strtoupper($it_arr[$it_idx]["item_unit"] ?? ""));				
				} else if ($k==7) {
					$atext = strval($it_arr[$it_idx]["item_msrp"]);
					$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
				} else if ($k==8) {
					$atext = strval(round($it_arr[$it_idx]["item_qty_onhnd"]));
					$pdf->Text($x+$xarr[$k][1]-$pdf->GetStringWidth($atext)-$dx ,$y+$dy,$atext);				
				}
				$x += $xarr[$k][1];
			}
			$it_idx++;
			if ($it_idx==count($it_arr)) {
				break;
			}
			$y += $h;		
		}
	}
	
	$pdf->Output();

?>