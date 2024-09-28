<?php
//echo "CUST: $purch_vend_code <br> ITEM: $purdtl_item_code <br>";
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.items.php");
	include_once("class/class.purdtls.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.default.php");
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------

	$limit = 20;
	$c = new PoRcptDtls();
	$numrows = $c->getPoRcptDtlsHistRows($porcptdtl_item_code, $porcptch_vend_code);
	$recs = $c->getPoRcptDtlsHistList($porcptdtl_item_code, $porcptch_vend_code, $page, $limit);

	$t = new Items();
	$item_arr = $t->getItems($porcptdtl_item_code);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Popup Item History</title>


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
	s.porcptdtl_cost.value = cost;
	s.amount.value = Math.round(parseFloat(cost)*parseFloat(s.porcptdtl_qty.value)*100)/100;
	s.porcptdtl_cost.focus();
	self.close();
}

function setClose() {
	self.close();
	self.opener.document.form1.porcptdtl_item_code.select();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="4"><strong>Item History</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4">Item: <b><?= $porcptdtl_item_code ?></b>, Last Cost: <b><a href="javascript:setCode('<?= sprintf("%0.2f", $recs[0]["item_last_cost"]) ?>');"><?= number_format($item_arr["item_last_cost"], 2,".",",") ?></a></b></td>
                    </tr>
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">PO Rcpt.#</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Date</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Cost</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
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
                              <?= $recs[$i]["porcpt_id"] ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["porcpt_date"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <a href="javascript:setCode('<?= sprintf("%0.2f",$recs[$i]["porcptdtl_cost"]) ?>');"><?= sprintf("%0.2f",$recs[$i]["porcptdtl_cost"]) ?></a>
                            </td>
                            <td width="7%" align="right"> 
                              <?= $recs[$i]["porcptdtl_qty"]+0 ?>
                            </td>
                        </tr>
<?php
	}
	if ($numrecs == 0) {
?>
									<tr bgcolor="#EEEEEE">
                            <td colspan="8" align="center"> 
                              <b>Empty!</b>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=1&porcpt_vend_code=$porcpt_vend_code&purdtl_item_code=$porcptdtl_item_code" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$prevpage&porcpt_vend_code=$porcpt_vend_code&porcptdtl_item_code=$porcptdtl_item_code" ?>">&lt;Prev</a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$nextpage&porcpt_vend_code=$porcpt_vend_code&porcptdtl_item_code=$porcptdtl_item_code" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$totalpage&porcpt_vend_code=$porcpt_vend_code&porcptdtl_item_code=$porcptdtl_item_code" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
