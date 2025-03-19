<?php
include 'db_connection.php';

$data = array();


$sql = "SELECT block,room_no,usn,name,cyear,phno,entrykey FROM users WHERE 1=1 ORDER BY cyear ASC,usn ASC";

$stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

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
