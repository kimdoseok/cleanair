<?php
	$d = new Datex();
	
	$f = new FormUtil();
	$limit = 1;
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	if (!$totalpage = $n->getTotalPage()) $totalpage = 1;
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();


?>
<SCRIPT LANGUAGE="JavaScript">
<!--

function makeInvoice() {
	var yn;

	yn = confirm("Are you sure to make invoice?");
	
	if (yn) window.location='ar_proc.php?cmd=make_invoice&pick_id=<?= $pick_id ?>';

}

-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>View Picking Ticket</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="4"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | </font>
                      </td>
                      <td colspan="4" align="right"><font size="2">
						
					  | <a href="<?php echo "ar_proc.php?cmd=pick_print&pick_id=$pick_id" ?>">Print</a>
		
					  | </font>
					  </td>
					</tr>
                    <tr align="right"> 
                      <td colspan="8" valign="top">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="62%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td width="25%" align="right" bgcolor="silver"><?= $label[$lang]["Customer"] ?>:</td>
                                  <td width="75%"> 
                                    <?= $pick_cust_code ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td height="20" align="right" bgcolor="silver">Ship Name:</td>
                                  <td> 
                                    <?= $pick_name ?>
                                  </td>
                                </tr>
                                <tr> 
                                  <td align="right" bgcolor="silver">Address</td>
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
                                <tr> 
                                  <td align="right" bgcolor="silver">Telephone&nbsp;</td>
                                  <td> 
                                    <?= $pick_tel ?>
                                  </td>
                                </tr>
                              </table></td>
                            <td width="2%">&nbsp;</td>
                            <td width="36%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr> 
                                  <td align="right" bgcolor="silver">Picking #&nbsp;</td>
                                  <td>
                                    <?= $pick_id ?>
                                  </td>
                                </tr>
								<tr> 
                                  <td align="right" bgcolor="silver">Slip #</td>
                                  <td> 
                                    <?= $pick_code ?>
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
                                <tr> 
                                  <td align="right" bgcolor="silver"><?= $label[$lang]["User_no"] ?>&nbsp;</td>
                                  <td> 
                                    <?= number_format($varr["cust_balance"],2,".",",") ?>
								  </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table> </td>
                    </tr>
                    <tr align="right">
					
 <td colspan="8">
					    <table width="100%" border="0" cellspacing="1" cellpadding="0">
						  <tr> 

						    <th bgcolor="gray" width="5%">
							  <font color="white"><?= $label[$lang]["No"] ?></font>
							</th>
							<th colspan="2" bgcolor="gray">
							  <font color="white"><?= $label[$lang]["Item"] ?></font>
							</th>
							<th bgcolor="gray" width="8%">
							  <font color="white"><?= $label[$lang]["Cost"] ?></font>
							</th>
							<th bgcolor="gray" width="7%">
							  <font color="white"><?= $label[$lang]["qty"] ?></font>
							</th>
							<th bgcolor="gray" width="10%">
							  <font color="white"><?= $label[$lang]["Amount"] ?></font>
							</th>
							<th bgcolor="gray" width="1%">
							  <font color="white">Tx</font>
							</th>
						  </tr>
<?php
	$d = new PickDtls();
	$s = new SaleDtls();
/*
	if (session_is_registered("pickdtls_edit")) {
		$recs = $_SESSION["pickdtls_edit"];
		if ($recs[0]["pickdtl_pick_id"] != $pick_id) {
			$olds = $d->getPickDtlsList($pick_id);
			$recs = array();
			for ($i=0;$i<count($olds);$i++) {
				$rec=array("pickdtl_id"=>"", "pickdtl_pick_id"=>"", "pickdtl_slsdtl_id"=>"", "pickdtl_qty"=>"", "pickdtl_cost"=>"", "pickdtl_code"=>"");
				$rec["pickdtl_pick_id"]		= $pick_id;
				$rec["pickdtl_id"]			= $olds[$i]["pickdtl_id"];
				$rec["pickdtl_code"]			= $olds[$i]["pickdtl_slsdtl_id"];
				$rec["pickdtl_qty"]			= $olds[$i]["pickdtl_qty"];
				$rec["pickdtl_cost"]			= $olds[$i]["pickdtl_cost"];
				array_push($recs, $rec);
			}
			$pickdtls_edit = $recs;
			session_register("pickdtls_edit");
		}
	} else {
		$olds = $d->getPickDtlsList($pick_id);
		if (!empty($olds)){
			$recs = array();
			for ($i=0;$i<count($olds);$i++) {
				$rec=array("pickdtl_id"=>"", "pickdtl_pick_id"=>"", "pickdtl_slsdtl_id"=>"", "pickdtl_qty"=>"", "pickdtl_cost"=>"", "pickdtl_code"=>"");
				$rec["pickdtl_pick_id"]		= $pick_id;
				$rec["pickdtl_id"]			= $olds[$i]["pickdtl_id"];
				$rec["pickdtl_code"]			= $olds[$i]["pickdtl_slsdtl_id"];

				$rec["pickdtl_qty"]			= $olds[$i]["pickdtl_qty"];

				$rec["pickdtl_cost"]			= $olds[$i]["pickdtl_cost"];
				array_push($recs, $rec);

			}

			$pickdtls_edit = $recs;

			session_register("pickdtls_edit");

		}

	}
*/
	$recs = $d->getPickDtlsList($pick_id);
	$subtotal = 0;
	$taxtotal = 0;

	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>";
			else echo "<tr bgcolor=\"#EEEEEE\">";
			$subtotal += sprintf("%0.2f",$recs[$i]["pickdtl_cost"])*$recs[$i]["pickdtl_qty"];
			if ($recs[$i]["slsdtl_taxable"]=="t") $taxtotal += round($recs[$i]["pickdtl_cost"]*100)/100*$recs[$i]["pickdtl_qty"];
