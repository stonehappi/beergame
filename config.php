<?php
	// --- Database Config ---
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASS", "");
	define("DB_NAME", "dbbeergame_siit");

	function mytrim($str) {
    	return str_replace(' ','',$str);
   	}
	
	function rtnGamePlayPreviousData($pDataField, $pGamePlayRound, $pGameNo, $pTeamID, $pPlayerID) {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
		mysql_select_db(DB_NAME, $conn) or die();

		$strSQL = "SELECT $pDataField FROM tbgameplay
			WHERE teamid='$pTeamID' AND playerid='$pPlayerID' AND gameno='" . $_SESSION['_gameno'] . "' AND gameplayround='$pGamePlayRound'";
		$rs = mysql_query($strSQL,$conn);
		
		$rtn = "";
		if ($rs) {
			if (mysql_num_rows($rs)>0) {
				if ($rsField = mysql_fetch_array($rs)) {
					$rtn = $rsField[$pDataField];
				}
			}
		}
		
		return $rtn;
	}

	function rtnGamePlayUsername($pGameNo, $pTeamID, $pPlayerID) {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
		mysql_select_db(DB_NAME, $conn) or die();

		$strSQL = "SELECT * FROM tbgameplay
			WHERE teamid='$pTeamID' AND playerid='$pPlayerID' AND gameno='" . $_SESSION['_gameno'] . "'";
		$rs = mysql_query($strSQL,$conn);
		
		$rtn = "&nbsp;";
		if ($rs) {
			if (mysql_num_rows($rs)>0) {
				if ($rsField = mysql_fetch_array($rs)) {
					$rtn = $rsField['username'];
				}
			}
		}
		
		return $rtn;
	}

	function rtnProjectDetailData($pDataField, $pGameNo, $pTurn) {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
		mysql_select_db(DB_NAME, $conn) or die();

		$strSQL = "SELECT $pDataField
			FROM tbgame A 
				INNER JOIN tbproject B ON A.projectid=B.projectid 
				INNER JOIN tbprojectdetail C ON B.projectid=C.projectid
			WHERE gameno='$pGameNo' AND turn='$pTurn'";
		$rs = mysql_query($strSQL,$conn);
		
		$rtn = "";
		if ($rs) {
			if (mysql_num_rows($rs)>0) {
				if ($rsField = mysql_fetch_array($rs)) {
					$rtn = $rsField[$pDataField];
				}
			}
		}
		
		return $rtn;
	}

	function rtnProjectData($pDataField, $pGameNo) {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
		mysql_select_db(DB_NAME, $conn) or die();

		$strSQL = "SELECT $pDataField
			FROM tbgame A INNER JOIN tbproject B ON A.projectid=B.projectid 
			WHERE gameno='$pGameNo'";
		$rs = mysql_query($strSQL,$conn);
		
		if ($pDataField=="orderreceived") $rtn = "0";
		else if ($pDataField=="startinginventory") $rtn = "0";
		else if ($pDataField=="turns") $rtn = "0";
		else $rtn = "";
			
		if ($rs) {
			if (mysql_num_rows($rs)>0) {
				if ($rsField = mysql_fetch_array($rs)) {
					$rtn = $rsField[$pDataField];
				}
			}
		}
		
		return $rtn;
	}

	function rtnGameNo() {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
		mysql_select_db(DB_NAME, $conn) or die();

		$strSQL = "SELECT gameno
			FROM tbgame 
			WHERE ischosen='Y'";
		$rs = mysql_query($strSQL,$conn);
		
		$rtn = "";
		if ($rs) {
			if (mysql_num_rows($rs)>0) {
				if ($rsField = mysql_fetch_array($rs)) {
					$rtn = $rsField['gameno'];
				}
			}
		}
		
		return $rtn;
	}

	function rtnTeamName($pTeamID) {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
		mysql_select_db(DB_NAME, $conn) or die();

		$strSQL = "SELECT teamname
			FROM tbteam 
			WHERE teamid='" . $pTeamID . "'";
		$rs = mysql_query($strSQL,$conn);
		
		$rtn = "";
		if ($rs) {
			if (mysql_num_rows($rs)>0) {
				if ($rsField = mysql_fetch_array($rs)) {
					$rtn = $rsField['teamname'];
				}
			}
		}
		
		return $rtn;
	}

	function rtnPlayerIDName($pPlayerID) {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
		mysql_select_db(DB_NAME, $conn) or die();

		$strSQL = "SELECT playerid, playername
			FROM tbplayer 
			WHERE playerid='" . $pPlayerID . "'";
		$rs = mysql_query($strSQL,$conn);
		
		$rtn = "";
		if ($rs) {
			if (mysql_num_rows($rs)>0) {
				if ($rsField = mysql_fetch_array($rs)) {
					$rtn = $rsField['playerid'] . ": " . $rsField['playername'];
				}
			}
		}
		
		return $rtn;
	}
?>