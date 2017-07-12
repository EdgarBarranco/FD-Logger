 <?php
//	$dbhandle = sqlite_open('event.db', 0666, $error);
//	$query = sqlite_query($dbhandle, "SELECT * from Event");

	include_once("conf.php");
	try 
	{
		$db = new PDO('sqlite:'.$db_name);
		$sql = "SELECT * FROM $event_table";
		$stmt = $db->prepare($sql);
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}

	$stats = array();
	$total=0;
		
	while ($val = $stmt->fetchall(PDO::FETCH_ASSOC)) {
		foreach($val as $row){
			if (!array_key_exists($row['call'], $stats)){
				$stats[$row['call']]['points']=0;
			}
			$total += $row['points'];
			$stats[$row['call']]['points'] += $row['points'];
		}
		$rows = array();
			foreach ($stats as $i => $value){
				$r[0] = $i;
				$val = (($value['points'] / $total) * 100.00);
				$r[1] = $val;
			array_push($rows,$r);
		}
	}
	
	print json_encode($rows);
?>