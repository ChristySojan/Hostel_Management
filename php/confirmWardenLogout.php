<?php
session_start();
include 'db_connection.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if the admin is logged in
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: ../warden_auth.php");
        exit();
    }

    // Retrieve the password submitted for logout
    $pwd = $_POST['pwd-logout'];
    if (empty($pwd)) {
        echo '<script>
                alert("Password is required!");
                window.location.href = "../warden.php";
              </script>';
        exit();
    }

    // Get the admin ID from session
    $admin_id = $_SESSION['Admin_id']; // Use the correct session variable

    // Fetch the password hash for the admin from the database
    $sql = "SELECT pass_hash FROM warden_login WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($pwd, $row['pass_hash'])) {
            // Password correct, proceed to logout
            unset($_SESSION['Admin_id']);
            unset($_SESSION['admin_logged_in']);
            
            header("Location: ../warden_auth.php"); // Redirect to login page
            exit();
        } else {
            // Invalid password
            echo '<script>
                    alert("Invalid Password!");
                    window.location.href = "../warden.php";
                  </script>';
        }
    } else {
        // User not found in the database
        echo '<script>
                alert("User not found!");
                window.location.href = "../warden.php";
              </script>';
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
