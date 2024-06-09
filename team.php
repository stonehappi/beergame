<?php
	session_start(); 
	if ($_SESSION['_logon']==0) {
		header("location:index.php");
	}
	
	include("config.php");
	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
	mysql_select_db(DB_NAME, $conn) or die();

	$thisturns = rtnProjectData("turns", $_SESSION['_gameno']);
	
	$_SESSION['_gameno'] = rtnGameNo();
	$strSQL = "SELECT *
		FROM tbgameplay
		WHERE teamid='" . $_SESSION['_teamid'] . "' AND playerid='" . $_SESSION['_playerid'] . "' AND gameno=" . $_SESSION['_gameno'];
	$strSQL .= " ORDER BY gameplayround DESC"; 
	$rs = mysql_query($strSQL,$conn);
	
	$gameplayround = 0;
	$inventory = 0; $backlog = 0;
	$cost1 = 0; $cost2 = 0;
	if ($rs) {
		if (mysql_num_rows($rs)>0) {
			if ($rsField = mysql_fetch_array($rs)) {
				$gameplayround = $rsField['gameplayround'];
				$inventory = $rsField['inventory'];
				$backlog = $rsField['backlog'];
				$cost1 = $rsField['cost1'];
			}
		}
	}

	$strSQL = "SELECT *
		FROM tbgameplay
		WHERE teamid='" . $_SESSION['_teamid'] . "' AND playerid IN ('1','2','3','4') AND gameno=" . $_SESSION['_gameno'] . " AND gameplayround='$gameplayround'";
	$rs = mysql_query($strSQL,$conn);
	if ($rs) {
		if (mysql_num_rows($rs)==4) {
			$gameplayround++;
		}
	}
	
	if ($gameplayround==1) {
		$round_orderplaced = 0;
		if ($_SESSION['_playerid']=='1') {
			$round_orderreceived = rtnProjectDetailData("customerdemand", $_SESSION['_gameno'], $gameplayround);
		} else {
			$round_orderreceived = rtnProjectData("orderreceived", $_SESSION['_gameno']);
		}
		$round_inventory = rtnProjectData("startinginventory", $_SESSION['_gameno']);
		$round_inbound = 0;
		$round_inprogress = 0;
		
	} else if ($gameplayround > 1) {
		$round_orderplaced = rtnGamePlayPreviousData("round_orderplaced", $gameplayround-1, $_SESSION['_gameno'], $_SESSION['_teamid'], $_SESSION['_playerid']);

		if ($_SESSION['_playerid']<=3) {
			$round_orderreceived = rtnGamePlayPreviousData("round_orderplaced", $gameplayround-1, $_SESSION['_gameno'], $_SESSION['_teamid'], $_SESSION['_playerid']+1);
		} else {
			if ($gameplayround > $thisturns) {
				$round_orderreceived = 0;
			} else {
				$round_orderreceived = rtnProjectDetailData("customerdemand", $_SESSION['_gameno'], $gameplayround);
				if ($round_orderreceived=="") {
					if ($_GET['r']!="") 
						$round_orderreceived = $_GET['r'];
					else
						$round_orderreceived = rand(rtnProjectData("customerdemand_min", $_SESSION['_gameno']),rtnProjectData("customerdemand_max", $_SESSION['_gameno']));
				}
			}
		}

		if ($_SESSION['_playerid']>1) {
			$round_inprogress = rtnGamePlayPreviousData("round_outbound", $gameplayround-1, $_SESSION['_gameno'], $_SESSION['_teamid'], $_SESSION['_playerid']-1);
		} else {
			$round_inprogress = rtnGamePlayPreviousData("round_orderplaced", $gameplayround-1, $_SESSION['_gameno'], $_SESSION['_teamid'], $_SESSION['_playerid']);
		}
		$round_inventory = rtnGamePlayPreviousData("round_inventory", $gameplayround-1, $_SESSION['_gameno'], $_SESSION['_teamid'], $_SESSION['_playerid']) + rtnGamePlayPreviousData("round_inprogress", $gameplayround-1, $_SESSION['_gameno'], $_SESSION['_teamid'], $_SESSION['_playerid']);
		$round_inbound = 0;
	}
	
	if ($round_inventory >= $round_orderreceived + $backlog) {
		$round_inventory = $round_inventory - $round_orderreceived - $backlog;
		$round_outbound = $round_orderreceived + $backlog;

		$backlog = 0;
	} else {
		$round_outbound = $round_inventory;

		$backlog = $backlog + $round_orderreceived - $round_inventory;
		$round_inventory = 0;
	}
	$inventory = $round_inventory;

	$cost2 = ($inventory * rtnProjectData("inventorycost", $_SESSION['_gameno'])) + 
		($backlog * rtnProjectData("backlogcost", $_SESSION['_gameno']));
	$cost1 = $cost1 + $cost2;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Beer Game (TMB & SIIT)</title>

