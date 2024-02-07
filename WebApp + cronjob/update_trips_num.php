<?php
include "kapcsolat.php";

// SQL query to retrieve users
$sql = "Update students set total_trips=total_trips+1 where user_id='".$_GET['student_id']."'";
$result = $db->query($sql);



$db->close();
?>