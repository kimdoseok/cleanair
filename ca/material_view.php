                  <table width="100%" border="0" cellspacing="1" cellpadding="0">
                    <tr align="right"> 
                      <td colspan="8"><strong>View Material</strong></td>
                    </tr>
                    <tr> 
                      <td colspan="8" align="left"><font size="2"> | <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=a" ?>">New</a>  |
								<a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=e&material_code=$material_code" ?>">Edit</a>  |
                        <a href="<?php echo htmlentities($_SERVER['PHP_SELF'])."?ty=l" ?>">List</a>  | </font></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr bgcolor="white"> 
                  <td width="20%" align="right" bgcolor="silver">Material#:&nbsp;</td>
                  <td width="80%"> 
                    <?= $material_code ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Name:&nbsp;</td>
                  <td> 
                    <?= $material_name ?>
                  </td>
                </tr>
                <tr> 
                  <td align="right" bgcolor="silver">Description:&nbsp;</td>
                  <td> 
                    <?= $material_desc ?>
                  </td>
                </tr>
        </table></td>
                    </tr>
                    <tr align="right"> 
                      <td colspan="8">
					    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td width="64%" align="center"><a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-2&material_code=$material_code" ?>">&lt;&lt;First</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=-1&material_code=$material_code" ?>">&lt;Prev</a> 
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=1&material_code=$material_code" ?>">Next&gt;</a>
                              &nbsp; <a href="<?= htmlentities($_SERVER['PHP_SELF'])."?ty=v&dir=2&material_code=$material_code" ?>">Last&gt;&gt;</a></td>
                          </tr>
                        </table>
					  </td>
                    </tr>
                  </table>
