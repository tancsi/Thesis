 <?php
include "../kapcsolat.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
	$trip_num = $_POST['trip_num'];
    
    $sql = "SELECT highway_distance from trip_summary where user_id='$user_id' and trip_num=$trip_num";
	$result = $db->query($sql);
	$distance = array();
	if ($result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
		  $distance[] = $row;
	  }
	} 

    echo json_encode($distance);
}
?>