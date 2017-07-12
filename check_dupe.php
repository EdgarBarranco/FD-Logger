<?php
  include_once("conf.php");
  $db = new PDO('sqlite:'.$db_name);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT contact FROM $event_table WHERE contact=:Contact AND mode=:Mode AND band=:Band AND gota=:Gota";
	$p_contact = strtoupper($_POST['contact']);
	$p_mode = strtoupper($_POST['mode']);
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':Contact',$p_contact,PDO::PARAM_STR);
	$stmt->bindParam(':Band',$_POST["band"],PDO::PARAM_STR);
	$stmt->bindParam(':Mode',$p_mode,PDO::PARAM_STR);
	$stmt->bindParam(':Gota',$_POST['gota'],PDO::PARAM_INT);
	$stmt->execute();

	if($stmt->fetch(PDO::FETCH_NUM))
	 echo json_encode(array("response" => 1));
	else
	 echo json_encode(array("response" => 0));
 
  $db=null;
?>
