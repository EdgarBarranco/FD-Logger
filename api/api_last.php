<?php
include_once("api_helper.php");

try 
{
	$sql = "SELECT * FROM $event_table ORDER BY id DESC limit :Limit";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':Limit',$jb['last'],PDO::PARAM_INT);
	
	$stmt->execute();
	$output = json_encode($stmt->fetchall(PDO::FETCH_ASSOC));
}
catch(PDOException $e)
{
	$output = json_encode(array("error" => $e->getMessage()));
}

$db = null;
echo ($output);
?>