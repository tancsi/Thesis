<?php
include "../kapcsolat.php";

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

if (isset($data["teacher_id"]) && isset($data["student_ids"])) {
    $teacherId = $data["teacher_id"];
    $studentIds = $data["student_ids"];
    error_log(json_encode($data));

    if (is_array($studentIds) && count($studentIds) > 0) {
        $tempConIds = [];
        foreach ($studentIds as $studentId) {
            $sql = "SELECT conid FROM teacher_student_con WHERE teacher_id = ? AND user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("is", $teacherId, $studentId);
            $stmt->execute();
            $stmt->bind_result($conId);
            $stmt->fetch();
            $stmt->close();
            if ($conId) {
                $tempConIds[] = $conId;
            }
        }

        // Ha vannak találatok, töröljük azokat
        if (!empty($tempConIds)) {
            $placeholders = implode(",", array_fill(0, count($tempConIds), "?"));
            $sql = "DELETE FROM teacher_student_con WHERE conid IN ($placeholders)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param(str_repeat("i", count($tempConIds)), ...$tempConIds); // Dinamikus bind_param hívás

            $stmt->execute();
			error_log("teacher_id: " . $teacherId);
			error_log("user_ids: " . json_encode($studentIds));
			error_log("Affected Rows: " . $stmt->affected_rows);
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "No records deleted"]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "No records found for deletion"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid or missing data"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid or missing data"]);
}

$db->close();
?>
