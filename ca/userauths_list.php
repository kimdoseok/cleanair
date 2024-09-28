<?php
	$selbox = array(0=>array("value"=>"code", "name"=>"Code"),
					1=>array("value"=>"name", "name"=>"Name")
	);

	$ulimit = 8;
	$ua = new UserAuths();
	$d = new Datex();
	$a = new Auths();
	$u = new Users();
	$urows = $u->getUsersRows($cn, $ft);
	$urecs = $u->getUsersList("active", True, $rv, $page, $ulimit);
	$arows = $a->getAuthsRows();
	$arecs = $a->getAuthsList("","","",$page,$arows);
	$uarows = $ua->getUserAuthsRows();
	$uarecs = $ua->getUserAuthsList("","","",$page,$uarows);
	$_SESSION["olds"] = $uarecs;
	$n = new Navigates();
	$n->setTotalPage($urows, $ulimit);
	$n->setPage($page);
	$totalpage = $n->getTotalPage();
	$nextpage = $n->getNextPage();
	$prevpage = $n->getPrevPage();
	$page = $n->getPage();
?>
				  <table width="100%" border="0" cellspacing="1" cellpadding="0">
				  <form action="sy_proc.php" method="post">
                    <tr align="right"> 
                      <td><strong>UserAuths Table</strong></td>
                    </tr>
					<tr align="right"> 
                      <td>
						<?= $f->fillTextBox("ft",$ft,30) ?>
						<?= $f->fillSelectBox($selbox,"cn", "value", "name", $cn) ?>
						<input type="checkbox" name="rv" value="t" <?= ($rv=="t")?"checked":"" ?>>
						<?= $label[$lang]["Reverse"] ?>
						<input type="button" name="filter" value="<?= $label[$lang]["Filter"] ?>" onClick="setFilter()">
						<?= $f->fillHidden("objname",$objname) ?>
						<?= $f->fillHidden("page",$page) ?>
						<?= $f->fillHidden("cmd","userauth_save") ?>
						<?= $f->fillHidden("ulimit",$ulimit) ?>
					  </td>
                    </tr>
                    <tr align="right"> 
                      <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
<?php
	$unum = count($urecs);
	$width = round(100/($ulimit+1));
	$dwidth = $width*2;

	echo "<tr>";
	echo "<th bgcolor='gray' width='$dwidth%' align='center'><font color='white'><INPUT TYPE='submit'>&nbsp;</font></th>";
	for ($i=0;$i<$ulimit;$i++) {
		if ($i <= $unum) echo "<th bgcolor='gray' width='$width%'><font color='white'><a href='sy_proc.php?cmd=userauth_allc&id=".$urecs[$i]["user_id"]."'> ".$urecs[$i]["user_code"]."</a></font></th>";
		else echo "<th bgcolor='gray' width='$width%'><font color='white'>&nbsp;</font></th>";
	}
	echo "</tr>";

	for ($i=0; $i<$arows; $i++) {
		if ($i%2 == 1) echo "<tr>\n"; 
		else echo "<tr bgcolor=\"#EEEEEE\">\n";
		echo "<td align='left' bgcolor='gray'><font color='white'><a href='sy_proc.php?cmd=userauth_allr&id=".$arecs[$i]["auth_id"]."'>";
		echo $arecs[$i]["auth_code"];
		echo "</a></font></td>\n";
		for ($j=0; $j<$ulimit; $j++) {
			$name = "";
			$found = 0;
			echo "<td align='center'>\n";
			if ($j<$unum) {
				$name = "au_";
				$name .= $arecs[$i]["auth_id"];
				$name .= "_";
				$name .= $urecs[$j]["user_id"];
				$checked = "";
				for ($k=0;$k<$uarows;$k++) {
					if ($uarecs[$k]["userauth_auth_id"]==$arecs[$i]["auth_id"] && $uarecs[$k]["userauth_user_id"]==$urecs[$j]["user_id"]) {
						$value = $uarecs[$k]["userauth_allow"];
						$checked = "checked";
						break;
					}
				}
				echo "<INPUT TYPE='checkbox' NAME='$name' VALUE='t' $checked>\n";
			} else {
				echo "&nbsp;";
			}
			echo "</td>\n";
		}
		echo "</tr>\n";
	}

	if ($arows == 0) {
		echo "<tr><td colspan='";
		echo $ulimit+1;
		echo "' align='center'><font color='red'>No Data!</font></td></tr>";
	}
?>
								</table></td>
                    </tr>
                    <tr align="right"> 
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&ty=l&page=1" ?>">&lt;&lt;<?= $label[$lang]["First"] ?></a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&ty=l&page=$prevpage" ?>">&lt;<?= $label[$lang]["Prev_1"] ?></a> &nbsp; 
										<font color="gray"><?= "[$page / $totalpage]" ?></font>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&ty=l&page=$nextpage" ?>"><?= $label[$lang]["Next_1"] ?>&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?cn=$cn&ft=$ft&ty=l&page=$totalpage" ?>"><?= $label[$lang]["Last"] ?>&gt;&gt;</a></td>
                          </tr>
                        </table></td>
                    </tr>
				  </form>
                  </table>
