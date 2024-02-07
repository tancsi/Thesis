<?php
include "../kapcsolat.php";

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    $sql = "UPDATE students SET active = 1 WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $student_id);

    if ($stmt->execute()) {
        header("Location: student_management.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid student ID.";
}

$db->close();
?>