<script type="text/javascript">
	var timer=0, timeout=<?php echo rtnProjectData("timeout", $_SESSION['_gameno']); ?>, timer2=<?php if ($_GET['t']=="") echo "0"; else echo $_GET['t']; ?>;
	
	function startTimer() {
		setInterval("timerUp()",1000);
	}

	function timerUp() {
		timer++; timer2++;
		var resetat=10; 
		
		if (timer2 == timeout) {
			alert("Time out = " +  timeout + " Seconds");
		}
		if (timer == resetat) {
			window.location = "team.php?t=" + timer2 + "&r=<?php echo $round_orderreceived; ?>";
		}
		var tleft=timer2 + " / " + timeout + " Seconds";
		document.getElementById('timer').innerHTML=tleft;
	}
</script>

</head>

<?php
	if ($gameplayround <= $thisturns) {
?>
<body onLoad="startTimer()" leftmargin="0" topmargin="0">
<?php
	} else {
?>
<body leftmargin="0" topmargin="0">
<?php
	}
?>
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
            <td><img src="images/bgwhite.gif" width="6" height="6"></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div align="center">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td style="border: 1px #ff6600 solid"><div align="center" style="font-size:16pt"><b><?php echo rtnTeamName($_SESSION['_teamid']); ?></b></div></td>
                    </tr>
                    <tr>
                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="20%"><div align="center">
                            <table width="95%" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="font-size:14pt; background-color:#CCCCCC"><div align="center"><b>Week: <?php echo $gameplayround ?> / <?php echo $thisturns ?></b></div></td>
                              </tr>
                            </table>
                          </div></td>
                          <td width="20%" style="font-size:20pt"><div align="center">
                            <table width="95%" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="font-size:14pt; background-color:#CCCCCC"><div align="center"><b>Inventory: <?php echo $inventory ?></b></div></td>
                              </tr>
                            </table>
                          </div></td>
                          <td width="20%" style="font-size:20pt"><div align="center">
                            <table width="95%" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="font-size:14pt; background-color:#CCCCCC"><div align="center"><b>Backlog: <?php echo $backlog ?></b></div></td>
                              </tr>
                            </table>
                          </div></td>
                          <td width="20%" style="font-size:20pt"><div align="center">
                            <table width="95%" border="0" align="left" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="font-size:14pt; background-color:#CCCCCC"><div align="center"><b>Cost: <?php echo "$" . $cost1 ?></b></div></td>
                              </tr>
                            </table>
                          </div></td>
                          <td width="20%" style="font-size:20pt"><table width="95%" border="0" align="left" cellpadding="0" cellspacing="0">
                            <tr>
                              <td style="font-size:14pt; background-color:#CCCCCC"><div align="center"><b><?php echo "+ $" . $cost2 ?></b></div></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table>
                </div></td>
              </tr>
              <tr>
                <td><img src="images/bgwhite.gif" width="6" height="6"></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="25%"><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td style="border: 1px #ff6600 solid"><div align="center"><?php echo rtnGamePlayUsername($_SESSION['_gameno'],$_SESSION['_teamid'],"1") ?></div></td>
                      </tr>
                    </table></td>
                    <td width="25%"><div align="center">
                      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td style="border: 1px #ff6600 solid"><div align="center"><?php echo rtnGamePlayUsername($_SESSION['_gameno'],$_SESSION['_teamid'],"2") ?></div></td>
                        </tr>
                      </table>
                    </div></td>
                    <td width="25%"><div align="center">
                      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td style="border: 1px #ff6600 solid"><div align="center"><?php echo rtnGamePlayUsername($_SESSION['_gameno'],$_SESSION['_teamid'],"3") ?></div></td>
                        </tr>
                      </table>
                    </div></td>
                    <td width="25%"><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td style="border: 1px #ff6600 solid"><div align="center"><?php echo rtnGamePlayUsername($_SESSION['_gameno'],$_SESSION['_teamid'],"4") ?></div></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                    <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                    <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                    <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                  </tr>
                  <tr>
                    <td><div align="center">
<?php 
	if ($_SESSION['_playerid']=='1') {
?>	
					<img src="images/player2.gif" width="70" height="63">
<?php
	} else {
		if (rtnGamePlayPreviousData("playerid", $gameplayround, $_SESSION['_gameno'], $_SESSION['_teamid'], 1)=="") {
?>		
					<img src="images/player4.gif" width="70" height="63">
<?php
		} else {
?>
					<img src="images/player.gif" width="70" height="63">
<?php
		}
	}
