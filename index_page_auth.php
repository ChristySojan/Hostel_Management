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
    <title>Hostel Login Page</title>
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
            <h2 class="form-heading">STUDENT LOGIN</h2>
            <form method="POST" class="login-form">
                <input type="text" name="user_id" class="input-field" placeholder="User ID" required>
                <input type="password" name="pswd" class="input-field" placeholder="Password" required>
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
        if (!empty($_POST['user_id']) && !empty($_POST['pswd'])) {
            $user = $_POST['user_id'];
            $pswd = $_POST['pswd'];

            // Fetch user data
            $query = "SELECT * FROM user_login WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $userData = $result->fetch_assoc();

                // Verify password
                if (password_verify($pswd, $userData['pass_hash'])) {
                    $_SESSION['hostel_logged_in'] = true;
                    $_SESSION['user_id'] = $userData['id'];
                    header("Location: index.php");
                    exit();
                } else {
                    echo '<script>alert("Invalid ID or Password!");</script>';
                }
            } else {
                echo '<script>alert("Invalid ID or Password!");</script>';
            }
        } else {
            echo '<script>alert("User ID or Password not provided!");</script>';
        }
    }
    $conn->close();
    ?>
</body>
</html>
