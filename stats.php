<!DOCTYPE html>
<html>
	<head>
		<title>Field Day Log - Statistics</title>
		<META HTTP-EQUIV=Refresh CONTENT='60; URL=stats.php'>
<?php
	if (isset($_COOKIE["selectedStyle"])) // has the cookie already been set
	{
		$style=$_COOKIE["selectedStyle"];
	}
	else
	{
		$style = "style-day";
	}

	echo '		<link rel="stylesheet" href="css/'.$style.'.css" type="text/css"  />';

?>

		<script type="text/javascript" src="js/jquery-1.12.3.min.js"></script>
		<script type="text/javascript" src="js/jquery.tablesorter/jquery.tablesorter.min.js"></script> 
		<script type="text/javascript">
			$(document).ready(function() { $("#table").tablesorter({widgets: ["zebra"]});});

			$(document).ready(function() {
			var options = {
				chart: {
					renderTo: 'container',
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Points Distribution by Call'
				},
				tooltip: {
					formatter: function() {
						return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
					}
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							color: '#000000',
							connectorColor: '#000000',
							formatter: function() {
								return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
							}
						}
					}
				},
				series: [{
					type: 'pie',
					name: 'Call Contacts',
					data: []
				}]
			}

			$.getJSON("graph.php", function(json) {
				options.series[0].data = json;
				chart = new Highcharts.Chart(options);
			});

			});
		</script>
		<script src="js/highcharts.js"></script>
		<script src="js/exporting.js"></script>
	</head>
	<body>
<?php
	include_once("conf.php");

	try 
	{
		$db = new PDO('sqlite:'.$db_name);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		foreach ($tables as $table)
			$db->exec("CREATE TABLE IF NOT EXISTS $table (id INTEGER PRIMARY KEY, call TEXT NOT NULL, contact TEXT NOT NULL, band TEXT NOT NULL, mode TEXT NOT NULL, class TEXT NOT NULL, section TEXT NOT NULL, comment LONGTEXT NOT NULL, gota INTEGER NOT NULL, gota_mentor INTEGER NOT NULL,  points INTEGER NOT NULL, time DATETIME)");
		$sql = "SELECT * FROM $event_table";
		$stmt = $db->prepare($sql);
		$stmt->execute();
	}

	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
	
	//$dbhandle = sqlite_open('event.db', 0666, $error);
	//$query = sqlite_query($dbhandle, "SELECT * from Event");
	
	
	
	function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
		$array=$ret;
	}


	$stats = array();
	$total_cw = 0;
	$total_digital = 0;
	$total_phone = 0;
	$total_points = 0;
	$total_contacts = 0;
	$total_gota = 0;
	$total_gota_mentor = 0;

	$total_points_band["160m"]['order']=1;
	$total_points_band["80m"]['order']=2;
	$total_points_band["40m"]['order']=3;
	$total_points_band["20m"]['order']=4;
	$total_points_band["15m"]['order']=5;
	$total_points_band["10m"]['order']=6;
	$total_points_band["6m"]['order']=7;
	$total_points_band["2m"]['order']=8;
	$total_points_band["70cm"]['order']=9;

	$total_contacts_band["160m"]['order']=1;
	$total_contacts_band["80m"]['order']=2;
	$total_contacts_band["40m"]['order']=3;
	$total_contacts_band["20m"]['order']=4;
	$total_contacts_band["15m"]['order']=5;
	$total_contacts_band["10m"]['order']=6;
	$total_contacts_band["6m"]['order']=7;
	$total_contacts_band["2m"]['order']=8;
	$total_contacts_band["70cm"]['order']=9;

	$total_points_band["160m"]['points']=0;
	$total_points_band["80m"]['points']=0;
	$total_points_band["40m"]['points']=0;
	$total_points_band["20m"]['points']=0;
	$total_points_band["15m"]['points']=0;
	$total_points_band["10m"]['points']=0;
	$total_points_band["6m"]['points']=0;
	$total_points_band["2m"]['points']=0;
	$total_points_band["70cm"]['points']=0;

	$total_contacts_band["160m"]['contacts']=0;
	$total_contacts_band["80m"]['contacts']=0;
	$total_contacts_band["40m"]['contacts']=0;
	$total_contacts_band["20m"]['contacts']=0;
	$total_contacts_band["15m"]['contacts']=0;
	$total_contacts_band["10m"]['contacts']=0;
	$total_contacts_band["6m"]['contacts']=0;
	$total_contacts_band["2m"]['contacts']=0;
	$total_contacts_band["70cm"]['contacts']=0;
	
	while ($val = $stmt->fetchall(PDO::FETCH_ASSOC))
	foreach($val as $row){ 
	//	echo"<pre>";print_r($row);echo"</pre>";
		if (!array_key_exists($row['call'], $stats)){
			$stats[$row['call']]['points']=0;
			$stats[$row['call']]['contacts']=0;
			$stats[$row['call']]['phone']=0;
			$stats[$row['call']]['digital']=0;
			$stats[$row['call']]['cw']=0;
		}
		$total_contacts++;
		$total_gota += $row['gota'];
		$total_gota_mentor += $row['gota_mentor'];
		
		$total_points += $row['points'];
		if ($row['mode']=="CW")
			$total_cw++;
		else if (($row['mode']=="DIGITAL"))
			$total_digital++;
		else
			$total_phone++;
			
		$stats[$row['call']]['points'] += $row['points'];
		$stats[$row['call']]['contacts'] += 1;
		if ($row['mode']=="PHONE")
			$stats[$row['call']]['phone'] += 1;
		else if ($row['mode']=="DIGITAL")
			$stats[$row['call']]['digital'] += 1;
		else if ($row['mode']=="CW")
			$stats[$row['call']]['cw'] += 1;

		$total_points_band[$row['band']]['points'] += $row['points'];
		$total_contacts_band[$row['band']]['contacts'] += 1;
	}
	$db=null;
?>
		<center>
			<h1>Statistics as of <?php echo strftime( "%D at %r", time()); ?></h1><br />
			<b>
<?php
	echo "				Total Points: $total_points | Total Contacts: $total_contacts | Total Phone Contacts: $total_phone | Total Digital Contacs: $total_digital | Total CW Contacts: $total_cw<br />Total GOTA Contacts: $total_gota | Total GOTA Mentor: $total_gota_mentor<br /><br />\n";
	echo "				Total Points per band:";
	aasort($total_points_band,"order");
	aasort($total_contacts_band,"order");
	foreach ($total_points_band as $i => $value){
		echo " | $i :  $value[points] |";
	}
	echo "<br />\n";
	echo "				Total Contacts per band: ";
	foreach ($total_contacts_band as $i => $value){
		echo " | $i :  $value[contacts] |";
	}
	echo "
			</b>
			<br />
			<hr>";
?>

			<table id="table" cellspacing="0" width="60%" class="tablesorter" >

				<thead>
				  <tr>
					<th width="20%"> Call </th>
					<th width="20%"> Points </th>
					<th width="20%"> Contacts </th>
					<th width="10%"> Phone </th>
					<th width="10%"> Digital </th>
					<th width="10%"> CW </th>
				  </tr>
				</thead>
<?php
	foreach ($stats as $i => $value){
		echo "					<tr>
						<td>$i</td>
						<td>$value[points]</td>
						<td>$value[contacts]</td>
						<td>$value[phone]</td>
						<td>$value[digital]</td>
						<td>$value[cw]</td>
					</tr>
		"; 
	}
?>

			</table>
			<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
		</center>
	</body>
</html>