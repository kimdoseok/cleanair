<?php
foreach (array('_GET', '_POST') as $_SG) {
	foreach ($$_SG as $_SGK => $_SGV) {
		$$_SGK = $_SGV;
	}
}
session_start();

?>