<?php
include "../kapcsolat.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = $_POST['teacher_id'];
    $sql = "SELECT user_id, first_name, last_name FROM students WHERE active=1 and user_id IN 
            (SELECT user_id FROM teacher_student_con WHERE teacher_id = ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $teacher_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
}
?>
