<?php
	include_once("class/register_globals.php");


if ($cmd =="copy") {
	$last_value = $v;
	$_SESSION["last_value"]=$last_value;
	header($HTTP_REFERER);
} else if ($cmd =="paste") {
	header($HTTP_REFERER);
}


?>