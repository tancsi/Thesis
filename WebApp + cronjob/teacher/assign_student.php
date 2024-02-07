<?php
include "../kapcsolat.php";

if (isset($_POST['teacher_id'], $_POST['user_id'])) {
    $teacherId = $_POST['teacher_id'];
    $userId = $_POST['user_id'];

    $checkSql = "SELECT * FROM teacher_student_con WHERE teacher_id = ? AND user_id = ?";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->bind_param("ss", $teacherId, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(array("success" => false, "message" => "Assignment already exists."));
    } else {
        $insertSql = "INSERT INTO teacher_student_con (teacher_id, user_id) VALUES (?, ?)";
        $insertStmt = $db->prepare($insertSql);
        $insertStmt->bind_param("ss", $teacherId, $userId);

        if ($insertStmt->execute()) {
            echo json_encode(array("success" => true, "message" => "Student assigned successfully."));
        } else {
            echo json_encode(array("success" => false, "message" => "Error assigning student."));
        }
    }

    $checkStmt->close();
    $insertStmt->close();
} else {
    echo json_encode(array("success" => false, "message" => "Invalid parameters."));
}

$db->close();
?>
