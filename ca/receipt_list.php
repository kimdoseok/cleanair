<?php
	include_once("class/class.customers.php");

/*
	if ($cmd=="filter") {
		$_SESSION[receipt_filter]["ft"] = $ft;
		$_SESSION[receipt_filter]["rv"] = $rv;
		$_SESSION[receipt_filter]["pg"] = $pg;
	} else {
		if (empty($ft)) $ft = $_SESSION[receipt_filter]["ft"];
		else $_SESSION[receipt_filter]["ft"]=NULL;
		if (empty($rv)) $rv = $_SESSION[receipt_filter]["rv"];
		else $_SESSION[receipt_filter]["rv"]=NULL;
		if (empty($pg)) $pg = $_SESSION[receipt_filter]["pg"];
		else $_SESSION[receipt_filter]["pg"]=NULL;
	}
*/

	$selbox = array(0=>array("value"=>"code", "name"=>"Recipt Number"),
					1=>array("value"=>"cust", "name"=>"Customer Code"),
					2=>array("value"=>"date", "name"=>"Receipt Date"),
					3=>array("value"=>"desc", "name"=>"Receipt Description"),
					4=>array("value"=>"check", "name"=>"Receipt Check #"),
					5=>array("value"=>"amt", "name"=>"Receipt Amount"),
					6=>array("value"=>"type", "name"=>"Receipt Type")
			  );
	$dx = new Datex();
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function setFilter() {
		var f = document.forms[0];
		f.method = "GET" ;
		f.pg.value = 1;
		f.cmd.value = 'filter';
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}


//-->
</SCRIPT>
				<table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang]["List_Cash_Receipt"] ?></strong></td>
                    </tr>
                    <tr>
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> | </font></td>
                    </tr>
					<FORM>
                    <tr>
                      <td colspan="8" align="right"><font size="2">
								<?= $f->fillTextBox("ft",$ft,30) ?>
								<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
								<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> ><?= $label[$lang]["Reverse"] ?>
								<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="javascript:setFilter()">
								<?= $f->fillHidden("pg",$pg) ?>
								<?= $f->fillHidden("cmd","") ?>
							 </td>
                    </tr>
					</FORM>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th width="8%" bgcolor="gray"><font color="white">Rcpt#</font></th>
                            <th width="2%" bgcolor="gray"><font color="white">Ty</font></th>
                            <th width="10%" bgcolor="gray"><font color="white">Cust.</font></th>
                            <th width="30%" bgcolor="gray"><font color="white">Name</font></th>
                            <th width="12%" bgcolor="gray"><font color="white">Date</font></th>
                            <th width="10%" bgcolor="gray"><font color="white">Paid</font></th>
                            <th width="10%" bgcolor="gray"><font color="white">Discount</font></th>
                            <th width="12%" bgcolor="gray"><font color="white">Total</font></th>
                            <th width="5%" bgcolor="gray"><font color="white">&nbsp;</font></th>
                          </tr>
<?php
	$limit = 100;
	$v = new Custs();
	$c = new Receipt();
	$numrows = $c->getReceiptRows($cn, $ft);
	if ($cn == "date") $ft = $dx->toIsoDate($ft);
	$recs = $c->getReceiptList($cn, $ft, $rv, $pg, $limit);
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
		$arr = $v->getCusts($recs[$i]["rcpt_cust_code"]);
?>
                            <td align="center"> 
                              <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&rcpt_id=".$recs[$i]["rcpt_id"] ?>"><?= $recs[$i]["rcpt_id"] ?></a>
                            </td>
                            <td> 
                              <?= strtolower($recs[$i]["rcpt_type"]) ?>
                            </td>
                            <td> 
                              <?= $recs[$i]["rcpt_cust_code"] ?>
                            </td>
                            <td> 
                              <?= $arr["cust_name"] ?>
                            </td>
                            <td align="center"> 
                              <?= $recs[$i]["rcpt_date"] ?>
                            </td>
                            <td align="right"> 
                              <?= number_format($recs[$i]["rcpt_amt"],2,".",",") ?>
                            </td>
                            <td align="right"> 
                              <?= number_format($recs[$i]["rcpt_disc_amt"],2,".",",") ?>
                            </td>
                            <td align="right"> 
                              <?= number_format($recs[$i]["rcpt_amt"]+$recs[$i]["rcpt_disc_amt"],2,".",",") ?>
                            </td>
                            <td align="center"> 
                              <a href="ar_proc.php?cmd=rcpt_del&rcpt_id=<?= $recs[$i]["rcpt_id"] ?>"><?= $label[$lang]["Del"] ?></a>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&ft=$ft&cn=$cn&pg=1&rv=$rv" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&ft=$ft&cn=$cn&pg=$prevpage&rv=$rv" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$pg / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&ft=$ft&cn=$cn&pg=$nextpage&rv=$rv" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&ft=$ft&cn=$cn&pg=$totalpage&rv=$rv" ?>"><?= $label[$lang]["Last"] ?>t&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>