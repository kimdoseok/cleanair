<?php
//echo "CUST: $purch_vend_code <br> ITEM: $porcptdtl_item_code <br>";
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.items.php");
	include_once("class/class.porcptdtls.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
	include_once("class/map.default.php");
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");

//-----------------------------------------------------------------------

	$f = new FormUtil();

	$limit = 20;
	$c = new PoRcptDtls();
	$numrows = $c->getPoRcptDtlsHistRows($ft, $porcpt_vend_code, "t");
	$recs = $c->getPoRcptDtlsHistList($ft, $porcpt_vend_code, $pg, $limit, "t");

	$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
					1=>array("value"=>"desc", "name"=>"Description")
	);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>PO History</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<!-- <LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">
-->
<SCRIPT LANGUAGE="JavaScript">
function updateForm(page) {
	document.forms[0].pg.value = page;
	document.forms[0].method="POST";
	document.forms[0].action="<?=htmlentities($_SERVER['PHP_SELF']) ?>";
	document.forms[0].submit();
}

function setCode(code, cost, desc) {
	var s = self.opener.document.forms[0];
	s.porcptdtl_item_code.value = code;
	s.porcptdtl_item_desc.value = desc;
	s.porcptdtl_cost.value = cost;
	s.porcptdtl_item_code.focus();
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
                      <td colspan="6"><strong>Item Purchase History of <?= $porcpt_vend_code ?></strong></td>
                    </tr>
<!--
					<tr align="left"> 
					<FORM>
                      <td colspan="6">
						<?= $f->fillTextBox("ft",$ft,30) ?>
						<input type="submit" name="filter" value="Filter">
						<?= $f->fillHidden("pg",$pg) ?>
						<?= $f->fillHidden("objname",$objname) ?>
					  </td>
					</form>
                    </tr>
-->
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">PO #</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Date</font></th>
                            <th bgcolor="gray" width="5%" colspan="2"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Price</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
                          </tr>
<?php
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	else $numrecs = 0;
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
                            <td width="10%" align="left"> 
                              <a href="javascript:setCode('<?= addslashes($recs[$i]["porcptdtl_item_code"]) ?>','<?= $recs[$i]["porcptdtl_cost"] ?>','<?= addslashes($recs[$i]["porcptdtl_item_desc"]) ?>')"><?= $recs[$i]["porcptdtl_item_code"] ?></a>
                            </td>
                            <td width="40%" align="left"> 
                              <?= $recs[$i]["porcptdtl_item_desc"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["porcptdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="center"> 
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=1&porcpt_vend_code=$porcpt_vend_code&porcptdtl_item_code=$porcptdtl_item_code" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$prevpage&purch_vend_code=$porcpt_vend_code&porcptdtl_item_code=$porcptdtl_item_code" ?>">&lt;Prev</a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$nextpage&porcpt_vend_code=$porcpt_vend_code&porcptdtl_item_code=$porcptdtl_item_code" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&page=$totalpage&porcpt_vend_code=$porcpt_vend_code&porcptdtl_item_code=$purdtl_item_code" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
