<?php
session_start();
include 'db_connection.php';

// Include PhpSpreadsheet library
require 'C:/wamp64/www/vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file is uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Allowed file extensions
        $allowedfileExtensions = array('csv', 'xlsx');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Load the spreadsheet
            if ($fileExtension === 'csv') {
                // Open the CSV file
                if (($handle = fopen($fileTmpPath, "r")) !== false) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                        // Sanitize and check if data exists before inserting
                        $usn = isset($data[0]) ? $data[0] : null;
                        $name = isset($data[1]) ? $data[1] : null;
                        $year = isset($data[2]) ? $data[2] : null;
                        $phno = isset($data[3]) ? $data[3] : null;
                        $block = isset($data[4]) ? $data[4] : null;
                        $room = isset($data[5]) ? $data[5] : null;
                        $entrykey = substr($usn, -3);

                        $x = substr($usn, 0, 3);

                        // Only proceed if $usn and $name are not null
                        if ($usn && $name) {
                            $room = str_pad($room, 3, "0", STR_PAD_LEFT);
                            if (strcasecmp($x, 'USN') && strcasecmp($x, 'Uni')) {
                                // Check if room availability > 0
                                $stmt = $conn->prepare('SELECT available FROM rooms WHERE room = ? AND block = ?');
                                $stmt->bind_param('ss', $room, $block); // Bind the parameters
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $available = $row['available'];

                                    if ($available > 0) {
                                        // Insert into users table
                                        $stmt = $conn->prepare("INSERT IGNORE INTO users (usn, name, cyear, phno, block, room_no, entrykey, last_promoted_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())");
                                        $stmt->bind_param("ssissss", $usn, $name, $year, $phno, $block, $room, $entrykey);
                                        $stmt->execute();

                                        if ($stmt->affected_rows > 0){
                                            $updatedAvailable = $available - 1;
                                            $updateStmt = $conn->prepare('UPDATE rooms SET available = ? WHERE room = ? AND block = ?');
                                            $updateStmt->bind_param('iss', $updatedAvailable, $room, $block);
                                            $updateStmt->execute();
                                        }
                                    } else {
                                        echo "Room is filled";
                                    }
                                } else {
                                    echo "No matching room found.";
                                }
                            } else {
                                continue;
                            }
                        }
                    }
                    fclose($handle);
                }
            } elseif ($fileExtension === 'xlsx') {
                // Load the XLSX file
                $spreadsheet = IOFactory::load($fileTmpPath);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                foreach ($sheetData as $row) {
                    // Sanitize and check if data exists before inserting
                    $usn = isset($row[0]) ? $row[0] : null;
                    $name = isset($row[1]) ? $row[1] : null;
                    $year = isset($row[2]) ? $row[2] : null;
                    $phno = isset($row[3]) ? $row[3] : null;
                    $block = isset($row[4]) ? $row[4] : null;
                    $room = isset($row[5]) ? $row[5] : null;
                    $entrykey = substr($usn, -3);

                    $x = substr($usn, 0, 3);

                    // Only proceed if $usn and $name are not null
                    if ($usn && $name) {
                        $room = str_pad($room, 3, "0", STR_PAD_LEFT);
                        if (strcasecmp($x, 'USN') && strcasecmp($x, 'Uni')) {
                            // Check if room availability > 0
                            $stmt = $conn->prepare('SELECT available FROM rooms WHERE room = ? AND block = ?');
                            $stmt->bind_param('ss', $room, $block); // Bind the parameters
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $available = $row['available'];

                                if ($available > 0) {
                                    // Insert into users table
                                    $stmt = $conn->prepare("INSERT IGNORE INTO users (usn, name, cyear, phno, block, room_no, entrykey, last_promoted_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())");
                                    $stmt->bind_param("ssissss", $usn, $name, $year, $phno, $block, $room, $entrykey);
                                    $stmt->execute();

                                    if ($stmt->affected_rows > 0){
                                        $updatedAvailable = $available - 1;
                                        $updateStmt = $conn->prepare('UPDATE rooms SET available = ? WHERE room = ? AND block = ?');
                                        $updateStmt->bind_param('iss', $updatedAvailable, $room, $block);
                                        $updateStmt->execute();
                                    }
                                } else {
                                    echo "Room is filled";
                                }
                            } else {
                                echo "No matching room found.";
                            }
                        } else {
                            continue;
                        }
                    }
                }
            }
            $_SESSION['message'] = "Data imported successfully.";
            header("Location: ../warden.php");
            exit;
        } else {
            $_SESSION['message'] = "Upload failed. Only .csv and .xlsx files are allowed.";
            header("Location: ../warden.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "There was an error uploading the file.";
        header("Location: ../warden.php");
        exit;
    }
}

$conn->close();
