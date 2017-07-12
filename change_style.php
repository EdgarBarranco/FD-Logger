<?php
	if (isset($_COOKIE["selectedStyle"])) // has the cookie already been set
	{
		$style=$_COOKIE["selectedStyle"];
	}
	else
	{
		$style = "style-day";
	}
	  
	if (isset($_GET["style"]))  // changing the style
	{
		$style=$_GET["style"];
	}
	$expire=time()+60*60*24*30;
	setcookie("selectedStyle",$style,$expire); // update or create the cookie
	
	echo '
<html>
  <head>
    <title>Change Style</title>
    <META http-equiv="refresh" content="0;URL=./">
  </head>
  <body bgcolor="#ffffff">
    <center>If this page does not load automatically. Please <a href="./">Click Here</a>.
    </center>
  </body>
</html>

	';

?>