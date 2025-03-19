<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index_page_auth.php");
        exit();
    }

    $pwd = $_POST['pwd-logout'];
    if (empty($pwd)) {
        echo '<script>
                alert("Password is required!");
                window.location.href = "../index.php";
              </script>';
        exit();
    }

    $user_id = $_SESSION['user_id']; // Use the correct session variable

    // Fetch the password hash for the user
    $sql = "SELECT pass_hash FROM user_login WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pwd, $row['pass_hash'])) {
            // Logout successful: unset user-related session variables
            unset($_SESSION['user_id']);
            unset($_SESSION['hostel_logged_in']); // Only unset these specific session variables
            // Do not destroy the entire session, to keep session alive for other pages
            header("Location: ../index_page_auth.php");
            exit();
        } else {
            // Invalid password
            echo '<script>
                    alert("Invalid Password!");
                    window.location.href = "../index.php";
                  </script>';
        }
    } else {
        echo '<script>
                alert("User not found!");
                window.location.href = "../index.php";
              </script>';
    }

    $stmt->close();
    $conn->close();
}
?>