?>		
					</div></td>
                    <td><div align="center">
<?php 
	if ($_SESSION['_playerid']=='2') {
?>	
					<img src="images/player2.gif" width="70" height="63">
<?php
	} else {
		if (rtnGamePlayPreviousData("playerid", $gameplayround, $_SESSION['_gameno'], $_SESSION['_teamid'], 2)=="") {
?>		
					<img src="images/player4.gif" width="70" height="63">
<?php
		} else {
?>
					<img src="images/player.gif" width="70" height="63">
<?php
		}
	}
?>		
					</div></td>
                    <td><div align="center">
<?php 
	if ($_SESSION['_playerid']=='3') {
?>	
					<img src="images/player2.gif" width="70" height="63">
<?php
	} else {
		if (rtnGamePlayPreviousData("playerid", $gameplayround, $_SESSION['_gameno'], $_SESSION['_teamid'], 3)=="") {
?>		
					<img src="images/player4.gif" width="70" height="63">
<?php
		} else {
?>
					<img src="images/player.gif" width="70" height="63">
<?php
		}
	}
?>		
						</div></td>
                    <td><div align="center">
<?php 
	if ($_SESSION['_playerid']=='4') {
?>	
					<img src="images/player2.gif" width="70" height="63">
<?php
	} else {
		if (rtnGamePlayPreviousData("playerid", $gameplayround, $_SESSION['_gameno'], $_SESSION['_teamid'], 4)=="") {
?>		
					<img src="images/player4.gif" width="70" height="63">
<?php
		} else {
?>
					<img src="images/player.gif" width="70" height="63">
<?php
		}
	}
?>		
					</div></td>
                  </tr>
                  <tr valign="top">
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                      </tr>
                      <tr>
                        <td><div align="center" style="font-size:12pt"><b><?php echo rtnPlayerIDName("1"); ?></b></div></td>
                      </tr>
                    </table></td>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                      </tr>
                      <tr>
                        <td><div align="center" style="font-size:12pt"><b><?php echo rtnPlayerIDName("2"); ?></b></div></td>
                      </tr>
                    </table></td>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                      </tr>
                      <tr>
                        <td><div align="center" style="font-size:12pt"><b><?php echo rtnPlayerIDName("3"); ?></b></div></td>
                      </tr>
                    </table></td>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                      </tr>
                      <tr>
                        <td><div align="center" style="font-size:12pt"><b><?php echo rtnPlayerIDName("4"); ?></b></div></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
