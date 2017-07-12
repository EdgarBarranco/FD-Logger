<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once("../conf.php");
$tables = array($event_table,$deleted_table);
$date = strftime( "%D %r", time());
$db_name = "../".$db_name;

try 
{
	$db = new PDO('sqlite:'.$db_name);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	foreach ($tables as $table)
		$db->exec("CREATE TABLE IF NOT EXISTS $table (id INTEGER PRIMARY KEY AUTOINCREMENT, call TEXT NOT NULL, contact TEXT NOT NULL, band TEXT NOT NULL, mode TEXT NOT NULL, class TEXT NOT NULL, section TEXT NOT NULL, comment LONGTEXT NOT NULL, gota INTEGER NOT NULL, gota_mentor INTEGER NOT NULL,  points INTEGER NOT NULL, time DATETIME)");
}
catch(PDOException $e){
		$output = json_encode(array("error" => $e->getMessage()));
}

$jb = json_decode(file_get_contents('php://input'),true);