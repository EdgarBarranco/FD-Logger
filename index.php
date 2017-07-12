<!DOCTYPE html>
<html>
	<head>
		 <meta charset="utf-8"> 
		 <title>Field Day Log</title>
<?php
	include_once ("conf.php");
	if ($backups) include_once ("backup.php");
	if (isset($_COOKIE["selectedStyle"])) // has the cookie already been set
	{
		$style=$_COOKIE["selectedStyle"];
	}
	else
	{
		$style = "style-day";
	}
	echo '	 	 <link rel="stylesheet" href="css/'.$style.'.css" type="text/css"  />';
?>

		 <link rel="stylesheet" href="js/jquery-ui.min.css" type="text/css"  />
		 <script type="text/javascript" src="js/jquery-1.12.3.min.js"></script>
		 <script type="text/javascript" src="js/jquery-ui.min.js"></script>
		 <script type="text/javascript" src="js/jquery.form.min.js"></script>
		 <script type="text/javascript" src="js/helper.js"></script>
		 <script type="text/javascript" src="js/comm.js"></script>
		 <script type="text/javascript" src="js/jquery.tablesorter/jquery.tablesorter.min.js"></script>
		 <script type="text/javascript">
			$(function() {
				var availableTags = [<?php
					$file = "res/sct.txt";
					$f=fopen($file,"r");
					while (!feof($f)) {
						echo '"'.trim(fgets($f)).'",';
					}
					fclose($f);
				?>];
				$( "#section" ).autocomplete({
						source: availableTags,
						messages: {
							noResults: '',
							results: function() {}
						}
				});
			});
		 </script>
		 <script type="text/javascript">

			var currenttime = '<?php print date("F d, Y H:i:s", time())?>' //PHP method of getting server date

			///////////Stop editting here/////////////////////////////////

			var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
			var serverdate=new Date(currenttime)

			function padlength(what){
			  var output=(what.toString().length==1)? "0"+what : what
			  return output
			}

			function displaytime(){
			  serverdate.setSeconds(serverdate.getSeconds()+1)
			  var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
			  var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
			  document.getElementById("servertime").innerHTML="Server time: " + datestring+" "+timestring + ' <a href="./time.php" style="font-size:10px;">Wrong time?</a><hr>'
			}

			window.onload=function(){
			  setInterval("displaytime()", 1000)
			}
		 </script>
	</head>
<?php 
		flush(); 
?>

	<body id="body">
		<center>
			<div id="servertime" style="font-size:10px;"></div>
<?php
 if( (empty($_POST) || isset($_POST['delete']) ) )
 {
 ?>
			<form id="myForm" method="post" onsubmit="return storeValues(this);">
				<div>
					<label>Logger call:</label>
					<input id="call" type="input" name="call" maxlength="10" size="15" value="" />
					<label>Band:</label>
						<select name="band" id="band" size="1" >
						<option value="160m" >160m</option>
						<option value="80m" >80m</option>
						<option value="40m" >40m</option>
						<option value="20m" >20m</option>
						<option value="15m" >15m</option>
						<option value="10m" >10m</option>
						<option value="6m" >6m</option>
						<option value="2m" >2m</option>
						<option value="70cm" >70cm</option>
					</select>
					<label>Mode:</label>
					<select name="mode" id="mode" size="1" >
						<option value="PHONE" >PHONE</option>
						<option value="DIGITAL" >DIGITAL</option>
						<option value="CW" >CW</option>
					</select>
					<label>GOTA:</label>
					<input id="gota" type="checkbox" name="gota" value="1">
					<br /><hr>
					<label>Contact:</label>
					<input id="contact" type="input" name="contact" value="" maxlength="10" size="15" />
					<label>Class:</label>
					<input id="class" type="input" name="class" value="" maxlength="3" size="5"  />
					<label>Section:</label>
					<input id="section" type="text" name="section" maxlength="3" size="5"  />
					<label>Comment:</label>
					<input id="comment" type="input" name="comment" size="25" />
					<div id="mentor"><label>GOTA Mentor:</label><input id="gota_mentor" type="checkbox" name="gota_mentor" value="1"></div>
					<br /><br />
					<input class="a_button" type="submit" value="Log it!" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input class="a_button" type="button" onClick="formReset()" value="Clear" />
				</div>
			</form>
			<hr>
<?php
 }
?>
		</center>
		<div id="showdata">
