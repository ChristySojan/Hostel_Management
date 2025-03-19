<?php
session_start(); # Helps to remember who signed up
ob_start(); # Output buffering to handle headers

include './php/db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warden Login Page</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/index_login.css" />
</head>

<body>
    <!-- Navbar -->
    <div class="logo-container">
        <img src="./res/cec-better.png" alt="CEC Logo" class="logo">
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <div class="form-container">
            <h2 class="form-heading">WARDEN LOGIN</h2>
            <form method="POST" class="login-form">
                <input type="text" name="admin_id" class="input-field" placeholder="Admin ID" required>
                <input type="password" name="password" class="input-field" placeholder="Password" required>
                <div class="button-container">
                    <button type="submit" name="login" class="btn-login">LOGIN</button>
                    <button type="reset" class="btn-reset">RESET</button>
                </div>
            </form>
        </div>
    </div>

    <!-- PHP Code Here -->
    <?php
    if (isset($_POST['login'])) {
        if (!empty($_POST['admin_id']) && !empty($_POST['password'])) {
            $admin = $_POST['admin_id'];
            $password = $_POST['password'];

            // Fetch admin data
            $query = "SELECT * FROM warden_login WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $admin);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $adminData = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $adminData['pass_hash'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['Admin_id'] = $adminData['id'];
                    header("Location: warden.php");
                    exit();
                } else {
                    echo '<script>alert("Invalid ID or Password!");</script>';
                }
            } else {
                echo '<script>alert("Invalid ID or Password!");</script>';
            }
        } else {
            echo '<script>alert("Admin ID or Password not provided!");</script>';
        }
    }
    $conn->close();
    ?>
</body>
</html>
