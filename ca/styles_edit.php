<?php
	include_once("class/class.datex.php");
	include_once("class/class.formutils.php");
	include_once("class/class.styles.php");
	include_once("class/class.styldtls.php");
	include_once("class/class.items.php");

	$t = new Items();
	$d = new StylDtls();
	$x = new Datex();
	$f = new FormUtil();
	$s = new Styles();

	if (session_is_registered("styles_edit")) {
		$styl = unserialize(base64_decode($_SESSION[styles_edit]));
		if ($styl[styl_code]!=$styl_code) $styl = $s->getStyles($styl_code);
		foreach($styl as $k => $v) $$k = $v; 
	} else if ($styl = $s->getStyles($styl_code)) {
		foreach($styl as $k => $v) $$k = $v; 
	}
	$styles_edit = base64_encode(serialize($styl));
  $_SESSION["styles_edit"] = $styles_edit;


	if (session_is_registered("styldtls_edit")) {
		$recs = unserialize(base64_decode($_SESSION[styldtls_edit]));
		if ($recs[0][styldtl_styl_code]!=$styl_code) $recs = $d->getStylDtlsList($styl_code);
	} else {
		$recs = $d->getStylDtlsList($styl_code);
	}
	if (count($recs) > 0) $styldtls_edit = base64_encode(serialize($recs));
  $_SESSION["styldtls_edit"] = $styldtls_edit;

	$styl_cost_rmb = 0;
	for ($i=0;$i<count($recs);$i++) $styl_cost_rmb += $recs[$i][styldtl_rmb_per_pair];	

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.styl_code.value == "") {
			window.alert("Style Code should not be blank!");
		} else {
			f.styl_code.value = "<?= $styl_code ?>";
			f.cmd.value = "style_sess_add";
			f.ht.value = '<?= $ty ?>'
			f.method = "post";
			f.action = "ic_proc.php";
			f.submit();
		}
	}

	function delDtl(ht, did) {
		var f = document.forms[0];

		var agree=confirm("Are you sure you wish to continue?");

		if (agree) window.location='ic_proc.php?ty='+ht+'&cmd=style_detail_sess_del&did='+did;

	}



	function SaveToDB() {
		var f = document.forms[0];
		if (f.styl_code.value == "") {
			window.alert("Style Code should not be blank!");
		} else {
			f.cmd.value = "style_edit";
			f.ht.value = '<?= $ty ?>';
			f.method = "post";
			f.action = "ic_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "style_clear_sess_edit";
		f.method = "post";
		f.action = "ic_proc.php";
		f.submit();
	}
//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="ic_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ht","") ?>
							<?= $f->fillHidden("styl_code",$styl_code) ?>
                    <tr align="right"> 
                      <td colspan="8"><strong><?= $label[$lang][Edit_Sytle] ?></strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="8"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  
        <tr> 
          <td width="50%" height="121"><table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr> 
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang]["PO_no"] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $f->fillTextBox("styl_po_no", $styl_po_no , 20, 32, "inbox") ?>
                </td>
                <td width="10">&nbsp;</td>
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang]["Date_1"] ?>
                  :&nbsp;</td>
                <td width="265"> 
                  <?= $f->fillTextBox("styl_date", (empty($styl_date))?$x->getToday():$styl_date , 20, 32, "inbox") ?>
                </td>
              </tr>
              <tr> 
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang][Style_no] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $styl_code ?>
                  <?= $f->fillHidden("styl_code",$styl_code) ?>
                </td>
                <td width="10">&nbsp;</td>
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang]["Description"] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $f->fillTextBox("styl_desc", $styl_desc , 20, 32, "inbox") ?>
                </td>
              </tr>
              <tr> 
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang][PO_Pair] ?>
                  :&nbsp;</td>
                <td width="265"> 
                  <?= $f->fillTextBox("styl_qty_work", $styl_qty_work , 20, 32, "inbox") ?>
                </td>
                <td width="10">&nbsp;</td>
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang]["Cust_no"] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $f->fillTextBox("styl_cust_code", $styl_cust_code , 20, 32, "inbox") ?>
                </td>
              </tr>
              <tr> 
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang][Qty_Board] ?>
                  :&nbsp;</td>
                <td width="265"> 
                  <?= $f->fillTextBox("styl_qty_board", $styl_qty_board , 20, 32, "inbox") ?>
                </td>
                <td width="10">&nbsp;</td>
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang][On_Board_Date] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $f->fillTextBox("styl_onbrd_date", $styl_onbrd_date, 20, 32, "inbox") ?>
                </td>
              </tr>
              <tr> 
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang][USD_Cost] ?>
                  :&nbsp;</td>
                <td width="265"> 
                  <?= $f->fillTextBox("styl_cost_usd", empty($styl_cost_usd)? "0.00" : $styl_cost_usd , 20, 32, "inbox") ?>
                </td>
                <td width="10">&nbsp;</td>
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang][RMB_Cost] ?>
                  :&nbsp;</td>
                <td> 
                  <?= $f->fillTextBoxRO("styl_cost_rmb", empty($styl_cost_rmb)? "0.00" : $styl_cost_rmb , 20, 32, "inbox") ?>
                </td>
              </tr>
              <tr> 
                <td width="170" align="right" bgcolor="silver"> 
                  <?= $label[$lang][Status] ?>
                  :&nbsp;</td>
                <td colspan="4"> <input type="radio" name="styl_status" value="o" <?= ($styl_status=="o") ? "Checked" : "" ?>> 
                  <?= $label[$lang][Ordered] ?>
                  <input type="radio" name="styl_status" value="w" <?= ($styl_status=="w") ? "Checked" : "" ?>> 
                  <?= $label[$lang][Working] ?>
                  <input type="radio" name="styl_status" value="f" <?= ($styl_status=="f") ? "Checked" : "" ?>> 
                  <?= $label[$lang][Finished] ?>
                  <input type="radio" name="styl_status" value="h" <?= ($styl_status=="h") ? "Checked" : "" ?>> 
                  <?= $label[$lang][Hold] ?>
                  <input type="radio" name="styl_status" value="c" <?= ($styl_status=="c") ? "Checked" : "" ?>> 
                  <?= $label[$lang][Canceled] ?>
                </td>
              </tr>
            </table></td>
						  </tr>
						</table>
					  
					    
    </td>
                    </tr>

                    <tr> 
                      <td colspan="1" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()"><?= $label[$lang]["Add_Detail"] ?></A></FONT></td>
                      <td colspan="1" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="64%" align="center">&nbsp;</td>
                           <td width="36%" align="center">
								   <input type="button" name="Submit3222" value="<?= $label[$lang]["Record"] ?>" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="<?= $label[$lang]["Clear"] ?>" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
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
          <th width="50" bgcolor="gray"><font color="white">&nbsp;</font></th>
        </tr>
        <?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i][styldtl_item_code]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";

?>
        <td align="center"> <a href="style_details.php?ty=e&ht=e&did=<?= $i ?>"> 
          <?= $i+1 ?>
          </a> </td>
        <td width="100"> 
          <?= $recs[$i][styldtl_item_code] ?>
        </td>
        <td width="337"> 
          <?= $arr["item_desc"] ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][styldtl_meter_per_pair]+0 ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][styldtl_rmb_per_meter]+0 ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][styldtl_rmb_per_pair]+0 ?>
        </td>
        <td width="100" align="center">
          <?= $recs[$i][styldtl_unit] ?>
          </td>
        <td width="50" align="center">
          <a href="javascript:delDtl('a','<?= $i ?>')"><?= $label[$lang]["Del"] ?></a>
          </td>
        </tr>
        <?php
		}
	}
	if (count($arr) == 0) {
?>
        <tr bgcolor="#EEEEEE"> 
          <td colspan="8" align="center"> <b> 
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
<?php
//	print_r($_SESSION);
?>
