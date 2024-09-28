<?php

	if ($ht == "e") $recs = $_SESSION[itmbldtls_edit];

	else $recs = $_SESSION[itmbldtls_add];
	if ($recs[$did]) foreach ($recs[$did] as $k => $v) $$k = $v;


	if ($ht=="e") $sty = $_SESSION[itmblds_edit];

	else $sty = $_SESSION[itmblds_add];

	if (!empty($sty)) foreach($sty as $k => $v) $$k = $v;


	$styl_cost_rmb = 0;
	for ($i=0;$i<count($recs);$i++) $styl_cost_rmb += $recs[$i][itmbldtl_rmb_per_pair];	

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function itemLookUp() {
		var f = document.forms[0];
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.cmd.value = "ilu";
		f.method = "get";
		f.submit();
	}

	function EditDtl() {
		var f = document.forms[0];
		f.action = "itm_builds_proc.php";
		f.cmd.value = "itmblds_detail_sess_edit";
		f.method = "post";
		f.submit();
	}
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="itm_builds_proc.php">
						<INPUT TYPE="hidden" name="cmd" value="">
						<INPUT TYPE="hidden" name="itmbldtl_itmbuild_id" value="<?= $itmbuild_id ?>">
						<?= $f->fillHidden("ht", $ht) ?>						
						<?= $f->fillHidden("did", $did) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong>Edit Item Build Detail</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a&itmbuild_id=$itmbuild_id" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?= "itm_builds.php?ty=$ht&itmbuild_id=$itmbuild_id" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="white">
                <tr> 
                  <td width="170" align="right" bgcolor="silver">Code:&nbsp;</td>
                  <td width="308" bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_item_code", $itmbldtl_item_code, 20, 32, "inbox") ?>
					  <A HREF="javascript:openItemBrowse('itmbldtl_item_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_desc", $itmbldtl_desc, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver">Quantity:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_qty", $itmbldtl_qty, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver">Cost:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_cost", $itmbldtl_cost, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver">Unit:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_unit_code", $itmbldtl_unit_code, 20, 32, "inbox") ?>
                  </td>
                </tr>
		$sdtl[itmbldtl_id] = $itmbldtl_id;
		$sdtl[itmbldtl_itmbuild_id] = $itmbuild_id;
		$sdtl[itmbldtl_item_code] = $itmbldtl_item_code;
		$sdtl[itmbldtl_desc] = $itmbldtl_desc;
		$sdtl[] = $itmbldtl_unit_code;
		$sdtl[] = $itmbldtl_qty;
		$sdtl[] = $itmbldtl_cost;
                <tr> 
                  <td width="170" align="right" bgcolor="silver">Cost:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_unit", $itmbldtl_unit, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver"><?= $label[$lang]["Unit"] ?>:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_unit", $itmbldtl_unit, 20, 32, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>
					<tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="button" name="Submit32" value="Record" onClick="EditDtl()"> 
                              <input type="button" name="Submit222" value="<?= $label[$lang]["Cancel"] ?>"></td>
                          </tr>
                        </table></td>
                    </tr>
						  </form>

					<tr align="right"> 
                      <td colspan="8">
					    <hr>
					  </td>
                    </tr>

                    <tr align="right"> 
          <td width="50%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr> 
                <td align="right" bgcolor="silver"> 
                  <?= $label[$lang]["PO_no"] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $styl_po_no ?>
                </td>
                <td width="10">&nbsp;</td>
                <td width="120" align="right" bgcolor="silver"> 
                  <?= $label[$lang]["Date_1"] ?>
                  :&nbsp;</td>
                <td width="265"> 
                  <?= $styl_date ?>
                </td>
              </tr>
              <tr> 
                <td align="right" bgcolor="silver"> 
                  <?= $label[$lang][Style_no] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $itmbuild_id ?>
                </td>
                <td width="10">&nbsp;</td>
                <td align="right" bgcolor="silver"> 
                  <?= $label[$lang]["Description"] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $styl_desc ?>
                </td>
              </tr>
              <tr> 
                <td width="120" align="right" bgcolor="silver"> 
                  <?= $label[$lang][Work_Qty] ?>
                  :&nbsp;</td>
                <td width="265"> 
                  <?= $styl_qty_work ?>
                </td>
                <td width="10">&nbsp;</td>
                <td align="right" bgcolor="silver"> 
                  <?= $label[$lang]["Cust_no"] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $styl_cust_code ?>
                </td>
              </tr>
              <tr> 
                <td width="120" align="right" bgcolor="silver"> 
                  <?= $label[$lang][Qty_Board] ?>
                  :&nbsp;</td>
                <td width="265"> 
                  <?= $styl_qty_board ?>
                </td>
                <td width="10">&nbsp;</td>
                <td width="140" align="right" bgcolor="silver"> 
                  <?= $label[$lang][On_Board_Date] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $styl_onbrd_date ?>
                </td>
              </tr>
              <tr> 
                <td width="120" align="right" bgcolor="silver"> 
                  <?= $label[$lang][USD_Cost] ?>
                  :&nbsp;</td>
                <td width="265"> 
                  <?= $styl_cost_usd ?>
                </td>
                <td width="10">&nbsp;</td>
                <td width="140" align="right" bgcolor="silver"> 
                  <?= $label[$lang][RMB_Cost] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $styl_cost_rmb ?>
                </td>
              </tr>
              <tr> 
                <td align="right" bgcolor="silver"> 
                  <?= $label[$lang][Status] ?>
                  :&nbsp;</td>
                <td> 
                  <?php
						if ($styl_status=="o") echo $label[$lang][Ordered];
						else if ($styl_status=="w") echo $label[$lang][Working];
                  else if ($styl_status=="f") echo $label[$lang][Finished];
                  else if ($styl_status=="h") echo $label[$lang][Hold];
                  else if ($styl_status=="c") echo $label[$lang][Cancled];
					 ?>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table> </td>
						  </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr> 
          <th bgcolor="gray" width="34"><font color="white"> 
            <?= $label[$lang]["No"] ?>
            </font></th>
          <th colspan="2" bgcolor="gray"><font color="white"> 
            <?= $label[$lang]["Item"] ?>
            </font></th>
          <th bgcolor="gray" width="120"><font color="white"> 
            <?= $label[$lang][Meter] ?>/<?= $label[$lang][Pair] ?>
            </font></th>
          <th bgcolor="gray" width="120"><font color="white"> 
            <?= $label[$lang][RMB] ?>/<?= $label[$lang][Meter] ?>
            </font></th>
          <th width="120" bgcolor="gray"><font color="white"> 
            <?= $label[$lang][RMB] ?>/<?= $label[$lang][Pair] ?>
            </font></th>
          <th width="100" bgcolor="gray"><font color="white"><?= $label[$lang]["Unit"] ?></font></th>
        </tr>
        <?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i][itmbldtl_item_code]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";

?>
        <td align="center"> <a href="itmblds_details.php?ty=e&ht=<?= $ht ?>&did=<?= $i ?>"> 
          <?= $i+1 ?>
          </a> </td>
        <td width="100"> 
          <?= $recs[$i][itmbldtl_item_code] ?>
        </td>
        <td width="337"> 
          <?= $arr["item_desc"] ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][itmbldtl_meter_per_pair]+0 ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][itmbldtl_rmb_per_meter]+0 ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][itmbldtl_rmb_per_pair]+0 ?>
        </td>
        <td width="100" align="center">
          <?= $recs[$i][itmbldtl_unit] ?>
          </td>
        </tr>
        <?php
		}
	}
	if (count($arr) == 0) {
?>
        <tr bgcolor="#EEEEEE"> 
          <td colspan="7" align="center"> <b> 
            <?= $label[$lang]["Empty_1"] ?>
            !</b> </td>
        </tr>
        <?php
	}
?>
        <tr> 
          <td colspan="7" align="center"> <b>&nbsp;</b> </td>
        </tr>
      </table></td>
                    </tr>

                  </table>
