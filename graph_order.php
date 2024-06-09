<?php 
	$player = explode("|", $_GET['player']);
	session_start();

	include("config.php");
	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
	mysql_select_db(DB_NAME, $conn) or die();

	$data = array(); $data_player = array();
	$strSQL = "SELECT gameplayround
		FROM tbgameplay
		WHERE teamid='" . $_SESSION['_teamid'] . "' AND gameno='" . $_SESSION['_gameno'] . "' AND gameplayround>0
		GROUP BY gameplayround";
	$strSQL .= " ORDER BY gameplayround"; 
	$rs = mysql_query($strSQL,$conn);
	if ($rs) {
		if (mysql_num_rows($rs)>0) {
			while ($rsField = mysql_fetch_array($rs)) {
				for ($i=0; $i<count($player); $i++) {
					$data_player[$i] = 0;
					
					$strSQL = "SELECT round_orderplaced 
						FROM tbgameplay
						WHERE teamid='" . $_SESSION['_teamid'] . "' AND gameno='" . $_SESSION['_gameno'] . "' AND gameplayround='" . $rsField['gameplayround'] . "' AND playerid='" . $player[$i] . "'";
					$rs2 = mysql_query($strSQL,$conn);
					if ($rs2) {
						if (mysql_num_rows($rs2)>0) {
							if ($rs2Field = mysql_fetch_array($rs2)) {
								$data_player[$i] = $rs2Field['round_orderplaced'];
							}
						}
					}
				} 
				if (count($player)==4)
					$data[] = array($rsField['gameplayround'], $data_player[0], $data_player[1], $data_player[2], $data_player[3]);
				else if (count($player)==3)
					$data[] = array($rsField['gameplayround'], $data_player[0], $data_player[1], $data_player[2]);
				else if (count($player)==2)
					$data[] = array($rsField['gameplayround'], $data_player[0], $data_player[1]);
				else if (count($player)==1)
					$data[] = array($rsField['gameplayround'], $data_player[0]);
			}
		}
	}

	require_once 'phplot-6.2.0/phplot.php';

	$plot = new PHPlot(1000,400);
	$plot->SetImageBorderType('plain');
	$plot->SetPlotType('linepoints');
	$plot->SetDataType('text-data');
	$plot->SetDataValues($data);

//	$plot->SetXTickLabelPos('none');
//	$plot->SetXTickPos('none');
//	$plot->SetYTickLabelPos('none');
//	$plot->SetYTickPos('none');

	$plot->SetYLabelType('data');
	$plot->SetYDataLabelPos('plotin');
	$plot->SetPrecisionY(0);

	$plot->SetTitle('Orders');
	$plot->SetXTitle('Turn');
	$plot->SetYTitle('Order volume placed');

	if (count($player)==4)
		$plot->SetLegend(array(
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[0]),
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[1]),
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[2]),
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[3])));
	else if (count($player)==3)
		$plot->SetLegend(array(
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[0]),
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[1]),
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[2])));
	else if (count($player)==2)
		$plot->SetLegend(array(
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[0]),
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[1])));
	else if (count($player)==1)
		$plot->SetLegend(array(
			rtnGamePlayPreviousData("username", 0, $_SESSION['_gameno'], $_SESSION['_teamid'], $player[0])));
	
	$plot->DrawGraph();
?>