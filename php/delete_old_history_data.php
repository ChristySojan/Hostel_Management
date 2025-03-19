<?php
include 'db_connection.php';

$query = 'SELECT COUNT(*) FROM record
        WHERE Date <= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)';
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    $query = 'DELETE FROM record
            WHERE Date <= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)';
    $stmt = $conn->prepare($query);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Successfully deleted 6+ month Old Data From the History Table"]);
    } else {
        echo json_encode(['success' => true, 'message' => "Successfully deleted 6+ month Old Data From the History Table"]);
    }
} else {
    echo json_encode(['message' => "No 6+ Month Old Data Exists"]);
}

