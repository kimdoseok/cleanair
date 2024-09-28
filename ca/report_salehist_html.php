<?php
	include_once("class/register_globals.php");

function TimeDelta($td) {
	$outstr = "";
	$hour = floor($td/3600);
	$td = $td - $hour*3600;
	if ($hour>0) $outstr .= sprintf("%02d", $hour).":";
	$min = floor($td/60);
	if ($min>0) $outstr .= sprintf("%02d", $min).":";
	$sec = $td - $min*60;
	$outstr .= sprintf("%02d", $sec);
	return $outstr;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Sales History Report</TITLE>
</HEAD>
<BODY>
<table width="100%" border="0" cellpadding="2" cellspacing="1">
  <tr bgcolor="white"> 
	<td align="center" colspan="15"><font size="6" face="Arial, Helvetica, sans-serif"><strong>Sales History Report</strong></font>
	</td>
  </tr>
  <tr align="center" bgcolor="gray"> 
    <td width="5%"><strong><font size="2" face="Arial, Helvetica, sans-serif">#</font></strong></td>
    <td width="20%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Sales#</font></strong></td>
    <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">Type</font></strong></td>
    <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">User#</font></strong></td>
    <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">modified</font></strong></td>
    <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">TimeDelta1</font></strong></td>
    <td width="15%"><strong><font size="2" face="Arial, Helvetica, sans-serif">TimeDelta2</font></strong></td>
  </tr>
<?php
	$sid = $_POST[start_sale_id];
	$eid = $_POST[end_sale_id];
	$sdt = $_POST[start_datetime_year] . $_POST[start_datetime_month] . $_POST[start_datetime_day] . $_POST[start_datetime_hour] . $_POST[start_datetime_minute] . $_POST[start_datetime_second];
	$edt = $_POST[end_datetime_year] . $_POST[end_datetime_month] . $_POST[end_datetime_day] . $_POST[end_datetime_hour] . $_POST[end_datetime_minute] . $_POST[end_datetime_second];
	$recs = $sh->getSaleHistsReport($sid,$eid,$sdt,$edt);
	$num = count($recs);
	for ($i=0;$i<$num;$i++) {
		$year = substr($recs[$i][salehist_modified],0,4);
		$month = substr($recs[$i][salehist_modified],5,2);
		$day = substr($recs[$i][salehist_modified],8,2);
		$hour = substr($recs[$i][salehist_modified],11,2);
		$minute = substr($recs[$i][salehist_modified],14,2);
		$second = substr($recs[$i][salehist_modified],17,2);
		$curts = mktime($hour,$minute,$second, $month,$day,$year);
		if ($i==0) {
			$starts = $curts;
			$lastts = $curts;
		}
		$diffirst = $curts-$starts;
		$difflast = $curts-$lastts;
		$lastts = $curts;

		if ($recs[$i][salehist_type]=="i") $shtype = "Add";
		else if ($recs[$i][salehist_type]=="u") $shtype = "Edit";
		else if ($recs[$i][salehist_type]=="d") $shtype = "Delete";
		else if ($recs[$i][salehist_type]=="p") $shtype = "Print";
		else if ($recs[$i][salehist_type]=="n") $shtype = "Pending";
		else if ($recs[$i][salehist_type]=="v") $shtype = "Convert";
		else $shtype = "Unknown";
?>
   	     	  
  <tr> 
    <td align="left" bgcolor="silver"><font size="2" face="Arial, Helvetica, sans-serif"><?= $recs[$i][salehist_id] ?></font></td>
    <td align="left" bgcolor="silver"><font size="2" face="Arial, Helvetica, sans-serif"><?= $recs[$i][salehist_sale_id] ?></font></td>
    <td align="left" bgcolor="silver"><font size="2" face="Arial, Helvetica, sans-serif"><?= $shtype ?></font></td>
    <td align="left" bgcolor="silver"><font size="2" face="Arial, Helvetica, sans-serif"><?= $recs[$i][salehist_user_code] ?></font></td>
    <td align="left" bgcolor="silver"><font size="2" face="Arial, Helvetica, sans-serif"><?= $recs[$i][salehist_modified] ?></font></td>
    <td align="right" bgcolor="silver"><font size="2" face="Arial, Helvetica, sans-serif"><?= timedelta($difflast) ?></font></td>
    <td align="right" bgcolor="silver"><font size="2" face="Arial, Helvetica, sans-serif"><?= timedelta($diffirst) ?></font></td>
  </tr>
  <tr> 
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td align="left" bgcolor="<?= $bgcolor ?>" colspan=6><font size="2" face="Arial, Helvetica, sans-serif"><?= $recs[$i][salehist_header] ?></font></td>
  </tr>
  <tr> 
    <td align="left" bgcolor="<?= $bgcolor ?>"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td align="left" bgcolor="<?= $bgcolor ?>" colspan=6><font size="2" face="Arial, Helvetica, sans-serif"><?= $recs[$i][salehist_lines] ?></font></td>
  </tr>
<?php
	}
?>
</table>
</BODY>
</HTML>