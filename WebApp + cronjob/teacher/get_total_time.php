<?php
include "../kapcsolat.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    
    $sql = "SELECT 
    user_id,
    SUM(trip_duration_minutes) AS total_duration_minutes
FROM (
    SELECT 
        user_id,
        trip_num,
        TIMESTAMPDIFF(MINUTE, MIN(time), MAX(time)) AS trip_duration_minutes
    FROM 
        obd_data
    WHERE 
        user_id = '$user_id'
    GROUP BY 
        user_id, trip_num
) AS subquery
GROUP BY 
    user_id;";
	$result = $db->query($sql);
	$sumtime = array();
	if ($result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
		  $sumtime[] = $row;
	  }
	} 

    echo json_encode($sumtime);
}
?>