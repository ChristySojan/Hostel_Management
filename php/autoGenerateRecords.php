<?php
include 'db_connection.php';

$query = "SELECT * FROM users";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $conn->begin_transaction();

    // Prepare an insert statement for the 'record' table
    $insertRecordQuery = "INSERT INTO record (usn,date,time,status) VALUES (?,CURDATE(),NULL,NULL)";
    $insertStmt = $conn->prepare($insertRecordQuery);

    while ($user = $result->fetch_assoc()) {
        $usn = $user['usn'];
        $insertStmt->bind_param('s', $usn);
        $insertStmt->execute();
    }

    // Commit the transaction
    $conn->commit();
    echo json_encode(['success' => true, 'message' => "All users have been successfully added to the record."]);
} else {
    echo json_encode(['success' => false, 'message' => "No users found in the users table."]);
}