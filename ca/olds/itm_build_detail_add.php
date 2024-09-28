<?php
	$t = new Items();
	$f = new FormUtil();
	$d = new Datex();
	$s = new ItmBuilds();

	if ($ht=="e") {
		if ($_SESSION[itmblds_edit]) {
			foreach($_SESSION[itmblds_edit] as $k => $v) $$k = $v; 
		}
	} else {
		if ($_SESSION[itmblds_add]) {
			foreach($_SESSION[itmblds_add] as $k => $v) $$k = $v; 
		}
	}

	if ($ht=="e") $recs = $_SESSION[itmbldtls_edit];
	else $recs = $_SESSION[itmbldtls_add];


	if ($item_code) $itmbldtl_item_code = $item_code;
	if (!empty($itmbldtl_item_code) && $ctrl == 1) {
		$t = new Items();
		$t->active = "t";
		if ($tarr = $t->getItems($itmbldtl_item_code)) {
			$itmbldtl_item_code = strtoupper($itmbldtl_item_code);
			$old_itmbldtl_item_code = $itmbldtl_item_code;
			if (empty($itmbldtl_desc)) $itmbldtl_desc = $tarr["item_desc"];
			if (empty($itmbldtl_qty) || $itmbldtl_qty==0) $itmbldtl_qty = 1;
			if (empty($itmbldtl_unit_code) || $itmbldtl_unit_code==0) $itmbldtl_unit_code = $tarr["item_unit"];
			$not_found_item = 0;
		} else {
			$not_found_item = 1;
			$itmbldtl_item_code	= $old_itmbldtl_item_code;
		}
	}


?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		f.action = "itm_builds_proc.php";
		f.ty.value = "<?= $ty ?>";
		f.cmd.value = "itmblds_detail_sess_add";
		f.method = "post";
		f.submit();
	}

<?php
	echo "	var f = document.forms[0];";
	if ($not_found_item == 1) echo "	openItemBrowseFilter('sale_item_code', '$item_code');";
?>

//-->
</SCRIPT>

						<table width="100%" border="0" cellspacing="1" cellpadding="0">
						<form method=get action="gl_proc.php">
							<INPUT TYPE="hidden" name="cmd" value="">
							<?= $f->fillHidden("ht", $ht) ?>
							<?= $f->fillHidden("ty", $ty) ?>
							<?= $f->fillHidden("itmbldtl_itmbuild_id", $itmbldtl_itmbuild_id) ?>
							<?= $f->fillHidden("itmbuild_id", $itmbuild_id) ?>
						<INPUT TYPE="hidden" name="itmbldtl_itmbuild_id" value="<?= $itmbldtl_itmbuild_id ?>">
                    <tr align="right"> 
                      <td colspan="8"><strong>New Item Build Detail</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8" align="left"><font size="2">
							   | <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=a&itmbuild_id=$itmbldtl_itmbuild_id&ht=" ?><?= ($ht=="e")?"e":"a" ?>">New</a> |
                        <a href="<?php echo "itm_builds.php?ty=$ht&itmbuild_id=$itmbuild_id" ?>">Header</a> |</font></td>
                    </tr>

                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
            <td width="477" align="right">
			  <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="white">
                <tr>

                  <td width="15%" align="right" bgcolor="silver">Item Code:&nbsp;</td>
                  <td width="85%" bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_item_code", $itmbldtl_item_code, 20, 32, "inbox") ?>
					   <A HREF="javascript:openItemBrowse('itmbldtl_item_code')"><font size="2">Lookup</font></A>

                </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_desc", $itmbldtl_desc, 50, 200, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Qty:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_qty", $itmbldtl_qty, 20, 32, "inbox") ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">UoM:&nbsp;</td>
                  <td bgcolor="white"> 
                    <?= $f->fillTextBox("itmbldtl_unit_code", $itmbldtl_unit_code, 20, 32, "inbox") ?>
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table></td>
                    </tr>


					<tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="100%" align="center"><input type="button" name="Submit32" value="Record" onClick="AddDtl()"> 
                              <input type="button" name="Submit222" value="Cancel"></td>
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
                <td width="15%" align="right" bgcolor="silver"> 
                  Name
                  :&nbsp;</td>
                <td width="85%"> 
                  <?= $itmbuild_name ?>
                </td>
              </tr>
              <tr> 
                <td align="right" bgcolor="silver"> 
                  Description
                  :&nbsp;</td>
                <td> 
                  <?= $itmbuild_desc ?>
                </td>
              </tr>
              <tr> 
                <td align="right" bgcolor="silver"> 
					User
                  :&nbsp;</td>
                <td> 
                  <?= $itmbuild_user_code ?>
                </td>
              </tr>
            </table> </td>
						  </tr>
  <tr align="right"> 
    <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr> 
          <th width="5%" bgcolor="gray"><font color="white"> 
            #
            </font></th>
          <th width="15%" bgcolor="gray"><font color="white"> 
            Item
            </font></th>
          <th width="65%" bgcolor="gray"><font color="white"> 
            Description
            </font></th>
          <th width="10%" bgcolor="gray"><font color="white"> 
            Qty
            </font></th>
          <th width="5%" bgcolor="gray"><font color="white"> 
            UoM
            </font></th>
<?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";

?>
        <td align="center"> <a href="itmblds_details.php?ty=e&ht=a&did=<?= $i ?>"> 
          <?= $i+1 ?>
          </a> </td>
        <td> 
          <?= $recs[$i][itmbldtl_item_code] ?>
        </td>
        <td> 
          <?= $recs[$i][itmbldtl_desc] ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][itmbldtl_qty] ?>
        </td>
        <td align="center"> 
          <?= $recs[$i][itmbldtl_unit_code] ?>
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
