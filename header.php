<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<body leftmargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" height="155" border="0" cellpadding="0" cellspacing="0">
      <tr valign="top">
        <td background="images/header.jpg"><table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><img src="images/bgwhite.gif" width="25" height="25"></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr valign="top">
				<td width="1%"><img src="images/bgwhite.gif" width="20"></td>
                <td width="1%"><table width="214" border="0" cellspacing="0" cellpadding="0">
                  <tr valign="top"> 
                    <td width="102"><img src="images/logotmb.png" width="175" height="83"></td>
					<td width="10">&nbsp;</td>
                    <td width="102"><img src="images/logosiit.png" width="175" height="62"></td>
                  </tr>
                </table></td>
                <td width="99%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="1%"><img src="images/bgwhite.gif" width="15"></td>
                    <td width="100%" style="font-size:30pt"><img src="images/supplychaingame.png" width="300" height="37"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td style="font-size:14pt"><img src="images/bytmbsiit.png"></td>
                  </tr>
                  <tr>
                    <td><img src="images/bgwhite.gif" width="10" height="10"></td>
                    <td><img src="images/bgwhite.gif" width="10" height="10"></td>
                  </tr>
<?php
	if ($_SESSION['_logon']==0) {
?>		  
                  <tr>
                    <td>&nbsp;</td>
                    <td><form name="form2" method="post" action="checklogin.php">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td>&nbsp;&nbsp;&nbsp;Username :
                            <input name="txtUsername" type="text" id="txtUsername" size="20">
                            Password :
                            <input name="txtPassword" type="text" id="txtPassword" size="20">
                            <input type="submit" name="Submit" value="Log on"></td>
                        </tr>
                      </table>
                                        </form>                    </td>
                  </tr>
<?php
	} else {
?>					  
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;Welcome -> <u><?php echo $_SESSION['_username']; ?></u> -> 
                          <input type="button" name="Submit2" value="Main menu" onClick="window.location.href='index.php'">
                          <input type="button" name="Submit22" value="Log off" onClick="window.location.href='logoff.php'">
                    </td>
                  </tr>
<?php
	}
?>				  
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>