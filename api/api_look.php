<?php
include_once("api_helper.php");

try {
	$sql = "SELECT * FROM $event_table WHERE id=:ID";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':ID',$jb['look'],PDO::PARAM_INT);
	$stmt->execute();
	$output = json_encode($stmt->fetch(PDO::FETCH_ASSOC),JSON_PRETTY_PRINT);

	if ($output === 'false')
		$output = json_encode(array());
}
catch(PDOException $e)
{
	$output = json_encode(array("error" => $e->getMessage()));
}

$db = null;
echo ($output);
?>