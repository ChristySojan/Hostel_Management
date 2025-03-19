<?php
include 'db_connection.php';

$sql = 'UPDATE record SET status= "Absent",time=NOW() WHERE status IS NULL';
if ($conn->query($sql) === TRUE) {
    $response = [
        'success' => true,
        'message' => 'Records successfully updated. Status set to "Absent" where it was NULL.'
    ];
} else {
    $response = [
        'success' => false,
        'message' => 'Error updating records: ' . $conn->error
    ];
}

echo json_encode($response);

$conn->close();
?>