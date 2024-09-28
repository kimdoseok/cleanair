<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.items.php");
	include_once("class/class.saledtls.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------

	$f = new FormUtil();

	$limit = 20;
	$c = new SaleDtls();
	$numrows = $c->getSaleDtlsHistRows($ft, $sale_cust_code, "t");
	$recs = $c->getSaleDtlsHistList($ft, $sale_cust_code, $pg, $limit, "t");

	$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
					1=>array("value"=>"desc", "name"=>"Description")
	);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Sales History</title>
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
	s.slsdtl_item_code.value = code;
	s.slsdtl_item_desc.value = desc;
	s.slsdtl_cost.value = cost;
	s.slsdtl_item_code.focus();
	self.close();
}

function setClose() {
	self.close();
	self.opener.document.form1.slsdtl_item_code.select();
}

</SCRIPT>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="6"><strong>Item Sales History of <?= $sale_cust_code ?></strong></td>
                    </tr>
					<tr align="right"> 
					<FORM action="<?= $_SERVER[PHP_SELF] ?>" method="GET">
                      <td colspan="6">
						<?= $f->fillTextBox("ft",$ft,30) ?>
						<input type="submit" name="filter" value="Filter">
						<?= $f->fillHidden("pg",$pg) ?>
						<?= $f->fillHidden("sale_cust_code", $_GET["sale_cust_code"]) ?>
						<?= $f->fillHidden("objname",$objname) ?>
					  </td>
					</form>
                    </tr>
                          <tr> 
                            <th bgcolor="gray" width="5%"><font color="white">Sales #</font></th>
                            <th bgcolor="gray" width="5%"><font color="white">Date</font></th>
                            <th bgcolor="gray" width="5%" colspan="2"><font color="white">Item</font></th>
                            <th bgcolor="gray" width="8%"><font color="white">Price</font></th>
                            <th bgcolor="gray" width="7%"><font color="white">Qty</font></th>
                            <th bgcolor="gray" width="2%"><font color="white">Unit</font></th>
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
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
							<td width="5%" align="center"> 
                              <?= $recs[$i]["sale_id"] ?>
                            </td>
                            <td width="10%" align="center"> 
                              <?= $recs[$i]["sale_date"] ?>
                            </td>
                            <td width="10%" align="left"> 
                              <a href="javascript:setCode('<?= addslashes($recs[$i]["slsdtl_item_code"]) ?>','<?= $recs[$i]["slsdtl_cost"] ?>','<?= addslashes($recs[$i]["slsdtl_item_desc"]) ?>')"><?= $recs[$i]["slsdtl_item_code"] ?></a>
                            </td>
                            <td width="40%" align="left"> 
                              <?= $recs[$i]["slsdtl_item_desc"] ?>
                            </td>
                            <td width="8%" align="right"> 
                              <?= sprintf("%0.2f",$recs[$i]["slsdtl_cost"]) ?>
                            </td>
                            <td width="7%" align="center"> 
                              <?= $recs[$i]["slsdtl_qty"]+0 ?>
                            </td>
                            <td width="2%" align="center"> 
                              <?= $recs[$i]["slsdtl_unit"] ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=1&sale_cust_code=$sale_cust_code&ft=$ft" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=$prevpage&sale_cust_code=$sale_cust_code&ft=$ft" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=$nextpage&sale_cust_code=$sale_cust_code&ft=$ft" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&objname=$objname&pg=$totalpage&sale_cust_code=$sale_cust_code&ft=$ft" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
