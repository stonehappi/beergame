<?php
	session_start();
	
	include("config.php");
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="0">
<SCRIPT language=JavaScript>
<!--
	function frmRedirect(pMessage,pURL) {
		alert(pMessage);
		window.location=pURL;
	}
// --> </SCRIPT>
</head>
<?php
	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
	mysql_select_db(DB_NAME, $conn) or die();

	$teamid = $_SESSION['_teamid'];
	$playerid = $_SESSION['_playerid'];
	$username = $_SESSION['_username'];
	$gameplayround = $_POST['hiddenGamePlayRound'];
	$gameno = $_POST['hiddenGameNo'];

	$round_orderplaced = $_POST['txtRound_OrderPlaced'];
	$round_inbound = $_POST['hiddenRound_InBound'];
	$round_inprogress = $_POST['hiddenRound_InProgress'];
	$round_inventory = $_POST['hiddenRound_Inventory'];
	$round_outbound = $_POST['hiddenRound_OutBound'];
	$round_orderreceived = $_POST['hiddenRound_OrderReceived'];

	$inventory = $_POST['hiddenInventory'];
	$backlog = $_POST['hiddenBackLog'];
	$cost1 = $_POST['hiddenCost1'];
	$cost2 = $_POST['hiddenCost2'];
	
	if (strlen(mytrim($teamid))>0 && strlen(mytrim($playerid))>0 && strlen(mytrim($gameplayround))>0 && strlen(mytrim($gameno))>0) {
		$rs = mysql_query("SELECT * FROM tbgameplay
			WHERE teamid='$teamid' AND playerid='$playerid' AND gameplayround='$gameplayround' AND gameno='$gameno'",$conn);
		if ($rs) {
			if (mysql_num_rows($rs)==0) {
				$strSQL = "INSERT INTO tbgameplay(teamid,playerid,gameno,gameplayround,username,inventory,backlog,cost1,cost2,round_orderplaced,round_inbound,round_inprogress,round_inventory,round_outbound,round_orderreceived) VALUES('$teamid','$playerid','$gameno','$gameplayround','$username','$inventory','$backlog','$cost1','$cost2','$round_orderplaced','$round_inbound','$round_inprogress','$round_inventory','$round_outbound','$round_orderreceived')";
				$rs = mysql_query($strSQL,$conn);
				if ($rs) {
?>				
<body onLoad="javascript:frmRedirect('Completed','team.php')">
</body>
<?php 
				} else {
?>
<body onLoad="javascript:frmRedirect('Error','team.php')">
</body>
<?php 
				}
			} else {
?>
<body onLoad="javascript:frmRedirect('Duplicate Data','team.php')">
</body>
<?php 
			}
		}
	} else {
?>
<body onLoad="javascript:frmRedirect('Invalid Data','team.php')">
</body>
<?php
	}
?>
</html>