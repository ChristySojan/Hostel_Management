<?php
include 'db_connection.php';

if (isset($_POST['name'], $_POST['block'], $_POST['room'], $_POST['entrykey'])) {
    $name = $_POST['name'];
    $block = $_POST['block'];
    $room = $_POST['room'];
    $entrykey = $_POST['entrykey'];

    // Fetch student details from 'users' table
    $query = "SELECT * FROM users WHERE name = ? AND block = ? AND room_no = ? AND entrykey = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $name, $block, $room, $entrykey);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();
        $usn = $student['usn'];

        // Check last punch-in time from 'record' table
        $checkQuery = "SELECT * FROM record WHERE usn = ? ORDER BY date DESC, time DESC LIMIT 1";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('s', $usn);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows === 1) {
            $lastRecord = $checkResult->fetch_assoc();
            $lastDate = $lastRecord['date'];
            $lastTime = $lastRecord['time'];

            // Check if date or time is null
            if (!is_null($lastDate) && !is_null($lastTime)) {
                $lastPunchIn = $lastDate . ' ' . $lastTime; // Combine date and time

                // Calculate the time difference
                $lastPunchInTime = new DateTime($lastPunchIn);
                $currentTime = new DateTime();
                $interval = $lastPunchInTime->diff($currentTime);

                $hoursSinceLastPunch = ($interval->days * 24) + $interval->h;

                if ($hoursSinceLastPunch < 20) {
                    echo json_encode(['success' => false, 'message' => 'You can only punch in after 20 hours from your last punch-in.']);
                    exit;
                }
            }
        }

        $updateQuery = "UPDATE record SET status = 'Present', time = NOW() WHERE usn = ? AND status IS NULL";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('s',$usn);
        $updateResult = $updateStmt->execute();

        if ($updateResult) {
            echo json_encode(['success' => true, 'message' => 'Punch-in recorded successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to record punch-in']);
        }
    } else {
        // If the student is not found in the 'users' table
        echo json_encode(['success' => false, 'message' => 'Student not found or invalid entry key']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
}
?>