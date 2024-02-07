<?php
include "../kapcsolat.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    
    $sql = "SELECT sum(distance) as total_distance from trip_summary where user_id='$user_id'";
	$result = $db->query($sql);
	$sumdist = array();
	if ($result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
		  $sumdist[] = $row;
	  }
	} 

    echo json_encode($sumdist);
}
?>