?>
			<td width="5%" align="center">
			  <?= $i+1 ?>
			</td>
			<td width="15%">
			  <?= $recs[$i]["slsdtl_item_code"] ?>
			</td>
			<td width="35%">
			  <?= $recs[$i]["slsdtl_item_desc"] ?>
			</td>
			<td width="8%" align="right">
			  <?= number_format($recs[$i]["pickdtl_cost"], 2, ".", ",") ?>
			</td>
			<td width="7%" align="right">
			  <?= $recs[$i]["pickdtl_qty"]+0 ?>
			</td>
			<td width="10%" align="right">
			  <?= number_format($recs[$i]["pickdtl_cost"]*$recs[$i]["pickdtl_qty"], 2, ".", ",") ?>
			</td>
			<td width="1%" align="center">
			  <?= ($recs[$i]["slsdtl_taxable"]=="t")?"X":"&nbsp;" ?>
			</td>
		</tr>
<?php
		}

	}

	if (count($recs) == 0) {

?>
		<tr bgcolor="#EEEEEE">
		  <td colspan="8" align="center">
		    <b><?= $label[$lang]["Empty_1"] ?>!</b>
		  </td>
		</tr>
<?php
	}
?>
		<tr>
		  <td colspan="8">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="62%" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr width="20%"> 
              <td width="24%" bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Ship_Via"] ?>&nbsp;</td>
              <td width="76%" width="80%">
                <?= $pick_shipvia ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Tax Rate&nbsp;</td>
              <td width="76%">
                <?= $pick_taxrate+0 ?> %
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Comment"] ?>&nbsp;</td>
              <td>
                <?= $pick_comnt ?>
              </td>
            </tr>
            <tr> 
              <td width="24%" bgcolor="silver" valign="top" align="right">Ref #&nbsp;</td>
              <td width="76%">
<?php
	$arr = $s->getSaleDtlsListPicks($sale_id);
	if ($arr) $arr_num = count($arr);
	else $arr_num =0;
	for ($i=0;$i<$arr_num;$i++) {
		if ($i!=0) echo ", ";
		echo "<a href=\"picking.php?ty=l&ft=" . $arr[$i]["pickdtl_pick_id"]."&cn=code\">" . $arr[$i]["pickdtl_pick_id"]."<a>\n";
	}
?>
              </td>
            </tr>
          </table></td>
        <td width="2%">&nbsp;</td>
        <td width="36%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%"><?= $label[$lang]["Sub_Total"] ?></td>
              <td width="50%" align="right">
                <?= number_format($subtotal, 2, ".", ",") ?>
              </td>
            </tr>
            <tr align="right"> 
              <td bgcolor="silver" valign="top" align="right" width="50%">Taxable Total</td>
              <td width="50%" align="right">
                <?= number_format($taxtotal, 2, ".", ",") ?>
              </td>
            </tr>
            <tr> 
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Freight"] ?></td>
              <td align="right">
                <?= number_format($pick_freight_amt, 2, ".", ",") ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Tax"] ?>&nbsp;</td>
              <td>
                <?= number_format($pick_tax_amt, 2, ".", ",") ?>
              </td>
            </tr>
            <tr align="right">
              <td bgcolor="silver" valign="top" align="right"><?= $label[$lang]["Total_Amount"] ?></td>
              <td>
                <?= number_format($pick_tax_amt+$pick_freight_amt+$subtotal, 2, ".", ",") ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&pick_id=$pick_id" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&pick_id=$pick_id" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&pick_id=$pick_id" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&pick_id=$pick_id" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
<?php
//echo "<div style=\"page-break-before: always;\">"
?>