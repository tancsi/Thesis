<?php
include "kapcsolat.php";

// SQL query to retrieve users
$sql = "SELECT * FROM students where active=1";
$result = $db->query($sql);

// Fetch data and encode as JSON
$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users, JSON_UNESCAPED_UNICODE);
} else {
    echo "No users found";
}

$db->close();
?>