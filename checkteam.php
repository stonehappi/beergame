<?php 
	session_start(); 

	if ($_SESSION['_logon']==0) {
		header("location:index.php");
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="expires" content="0">
<SCRIPT language=JavaScript>
<!--
	function frmRedirect(pMessage,pURL) {
		if (pMessage!='') { alert(pMessage); }
		window.location=pURL;
	}
// --> </SCRIPT>
</head>
<?php
	$teamid = $_GET['t'];
	$playerid = $_GET['p'];

	include("config.php");
	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
	mysql_select_db(DB_NAME, $conn) or die();
	
	$_SESSION['_teamid'] = $teamid;
	$_SESSION['_playerid'] = $playerid;
?>
<body onLoad="javascript:frmRedirect('','team.php')">
</body>
</html>