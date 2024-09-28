<?php
	include_once("class/class.formutils.php");
	include_once("class/class.picks.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");
	include_once("class/class.pickdtls.php");
	include_once("class/class.items.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	$f = new FormUtil();
//------------------------------------------------------------------------
	include("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------
	$d = new Datex();
	$f = new FormUtil();
	$s = new Picks();
	$y = new PickDtls();
	$t = new Items();
		
	$limit = 1;
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	if ($pk = $s->getPicks($pick_id)) foreach($pk as $k => $v) $$k = $v; 
	$recs = $y->getPickDtlsList($pick_id);
?>
<html>
<head>
<title> 
Print Picking
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function OnPrint() {
	window.print();
}

//-->
</SCRIPT>
<style type="text/css">
<!--
.between {  border-color: white black; border-style: solid; border-top-width: auto; border-right-width: auto; border-bottom-width: auto; border-left-width: auto}
-->
</style>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  onLoad="OnPrint()"">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td><strong>Picking Ticket</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> 

						<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="49%"><table width="100%" border="1" cellspacing="0" cellpadding="1">
                                <tr> 
                                  <td width="30%" align="right" bgcolor="silver"><?= $label[$lang]["Customer"] ?>:</td>
                                  <td width="70%"> 
                                    <?= $pick_cust_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver"><?= $label[$lang][Ship_To] ?>:</td>
                                  <td> 
                                    <?= $pick_addr1 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $pick_addr2 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">&nbsp;</td>
                                  <td> 
                                    <?= $pick_addr3 ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["C_S_Z_C"] ?></td>
                                  <td> 
                                    <?= $pick_city ?>
                                    <?= $pick_state ?>
                                    <?= $pick_zip ?>
                                    <?= $pick_country ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="49%" valign="top"><table width="100%" border="1" cellspacing="1" cellpadding="1">
                                <tr> 
                                  <td align="right" bgcolor="silver" width="30%">Picking #&nbsp;</td>
                                  <td width="70%">
                                    <?= $pick_id ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["Date_1"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $pick_date ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= $pick_user_code ?>
								  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table>



				      </td>
                    </tr>
                    <tr align="right"> 
						<td colspan="7">&nbsp</td>
                    </tr>
                    <tr align="right"> 
    <td colspan="7"> <table width="100%" border="1" cellspacing="0" cellpadding="1">
        <tr> 
          <th bgcolor="gray" width="5%"><font color="white"><?= $label[$lang]["No"] ?></font></th>
          <th colspan="2" bgcolor="gray"><font color="white"><?= $label[$lang]["Item"] ?></font></th>
          <th bgcolor="gray" width="8%"><font color="white"><?= $label[$lang]["Cost"] ?></font></th>
          <th bgcolor="gray" width="7%"><font color="white"><?= $label[$lang]["qty"] ?></font></th>
          <th bgcolor="gray" width="10%"><font color="white"><?= $label[$lang]["Amount"] ?></font></th>
          <th bgcolor="gray" width="1%"><font color="white">Tx</font></th>
        </tr>
<?php
	$subtotal = 0;
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += $recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"];
?>
          <td width="5%" align="center"> 
             <?= $i+1 ?>
          </td>
          <td width="15%"> 
            <?= $recs[$i]["slsdtl_item_code"] ?>
          </td>
          <td width="35%"> 
            <?= $recs[$i]["item_desc"] ?>
          </td>
          <td width="8%" align="right"> 
            <?= sprintf("%0.2f", $recs[$i]["slsdtl_cost"]) ?>
          </td>
          <td width="7%" align="right"> 
            <?= $recs[$i]["slsdtl_qty"]+0 ?>
          </td>
          <td width="10%" align="right"> 
            <?= sprintf("%0.2f", $recs[$i]["slsdtl_cost"]*$recs[$i]["slsdtl_qty"]) ?>
          </td>
          <td width="1%" align="center"> 
            <?= $recs[$i]["item_tax"] ?>
          </td>
        </tr>
<?php
		}
	}
?>
      </table></td>
  </tr>
  <tr>
    <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="1" cellspacing="0" cellpadding="1">
            <tr width="20%"> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%" width="80%">
                <?= $pick_shipvia ?>&nbsp;
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $pick_comnt ?>&nbsp;
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="1" cellspacing="0" cellpadding="1">
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%"><?= $label[$lang]["Sub_Total"] ?></td>
              <td width="50%">
                <?= sprintf("%0.2f", $subtotal) ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td align="right">
                <?= sprintf("%0.2f", $pick_freight_amt) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= sprintf("%0.2f", $pick_tax_amt) ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= sprintf("%0.2f", $pick_tax_amt+$pick_freight_amt+$subtotal) ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
    </tr>


                  </table>
<?php
//	print_r($_SESSION);
?>
</body>
</html>