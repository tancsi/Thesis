 <?php
include "../kapcsolat.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
	$trip_num = $_POST['trip_num'];
    
    $sql = "SELECT 
  MAX(speed) AS max_speed, 
  FLOOR(SUM(CASE WHEN speed > 0 THEN speed ELSE 0 END) / NULLIF(COUNT(CASE WHEN speed > 0 THEN 1 ELSE NULL END), 0)) AS avg_speed, 
  TIMESTAMPDIFF(MINUTE, MIN(time), MAX(time))  AS trip_duration_minutes 
FROM 
  obd_data 
WHERE 
  user_id = '$user_id' 
  AND trip_num = '$trip_num';";
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