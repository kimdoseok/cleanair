<?php
	include_once("class/class.datex.php");
	include_once("class/class.formutils.php");
	include_once("class/class.itm_builds.php");
	include_once("class/class.styldtls.php");
	include_once("class/class.items.php");

	$t = new Items();
	$d = new ItmBuilDtls();
	$x = new Datex();
	$f = new FormUtil();
	$s = new ItmBuilds();

	if (session_is_registered("itmblds_edit")) {
		$ibld = $_SESSION[itmblds_edit];
		if ($ibld[itmbuild_id]!=$itmbuild_id) $ibld = $s->getItmBuilds($itmbuild_id);
		foreach($ibld as $k => $v) $$k = $v; 
	} else if ($ibld = $s->getItmBuilds($itmbuild_id)) {
		foreach($ibld as $k => $v) $$k = $v; 
	}
	$_SESSION[itmblds_edit] = $ibld;
	if ($_SESSION[itmbldtls_edit]) {
		$recs = $_SESSION[itmbldtls_edit];
		if ($recs[0][itmbldtl_itmbuild_id]!=$itmbuild_id) $recs = $d->getItmBuilDtlsList($itmbuild_id);
	} else {
		$recs = $d->getItmBuilDtlsList($itmbuild_id);
	}
	if ($recs) $_SESSION[itmbldtls_edit] = $recs;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function AddDtl() {
		var f = document.forms[0];
		if (f.itmbuild_id.value == "") {
			window.alert("Item Build Code should not be blank!");
		} else {
			f.itmbuild_id.value = "<?= $itmbuild_id ?>";
			f.cmd.value = "itmblds_sess_add";
			f.ht.value = '<?= $ty ?>'
			f.method = "post";
			f.action = "itm_builds_proc.php";
			f.submit();
		}
	}

	function delDtl(ht, did) {
		var f = document.forms[0];

		var agree=confirm("Are you sure to delete?");

		if (agree) window.location='itm_builds_proc.php?ty='+ht+'&cmd=itmblds_detail_sess_del&did='+did;

	}



	function SaveToDB() {
		var f = document.forms[0];
		if (f.itmbuild_id.value == "") {
			window.alert("Item Build Code should not be blank!");
		} else {
			f.cmd.value = "itmblds_edit";
			f.ht.value = '<?= $ty ?>';
			f.method = "post";
			f.action = "itm_builds_proc.php";
			f.submit();
		}
	}

	function clearSess() {
		var f = document.forms[0];
		f.cmd.value = "itmblds_clear_sess_edit";
		f.method = "post";
		f.action = "itm_builds_proc.php";
		f.submit();
	}
//-->
</SCRIPT>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<form method=post action="itm_builds_proc.php">
							<?= $f->fillHidden("cmd","") ?>
							<?= $f->fillHidden("ht","") ?>
							<?= $f->fillHidden("itmbuild_id",$itmbuild_id) ?>
                    <tr align="right"> 
                      <td colspan="1"><strong>Edit Item Build</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td colspan="1"><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr> 
								<td width="15%" align="right" bgcolor="silver"> 
								  Name
								  :&nbsp;</td>
								<td width="85%"> 
								  <?= $f->fillTextBox("itmbuild_name", $itmbuild_name, 20, 32, "inbox") ?>
								</td>
							  </tr>
							  <tr> 
								<td align="right" bgcolor="silver"> 
								  Description
								  :&nbsp;</td>
								<td> 
								  <?= $f->fillTextBox("itmbuild_desc", $itmbuild_desc, 50, 100, "inbox") ?>
								</td>
							  </tr>
							  <tr> 
								<td align="right" bgcolor="silver"> 
									User
								  :&nbsp;</td>
								<td> 
								  <?= $f->fillTextBoxRO("itmbuild_user_code", $itmbuild_user_code, 20, 32, "inbox") ?>
								</td>
						  </tr>
						</table>
					  </td>
                    </tr>

                    <tr> 
                      <td colspan="1" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">Add Detail</A></FONT></td>
                      <td colspan="1" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr> 
                           <td width="64%" align="center">&nbsp;</td>
                           <td width="36%" align="center">
								   <input type="button" name="Submit3222" value="Record" onClick="SaveToDB()"> 
                           <input type="button" name="Submit22222" value="Clear" onClick="clearSess()"></td>
                         </tr>
                      </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="1"> 
					    <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr> 
          <th bgcolor="gray" width="34"><font color="white">#</font></th>
          <th colspan="2" bgcolor="gray"><font color="white">Item Code</font></th>
          <th bgcolor="gray" width="120"><font color="white">Description</font></th>
          <th bgcolor="gray" width="120"><font color="white">Qty</font></th>
          <th width="120" bgcolor="gray"><font color="white">Unit</font></th>
          <th width="50" bgcolor="gray"><font color="white">&nbsp;</font></th>

        </tr>
<?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
        <td align="center"> <a href="itmblds_details.php?ty=e&ht=e&did=<?= $i ?>"> 
          <?= $i+1 ?>
          </a> </td>
itmbldtl_id  int(10)  UNSIGNED No    auto_increment              
   itmbldtl_itmbuild_id  int(10)  UNSIGNED No  0                
   itmbldtl_item_code  varchar(16)   No                  
     decimal(15,6)   No  0.000000                
     
        <td width="100"> 
          <?= $recs[$i][itmbldtl_item_code] ?>
        </td>
        <td width="337"> 
          <?= $arr[itmbldtl_desc] ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][itmbldtl_qty]+0 ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][itmbldtl_unit_code] ?>
        </td>
        <td align="right"> 
          <?= $recs[$i][itmbldtl_rmb_per_pair]+0 ?>
        </td>
        <td width="100" align="center">
          <?= $recs[$i][itmbldtl_unit] ?>
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
          <td colspan="1" align="center"> <b> 
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
