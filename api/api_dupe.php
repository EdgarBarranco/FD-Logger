<?php
include_once("api_helper.php");

try 
{
	$sql = "SELECT contact FROM $event_table WHERE contact=:Contact AND mode=:Mode AND band=:Band AND gota=:Gota";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':Contact',strtoupper($jb['contact']),PDO::PARAM_STR);
	$stmt->bindParam(':Band',strtoupper($jb['band']),PDO::PARAM_STR);
	$stmt->bindParam(':Mode',strtoupper($jb['mode']),PDO::PARAM_STR);
	$stmt->bindParam(':Gota',$jb['gota'],PDO::PARAM_INT);
	$stmt->execute();
	
	if ($stmt->fetch(PDO::FETCH_NUM))
		$output = json_encode(array("response" => 1));
	else
		$output = json_encode(array("response" => 0));
}
catch(PDOException $e)
{
	$output = json_encode(array("error" => $e->getMessage()));
}

$db = null;
echo ($output);
?>