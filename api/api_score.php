<?php
include_once("api_helper.php");

try 
{
	$sql = "SELECT * FROM $event_table";
	$stmt = $db->prepare($sql);

	$stmt->execute();
	
	$total_cw = 0;
	$total_digital = 0;
	$total_phone = 0;
	$total_points = 0;
	$total_contacts = 0;
	$stats_contacts = array();
	$stats_points = array();
	
	while ($val = $stmt->fetchall(PDO::FETCH_ASSOC)) {
		 foreach($val as $row){
			if (!array_key_exists($row['call'], $stats_contacts)){
				$stats_contacts[$row['call']]=0;
			}
			if (!array_key_exists($row['call'], $stats_points)){
				$stats_points[$row['call']]=0;
			}
			
			$stats_contacts[$row['call']]+= 1;
			$stats_points[$row['call']]+=$row['points'];
			
			$total_contacts++;
			$total_points += $row['points'];
			if ($row['mode']=="CW")
				$total_cw++;
			else if (($row['mode']=="DIGITAL"))
				$total_digital++;
			else
				$total_phone++;
		 }
		}
		$count = 1;
		
		arsort($stats_contacts);
		arsort($stats_points);
	
		$win_c = array_keys($stats_contacts);
		$win_p = array_keys($stats_points);
		
		$arr = array('TP' => $total_points, 'TC' => $total_contacts, 'T_cw' => $total_cw, 'T_di' => $total_digital, 'T_ph' => $total_phone,
	  'M_CC' => $win_c[0],'M_CP' => $stats_contacts[$win_c[0]], 'M_PC' => $win_p[0], 'M_PP' => $stats_points[$win_p[0]]);

	$output = json_encode($arr);
}

catch(PDOException $e)
{

	$output = json_encode(array("error" => $e->getMessage()));

}

$db = null;
echo ($output);
?>