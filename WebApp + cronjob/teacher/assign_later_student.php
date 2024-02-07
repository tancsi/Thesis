<?php
include "../kapcsolat.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacherId = $_POST["teacherId"];
    $studentIds = $_POST["students"];

    if (empty($studentIds)) {
        echo json_encode(["error" => "Please choose at least one person to assign"]);
    } else {
        $alreadyAssigned = [];
        $successfullyAssigned = [];
        $halfAssigned = [];
        
        foreach ($studentIds as $studentId) {
            $sql = "SELECT * FROM teacher_student_con WHERE teacher_id = '$teacherId' AND user_id = '$studentId'";
            $result = $db->query($sql);
            if ($result->num_rows > 0) {
                $alreadyAssigned[] = $studentId;
            } else {
                $sql = "INSERT INTO teacher_student_con (teacher_id, user_id) VALUES ('$teacherId', '$studentId')";
                $db->query($sql);
                $successfullyAssigned[] = $studentId;
            }
        }
        
        if (!empty($alreadyAssigned)) {
            $response['already_assigned'] = $alreadyAssigned;
        }
        
        if (!empty($successfullyAssigned)) {
            $response['successfully_assigned'] = $successfullyAssigned;
        }
        
        if (count($alreadyAssigned) + count($successfullyAssigned) < count($studentIds)) {
            foreach ($studentIds as $studentId) {
                if (!in_array($studentId, $alreadyAssigned) && !in_array($studentId, $successfullyAssigned)) {
                    $halfAssigned[] = $studentId;
                }
            }
            $response['half_assigned'] = $halfAssigned;
        }
        
        echo json_encode($response);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
