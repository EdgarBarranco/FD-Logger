<?php
include_once("api_helper.php");

try 
{
	$sql = "SELECT * FROM $event_table";
	$stmt = $db->prepare($sql);

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