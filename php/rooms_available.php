<?php
include 'db_connection.php';

$data = array();


$sql = "SELECT block,room,capacity,available FROM rooms WHERE 1=1 ORDER BY block ASC,room ASC";

$stmt = $conn->prepare($sql);

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch all rows into the data array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return data as JSON
echo json_encode(['success' => true, 'data' => $data]);

// Close the statement and connection
$stmt->close();
$conn->close();
