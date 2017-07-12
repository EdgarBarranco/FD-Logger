<?php
function backup($destination)
{
  include("conf.php");
  $tables = array($event_table,$deleted_table);
  $date = strftime( "%D %r", time());

  try 
  {
    $db = new PDO('sqlite:'.$db_name);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    foreach ($tables as $table)
      $db->exec("CREATE TABLE IF NOT EXISTS $table (id INTEGER PRIMARY KEY, call TEXT NOT NULL, contact TEXT NOT NULL, band TEXT NOT NULL, mode TEXT NOT NULL, class TEXT NOT NULL, section TEXT NOT NULL, comment LONGTEXT NOT NULL, gota INTEGER NOT NULL, gota_mentor INTEGER NOT NULL,  points INTEGER NOT NULL, time DATETIME)");
  }
  catch(PDOException $e)
  {
    echo $e->getMessage();
  }

  $sql = "SELECT * FROM $event_table";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $out = '';
  $all_contacts = $stmt->fetchall(PDO::FETCH_ASSOC);

  foreach ($all_contacts as $contact)
  {
    $first = TRUE;
    foreach ($contact as $field)
    {
      if ($first)
        $first = FALSE;
      else
        $out .='"'.trim(str_replace(array("\n", "\r"), ' ',$field)).'",';
    }
    $out = rtrim($out,",");
    $out .="\n";
  }	
	
  $file = "./bck/".$destination."/fd-".strftime( "%m-%d-%Y-%H%M%S", time()).".csv";
  $f = fopen ($file,'w');

  fputs($f, $out);
  fclose($f);
	
  $db = NULL;
 }
 ?>