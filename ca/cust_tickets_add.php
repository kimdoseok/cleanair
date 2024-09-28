
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <form method=post action="sy_proc.php">
    <INPUT TYPE="hidden" name="cmd" value="ticket_add">
    <tr align="right">
      <td colspan="8"><strong>New Ticket</strong></td>
    </tr>
    <tr align="right">
      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a> |</font></td>
    </tr>
    <tr align="right">
      <td colspan="8">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
        
        <td width="468" align="right" bgcolor="white">
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td width="97" align="right" bgcolor="silver">Customer#:&nbsp;</td>
            <td>
			<?= $f->fillTextBox("tkt_cust_code", $tkt_cust_code, 16, 16, "inbox") ?><A HREF="javascript:openCustBrowse('tkt_cust_code')"><font size="2">Lookup</font></A>
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Employee#:&nbsp;</td>
            <td><?= $f->fillTextBoxRO("tkt_user_code", $_SERVER["PHP_AUTH_USER"], 16, 16, "inbox") ?>
            </td>
          </tr>
          <tr>
            <td width="97" align="right" bgcolor="silver">Sales#:&nbsp;</td>
            <td><?= $f->fillTextBox("tkt_refnum", $tkt_refnum, 16, 16, "inbox") ?> <A HREF="javascript:openSalesBrowse('tkt_refnum')"><font size="2">Lookup</font></A>
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
            <td colspan="2"><textarea class="inbox" cols="80" rows="10" name="tkt_body"><?= $tkt_body ?></textarea>
            </td>
          </tr>
        </table>
        </td>
        </tr>
      </table>
      </td>
    </tr>
    <tr align="right">
      <td colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="100%" align="center"><input type="submit" name="Submit32" value="Record">
              <input type="reset" name="Submit222" value="Cancel"></td>
          </tr>
        </table></td>
    </tr>
  </form>
</table>
