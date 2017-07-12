		<script type="text/javascript">
			$(document).ready(function() {
				$("#Contacts").tablesorter({widgets: ["zebra"]});
			});
		</script>
<?php
		include_once("conf.php");
		if(isset($_GET['limit'])){
			$limit = $_GET['limit'];
		}
		if(isset($_GET['can_delete'])){
			$can_delete = $_GET['can_delete'];
		}

		$file = fopen("res/sections.txt", "r");
		$sections = array();
		$sec = array();
		while (!feof($file)) {
			$sec = explode("|",fgets($file));
			$sections[$sec[0]]=  trim($sec[1]);
		}
		fclose($file);
		
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
		
		echo "			<b><center>\n			Total Points: $total_points &nbsp;|&nbsp; Total Contacts: $total_contacts &nbsp;|&nbsp; Total Phone Contacts: $total_phone &nbsp;|&nbsp; Total Digital Contacts: $total_digital &nbsp;|&nbsp; Total CW Contacts: $total_cw \n";
		if (($win_c != NULL) && ($win_p != NULL))
			echo "		<br />Most Contacts (".$stats_contacts[$win_c[0]]."):<img src=\"./img/m_c.png\"> &nbsp; &nbsp;|&nbsp; &nbsp; Most Points (".$stats_points[$win_p[0]]."):<img src=\"./img/m_p.png\">";
		echo "			</center></b><hr>";
?>

		<table id="Contacts" cellspacing="0" width="60%" class="tablesorter" >
			<thead>
			  <tr>
				<th width="2%"># </th>
				<th width="6%">Contact by </th>
				<th width="6%">Contacted </th>
				<th width="10%">Date and Time (EST)</th>
				<th width="4%">Band </th>
				<th width="4%">Mode </th>
				<th width="4%">Class </th>
				<th width="12%">Section </th>
				<th width="12%">Comment </th>
				<th width="1%">GOTA </th>
				<th width="2%">GOTA Mentor </th>
				<th width="4%">Points </th>
				<?php if (isset($can_delete) && $can_delete != 0) echo '<th width="4%">Delete </th>'; ?>	
			  </tr>
			</thead>
			<tfoot>
			  <tr>
				<th width="2%"># </th>
				<th width="6%">Contact by </th>
				<th width="6%">Contacted </th>
				<th width="10%">Date and Time (EST)</th>
				<th width="4%">Band </th>
				<th width="4%">Mode </th>
				<th width="4%">Class </th>
				<th width="12%">Section </th>
				<th width="12%">Comment </th>
				<th width="1%">GOTA </th>
				<th width="2%">GOTA Mentor </th>
				<th width="4%">Points </th>
				<?php if (isset($can_delete) && $can_delete != 0) echo '<th width="4%">Delete </th>'; ?>			
			  </tr>
			</tfoot>
	<?php

	$db= null;
	try 
	{
		$db = new PDO('sqlite:'.$db_name);
		if(!isset($_GET['limit']))
			$sql = "SELECT * FROM $event_table";
		else
		{
			$sql = "SELECT * from Event ORDER BY id DESC limit $limit";
			echo "<center><h2>Latest $limit contacts</h2></center>";
		}
		$stmt = $db->prepare($sql);
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}


	while ($val = $stmt->fetchall(PDO::FETCH_ASSOC)) 
		 foreach($val as $row){
	?>
		<tr>
				<?php
					if(isset($_GET['limit']))
						echo "<td>".$total_contacts--."</td>";
					else
						echo "<td>".$count++."</td>";
				?>
				
				<td><?php 
					if ($row['call'] == $win_c[0]) 
						echo '
				 <img src="./img/m_c.png" title="Contats: '.$stats_contacts[$win_c[0]].'">'; 
					if ($row['call']== $win_p[0])
						echo '
				 <img src="./img/m_p.png" title="Points: '.$stats_points[$win_p[0]].'">';
					echo "\n				 ".$row['call']; 
				?>
				
				</td>
				<td><?php echo $row['contact']; ?></td>
				<td><?php echo $row['time']; ?></td>
				<td><?php echo $row['band']; ?></td>
				<td><?php echo $row['mode']; ?></td>
				<td><?php echo $row['class']; ?></td>
				<td><?php echo $row['section'] ." - ". array_search($row['section'],$sections); ?></td>
				<td><?php echo stripslashes ($row['comment']); ?></td>
				<td><?php echo $row['gota']; ?></td>
				<td><?php echo $row['gota_mentor']; ?></td>
				<td><?php echo $row['points']; ?></td>
				<?php if (isset($can_delete) && $can_delete != 0) echo '<td>
				 <form action="index.php" method="post" onSubmit="return window.confirm(\'Are you sure?\');">
				 <input type="hidden" name="delete" value="'.$row['id'].'">
				 <input type="submit" value="Delete" style="font-size: 85%; ">
				 </form>
				</td>' ?>		
			</tr>
	 <?php
	 }
	 $db=null;
	 ?>
	</table>