<?php
	if ($gameplayround>-1 && $gameplayround<=$thisturns) {
?>		  
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><form name="form3" method="post" action="gameplay_p1.php">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
	if ($gameplayround > 0) {
?>
                    <tr>
                      <td><table width="1000" border="0" cellspacing="0" cellpadding="0" background="images/flow<?php echo $_SESSION['_playerid'] ?>.gif">
					  	<tr><td>&nbsp;</td></tr>
					  	<tr><td>&nbsp;</td></tr>
					  	<tr><td>&nbsp;</td></tr>
					  	<tr><td>&nbsp;</td></tr>
                        <tr valign="top">
                          <td width="110"><table style="border: 3px #999999 solid" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="background-color:#999999"><div align="center" style="font-size:14pt; color:#FFFFFF"><b>Order<br>
                                  placed<br>
                                </b></div></td>
                              </tr>
                              <tr>
                                <td style="background-color:#EEEEEE"><div align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                    <tr>
                                      <td><div align="center" style="font-size:16pt"><b><?php echo $round_orderplaced ?></b></div></td>
                                    </tr>
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                  </table>
                                </div></td>
                              </tr>
                          </table></td>
                          <td width="50">&nbsp;</td>
                          <td width="110"><table style="border: 3px #999999 solid" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="background-color:#999999"><div align="center" style="font-size:14pt; color:#FFFFFF"><b>Inbound<br>
                                  &nbsp;</b></div></td>
                              </tr>
                              <tr>
                                <td style="background-color:#EEEEEE"><div align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                    <tr>
                                      <td><div align="center" style="font-size:16pt"><b><?php echo $round_inbound ?>
                                        <input name="hiddenRound_InBound" type="hidden" id="hiddenRound_InBound" value="<?php echo $round_inbound ?>">
                                      </b></div></td>
                                    </tr>
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                  </table>
                                </div></td>
                              </tr>
                          </table></td>
                          <td width="50">&nbsp;</td>
                          <td width="155"><table style="border: 3px #BF0000 solid" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="background-color:#BF0000"><div align="center" style="font-size:14pt; color:#FFFFFF"><b>In Progress <br>
                                  &nbsp;</b></div></td>
                              </tr>
                              <tr>
                                <td style="background-color:#EEEEEE"><div align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                    <tr>
                                      <td><div align="center" style="font-size:16pt"><b><?php echo $round_inprogress ?>
                                        <input name="hiddenRound_InProgress" type="hidden" id="hiddenRound_InProgress" value="<?php echo $round_inprogress ?>">
                                      </b></div></td>
                                    </tr>
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                  </table>
                                </div></td>
                              </tr>
                          </table></td>
                          <td width="50">&nbsp;</td>
                          <td width="155"><table style="border: 3px #BF0000 solid" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="background-color:#BF0000"><div align="center" style="font-size:14pt; color:#FFFFFF"><b>Inventory<br>
                                  &nbsp;</b></div></td>
                              </tr>
                              <tr>
                                <td style="background-color:#EEEEEE"><div align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                    <tr>
                                      <td><div align="center" style="font-size:16pt"><b><?php echo $round_inventory ?>
                                        <input name="hiddenRound_Inventory" type="hidden" id="hiddenRound_Inventory" value="<?php echo $round_inventory ?>">
                                      </b></div></td>
                                    </tr>
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                  </table>
                                </div></td>
                              </tr>
                          </table></td>
                          <td width="50">&nbsp;</td>
                          <td width="110"><table style="border: 3px #999999 solid" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="background-color:#999999"><div align="center" style="font-size:14pt; color:#FFFFFF"><b>Outbound<br>
                                  &nbsp;</b></div></td>
                              </tr>
                              <tr>
                                <td style="background-color:#EEEEEE"><div align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                    <tr>
                                      <td><div align="center" style="font-size:16pt"><b><?php echo $round_outbound ?>
                                        <input name="hiddenRound_OutBound" type="hidden" id="hiddenRound_OutBound" value="<?php echo $round_outbound ?>">
                                      </b></div></td>
                                    </tr>
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                  </table>
                                </div></td>
                              </tr>
                          </table></td>
                          <td width="50">&nbsp;</td>
                          <td width="110"><table style="border: 3px #999999 solid" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <tr>
                                <td style="background-color:#999999"><div align="center" style="font-size:14pt; color:#FFFFFF"><b>Order<br>
                                  received&nbsp;</b></div></td>
                              </tr>
                              <tr>
                                <td style="background-color:#EEEEEE"><div align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                    <tr>
                                      <td><div align="center" style="font-size:16pt"><b><?php echo $round_orderreceived ?>
                                        <input name="hiddenRound_OrderReceived" type="hidden" id="hiddenRound_OrderReceived" value="<?php echo $round_orderreceived ?>">
                                      </b></div></td>
                                    </tr>
                                    <tr>
                                      <td><img src="images/bgwhite.gif" width="6" height="6"></td>
                                    </tr>
                                  </table>
                                </div></td>
                              </tr>
                          </table></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
<?php
	}
?>
                    <tr>
                      <td><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">

                        <tr>
                          <td><div align="center">
                              <?php 
		$this_orderplaced = rtnGamePlayPreviousData("round_orderplaced", $gameplayround, $_SESSION['_gameno'], $_SESSION['_teamid'], $_SESSION['_playerid']);
		if ($this_orderplaced=="") {
			if ($gameplayround==0) {
?>
                              <br><input style="font-size:20pt" type="submit" name="Submit322" value="         START         ">
                              <?php 
			} else if ($gameplayround <= $thisturns) {
?>
                            <div id="timer" style="font-size:16pt"></div>
                              <input style="font-size:14pt" type="submit" name="Submit322" value="PLACE ORDER">&nbsp;:&nbsp;<input style="font-size:14pt" name="txtRound_OrderPlaced" type="text" id="txtRound_OrderPlaced" size="2" value="<?php echo $this_orderplaced; ?>" <?php if ($this_orderplaced != "") echo "readonly"; ?>>
                            <?php
			}
		} else { 
?>
                            <br>- - - WAIT - - -
                            <?php
		}
?>
                            <input name="hiddenGamePlayRound" type="hidden" id="hiddenGamePlayRound" value="<?php echo $gameplayround ?>">
                            <input name="hiddenGameNo" type="hidden" id="hiddenGameNo" value="<?php echo $_SESSION['_gameno']; ?>">
                            <input name="hiddenInventory" type="hidden" id="hiddenInventory" value="<?php echo $inventory; ?>">
                            <input name="hiddenBackLog" type="hidden" id="hiddenBackLog" value="<?php echo $backlog; ?>">
                            <input name="hiddenCost1" type="hidden" id="hiddenCost1" value="<?php echo $cost1; ?>">
                            <input name="hiddenCost2" type="hidden" id="hiddenCost2" value="<?php echo $cost2; ?>">
                          </div></td>
                        </tr>

                      </table></td>
                    </tr>
                  </table>
                                </form>
                </td>
              </tr>
            </table></td>
          </tr>
<?php
	}
	if ($gameplayround > $thisturns) {
?>
          <tr><td>&nbsp;</td></tr>
		  <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="font-size:14pt"><form name="form1" method="post" action="">
                  <input type="submit" name="Submit3" value="View Chart">
                  : 
                  <input name="radioChart" type="radio" value="1"<?php if ($_POST['radioChart']=='1' || $_POST['radioChart']=='') echo " checked"; ?>> Cost
                  <input name="radioChart" type="radio" value="2"<?php if ($_POST['radioChart']=='2') echo " checked"; ?>> Inventory
                  <input name="radioChart" type="radio" value="3"<?php if ($_POST['radioChart']=='3') echo " checked"; ?>> 
                  Order
                | Show Player
                  <input type="checkbox" name="chkPlayer1" value="1"<?php if ($_POST['chkPlayer1']=='1' || $_SESSION['_playerid']=='1') { echo " checked";} if ($_SESSION['_playerid']=='1') echo " disabled"; ?>> 1
                  <input type="checkbox" name="chkPlayer2" value="1"<?php if ($_POST['chkPlayer2']=='1' || $_SESSION['_playerid']=='2') { echo " checked";} if ($_SESSION['_playerid']=='2') echo " disabled"; ?>> 2
                  <input type="checkbox" name="chkPlayer3" value="1"<?php if ($_POST['chkPlayer3']=='1' || $_SESSION['_playerid']=='3') { echo " checked";} if ($_SESSION['_playerid']=='3') echo " disabled"; ?>> 3
                  <input type="checkbox" name="chkPlayer4" value="1"<?php if ($_POST['chkPlayer4']=='1' || $_SESSION['_playerid']=='4') { echo " checked";} if ($_SESSION['_playerid']=='4') echo " disabled"; ?>> 4
                </form>                </td>
              </tr>
              <tr>
                <td>
<?php
	if ($_POST['radioChart']=='1' || $_POST['radioChart']=='') {
?>
					<img src="graph_cost.php">
<?php
	} else if ($_POST['radioChart']=='2') {
?>
					<img src="graph_inventory.php?player=
<?php 
	if ($_POST['chkPlayer1']=='1' || $_SESSION['_playerid']=='1') {
		$tmpplayer = "1";
	}
	if ($_POST['chkPlayer2']=='1' || $_SESSION['_playerid']=='2') {
		if ($tmpplayer != "") $tmpplayer = $tmpplayer . "|";
		$tmpplayer = $tmpplayer . "2";
	}
	if ($_POST['chkPlayer3']=='1' || $_SESSION['_playerid']=='3') {
		if ($tmpplayer != "") $tmpplayer = $tmpplayer . "|";
		$tmpplayer = $tmpplayer . "3";
	}
	if ($_POST['chkPlayer4']=='1' || $_SESSION['_playerid']=='4') {
		if ($tmpplayer != "") $tmpplayer = $tmpplayer . "|";
		$tmpplayer = $tmpplayer . "4";
	}
	echo $tmpplayer;
?>
					">
<?php
	} else if ($_POST['radioChart']=='3') {
?>
					<img src="graph_order.php?player=
<?php 
	if ($_POST['chkPlayer1']=='1' || $_SESSION['_playerid']=='1') {
		$tmpplayer = "1";
	}
	if ($_POST['chkPlayer2']=='1' || $_SESSION['_playerid']=='2') {
		if ($tmpplayer != "") $tmpplayer = $tmpplayer . "|";
		$tmpplayer = $tmpplayer . "2";
	}
	if ($_POST['chkPlayer3']=='1' || $_SESSION['_playerid']=='3') {
		if ($tmpplayer != "") $tmpplayer = $tmpplayer . "|";
		$tmpplayer = $tmpplayer . "3";
	}
	if ($_POST['chkPlayer4']=='1' || $_SESSION['_playerid']=='4') {
		if ($tmpplayer != "") $tmpplayer = $tmpplayer . "|";
		$tmpplayer = $tmpplayer . "4";
	}
	echo $tmpplayer;
?>
					">
<?php
	}
?>				</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
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
