<?php
$conn = mysqli_connect("localhost", "root", "", "hostel_db"); // connecting to the DB

// check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
