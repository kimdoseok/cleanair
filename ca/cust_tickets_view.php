
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr align="right">
    <td colspan="8"><strong>View Tickets</strong></td>
  </tr>
  <tr>
    <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">
      New
      </a> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&tkt_id=$tkt_id" ?>">
      Edit
      </a> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">
      List
      </a> | </font></td>
  </tr>
        <tr bgcolor="white">
          <td width="466" align="right"><table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td width="97" align="right" bgcolor="silver">Code:&nbsp;</td>
            <td width="308"><?= $tkt_id ?>
            </td>
          </td>          
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Time/Date:&nbsp;</td>
            <td><?= $tkt_ts ?>
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Customer#:&nbsp;</td>
            <td><A HREF="customers.php?ty=v&cust_code=<?= $tkt_cust_code ?>"><?= $tkt_cust_code ?></A>
            [<A HREF="<?= "$PHPSELF?ft=$tkt_cust_code&fo=cu" ?>">FILTER</A>]
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Employee#:&nbsp;</td>
            <td><A HREF="users.php?ty=v&user_code=<?= $tkt_user_code ?>"><?= $tkt_user_code ?></A>
              [<A HREF="<?= "$PHPSELF?ft=$tkt_user_code&fo=us" ?>">FILTER</A>]
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Status:&nbsp;</td>
            <td>
<?php
	if ($tkt_status==0) echo "New";
	else if ($tkt_status==5) echo "Open";
	else if ($tkt_status==10) echo "Closed";
?>
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Ref. Type:&nbsp;</td>
            <td>
<?php
	if ($tkt_reftype==0) echo "Sales Order" ;
?>
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Ref. Number:&nbsp;</td>
            <td><a href="sales.php?ty=v&sale_id=<?= $tkt_refnum ?>"><?= $tkt_refnum ?></a>
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Title:&nbsp;</td>
            <td><?= $tkt_title ?>
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2" bgcolor="silver">Message</td>
          </tr>
          <tr>
            <td colspan="2"><?= nl2br($tkt_body) ?>
            </td>
          </tr>
            </table></td>
  </tr>
  <tr align="right">
    <td colspan="8">&nbsp;</td>
  </tr>
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
		   <td width="95%"><b><?= strtoupper($rsp[$i]["tkt_user_code"]) ?></b>, <?= $rsp[$i]["tkt_ts"] ?> </td>
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
  <tr align="right">
    <td colspan="8" bgcolor="white">
	  <table width="100%" border="0" cellspacing="0" cellpadding="2">
		<form method=post action="sy_proc.php">
		  <INPUT TYPE="hidden" name="cmd" value="ticket_response_add">
		  <INPUT TYPE="hidden" name="tkt_parent" value="<?= $tkt_id ?>">
		  <INPUT TYPE="hidden" name="tkt_cust_code" value="<?= $tkt_cust_code ?>">
		  <INPUT TYPE="hidden" name="tkt_user_code" value="<?= $_SERVER["PHP_AUTH_USER"] ?>">
		  <INPUT TYPE="hidden" name="tkt_refnum" value="<?= $tkt_refnum ?>">
		  <INPUT TYPE="hidden" name="tkt_title" value="<?= $tkt_title ?>">
          <tr>
            <td align="center" colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" colspan="2" bgcolor="gray">Response</td>
          </tr>
          <tr>
            <td colspan="2"><textarea class="inbox" cols="80" rows="10" name="tkt_body"></textarea>
            </td>
          </tr>
          <tr>
            <td colspan="2"><INPUT TYPE="checkbox" NAME="close_ticket" VALUE="1" <?= ($tkt_status==10)?"checked":"" ?>>Close Ticket?
            </td>
          </tr>
          <tr>
            <td align="center"><input type="submit" name="Submit32" value="Record"> <input type="reset" name="Submit222" value="Cancel"></td>
          </tr>
		</form>
	  </table>
	</td>
  </tr>
  <tr align="right">
    <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&tkt_id=$tkt_id" ?>">&lt;&lt;
            First
            </a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&tkt_id=$tkt_id" ?>">&lt;
            Prev
            </a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&tkt_id=$tkt_id" ?>">
            Next
            &gt;</a> &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&tkt_id=$tkt_id" ?>">
            Last
            &gt;&gt;</a></td>
        </tr>
      </table></td>
  </tr>
</table>
