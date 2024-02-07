<?php
include "../kapcsolat.php";

$sql = "SELECT
            s.user_id AS student_id,
            CONCAT(s.first_name, ' ', s.last_name, ' (', s.user_id, ')') AS student_fullname,
            s.total_trips AS student_total_trips,
            IFNULL(GROUP_CONCAT(CONCAT(t.first_name, ' ', t.last_name, ' (', t.username, ')') SEPARATOR ', '), 'No teacher assigned') AS teacher_names,
            s.reg_time AS student_registration_time,
            s.active
        FROM
            students AS s
        LEFT JOIN
            teacher_student_con AS ts ON s.user_id = ts.user_id
        LEFT JOIN
            teacher AS t ON ts.teacher_id = t.teacher_id
        GROUP BY
            s.user_id, s.first_name, s.last_name, s.total_trips, s.reg_time
        ORDER BY
            s.reg_time";

$result = $db->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$db->close();
?>
