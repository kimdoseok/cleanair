<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.saledtls.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	include("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Popup_Item_Codes</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
function updateForm(page) {
	document.forms[0].pg.value = page;
	document.forms[0].method="POST";
	document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
	document.forms[0].submit();
}

function setCode(code, qty, cost) {
	var s = self.opener.document.forms[0];
	if (s.pickdtl_code) {
		s.pickdtl_code.value = code;
		s.pickdtl_qty.value = qty;
		s.pickdtl_cost.value = cost;
		s.amount.value = Math.round(parseFloat(cost)*parseFloat(qty)*100)/100;
		s.pickdtl_code.focus();
		self.close();
	} else {
		window.alert("Parent form does not exist!");
	}
}

function setClose() {
	self.close();
	self.opener.document.form1.pickdtl_code.select();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>Item History</strong></td>
                    </tr>
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">Sales #</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Date</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Price</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Ord Qty</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Spd Qty</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new SaleDtls();
	$numrows = $c->getSaleDtlsRowsAvl($pick_cust_code);
	$recs = $c->getSaleDtlsListAvl($pick_cust_code, "", "f", $page, $limit);
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
		$qty = $recs[$i]["slsdtl_qty"]-$recs[$i]["slsdtl_qty_picked"]
?>
							<td width="5%" align="center"> 
                              <?= $recs[$i]["sale_id"] ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["sale_date"] ?>
                            </td>
                            <td width="10%" align="center"> 
                              <a href="javascript:setCode('<?= $recs[$i]["slsdtl_id"] ?>','<?= $qty ?>','<?= $recs[$i]["slsdtl_cost"] ?>');"><?= $recs[$i]["slsdtl_item_code"] ?></a>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["slsdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["slsdtl_qty"]+0 ?>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["slsdtl_qty_picked"]+0 ?>
                            </td>
                        </tr>
<?php
	}
	if ($numrows <= 0) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b><?= $label[$lang]["Empty_1"] ?>!</b>
                            </td>
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
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=1&pick_cust_code=$pick_cust_code&slsdtl_item_code=$slsdtl_item_code" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$prevpage&pick_cust_code=$pick_cust_code&slsdtl_item_code=$slsdtl_item_code" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$nextpage&pick_cust_code=$pick_cust_code&slsdtl_item_code=$slsdtl_item_code" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$totalpage&pick_cust_code=$pick_cust_code&slsdtl_item_code=$slsdtl_item_code" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
