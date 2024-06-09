<?php
	session_start();
	
	include("config.php");
	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
	mysql_select_db(DB_NAME, $conn) or die();
	
	$_SESSION['_gameno'] = rtnGameNo();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Beer Game (TMB & SIIT)</title>
</head>

<body leftmargin="0" topmargin="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php require_once("header.php"); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
          </tr>
<?php
	if ($_SESSION['_gameno']!="") {
		$strSQL = "SELECT *
			FROM tbplayer";
		$strSQL .= " ORDER BY playerid ASC"; 
		$rs = mysql_query($strSQL,$conn);
		
		$i=0;
		if ($rs) {
			if (mysql_num_rows($rs)>0) {
				while ($rsField = mysql_fetch_array($rs)) {
					$i++;
					$playerid[$i] = $rsField['playerid'];
					$playername[$i] = $rsField['playername'];
				}
			}
		}
	
		$strSQL = "SELECT *
			FROM tbteam";
		$strSQL .= " ORDER BY teamid ASC"; 
		$rs = mysql_query($strSQL,$conn);
	
		if ($rs) {
			if (mysql_num_rows($rs)>0) {
				while ($rsField = mysql_fetch_array($rs)) {
?>
          <tr>
            <td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30%" style="border: 1px #ff6600 solid"><div align="center">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><div align="center" style="font-size:12pt"><b><?php echo $rsField['teamid'] . ': ' . $rsField['teamname']; ?></b></div></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </div></td>
                <td width="70%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="25%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr>
                            <td style="border: 1px #ff6600 solid"><div align="center"><?php echo rtnGamePlayUsername($_SESSION['_gameno'],$rsField['teamid'],"1") ?></div></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr><td><div align="center"><img src="images/bgwhite.gif" width="6" height="6"></div></td></tr>
					  <tr>
                        <td><div align="center">
						<img src="images/player.gif" width="75" height="67" border="0">
						</div></td>
                      </tr>
                      <tr>
                        <td><img src="images/bgwhite.gif" width="10" height="10"></td>
                      </tr>
                      <tr>
                        <td><div align="center">
                          <input type="button" name="Submit23" value="<?php echo $playerid[1] . ': ' . $playername[1]; ?>" 
<?php
	if ($_SESSION['_logon']==1) {
?>						  
						  onClick="window.location.href='checkteam.php?t=<?php echo $rsField['teamid'] ?>&p=<?php echo $playerid[1] ?>'"
<?php
	}
?>						  
						  >
                        </div></td>
                      </tr>
                      <tr>
                        <td><div align="center"></div></td>
                      </tr>
                    </table></td>
                    <td width="25%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td style="border: 1px #ff6600 solid"><div align="center"><?php echo rtnGamePlayUsername($_SESSION['_gameno'],$rsField['teamid'],"2") ?></div></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td><div align="center"><img src="images/bgwhite.gif" width="6" height="6"></div></td>
                      </tr>
                      
                      <tr>
                        <td><div align="center"> <img src="images/player.gif" width="75" height="67" border="0"> </div></td>
                      </tr>
                      <tr>
                        <td><img src="images/bgwhite.gif" width="10" height="10"></td>
                      </tr>
                      <tr>
                        <td><div align="center">
                            <input type="button" name="Submit232" value="<?php echo $playerid[2] . ': ' . $playername[2]; ?>" 
<?php
	if ($_SESSION['_logon']==1) {
?>						  
						  onClick="window.location.href='checkteam.php?t=<?php echo $rsField['teamid'] ?>&p=<?php echo $playerid[2] ?>'"
<?php
	}
?>						  
						  >
                        </div></td>
                      </tr>
                      <tr>
                        <td><div align="center"></div></td>
                      </tr>
                    </table></td>
                    <td width="25%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td style="border: 1px #ff6600 solid"><div align="center"><?php echo rtnGamePlayUsername($_SESSION['_gameno'],$rsField['teamid'],"3") ?></div></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td><div align="center"><img src="images/bgwhite.gif" width="6" height="6"></div></td>
                      </tr>
                      
                      <tr>
                        <td><div align="center"> <img src="images/player.gif" width="75" height="67" border="0"> </div></td>
                      </tr>
                      <tr>
                        <td><img src="images/bgwhite.gif" width="10" height="10"></td>
                      </tr>
                      <tr>
                        <td><div align="center">
                            <input type="button" name="Submit233" value="<?php echo $playerid[3] . ': ' . $playername[3]; ?>" 
<?php
	if ($_SESSION['_logon']==1) {
?>						  
						  onClick="window.location.href='checkteam.php?t=<?php echo $rsField['teamid'] ?>&p=<?php echo $playerid[3] ?>'"
<?php
	}
?>						  
						  >
                        </div></td>
                      </tr>
                      <tr>
                        <td><div align="center"></div></td>
                      </tr>
                    </table></td>
                    <td width="25%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td style="border: 1px #ff6600 solid"><div align="center"><?php echo rtnGamePlayUsername($_SESSION['_gameno'],$rsField['teamid'],"4") ?></div></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td><div align="center"><img src="images/bgwhite.gif" width="6" height="6"></div></td>
                      </tr>
                      
                      <tr>
                        <td><div align="center"> <img src="images/player.gif" width="75" height="67" border="0"> </div></td>
                      </tr>
                      <tr>
                        <td><img src="images/bgwhite.gif" width="10" height="10"></td>
                      </tr>
                      <tr>
                        <td><div align="center">
                            <input type="button" name="Submit234" value="<?php echo $playerid[4] . ': ' . $playername[4]; ?>" 
<?php
	if ($_SESSION['_logon']==1) {
?>						  
						  onClick="window.location.href='checkteam.php?t=<?php echo $rsField['teamid'] ?>&p=<?php echo $playerid[4] ?>'"
<?php
	}
?>						  
						  >
                        </div></td>
                      </tr>
                      <tr>
                        <td><div align="center"></div></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table>
			</td>
          </tr>
		  <tr><td>&nbsp;</td></tr>
<?php		  
				}
			}
		}
	} else {
?>
		<tr><td><div align="center" style="font-size:20pt"><b>Config the Beer Game before Playing</b></div></td></tr>
<?php
	}
?>			
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php require_once("bottom.php"); ?></td>
  </tr>
</table>
</body>
</html>
