<?php
include 'db_connection.php';

$block = $_POST['block'];
$room = $_POST['room'];

$sql = 'SELECT name FROM users WHERE block = ? AND room_no = ? ORDER BY name ASC';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $block, $room);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows>0){
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
    }
}
else{
    echo "<option disabled>No Students available</option>";
}