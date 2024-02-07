 <?php
include "../kapcsolat.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    
    $sql = "SELECT trip_num, MIN(time) as first_date,user_id, t.first_name, t.last_name,t.username FROM obd_data inner join teacher t on obd_data.teacher_id=t.teacher_id WHERE user_id = ? GROUP BY trip_num";
	$stmt = $db->prepare($sql);
    $stmt->bind_param('s', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $routes = [];
    while ($row = $result->fetch_assoc()) {
        $formattedDate = date('Y-m-d', strtotime($row['first_date']));
        $row['first_date'] = $formattedDate;
        $routes[] = $row;
    }

    echo json_encode($routes);
}
?>