<?php
  try 
  {
    $db = new PDO('sqlite:'.$db_name);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(!file_exists($db_name)){
      foreach ($tables as $table)
        $db->exec("CREATE TABLE IF NOT EXISTS $table (id INTEGER PRIMARY KEY AUTOINCREMENT, call TEXT NOT NULL, contact TEXT NOT NULL, band TEXT NOT NULL, mode TEXT NOT NULL, class TEXT NOT NULL, section TEXT NOT NULL, comment LONGTEXT NOT NULL, gota INTEGER NOT NULL, gota_mentor INTEGER NOT NULL,  points INTEGER NOT NULL, time DATETIME)");
    }
  }
  catch(PDOException $e)
  {
		echo $e->getMessage();
  }
  
  if(!empty($_POST) && isset($_POST) && !isset($_POST['delete'])){
		$call = trim(strtoupper($_POST['call']));
		$contact = trim(strtoupper($_POST['contact']));
		$band = trim($_POST['band']);
		$mode = trim($_POST['mode']);
		if (isset($_POST['gota']))
			$gota = trim($_POST['gota']);
		else 
			$gota = 0;
		$class = trim(strtoupper($_POST['class']));
		$section = trim(strtoupper($_POST['section']));
		$comment = trim($_POST['comment']);
		$comment = ucfirst(strtolower($comment));
		$comment = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$comment);
		if (isset($_POST['gota_mentor']))
			$gota_mentor = trim($_POST['gota_mentor']);
		else
			$gota_mentor = 0;

		if (($mode == "CW") || ($mode == "DIGITAL"))
			$points = $multiplier * 2;
		else
			$points = $multiplier;
		$date = strftime( "%D %r", time());

		if ($call && $contact && $band && $mode && $class && $section)
		{
			$sql = "SELECT contact FROM $event_table WHERE contact=:Contact AND mode=:Mode AND band=:Band AND gota=:Gota";
			$stmt = $db->prepare($sql);
			$contact = strtoupper($contact);
			$band = strtolower($band);
			$mode = strtoupper($mode);
			$stmt->bindParam(':Contact',$contact,PDO::PARAM_STR);
			$stmt->bindParam(':Band',$band,PDO::PARAM_STR);
			$stmt->bindParam(':Mode',$mode,PDO::PARAM_STR);
			$stmt->bindParam(':Gota',$gota,PDO::PARAM_INT);
			$stmt->execute();
       if ($stmt->fetch(PDO::FETCH_NUM))
       {
        print '<script type="text/javascript">alert("The contact: '. $contact .' on band: '.$band.' and mode: '.$mode. ' is a dupe!");</script>';  
       }
		 else 
		  {
		   	$sql = "INSERT INTO $event_table (call,contact,band,mode,class,section,comment,gota,gota_mentor,points,time)
                VALUES (:Call, :Contact, :Band, :Mode, :Class, :Section, :Comment, :Gota, :Gota_Mentor, :Points, :Time)";
        $stmt = $db->prepare($sql);
        $call = strtoupper($call);
        $contact = strtoupper($contact);
        $band = strtolower($band);
        $mode = strtoupper($mode);
        $class = strtoupper($class);
        $section = strtoupper($section);
		
        $stmt->bindParam(':Call',$call,PDO::PARAM_STR);
        $stmt->bindParam(':Contact',$contact,PDO::PARAM_STR);
        $stmt->bindParam(':Band',$band,PDO::PARAM_STR);
        $stmt->bindParam(':Mode',$mode,PDO::PARAM_STR);
        $stmt->bindParam(':Class',$class,PDO::PARAM_STR);
        $stmt->bindParam(':Section',$section,PDO::PARAM_STR);
        $comment = trim($comment);
        $comment = ucfirst(strtolower($comment));
        $comment = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$comment);
        $stmt->bindParam(':Comment',$comment,PDO::PARAM_STR);
        $stmt->bindParam(':Gota',$gota,PDO::PARAM_INT);
        $stmt->bindParam(':Gota_Mentor',$gota_mentor,PDO::PARAM_INT);
        $stmt->bindParam(':Points',$points,PDO::PARAM_INT);
        $stmt->bindParam(':Time',$date,PDO::PARAM_STR);
        $stmt->execute();
        
        if ($backups) backup("add");
			
        $f = fopen ('count.php','w');
        $sql = "SELECT * FROM $event_table";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $num_rows = count($stmt->fetchAll());
        $str = json_encode(array("response" => $num_rows));
        fputs($f, $str);
        fclose($f);
		  }
		}
		else
		{
			print '<script type="text/javascript">alert("There were some missing fileds!\n\n Required fields are:\n\n - Logger call\n - Contact\n - Class\n - Section");</script>';  
		}
	}

  if (isset($_POST['delete'])){
		if ($_POST['delete']){ 
			if ($backups) backup("del");
			$sql = "INSERT INTO $deleted_table SELECT NULL, call, contact, band, mode, class, section, comment, gota, gota_mentor, points, time FROM $event_table WHERE id=:ID";	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':ID',$_POST['delete'],PDO::PARAM_INT);
			$stmt->execute();

			$sql = "DELETE FROM $event_table WHERE id=:ID";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':ID',$_POST['delete'],PDO::PARAM_INT);
			$stmt->execute();
		
			$f = fopen ('count.php','w');
			$sql = "SELECT * FROM $event_table";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$num_rows = count($stmt->fetchAll());
			$str = json_encode(array("response" => $num_rows));
			fputs($f, $str);
			fclose($f);
		}
	}
	$db=NULL;
?>
			<div id="Table">
				Loading...
			</div>
			<hr> 
			<div id="footer" align="center">
			<?php
				if (isset($_COOKIE["selectedStyle"])) 
				{
					$style=$_COOKIE["selectedStyle"];
				}
				else 
				{
					$style = "style-day";
				}

				if (isset($_GET["style"]))  // changing the style
					$style=$_GET["style"];
				if ($style=="style-day")
				{
					 echo '		<a href="change_style.php?style=style-night">Night View</a>';
				}
				else 
				{
					 echo '		<a href="change_style.php?style=style-day">Day View</a>';
				}
			?>

					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="./all.php?can_delete=1" target="_blank">View all contacts</a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="./stats.php" target="_blank">View Statistics</a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="csv.php">Download database</a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="./docs/2017FD_packet.pdf" target="_blank">Field Day Rules</a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="./docs/cheat_sheet.pdf" target="_blank">Field Day Script</a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="./docs/hambands_color.pdf" target="_blank">Band Plan</a>
			</div>
		</div>
	</body>
</html>
