<?php
include 'db_connection.php';

$new_pass = $_POST['new_pass'];
$reentered_pass = $_POST['re_new_pass'];
$old_pass = $_POST['old_pass'];

$password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

if (($new_pass == $reentered_pass) && isset($_POST['user_pass_change'])) {
    if (!preg_match($password_pattern, $new_pass)) {
        echo '<script type="text/javascript">';
        echo 'alert("Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, and one special character.");';
        echo 'window.location.href = "../warden.php";';
        echo '</script>';
        exit; // Stop further execution if the password doesn't meet the criteria
    }
    $result = $conn->query("SELECT id, pass_hash FROM user_login");

    // Check if any result was returned
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Verify the old password
        if (password_verify($old_pass, $admin['pass_hash'])) {
            // Hash the new password
            $password_hash = password_hash($new_pass, PASSWORD_BCRYPT);

            // Update the password
            $query = "UPDATE user_login SET pass_hash = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $password_hash, $admin['id']);

            if ($stmt->execute()) {
                // Verify if the password was updated correctly
                $stmt = $conn->prepare("SELECT pass_hash FROM user_login WHERE id = ?");
                $stmt->bind_param('s', $admin['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $updated_admin = $result->fetch_assoc();

                // Check if the updated password hash matches
                if ($updated_admin['pass_hash'] === $password_hash) {
                    echo '<script type="text/javascript">';
                    echo 'alert("Password successfully changed");';
                    echo 'window.location.href = "../warden.php";';
                    echo '</script>';
                } else {
                    echo '<script type="text/javascript">';
                    echo 'alert("Password failed to change");';
                    echo 'window.location.href = "../warden.php";';
                    echo '</script>';
                }
            } else {
                echo '<script type="text/javascript">';
                echo 'alert("Error updating password");';
                echo 'window.location.href = "../warden.php";';
                echo '</script>';
            }
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Current password is invalid");';
            echo 'window.location.href = "../warden.php";';
            echo '</script>';
        }
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("Error retrieving user data");';
        echo 'window.location.href = "../warden.php";';
        echo '</script>';
    }
    unset($_POST['user_pass_change']);
} else if (($new_pass == $reentered_pass) && isset($_POST['warden_pass_change'])) {
    if (!preg_match($password_pattern, $new_pass)) {
        echo '<script type="text/javascript">';
        echo 'alert("Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, and one special character.");';
        echo 'window.location.href = "../warden.php";';
        echo '</script>';
        exit; // Stop further execution if the password doesn't meet the criteria
    }
    $result = $conn->query("SELECT id, pass_hash FROM warden_login");

    // Check if any result was returned
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Verify the old password
        if (password_verify($old_pass, $admin['pass_hash'])) {
            // Hash the new password
            $password_hash = password_hash($new_pass, PASSWORD_BCRYPT);

            // Update the password
            $query = "UPDATE warden_login SET pass_hash = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $password_hash, $admin['id']);

            if ($stmt->execute()) {
                // Verify if the password was updated correctly
                $stmt = $conn->prepare("SELECT pass_hash FROM warden_login WHERE id = ?");
                $stmt->bind_param('s', $admin['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $updated_admin = $result->fetch_assoc();

                // Check if the updated password hash matches
                if ($updated_admin['pass_hash'] === $password_hash) {
                    echo '<script type="text/javascript">';
                    echo 'alert("Password successfully changed");';
                    echo 'window.location.href = "../warden.php";';
                    echo '</script>';
                } else {
                    echo '<script type="text/javascript">';
                    echo 'alert("Password failed to change");';
                    echo 'window.location.href = "../warden.php";';
                    echo '</script>';
                }
            } else {
                echo '<script type="text/javascript">';
                echo 'alert("Error updating password");';
                echo 'window.location.href = "../warden.php";';
                echo '</script>';
            }
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Current password is invalid");';
            echo 'window.location.href = "../warden.php";';
            echo '</script>';
        }
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("Error retrieving warden data");';
        echo 'window.location.href = "../warden.php";';
        echo '</script>';
    }
    unset($_POST['warden_pass_change']);
} else {
    if (isset($_POST['user_pass_change'])) {
        echo '<script type="text/javascript">';
        echo 'alert("Passwords do not match");';
        echo 'window.location.href = "../warden.php";';
        echo '</script>';
    } else if (isset($_POST['warden_pass_change'])) {
        echo '<script type="text/javascript">';
        echo 'alert("Passwords do not match");';
        echo 'window.location.href = "../warden.php";';
        echo '</script>';
    }
}

// header("Location: ../index.php");
$conn->close();

