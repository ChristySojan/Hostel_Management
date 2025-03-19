<?php
session_start();
include 'db_connection.php';

// Include PhpSpreadsheet library
require 'C:/wamp64/www/vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file is uploaded
    if (isset($_FILES['roomFile']) && $_FILES['roomFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['roomFile']['tmp_name'];
        $fileName = $_FILES['roomFile']['name'];
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
                        $block = isset($data[0]) ? $data[0] : null;
                        $room = isset($data[1]) ? $data[1] : null;
                        $capacity = isset($data[2]) ? $data[2] : null;
                        $available = isset($data[3]) ? $data[3] : null;

                        if ($block && $room && $capacity) {
                            $room = str_pad($room, 3, "0", STR_PAD_LEFT);
                            if (is_numeric($room)) {
                                $stmt = $conn->prepare("INSERT IGNORE INTO rooms (block,room,capacity,available) VALUES (?, ?, ?, ?)");
                                $stmt->bind_param("sssi", $block, $room, $capacity,$available);
                                $stmt->execute();
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
                    $block = isset($row[0]) ? $row[0] : null;
                    $room = isset($row[1]) ? $row[1] : null;
                    $capacity = isset($row[2]) ? $row[2] : null;
                    $available = isset($row[3]) ? $row[3] : null;

                    if ($block && $room && $capacity) {
                        $room = str_pad($room, 3, "0", STR_PAD_LEFT);
                        if (is_numeric($room)) {
                            $stmt = $conn->prepare("INSERT IGNORE INTO rooms (block,room,capacity,available) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("sssi", $block, $room, $capacity,$available);
                            $stmt->execute();
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
