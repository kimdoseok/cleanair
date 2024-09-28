<?php
	$x = new Datex();
	$f = new FormUtil();
	$t = new Items();

	if ($_SESSION[itmblds_add] && $styl = $_SESSION[itmblds_add]) foreach($styl as $k => $v) $$k = $v;
	$recs = $_SESSION[itmbldtls_add];

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function SaveToDB() {
		var f = document.forms[0];
		if (f.itmbuild_name.value == "") {
			window.alert("Item Build Name should not be blank!");
		} else {
			f.cmd.value = "itmblds_add";
			f.method = "post";
			f.action = "itm_builds_proc.php";
			f.submit();
		}
	}

//-->
</SCRIPT>
<table width="100%" border="0" cellspacing="0" cellpadding="0"><form method=post action="itm_builds_proc.php">
  <?= $f->fillHidden("cmd","") ?>
  <?= $f->fillHidden("ht","a") ?>
  <?= $f->fillHidden("ty","a") ?>
  <tr align="right"> 
    <td colspan="8"><strong>New Item Builds</strong></td>
  </tr>
  <tr align="left"> 
    <td colspan="8"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> | 
	<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font> </td>
  </tr>
  <tr align="right"> 
    <td colspan="8"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="100%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
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
                  <?= $f->fillTextBoxRO("itmbuild_user_code", $_SERVER["PHP_AUTH_USER"] , 20, 32, "inbox") ?>
                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="1" align="left"><FONT SIZE="2"><A HREF="javascript:AddDtl()">
      Add Detail
      </A></FONT></td>
    <td colspan="1" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="right"> 
			<input type="button" name="Submit32" value="Update" onClick="UpdateForm()">
			<input type="button" name="Submit3222" value="Record" onClick="SaveToDB()"> 
            <input type="button" name="Submit22222" value="Clear" onClick="clearSess()"></td>
        </tr>
      </table></td>
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
          <th width="63%" bgcolor="gray"><font color="white"> 
            Description
            </font></th>
          <th width="10%" bgcolor="gray"><font color="white"> 
            Qty
            </font></th>
          <th width="5%" bgcolor="gray"><font color="white"> 
            UoM
            </font></th>
          <th width="2%" bgcolor="gray"><font color="white"> 
            &nbsp;
            </font></th>
        </tr>
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
        <td align="center">
          <a href="javascript:delDtl('a','<?= $i ?>')">Del</a>
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
