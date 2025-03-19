<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["usn"])) {
    $usn = $_POST['usn'];
    $name = $_POST['name'];
    $year = $_POST['year'];
    $phno = $_POST['phno'];
    $nblock = $_POST['nblock'];
    $nroom = $_POST['nroom'];

    // Check if USN is not empty
    if (!empty($usn)) {
        // Fetch old block and room values
        $fetchStmt = $conn->prepare("SELECT block, room_no FROM users WHERE usn = ?");
        $fetchStmt->bind_param("s", $usn);
        $fetchStmt->execute();
        $fetchStmt->bind_result($oblock, $oroom);
        $fetchStmt->fetch();
        $fetchStmt->close();

        // Ensure old block and room values are fetched
        if ($oblock && $oroom) {
            // Update student information
            $updateStmt = $conn->prepare("UPDATE users SET name = ?, cyear = ?, phno = ?, block = ?, room_no = ? WHERE usn = ?");
            $updateStmt->bind_param("ssssss", $name, $year, $phno, $nblock, $nroom, $usn);

            if ($updateStmt->execute()) {
                // Increment availability in the old room
                $incrementStmt = $conn->prepare("UPDATE rooms SET available = available + 1 WHERE block = ? AND room = ?");
                $incrementStmt->bind_param("ss", $oblock, $oroom);
                $incrementStmt->execute();
                // if (!$incrementStmt->execute()) {
                //     $_SESSION['message'] .= "Error updating old room availability: " . $incrementStmt->error;
                // }
            
                // Decrement availability in the new room
                $decrementStmt = $conn->prepare("UPDATE rooms SET available = available - 1 WHERE block = ? AND room = ?");
                $decrementStmt->bind_param("ss", $nblock, $nroom);
                $decrementStmt->execute();
                // if (!$decrementStmt->execute()) {
                //     $_SESSION['message'] .= "Error updating new room availability: " . $decrementStmt->error;
                // }
            
                // $_SESSION['message'] = "Record updated successfully.";
            } else {
                $_SESSION['message'] = "Error updating student record: " . $updateStmt->error;
            }
            $updateStmt->close();
        } else {
            $_SESSION['message'] = "Old block and room values could not be found.";
        }
    } else {
        $_SESSION['message'] = "Please enter a valid USN.";
    }
} else {
    $_SESSION['message'] = "Form not submitted or USN invalid.";
}

// Close the connection
$conn->close();
header("Location: ../warden.php");
exit;
?>
