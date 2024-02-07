<?php
include "../kapcsolat.php";

$sql = "SELECT user_id, CONCAT(first_name, ' ', last_name, ' (', user_id, ')') AS name FROM students";
$result = $db->query($sql);

$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}


header('Content-Type: application/json');
echo json_encode($students);
?>
