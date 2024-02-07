<?php

$db = new mysqli("mysql.nethely.hu","spx","spx@narasoft","spx");
$db->set_charset("utf8mb4");

if ($db->connect_error) {
	echo "Hiba oka: " . $db->connect_error;
	exit();
}

?>
