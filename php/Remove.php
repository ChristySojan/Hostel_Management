<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['removechoice'])) {
    // Get the selected option
    $choice = $_POST['removechoice'];

    $currentTimestamp = date('Y-m-d H:i:s');

    // Removing 4th year students off the DB
    if ($choice == 'option1') {
        // Retrieve all 4th-year students eligible for deletion
        $stmt = $conn->prepare("SELECT usn, block, room_no FROM users WHERE Cyear = 4 AND (last_promoted_at IS NULL OR last_promoted_at < DATE_SUB('$currentTimestamp', INTERVAL 2 MONTH))");
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are records to delete
        if ($result->num_rows > 0) {
            // Store room data for updates
            $roomUpdates = [];
            while ($row = $result->fetch_assoc()) {
                $block = $row['block'];
                $room = $row['room_no'];
                $roomKey = $block . '-' . $room;

                // Increment the room availability in the updates array
                if (!isset($roomUpdates[$roomKey])) {
                    $roomUpdates[$roomKey] = ['block' => $block, 'room' => $room, 'count' => 0];
                }
                $roomUpdates[$roomKey]['count']++;
            }

            // Perform bulk deletion
            $stmt = $conn->prepare("DELETE FROM users WHERE Cyear = 4 AND (last_promoted_at IS NULL OR last_promoted_at < DATE_SUB('$currentTimestamp', INTERVAL 2 MONTH))");
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                foreach ($roomUpdates as $update) {
                    $stmt = $conn->prepare("UPDATE rooms SET available = available + ? WHERE block = ? AND room = ?");
                    $stmt->bind_param("iss", $update['count'], $update['block'], $update['room']);
                    $stmt->execute();
                }
            }

            // Verify deletion
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE Cyear = 4 AND (last_promoted_at IS NULL OR last_promoted_at < DATE_SUB('$currentTimestamp', INTERVAL 2 MONTH))");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $num = $row['count'];

            if ($num == 0) {
                $_SESSION['message'] = "Records deleted successfully and room availability updated.";
            } else {
                $_SESSION['message'] = "Failed to delete all records.";
            }
        } else {
            $_SESSION['message'] = "No records found for the given year of study.";
        }
    } elseif ($choice == 'option2') {
        // Single USN deletion logic (already provided, no changes needed)
        if (isset($_POST["usn"])) {
            $usn = $_POST["usn"];

            if (!empty($usn)) {
                // Check if any user exists with the given USN
                $stmt = $conn->prepare("SELECT COUNT(*) as count, block, room_no FROM users WHERE usn = ?");
                $stmt->bind_param("s", $usn);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $bnum = $row['count'];
                $block = $row['block'];
                $room = $row['room_no'];

                // If a user exists, delete the record
                if ($bnum > 0) {
                    $stmt = $conn->prepare("DELETE FROM users WHERE usn = ?");
                    $stmt->bind_param("s", $usn);
                    $stmt->execute();

                    // Verify deletion
                    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE usn = ?");
                    $stmt->bind_param("s", $usn);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $num = $row['count'];

                    if ($num == 0) {
                        $stmt = $conn->prepare("UPDATE rooms SET available = available + 1 WHERE block = ? AND room = ?");
                        $stmt->bind_param("ss", $block, $room);
                        $stmt->execute();
                        $_SESSION['message'] = "Record deleted successfully.";
                    } else {
                        $_SESSION['message'] = "Failed to delete record.";
                    }
                } else {
                    $_SESSION['message'] = "USN does not exist.";
                }
            } else {
                $_SESSION['message'] = "Please enter a valid USN.";
            }
        } else {
            $_SESSION['message'] = "Form not submitted or USN invalid.";
        }
    }
}

$conn->close();
header("Location: ../warden.php");
exit;
?>
