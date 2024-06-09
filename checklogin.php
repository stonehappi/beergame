<?php session_start(); ?>
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
	include("config.php");
	
	$username = $_POST['txtUsername'];
	$password = $_POST['txtPassword'];
	
	$_SESSION['_username'] = "";
	$_SESSION['_logon'] = 0;

	if (strlen(mytrim($username))>0) {
		$_SESSION['_username'] = $username;
		$_SESSION['_logon'] = 1;
	} else {
?>
<body onLoad="javascript:frmRedirect('Type Username','index.php')">
</body>
<?php
	}
?>
<body onLoad="javascript:frmRedirect('','index.php')">
</body>
</html>