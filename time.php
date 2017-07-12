<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Set Time</title>
  <?php
  include_once("conf.php");
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
  <script src="./js/modernizr-latest.js"></script>
</head>
<body>
 <center>
<?php
 if(!empty($_POST["fdate"]) && !empty($_POST["ftime"]))
{
 $sdate = "sudo date +%Y-%m-%d -s ". $_POST["fdate"];
 $stime = "sudo date +%T -s ". $_POST["ftime"];
 shell_exec($sdate);
 shell_exec($stime);
 shell_exec("sudo hwclock -w");
 echo  '<META http-equiv="refresh" content="0;URL=./">';
}

if (!empty($_POST["poweroff"]))
{
	if ($_POST["poweroff"] == "off")
	{
		echo "<center>System is going down!</center>";
		$poweroff = "sudo poweroff";
		shell_exec($poweroff);
	}
}


 echo "  Current Server Time: " . date("n/d/Y h:i:s A").".\n";
?>
<script language="javascript">

d = new Date();
document.write("<br>Current Browser Time: " + d.toLocaleString() + ".<br/>");
</script>
</body></html>
<hr>
 <h3>Set time</h3>
  <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
   <br />Set Date: <input id="MYdate" name="fdate" type=date min=2016-01-01>
	<script>
		if (!Modernizr.inputtypes.date) {
			document.write("Format: yyyy-mm-dd Ex: For June 27, 2015 enter: 2015-06-27");
		}
	</script>
   <br />Set Time <input id="MYtime" name="ftime" type="time" >
	<script>

		if (!Modernizr.inputtypes.time) {
			document.write("Format: HH:MM Ex: For 2:15 PM enter: 14:15");
		}
	</script>
	<script>
		d = new Date();
		if ((1 + d.getMonth()) <10)
			month  = "0"+(1 + d.getMonth());
		else 
			month = (1 + d.getMonth())

		if (d.getDate() < 10)
			day = "0"+ d.getDate();
		else 
			day = d.getDate();
		da = (d.getFullYear()+ "-" + month + "-" + day);
		document.getElementById('MYdate').value = da;
		if (d.getHours() < 10)
			hours = ("0"+d.getHours())
		else
			hours = d.getHours();

		if (d.getMinutes() < 10)
			minutes= "0" + d.getMinutes();
		else
			minutes= d.getMinutes();
			
		ti = (hours + ":" + minutes)

		document.getElementById('MYtime').value = ti ;
	</script>
   <br />
   <input type="submit" name="submit" value="Set Time">
  </form>
  <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
   <button type="submit" name="poweroff" value="off" onclick="return confirm('Are you sure?');" style="position: fixed; bottom: 0; right: 0;"><img src="img/power-button.png" alt="Turn off the system!" height="50" width="50" ></button>
  </form>
 </center>
</body>
</html>
