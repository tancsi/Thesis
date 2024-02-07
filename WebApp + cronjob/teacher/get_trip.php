<?php
include "../kapcsolat.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
	$trip_num =$_POST['trip_num'];
    
    $sql = "SELECT *FROM obd_data where user_id='$user_id' and trip_num='$trip_num'";
	$result = $db->query($sql);

	if ($result->num_rows > 0) {
		$routes = array();
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
		$temp_array=array(
			'user_id' => $row["user_id"],
			'teacher_id' => $row["teacher_id"],
			'time' => $row["time"],
			'latitude' => $row["latitude"],
			'longitude' => $row["longitude"],
			'altitude' => $row["altitude"],
			'engine_load' => $row["engine_load"],
			'engine_rpm' => $row["engine_rpm"],
			'speed' => $row["speed"],
			'trip_num' => $row["trip_num"],
		);
		array_push($routes,$temp_array);
	  }
	} 

    echo json_encode($routes);
}
?>