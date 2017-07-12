<?php
include_once("api_helper.php");

try 
{
	$sql = "UPDATE $event_table SET call=:Call, contact=:Contact, band=:Band, mode=:Mode, class=:Class, section=:Section, comment=:Comment, gota=:Gota, gota_mentor=:Gota_Mentor, points=:Points, time=:Time WHERE id=:ID";
	$stmt = $db->prepare($sql);
	
	$stmt->bindParam(':ID',$jb['edit'],PDO::PARAM_INT);
	$stmt->bindParam(':Call',strtoupper($jb['call']),PDO::PARAM_STR);
	$stmt->bindParam(':Contact',strtoupper($jb['contact']),PDO::PARAM_STR);
	$stmt->bindParam(':Band',strtoupper($jb['band']),PDO::PARAM_STR);
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
	
	$output = json_encode(array("edited" => $jb['edit']));
}
catch(PDOException $e)
{
	$output = json_encode(array("error" => $e->getMessage()));
}

$db = null;
echo ($output);
?>