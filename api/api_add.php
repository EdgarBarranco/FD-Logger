<?php
include_once("api_helper.php");

try
{
	$sql = "INSERT INTO $event_table (call,contact,band,mode,class,section,comment,gota,gota_mentor,points,time)
		VALUES (:Call, :Contact, :Band, :Mode, :Class, :Section, :Comment, :Gota, :Gota_Mentor, :Points, :Time)";
	$stmt = $db->prepare($sql);

	$stmt->bindParam(':Call',strtoupper($jb['call']),PDO::PARAM_STR);
	$stmt->bindParam(':Contact',strtoupper($jb['contact']),PDO::PARAM_STR);
	$stmt->bindParam(':Band',strtolower($jb['band']),PDO::PARAM_STR);
	$stmt->bindParam(':Mode',strtoupper($jb['mode']),PDO::PARAM_STR);
	$stmt->bindParam(':Class',strtoupper($jb['class']),PDO::PARAM_STR);
	$stmt->bindParam(':Section',strtoupper($jb['section']),PDO::PARAM_STR);
	$comment = trim($jb['comment']);
	$comment = ucfirst(strtolower($comment));
	$comment = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$comment);
	$stmt->bindParam(':Comment',$comment,PDO::PARAM_STR);
	$stmt->bindParam(':Gota',$jb['gota'],PDO::PARAM_INT);
	$stmt->bindParam(':Gota_Mentor',$jb['gota_mentor'],PDO::PARAM_INT);
	$stmt->bindParam(':Points',$jb['points'],PDO::PARAM_INT);
	$stmt->bindParam(':Time',$date,PDO::PARAM_STR);
	$stmt->execute();

	$output = json_encode(array("added" => $db->lastInsertId()));
}
catch(PDOException $e)
{
	$output = json_encode(array("error" => $e->getMessage()));
}

$db = null;
echo ($output);
?>