<?php
	$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
					1=>array("value"=>"name", "name"=>"Name"),
					2=>array("value"=>"addr", "name"=>"Address"),
					3=>array("value"=>"city", "name"=>"City"),
					4=>array("value"=>"tel", "name"=>"Tel")
	);
?>
				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
<?php
	if ($ty=="e") {
?>
                    <tr>
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo "custships.php?ty=a&cust_code=$cust_code" ?>">New Shipping Address</a> | </font></td>
                    </tr>
<?php
	}
?>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">#</font></th>
                            <th bgcolor="gray"><font color="white">Name</font></th>
                            <th bgcolor="gray"><font color="white">Address</font></th>
                            <th bgcolor="gray"><font color="white">City</font></th>
                            <th bgcolor="gray"><font color="white">Tel</font></th>
                            <th bgcolor="gray"><font color="white">A</font></th>
                          </tr>
<?php
	if (!empty($cust_code)) $custship_cust_code = $cust_code;
	$cs = new CustShips();
	$numrows = $cs->getCustShipsRows($custship_cust_code);
	$recs = $cs->getCustShipsList($custship_cust_code);
	$n = new Navigates();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	else $numrecs = 0;
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="10%" align="center"> 
                              <a href="<?= "custships.php?ty=e&cust_code=$cust_code&custship_id=".$recs[$i][custship_id] ?>"><?= $i+1 ?></a>
                            </td>
                            <td width="35%"><?= $recs[$i]["custship_name"] ?></td>
                            <td width="25%"><?= $recs[$i]["custship_addr1"] ?></td>
                            <td width="15%"><?= $recs[$i]["custship_city"] ?></td>
                            <td width="15%"><?= $recs[$i]["custship_tel"] ?></td>
                            <td width="1%"><?= ($recs[$i]["custship_active"] != "f")?"X":"&nbsp;" ?></td>
                          </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red"><?= $label[$lang]["No_Data"] ?>!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                  </table>
