<?php
include_once("api_helper.php");

try
{
	$sql = "INSERT INTO $deleted_table SELECT NULL, call, contact, band, mode, class, section, comment, gota, gota_mentor, points, time FROM $event_table WHERE id=:ID";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':ID',$jb['delete'],PDO::PARAM_INT);
	$stmt->execute();

	$sql = "DELETE FROM $event_table WHERE id=:ID";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':ID',$jb['delete'],PDO::PARAM_INT);
	$stmt->execute();

	$output = json_encode(array("deleted" => $jb['delete']));
}
catch(PDOException $e)
{
	$output = json_encode(array("error" => $e->getMessage()));
}

$db = null;
echo ($output);
?>