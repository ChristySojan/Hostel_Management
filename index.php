<?php
session_start(); // Ensure session is started
if (!isset($_SESSION['hostel_logged_in'])) {
    header("Location: index_page_auth.php"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Attendance</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <link href="css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a class="navbar-brand" href="#">
            <span>Hostel Attendance</span>
        </a>
        <button class="btn btn-outline-danger btn-logoutHostel" type="button">Logout</button>
    </nav>
    
    <!-- Hostel Logout Modal -->
    <div class="modal hostelLogout" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="php/confirmHostelLogout.php" id="hostelLogoutForm" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Enter Password to Logout</p>
                        <input type="password" name="pwd-logout" id="pwd-logout" class="form-control"
                            placeholder="Enter Password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="confirmHostelLogout btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="logo">
            <img src="./Res/cec-better.png" alt="Logo">
        </div>
        <h1>Welcome to Hostel Attendance</h1>
        <p>Please Mark Your Attendence Here</p>
        <button type="button" id="LoginBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
            Login
        </button>
    </div>

    <!-- Modal for Login -->
    <div class="modal" tabindex="-1" id="loginModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Login</h4>
                    <button type="button" data-bs-dismiss="modal" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="LoginForm">
                        <div class="mb-3">
                            <label for="block" class="form-label">Block</label>
                            <select name="block" class="form-select" id="block" placeholder="Enter branch">
                                <option selected disabled>Select Block</option>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="roomContainer">
                            <label for="room" class="form-label">Room</label>
                            <select class="form-select room" id="room" name="room">
                                <option selected disabled>Select Room</option>
                            </select>
                        </div>
                        <div id="ListContainer" class="d-none">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <select class="form-select name" id="name" name="name">
                                    <option selected disabled>Select Name</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="EntryKey">Entry Key (Last 3 Characters of your USN (Eg: 4CB22CSXXX))</label>
                                <input name="EntryKey" type="password" id="EntryKey" class="form-control mt-2"
                                    placeholder="Entry Key" aria-label="EntryKey">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="login">Accept</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/dataTables.bootstrap5.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/script.js"></script>

</body>

</html>
