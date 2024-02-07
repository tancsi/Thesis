<?php
include "kapcsolat.php";

$sql = "SELECT * FROM teacher";
$result = $db->query($sql);

$teacher = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
    echo json_encode($teachers, JSON_UNESCAPED_UNICODE);
} else {
    echo "No teachers found";
}

$db->close();
?>