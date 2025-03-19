<?php
include 'db_connection.php';

$block=$_GET['block'];

$sql = 'SELECT room FROM rooms WHERE block= ? AND available > 0';
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $block);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['room'] . '">' . $row['room'] . '<option>';
    }
} else {
    echo "<option value='' disabled>No rooms available</option>";
}