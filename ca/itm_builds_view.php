<?php
	$d = new Datex();
	$f = new FormUtil();
	$s = new ItmBuilds();
	$y = new ItmBuilDtls();
	$t = new Items();

	if ($styl = $s->getItmBuilds($itmbuild_id)) foreach($styl as $k => $v) $$k = $v; 
	$recs = $y->getItmBuilDtlsList($itmbuild_id);
?>

<SCRIPT LANGUAGE="JavaScript">

<!--

//-->

</SCRIPT>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="2"><strong>View Item Build</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td><font size="2">
							 | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>"><?= $label[$lang]["New_1"] ?></a> | 
							 <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&itmbuild_id=$itmbuild_id" ?>"><?= $label[$lang]["Edit"] ?></a> |
					       <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>"><?= $label[$lang]["List_1"] ?></a> | </font>
                      <td align="right"><font size="2">| <a href="javascript:memoOpen('<?= $itmbuild_id ?>')"><?= $label[$lang][Memo] ?></a>
							 | <a href="javascript:printOpen('<?= $itmbuild_id ?>','t')"><?= $label[$lang][Print_1] ?></a> |</font>
                      </td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="2"> 
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							
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
						</table>
					  
    </td>
                    </tr>

                    <tr align="right"> 
    <td colspan="7"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
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
          <th bgcolor="gray" width="120"><font color="white"> 
            <?= $label[$lang][RMB] ?>
            / 
            <?= $label[$lang][Pair] ?>
            </font></th>
        </tr>
        <?php
	for ($i=0;$i<count($recs);$i++) {
		if (!empty($recs[$i])) {
			$arr = $t->getItems($recs[$i][itmbldtl_item_code]);
			if ($i%2 == 1) echo "<tr>"; 
			else echo "<tr bgcolor=\"#EEEEEE\">";
?>
        <td align="center">
          <?= $i+1 ?>
        </td>
        <td width="68"> 
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
          <a href="itm_builds_proc.php?cmd=itmblds_detail_sess_del&did=<?= $i ?>"> </a> 
        </td>
        </tr>
        <?php
		}
	}
	if (count($arr) == 0) {
?>
        <tr bgcolor="#EEEEEE"> 
          <td colspan="6" align="center"> <b> 
            <?= $label[$lang]["Empty_1"] ?>
            !</b> </td>
        </tr>
        <?php
	}
?>
        <tr> 
          <td colspan="6" align="center"> <b>&nbsp;</b> </td>
        </tr>
      </table></td>
                    </tr>
                  </table>
<?php
//	print_r($_SESSION);
?>
