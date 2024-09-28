<?php
	include_once("class/class.formutils.php");
	include_once("class/class.accounts.php");
	include_once("class/class.navigates.php");
	include_once("class/class.itemhists.php");
//------------------------------------------------------------------------
	include_once("class/map.label.php");

	$vars = array("page");
	foreach ($vars as $var) {
		$$var = 0;
	}


	include_once("class/register_globals.php");

//------------------------------------------------------------------------


// Customer Screen Text View Language Select

	$lang = 'en';

	$charsetting = "iso-8859-1";

//-----------------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<title>Item Change History</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="../fns/css.txt" type="text/css">
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr> 
                      <td colspan="4" align="left"><strong>Item# : <?= strtoupper($item_code) ?></strong></td>
                      <td colspan="4" align="right"><strong>Item Change History</strong></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray"><font color="white">Date/Time</font></th>
                            <th bgcolor="gray"><font color="white">Old Price</font></th>
                            <th bgcolor="gray"><font color="white">New Price</font></th>
                            <th bgcolor="gray"><font color="white">User</font></th>
                          </tr>
<?php
	$limit = 20;
	$c = new ItemHists();
	$numrows = $c->getItemHistsRows($item_code);
	$recs = $c->getItemHistsList($item_code, "t", $page, $limit);

	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($page);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();

	$linenum = 1;
	// ***** Display current page details.
	if($recs) $numrecs = count($recs);
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
                            <td width="40%"> 
                              <?= $recs[$i]["itemhist_ts"] ?>
                            </td>
                            <td width="20%" align="center"> 
							<?= number_format($recs[$i]["itemhist_prc_old"],2,",",".") ?>
							</td>
                            <td width="20%" align="center"> 
							<?= number_format($recs[$i]["itemhist_prc_new"],2,",",".") ?>
							</td>
                            <td width="20%"> 
                              <?= strtoupper($recs[$i]["itemhist_user"]) ?>
                            </td>
						  </tr>
<?php
	}
	if ($numrecs == 0) {
?>
		<tr><td colspan="5" align="center"><font color="red">No Data!</font></td></tr>
<?php
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center">
							  <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&item_code=$item_code&page=1" ?>">&lt;&lt;First</a>
                              &nbsp; 
							  <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&item_code=$item_code&page=$prevpage" ?>">&lt;Prev</a> &nbsp; 
								<font color="gray"><?= "[$page / $totalpage]" ?></font>&nbsp; 
							  <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&item_code=$item_code&page=$nextpage" ?>">Next&gt;</a> &nbsp; 
							  <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=l&item_code=$item_code&page=$totalpage" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>

</BODY>
</HTML>
