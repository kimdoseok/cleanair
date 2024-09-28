<?php
include_once("class/class.company.php");
$co = new Company();
$co_arr = $co->getCompany();
echo "<font face='Helvetica' size='2' color='gray'><b>".$co_arr["name"]."</b></font>";
?>
