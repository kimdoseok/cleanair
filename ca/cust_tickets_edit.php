<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <form method=post action="sy_proc.php">
    <INPUT TYPE="hidden" name="cmd" value="ticket_edit">
    <tr align="right">
      <td colspan="8"><strong>Edit Tickets</strong></td>
    </tr>
    <tr>
      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=v&tkt_id=$tkt_id" ?>">View</a> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> | </font></td>
    </tr>
    <tr align="right">
      <td colspan="8">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr bgcolor="white">
        
        <td width="466" align="right">
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td width="97" align="right" bgcolor="silver">Code:&nbsp;</td>
            <td width="308"><?= $tkt_id ?>
              <?= $f->fillHidden("tkt_id", $tkt_id) ?>
              <?= $f->fillHidden("ty", $ty) ?>
			  <A HREF="sy_proc.php?cmd=ticket_del&parent_id=0&tkt_id=<?= $tkt_id ?>">Delete</A>
            </td></td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Customer#:&nbsp;</td>
            <td><?= $f->fillTextBox("tkt_cust_code", $tkt_cust_code, 16, 16, "inbox") ?><A HREF="javascript:openCustBrowse('tkt_cust_code')"><font size="2">Lookup</font></A>
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Employee#:&nbsp;</td>
            <td><?= $f->fillTextBoxRO("tkt_user_code", $tkt_user_code, 16, 16, "inbox") ?>
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Ref. Type:&nbsp;</td>
            <td><INPUT TYPE="radio" NAME="tkt_reftype" VALUE="0" <?= ($tkt_reftype==0)?"checked":"" ?>>
              Sales Order </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Ref. Number:&nbsp;</td>
            <td><?= $f->fillTextBox("tkt_refnum", $tkt_refnum, 16, 16, "inbox") ?><A HREF="javascript:openSalesBrowse('tkt_refnum')"><font size="2">Lookup</font></A>
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Title:&nbsp;</td>
            <td><?= $f->fillTextBox("tkt_title", $tkt_title, 64, 64, "inbox") ?>
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2" bgcolor="silver">Message</td>
          </tr>
          <tr>
            <td colspan="2"><textarea class="inbox" cols="50" rows="10" name="tkt_body"><?= $tkt_body ?></textarea>
            </td>
          </tr>
        </table>
        </td>
        </tr>
      </table>
      </td>
    </tr>
  <tr align="right">
    <td colspan="8">&nbsp;</td>
  </tr>
    <tr align="right">
      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-2" ?>">&lt;&lt;
              First
              </a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=-1" ?>">&lt;
              Prev
              </a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=1" ?>">
              Next
              &gt;</a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=e&dir=2" ?>">
              Last
              &gt;&gt;</a></td>
            <td width="36%" align="center"><input type="submit" name="Submit322" value="Record">
              <input type="reset" name="Submit2222" value="Cancel"></td>
          </tr>
        </table></td>
    </tr>
  </form>
  <tr align="right">
    <td colspan="8">
	  <table width="100%" border="0" cellspacing="0" cellpadding="1">
<?php
	$limit = 500;
	$numrows = $c->getTicketRspRows($tkt_id);
	$rsp = $c->getTicketResponses($tkt_id);
	
	for ($i=0;$i<$numrows;$i++) {
?>
	     <tr>
		   <td width="100%" colspan="2"><HR size="1"></td>
		 </tr>
	     <tr>
		   <td width="5%">&nbsp;</td>
		   <td width="95%"><b><?= strtoupper($rsp[$i]["tkt_user_code"]) ?></b>, <?= $rsp[$i]["tkt_ts"] ?> <A HREF="sy_proc.php?cmd=ticket_del&parent_id=<?= $rsp[$i]["tkt_parent"] ?>&tkt_id=<?= $rsp[$i]["tkt_id"] ?>">Delete</A></td>
		 </tr>
	     <tr>
		   <td width="5%">&nbsp;</td>
		   <td width="95%"><?= $rsp[$i]["tkt_body"] ?></td>
		 </tr>
<?php
	}
?>
	  </table>
	</td>
  </tr>
</table>
