<?php 
	session_start();

	include("config.php");
	$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die();
	mysql_select_db(DB_NAME, $conn) or die();

	$data = array(); $sumcost1=0;
	$strSQL = "SELECT username, cost1, backlog, playerid, gameplayround
		FROM tbgameplay
		WHERE teamid='" . $_SESSION['_teamid'] . "' AND gameno='" . $_SESSION['_gameno'] . "' AND gameplayround='" . rtnProjectData("turns", $_SESSION['_gameno']) . "'";
	$strSQL .= " ORDER BY playerid"; 
	$rs = mysql_query($strSQL,$conn);
	if ($rs) {
		if (mysql_num_rows($rs)>0) {
			while ($rsField = mysql_fetch_array($rs)) {
				$gameplayround = $rsField['gameplayround'];
				$backlog = $rsField['backlog'];

				if ($rsField['playerid']<=3) {
					$round_orderreceived = rtnGamePlayPreviousData("round_orderplaced", $gameplayround, $_SESSION['_gameno'], $_SESSION['_teamid'], $rsField['playerid']+1);
				} else {
					$round_orderreceived = 0;
				}
		
				$round_inventory = rtnGamePlayPreviousData("round_inventory", $gameplayround, $_SESSION['_gameno'], $_SESSION['_teamid'], $rsField['playerid']) + rtnGamePlayPreviousData("round_inprogress", $gameplayround, $_SESSION['_gameno'], $_SESSION['_teamid'], $rsField['playerid']);
			
				if ($round_inventory >= $round_orderreceived + $backlog) {
					$round_inventory = $round_inventory - $round_orderreceived - $backlog;
					$backlog = 0;
				} else {
					$backlog = $backlog + $round_orderreceived - $round_inventory;
					$round_inventory = 0;
				}
				$inventory = $round_inventory;

				$cost2 = ($inventory * rtnProjectData("inventorycost", $_SESSION['_gameno'])) + 
					($backlog * rtnProjectData("backlogcost", $_SESSION['_gameno']));
				$cost1 = $cost2 + $rsField['cost1'];

				$data[] = array($rsField['username'], $cost1);
				$sumcost1 = $sumcost1 + $cost1;
			}
		}
	}

	require_once 'phplot-6.2.0/phplot.php';

	$plot = new PHPlot(1000,400);
	$plot->SetImageBorderType('plain');
	$plot->SetPlotType('bars');
	$plot->SetDataType('text-data');
	$plot->SetDataValues($data);

	$plot->SetDataColors('green');

//	$plot->SetXTickLabelPos('none');
//	$plot->SetXTickPos('none');
//	$plot->SetYTickLabelPos('none');
//	$plot->SetYTickPos('none');

	$plot->SetYLabelType('data');
	$plot->SetYDataLabelPos('plotin');
	$plot->SetPrecisionY(0);

	$plot->SetTitle('Supply Chain Costs: $' . $sumcost1);
	$plot->SetYTitle('Cost per player');

	$plot->DrawGraph();
?>