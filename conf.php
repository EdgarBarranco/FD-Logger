<?php
date_default_timezone_set('America/New_York');
$multiplier = 2;

$db_name = 'event.db';
$event_table = 'Event';
$deleted_table = 'Deleted';
$tables = array($event_table,$deleted_table);
$backups = TRUE;