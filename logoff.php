<?php
	session_start();
	
	unset($_SESSION['_username']);
	unset($_SESSION['_logon']);

	header("location:index.php");
?>