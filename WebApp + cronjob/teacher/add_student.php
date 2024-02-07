<?php
include "../kapcsolat.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST["user_id"];
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];

    $sql = "INSERT INTO students (user_id, first_name, last_name, total_trips, reg_time, active) 
            VALUES (?, ?, ?, 0, CURRENT_TIMESTAMP, 1)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sss", $userId, $firstName, $lastName);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Student added successfully"]);
    } else {
		echo json_encode(["error" => "Error adding student"]);
    }

    $stmt->close();
}

$db->close();
?>
