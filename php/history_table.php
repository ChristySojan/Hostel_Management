<?php
include 'db_connection.php';

$data = array();

$date = isset($_GET['date']) ? $_GET['date'] : '';

// SQL Query
$sql = "SELECT u.USN, u.name, u.cyear, u.block, u.room_no,u.phno, h.time, h.status, DATE_FORMAT(h.date, '%d-%m-%Y') as date
        FROM users u 
        INNER JOIN record h ON u.USN = h.USN 
        WHERE 1=1";

$params = [];
$types = '';

if (!empty($date)) {
    $sql .= " AND h.date = ?";
    $params[] = $date;
    $types .= 's';
}

$sql .= " ORDER BY h.date DESC, h.time DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    echo json_encode(['success' => false, 'message' => 'Database query failed: ' . $conn->error]);
    exit();
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>
