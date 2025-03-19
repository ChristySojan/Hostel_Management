<?php
session_start();
include 'db_connection.php';

// If the form is submitted, execute the following block of code
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $usn = $_POST['usn'];

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE usn = ?");
    $stmt->bind_param("s", $usn);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $num = $row['count'];

    if ($num == 0) {
        $name = $_POST['name'];
        $year = $_POST['cyear'];
        $phno = $_POST['phno'];
        $block = $_POST['block'];
        $room = $_POST['room'];

        $entrykey = substr($usn, -3);

        $room = str_pad($room, 3, "0", STR_PAD_LEFT);

        // Insert data into the `users` table or update if the USN already exists
        $stmt = $conn->prepare("INSERT IGNORE INTO users (usn, name, cyear, phno, block, room_no, entrykey, last_promoted_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())");
        $stmt->bind_param("ssissss", $usn, $name, $year, $phno, $block, $room, $entrykey);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $updateStmt = $conn->prepare('UPDATE rooms SET available = available-1 WHERE room = ? AND block = ?');
            $updateStmt->bind_param('ss', $room, $block);
            $updateStmt->execute();
        }
    } else {
        $_SESSION["message"] = "Student with USN {$usn} already exist!!!";
    }

    // Close the connection
    $conn->close();
    header("Location: ../warden.php");
    exit;
}
?>