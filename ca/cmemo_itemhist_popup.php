<?php
//echo "CUST: $pick_cust_code <br> ITEM: $pickdtl_item_code <br>";
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.items.php");
	include_once("class/class.pickdtls.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------

	$limit = 20;
	$c = new PickDtls();
	$numrows = $c->getPickDtlsHistRows($cmemodtl_item_code, $cmemo_cust_code);
	$recs = $c->getPickDtlsHistList($cmemodtl_item_code, $cmemo_cust_code, $page, $limit);

	$t = new Items();
	$item_arr = $t->getItems($cmemodtl_item_code);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title><?= $label[$lang]["Popup_Item_Codes"] ?></title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
function updateForm(page) {
	document.forms[0].pg.value = page;
	document.forms[0].method="POST";
	document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
	document.forms[0].submit();
}

function setCode(cost) {
	var s = self.opener.document.forms[0];
	s.cmemodtl_cost.value = cost;
	s.amount.value = Math.round(parseFloat(cost)*parseFloat(s.cmemodtl_qty.value)*100)/100;
	s.cmemodtl_cost.focus();
	self.close();
}

function setClose() {
	self.close();
	self.opener.document.form1.cmemodtl_item_code.select();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="4"><strong>Item History</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4">Item: <b><?= $pickdtl_item_code ?></b>, MSRP: <b><a href="javascript:setCode('<?= number_format($recs[0]["item_msrp"], 2,".",",") ?>');"><?= number_format($item_arr["item_msrp"], 2,".",",") ?></a></b></td>
                    </tr>
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">Picks #</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Date</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Price</font></th>
                            <th bgcolor="gray" width="7%"><font color="white"><?= $label[$lang]["qty"] ?></font></th>
                          </tr>
<?php
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
							<td width="5%" align="center"> 
                              <?= $recs[$i]["pick_id"] ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["pick_date"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <a href="javascript:setCode('<?= sprintf("%0.2f",$recs[$i]["pickdtl_cost"]) ?>');"><?= sprintf("%0.2f",$recs[$i]["pickdtl_cost"]) ?></a>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["pickdtl_qty"]+0 ?>
                            </td>
                        </tr>
<?php
	}
	if ($numrecs == 0) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
                          </tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=1&pick_cust_code=$pick_cust_code&pickdtl_item_code=$pickdtl_item_code" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$prevpage&pick_cust_code=$pick_cust_code&pickdtl_item_code=$pickdtl_item_code" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$nextpage&pick_cust_code=$pick_cust_code&pickdtl_item_code=$pickdtl_item_code" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$totalpage&pick_cust_code=$pick_cust_code&pickdtl_item_code=$pickdtl_item_code" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
