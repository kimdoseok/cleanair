<?php
header("Content-type: image/jpg");

//$imgfile = $_SERVER["DOCUMENT_ROOT"].strtolower(trim($_GET["id"])).".jpg";
$imgfile = $_SERVER["DOCUMENT_ROOT"]."item_images/".strtolower(trim($_GET["id"])).".jpg";
//echo $imgfile;
//exit;

$im = @imagecreatefromjpeg($imgfile);
$w = imagesx($im);
$h = imagesy($im);

if (!empty($_GET[height])) $maxh = $_GET[height];
else $maxh = 1000;
if (!empty($_GET[width])) $maxw = $_GET[width];
else $maxw = 800;
if (empty($_GET[fixed])) $fixed="width";
else $fixed=$_GET[fixed];

if ($fixed=="width") {
    $hx=round($h*$maxw/$w);
    $wx=$maxw;
} else {
    $hx=$maxh;
    $wx=round($w*$maxh/$h);
}

$imx = imagecreatetruecolor($wx, $hx);
imagecopyresampled($imx, $im, 0, 0, 0, 0, $wx, $hx, $w, $h );

imagejpeg($imx);

imagedestroy($imx);
imagedestroy($im);
?>
