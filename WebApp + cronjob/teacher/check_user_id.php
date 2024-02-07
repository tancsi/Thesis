<?php
include "../kapcsolat.php";

if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $sql = "SELECT COUNT(*) AS count FROM students WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode(['exists' => $count > 0]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid user_id']);
}

$db->close();
?>
