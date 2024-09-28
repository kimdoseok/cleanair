<?php
include_once("class/class.dbutils.php");
include_once("class/class.datex.php");
include_once("class/class.customers.php");
include_once("class/class.requests.php");
//include_once("class/class.ezpdf.php");
include_once("class/fpdf.php");
include_once("class/register_globals.php");

//ob_end_clean();

$d = new Datex();
$r = new Dbutils();
$c = new Custs();

$activeonly = (isset($_POST['$activeonly'])) ? $_POST['$activeonly'] : '';
$start_cust = (isset($_POST['$start_cust'])) ? $_POST['$start_cust'] : '';
$end_cust = (isset($_POST['$end_cust'])) ? $_POST['$end_cust'] : '';
$sortby = (isset($_POST['$sortby'])) ? $_POST['$sortby'] : '';
$output = (isset($_POST['$output'])) ? $_POST['$output'] : '';
$avery = (isset($_POST['$avery'])) ? $_POST['$avery'] : '';

if ($activeonly == "1")
  $c->active = 't';
else
  $c->active = '';
if (empty($begin_date)) {
  $cust_arr = $c->getCustsRange($start_cust, $end_cust, $sortby);
} else {
  $begin_date = $d->isoDate($begin_date);
  $cust_arr = $c->getNewCustsRange($start_cust, $end_cust, $sortby, $begin_date);
}
// $start_date $end_date $start_cust $end_cust $zero_balance


$cust_num = count($cust_arr);
$time_limit = 2 * $cust_num + 30;
set_time_limit($time_limit);

if ($output == "excel") {
  header('Content-Disposition: attachment; filename="customer_list.csv"');
  for ($i = 0; $i < count($cust_arr); $i++) {
    printf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
      $cust_arr[$i]["cust_code"], $cust_arr[$i]["cust_name"], $cust_arr[$i]["cust_addr1"], $cust_arr[$i]["cust_addr2"],
      $cust_arr[$i]["cust_addr3"], $cust_arr[$i]["cust_city"], $cust_arr[$i]["cust_state"], $cust_arr[$i]["cust_zip"]);
  }
} else {
  //$first_pb = 1;  
  if ($avery == "5660") {
    $first_x = 0;
    $first_y = 0;
    $label_row = 10;
    $label_col = 3;
    $label_w = 189;
    $label_h = 72;
    $gab_x = 9;
    $gab_y = 0;
    $margin_x = 9;
    $margin_y = 50;
    $inner_x = 8;
    $inner_y = 8;
  } else {
    $first_x = 0;
    $first_y = 0;
    $label_row = 10;
    $label_col = 3;
    $label_w = 189;
    $label_h = 72;
    $gab_x = 9;
    $gab_y = 0;
    $margin_x = 9;
    $margin_y = 36;
    $inner_x = 8;
    $inner_y = 8;
  }

  $pdf = new FPDF("P","pt");

  //$pdf = new CezPdf("letter", "portrait");
  //$pdf->ezSetMargins(0, 0, 0, 0);
  $pdf->SetMargins(0,0,0);
  $pdf->SetFont("Helvetica","",10); 
  $line_h = 12;
  $page_height = $pdf->GetPageHeight();
  //echo $page_height."<br>";
  
  /*
  $pagetext = $pdf->openObject();
  $pdf->saveState();
  $font = 'class/fonts/Helvetica.afm';
  $pdf->selectFont($font);
  $line_h = 12;
*/

  $label_num = $label_row * $label_col;
  for ($i = 0; $i < $cust_num; $i++) {

    if ( ($i % $label_num) == 0) {
      $pdf->AddPage("P","Letter");

    }

    $ix = $i % $label_col;
    if ($ix == 0) {
      if ($i % $label_num == 0)
        $iy = 0;
      else
        $iy += 1;
    }
    $x = $first_x + $margin_x + $inner_x + $ix * ($label_w + $gab_x);
    $y = $first_y + $margin_y + $inner_y + $iy * ($label_h + $gab_y);
    $k = 0;
    $pdf->SetFontSize(10);
    //print_r($cust_arr[$i]);
    //print("SetXY1: $x, $y - $k * $line_h <br>");
    $pdf->SetXY($x, $y + $k * $line_h);
    $pdfstr = $cust_arr[$i]["cust_name"] . "(" . $cust_arr[$i]["cust_code"] . ")";
    $pdfstr = substr($pdfstr,0,36);
    $pstr = "";
    /*
    for ($i=0;$i<strlen($pdfstr);$i++) {
      if ($pdf->GetStringWidth($pstr.$pdfstr[$i])>$label_w) {
        //echo $pstr."<br>";
        break;
      }
      $pstr .= $pdfstr[$i];
    }
    */
    //print($i."/".$x."/". ($y - $k * $line_h)."/".($i % $label_num)."/"."="."/".$pstr."<br>");
    $pdf->Cell(0, $line_h, $pdfstr,0,0,"L");

    //print_r($cust_arr[$i]);
    //$pdf->addText($x, $y - $k * $line_h, 10, $cust_arr[$i]["cust_name"] . " (#" . $cust_arr[$i]["cust_code"] . ")");
    $k++;
    if (!empty($cust_arr[$i]["cust_addr1"])) {
      //print("SetXY2: $x, $y - $k * $line_h <br>");
      $pdf->SetXY($x, $y + $k * $line_h);
      $pdf->Cell(0, $line_h, $cust_arr[$i]["cust_addr1"],0,0,"L");  
      //$pdf->addText($x, $y - $k * $line_h, 10, $cust_arr[$i]["cust_addr1"]);
      $k++;
    }
    if (!empty($cust_arr[$i]["cust_addr2"])) {
      //print("SetXY3: $x, $y - $k * $line_h <br>");
      $pdf->SetXY($x, $y + $k * $line_h);
      $pdf->Cell(0, $line_h, $cust_arr[$i]["cust_addr2"],0,0,"L");  
      //$pdf->addText($x, $y - $k * $line_h, 10, $cust_arr[$i]["cust_addr2"]);
      $k++;
    }
    if (!empty($cust_arr[$i]["cust_addr3"])) {
      //print("SetXY4: $x, $y - $k * $line_h <br>");
      $pdf->SetXY($x, $y + $k * $line_h);
      $pdf->Cell(0, $line_h, $cust_arr[$i]["cust_addr3"],0,0,"L");  
      //$pdf->addText($x, $y - $k * $line_h, 10, $cust_arr[$i]["cust_addr3"]);
      $k++;
    }
    //print("SetXY5: $x, $y - $k * $line_h <br>");
    $pdf->SetXY($x, $y + $k * $line_h);
    $cust_addr4 = $cust_arr[$i]["cust_city"] . ", " . $cust_arr[$i]["cust_state"] . " " . $cust_arr[$i]["cust_zip"];
    $pdf->Cell(0, $line_h, $cust_addr4, 0, 0, "L");  
    //$cust_addr4 = $cust_arr[$i]["cust_city"] . ", " . $cust_arr[$i]["cust_state"] . " " . $cust_arr[$i]["cust_zip"];
    //$pdf->addText($x, $y - $k * $line_h, 10, $cust_addr4);
  }
  $pdf->Output("I");

}
//===========================================================================================================
?>