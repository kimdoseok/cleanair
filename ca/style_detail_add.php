<?php
	include_once("class/class.formutils.php");
	include_once("class/class.datex.php");
	include_once("class/class.styles.php");
	include_once("class/class.items.php");
	$t = new Items();
	$f = new FormUtil();
	$d = new Datex();
	$s = new Styles();


	if ($ht=="e") {
		if (session_is_registered("styles_edit")) {
			$styl = unserialize(base64_decode($_SESSION[styles_edit]));
			foreach($styl as $k => $v) $$k = $v; 
		}
	} else {
		if (session_is_registered("styles_add")) {
			$styl = unserialize(base64_decode($_SESSION[styles_add]));
			foreach($styl as $k => $v) $$k = $v; 
		}
	}

	if ($ht=="e") $recs = unserialize(base64_decode($_SESSION[styldtls_edit]));
	else $recs = unserialize(base64_decode($_SESSION[styldtls_add]));
	$styl_cost_rmb = 0;
	for ($i=0;$i<count($recs);$i++) $styl_cost_rmb += $recs[$i][styldtl_rmb_per_pair];	



?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		f.action = "ic_proc.php";
		f.ty.value = "<?= $ty ?>";
		f.cmd.value = "style_detail_sess_add";
		f.method = "post";
		f.submit();
	}
//-->
</SCRIPT>

						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="gl_proc.php">
							<INPUT TYPE="hidden" name="cmd" value="">
							<?= $f->fillHidden("ht", $ht) ?>
							<?= $f->fillHidden("ty", $ty) ?>
							<?= $f->fillHidden("styldtl_styl_code", $styldtl_styl_code) ?>
							<?= $f->fillHidden("styl_code", $styl_code) ?>
						<INPUT TYPE="hidden" name="styldtl_styl_code" value="<?= $styldtl_styl_code ?>">
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][New_Style_Detail] ?></strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=a&styl_code=$styldtl_styl_code&ht=" ?><?= ($ht=="e")?"e":"a" ?>"><?= $label[$lang]["New_1"] ?></a> |
                        <a href="<?php echo "styles.php?ty=$ht&styl_code=$styl_code" ?>"><?= $label[$lang]["Header_1"] ?></a> |</font></td>
                    </tr>

                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="white">
                <tr>
                  <td width="170" align="right" bgcolor="silver"><?= $label[$lang]["item_code"] ?>:&nbsp;</td>
                  <td width="308" bgcolor="white"> 
                    <?= $f->fillTextBox("styldtl_item_code", $styldtl_item_code, 20, 32, "inbox") ?>
					   <A HREF="javascript:openItemBrowse('styldtl_item_code')"><font size="2"><?= $label[$lang]["Lookup"] ?></font></A>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver"><?= $label[$lang]["Description"] ?>:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("styldtl_item_desc", $styldtl_item_desc, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver"><?= $label[$lang][Meter_Pair] ?>:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("styldtl_meter_per_pair", $styldtl_meter_per_pair, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver"><?= $label[$lang][RMB_Cost_Meter] ?>:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("styldtl_rmb_per_meter", $styldtl_rmb_per_meter, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver"><?= $label[$lang][RMB_Pair] ?>:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("styldtl_rmb_per_pair", $styldtl_rmb_per_pair, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td width="170" align="right" bgcolor="silver"><?= $label[$lang]["Unit"] ?>:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("styldtl_unit", $styldtl_unit, 20, 32, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>


					<tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="button" name="Submit32" value="<?= $label[$lang]["Record"] ?>" onClick="AddDtl()"> 
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
          <td width="100%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
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
                  <?= $styl_code ?>
						<?= $f->fillHidden("styl_code",$styl_code) ?>
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
                <td width="120" align="right" bgcolor="silver"> 
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
            <?= $label[$lang][Meter] ?>
            / 
            <?= $label[$lang][Pair] ?>
            </font></th>
          <th bgcolor="gray" width="120"><font color="white"> 
            <?= $label[$lang][RMB] ?>
            /
            <?= $label[$lang][Meter] ?>
            </font></th>
          <th width="120" bgcolor="gray"><font color="white"> 
            <?= $label[$lang][RMB] ?>
            /
            <?= $label[$lang][Pair] ?>
            </font></th>
          <th width="100" bgcolor="gray"><font color="white"><?= $label[$lang]["Unit"] ?></font></th>
        </tr>
<?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i][styldtl_item_code]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";

?>
        <td align="center"> <a href="style_details.php?ty=e&ht=<?= $ht ?>&did=<?= $i ?>"> 
          <?= $i+1 ?>
          </a> </td>
        <td width="68"> 
          <?= $recs[$i][styldtl_item_code] ?>
        </td>
        <td width="337"> 
          <?= $arr["item_desc"] ?>
        </td>
        <td width="120" align="right"> 
          <?= $recs[$i][styldtl_meter_per_pair]+0 ?>
        </td>
        <td width="120" align="right"> 
          <?= $recs[$i][styldtl_rmb_per_meter]+0 ?>
        </td>
        <td width="120" align="right"> 
          <?= $recs[$i][styldtl_rmb_per_pair]+0 ?>
        </td>
        <td width="100" align="center">
          <?= $recs[$i][styldtl_unit] ?>
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
