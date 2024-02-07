<?php
include "../kapcsolat.php";

if(isset($_GET['teacher_id'])) {
    $teacherId = $_GET['teacher_id'];
    $sql = "SELECT s.user_id, CONCAT(s.first_name, ' ', s.last_name, ' (', s.user_id, ')') AS name
FROM students s
INNER JOIN teacher_student_con tsc ON s.user_id = tsc.user_id
WHERE tsc.teacher_id = '$teacherId'";

    $result = $db->query($sql);

    $students = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($students);
} else {
    echo json_encode(["error" => "Teacher ID not provided"]);
}
?>