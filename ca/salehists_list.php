<?php

	include_once("class/class.formutils.php");
	include_once("class/class.salehists.php");
	include_once("class/class.category.php");
	include_once("class/class.navigates.php");
	include_once("class/class.datex.php");

//------------------------------------------------------------------------
	include_once("class/map.label.php");
//------------------------------------------------------------------------
//------------------------------------------------------------------------
	include_once("class/map.lang.php");
	include_once("class/register_globals.php");
//-----------------------------------------------------------------------


	$selbox = array(0=>array("value"=>"code", "name"=>"History ID"),
					1=>array("value"=>"sale", "name"=>"Sale ID"),
					2=>array("value"=>"user", "name"=>"User"),
					3=>array("value"=>"type", "name"=>"Trx Type")
					);
	$limit = 100;
	$d = new Datex();
	$f = new FormUtil();
	$c = new SaleHists();
	$numrows = $c->getSaleHistsRows($ft);
	$recs = $c->getSaleHistsList($cn, $ft, $rv, $pg, $limit);
	$n = new Navigates();
	$n->setTotalPage($numrows, $limit);
	$n->setPage($pg);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$pg = $n->getPage();
?>
<html>
<head>
<title>Sales History</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $charsetting ?>">
<LINK REL="StyleSheet" HREF="css.txt" type="text/css">
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#6666FF"> 
    <td><?php include("top_menu.php") ?></td>
  </tr>
  <tr> 
    <td> <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td width="110" bgcolor="#CCCCFF"> <?php include ("left_items.php") ?> </td>
          <td width="681" align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="3" bgcolor="#CCFFFF">&nbsp;<?php include("company_inc.php") ?></td>
              </tr>
              <tr> 
                <td width="10">&nbsp;</td>
                <td valign="top"> 
<SCRIPT LANGUAGE="JavaScript">
<!--
	function setFilter() {
		var f = document.forms[0];
		f.method = "GET" ;
		f.pg.value = 1;
		f.action = "<?= htmlentities($_SERVER['PHP_SELF']) ?>";
		f.submit();
	}
//-->
</SCRIPT>
						<table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8" width="100%"><strong><?= $label[$lang]["List_Item"] ?></strong></td>
                    </tr>
						  <form name="form1">
                    <tr>
                      <td colspan="8" align="right"><font size="2">
								<?= $f->fillTextBox("ft",stripslashes($ft),30) ?>
								<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
								<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?> ><?= $label[$lang]["Reverse"] ?>
								<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="javascript:setFilter()">
								<?= $f->fillHidden("pg",$pg) ?>
							 </td>
                    </tr>
						  </form>
                    <tr align="right"> 
                      <td colspan="8" width="100%"><table width="100%" border="0" cellspacing="1" cellpadding="0">
                          <tr> 
                            <th bgcolor="gray" width="10%"><font color="white">Hist#</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Sale#</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Type</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">User</font></th>
                            <th bgcolor="gray" width="30%"><font color="white">Modifed</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Header</font></th>
                            <th bgcolor="gray" width="10%"><font color="white">Body</font></th>
                          </tr>
<?php
	$linenum = 1;
	if ($recs) $numrecs = count($recs);
	else $numrecs = 0;
	for ($i=0; $i<$numrecs; $i++) {
		if ($i%2 == 1) echo "<tr>"; 
		else echo "<tr bgcolor=\"#EEEEEE\">";
?>
							<td align="left"> 
                              <?= $recs[$i][salehist_id] ?>
                            </td>
                            <td align="center"> 
                              <?= $recs[$i][salehist_sale_id] ?>
                            </td>
                            <td align="center"> 
                              <?= strtoupper($recs[$i][salehist_type]) ?>
                            </td>
                            <td align="center"> 
                              <?= $recs[$i][salehist_user_code] ?>
                            </td>
                            <td align="center"> 
                              <?= date("m/d/Y h:i:s A", $recs[$i][salehist_modified]) ?>
                            </td>
                            <td align="right"> 
                              <?= (empty($recs[$i][salehist_header]))?"X":"O" ?>
                            </td>
                            <td align="right"> 
                              <?= (empty($recs[$i][salehist_lines]))?"X":"O" ?>
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
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&pg=1" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>

                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&pg=$prevpage" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 

										<font color="gray"><?= "[$pg / $totalpage]" ?></font>

                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&pg=$nextpage" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>

                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&pg=$totalpage" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>

                          </tr>

                        </table></td>

                    </tr>

                  </table>

                </td>
                <td width="10">&nbsp;</td>
              </tr>
            </table></td>
          <td width="10" bgcolor="#CCCCFF">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr bgcolor="#6666FF"> 
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
