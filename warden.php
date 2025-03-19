<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: warden_auth.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/admin_dash.css" />
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <link href="css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-dark bg-dark fixed-top" style="height: 60px;">
        <div class="container-fluid position-relative" style="height:100%;">
            <span class="navbar-brand mb-0 h1 page-title position-absolute top-50 start-50 translate-middle">
                ADMIN PAGE
            </span>
            <div class="d-flex gap-3 ms-auto">
                <a class="nav-link px-3 active" style="color: white; cursor: pointer" data-bs-target="#select_role"
                    data-bs-toggle="modal">
                    CHANGE PASSWORD
                </a>
                <a class="nav-link px-3 active" style="color: white; cursor: pointer" data-bs-target="#adminLogoutModal"
                    data-bs-toggle="modal">
                    LOGOUT
                </a>
            </div>
        </div>
    </nav>

    <!-- Librarian Logout Modal -->
    <div class="modal" id="adminLogoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="php/confirmWardenLogout.php" id="adminLogoutForm" method="POST">
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
                        <button type="submit" onclick="confirmation(event,'', 'log out')"
                            class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Change Modals -->
    <div class="modal fade" id="select_role" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Change Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePassForm">
                        <p>Select Role:</p>
                        <label>
                            <input type="radio" name="changePassChoice" value="user" required> Student
                        </label><br>
                        <label>
                            <input type="radio" name="changePassChoice" value="warden" required> Warden
                        </label><br><br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" id="changePass" class="btn btn-primary">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Student password change -->
    <div class="modal fade" id="change_pass_modal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form method="POST" action="php/change_pass.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Change Student Password
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input name="old_pass" class="form-control mb-3" type="password"
                            placeholder="Enter Current Password">
                        <input name="new_pass" class="form-control mb-3" type="password"
                            placeholder="Enter New Password" id="new_pass">
                        <input name="re_new_pass" class="form-control" type="password"
                            placeholder="Re-enter New Password" id="re_new_pass">
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" name="user_pass_change" id="submit_change_pass"
                            class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Warden password change -->
    <div class="modal fade" id="warden_pass_modal" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form method="POST" action="php/change_pass.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Change Warden Password
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input name="old_pass" class="form-control mb-3" type="password"
                            placeholder="Enter Current Password">
                        <input name="new_pass" class="form-control mb-3" type="password"
                            placeholder="Enter New Password">
                        <input name="re_new_pass" class="form-control" type="password"
                            placeholder="Re-enter New Password">
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" name="warden_pass_change" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-body p-0">
            <div class="navbar-dark">
                <ul class="navbar-nav">
                    <li>
                        <a href="#" data-bs-target="#importModal" data-bs-toggle="modal"
                            class="nav-link px-3 active">Import</a>
                        <hr>
                        <a href="#" data-bs-target="#addStudentModal" data-bs-toggle="modal"
                            class="nav-link px-3 active">Add a Student</a>
                        <hr>
                        <a href="#" data-bs-target="#removeStudentModal" data-bs-toggle="modal"
                            class="nav-link px-3 active">Remove Student(s)</a>
                        <hr>
                        <a href="#" data-bs-target="#editModal" data-bs-toggle="modal" class="nav-link px-3 active">Edit
                            Student(s)</a>
                        <hr>
                        <a href="#" data-bs-target="#promoteModal" data-bs-toggle="modal"
                            class="nav-link px-3 active">Promote Students</a>
                        <hr>
                        <a href="#" data-bs-target="#formatModal" data-bs-toggle="modal"
                            class="nav-link px-3 active">Download Format</a>
                        <hr>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Dashboard Body -->
    <main id="admin-main" class="mt-5 pt-3">
        <div class=" container-fluid">
            <div class="row">
                <div class="card text-center mx-auto">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item db">
                                <a class="nav-link" style="text-decoration: none; color: black;" href="#">Users</a>
                            </li>
                            <li class="nav-item attendence">
                                <a class="nav-link" style="text-decoration: none; color: black;" href="#">Attendence</a>
                            </li>
                            <li class="nav-item available">
                                <a class="nav-link" style="text-decoration: none; color: black;" href="#">Available</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <!-- Users Body -->
                        <div id="db-content">
                            <div class="db m-3">
                                <div class="db-header">
                                    <h2><b>Users Table</b></h2>
                                </div>
                                <div class="db-body mt-3">
                                    <div class="Db">
                                        <form class="dbForm" id="dbForm">

                                        </form>
                                        <table id="dbtable" class="table table-striped table-bordered border-secondary">
                                            <thead>
                                                <th>Block</th>
                                                <th>Room no.</th>
                                                <th>USN</th>
                                                <th>Name</th>
                                                <th>Year of Study</th>
                                                <th>Contact no.</th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendence Body -->
                        <div id="history-content" class="d-none">
                            <div class="hist m-3">
                                <div class="hist-header">
                                    <h2><b>Attendence History Table</b></h2>
                                    <form id="historyForm" class="historyForm">
                                        <div class="history-filters d-flex align-items-center justify-content-between">
                                            <div class="history-date">
                                                <label for="history_date">Date:</label>
                                                <input type="date" class="form-control" id="history_date"
                                                    name="history_date">
                                            </div>
                                            <div class="history-btn">
                                                <div class="history-deletebtn">
                                                    <button type="button"
                                                        onclick="confirmation(event,'#historyForm','delete 6+ month old data')"
                                                        id="history_deletebtn" class="btn btn-danger"
                                                        style="padding: 3px; flex: 1; margin-right: 8px;">Delete 6+
                                                        Month
                                                        Old
                                                        Data</button>
                                                </div>
                                                <div class="history-refreshbtn">
                                                    <button type="button" id="history_refreshbtn"
                                                        class="btn btn-primary"
                                                        style="padding: 3px; flex: 1;margin-left: 5px;  width: 100px;">REFRESH</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="hist-body mt-3">
                                    <div class="History">
                                        <div id="hist_table">
                                            <table id="historyTable"
                                                class="table table-striped table-bordered border-secondary">
                                                <thead>
                                                    <tr>
                                                        <th>USN</th>
                                                        <th>Name</th>
                                                        <th>Year of Study</th>
                                                        <th>Block</th>
                                                        <th>Room no.</th>
                                                        <th>Date</th>
                                                        <th>Contact no.</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table data -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Available Body -->
                        <div id="available-content" class="d-none">
                            <div class="avail m-3">
                                <div class="avail-header">
                                    <h2><b>Rooms Available</b></h2>
                                </div>
                                <div class="avail-body mt-3">
                                    <div class="Available">
                                        <div id="avail_table">
                                            <table id="availableTable"
                                                class="table table-striped table-bordered border-secondary">
                                                <thead>
                                                    <tr>
                                                        <th>Block</th>
                                                        <th>Room no.</th>
                                                        <th>Capacity</th>
                                                        <th>Availabillity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table data -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Sidebar Modals -->
    <!-- Promote Modal -->
    <div class="modal fade" id="promoteModal" tabindex="-1" aria-labelledby="promoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promoteModalLabel">Promote Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for File Upload -->
                    <form method="POST" action="">
                        <div class="container">
                            <div class="row">
                                <div class="col-4">
                                    <!-- <input type="text" value="1" hidden> -->
                                    <button id="promote1st"
                                        onclick="confirmation(event, '#promote1stForm','promote 1st years')"
                                        form="promote1stForm" class="btn btn-light ms-auto" type="button">1st Year
                                        --
                                        2nd
                                        Year</button>
                                </div>
                                <div class="col-4">
                                    <!-- <input type="text" value="2" hidden> -->
                                    <button id="promote2nd"
                                        onclick="confirmation(event, '#promote2rdForm','promote 2rd years')"
                                        form="promote2rdForm" class="btn btn-light ms-auto" type="button">2nd Year
                                        --
                                        3rd
                                        Year</button>
                                </div>
                                <div class="col-4">
                                    <!-- <input type="text" value="3" hidden> -->
                                    <button id="promote3rd"
                                        onclick="confirmation(event,'#promote3rdForm','promote 3rd years')"
                                        form="promote3rdForm" class="btn btn-light ms-auto" type="button">3rd Year
                                        --
                                        4th
                                        Year</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importFileForm">
                        <p>Please select an option:</p>
                        <label>
                            <input type="radio" name="importchoice" value="importStudent" required> Import
                            Student
                        </label><br>
                        <label>
                            <input type="radio" name="importchoice" value="importRoom" required> Import Rooms
                        </label><br><br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="importFileBtn" class="btn btn-primary">Proceed</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Student Modal -->
    <div class="modal fade" id="importStudentModal" tabindex="-1" aria-labelledby="importStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importStudentModalLabel">Import Student Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importStudentFileForm" method="POST" action="php/import.php"
                        enctype="multipart/form-data">
                        <label for="file">Choose a text file:</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx" required><br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmation(event,'#importStudentFileForm','import file')"
                        form="importStudentFileForm" class="btn btn-primary">Import</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Room Modal -->
    <div class="modal fade" id="importRoomModal" tabindex="-1" aria-labelledby="importRoomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importRoomModalLabel">Import Room Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importRoomFileForm" method="POST" action="php/import_room.php"
                        enctype="multipart/form-data">
                        <label for="roomFile">Choose a text file:</label>
                        <input type="file" name="roomFile" id="roomFile" class="form-control" accept=".csv,.xlsx"
                            required><br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmation(event,'#importRoomFileForm','import file')"
                        form="importRoomFileForm" class="btn btn-primary">Import</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add a Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add a Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm" method="post" action="php/insert.php">
                        <label for="usn">USN:</label>
                        <input type="text" id="usn" placeholder="Enter Student USN" name="usn" class="form-control"
                            required><br>

                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Enter Student Name" class="form-control"
                            required><br>

                        <label for="cyear">Year:</label>
                        <select name="cyear" id="cyear" class="form-control">
                            <option selected disabled>Select year</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select><br>

                        <label for="phno">Contact no.:</label>
                        <input type="text" placeholder="Enter Contact no." name="phno" class="form-control"
                            required><br>

                        <label for="block">Block:</label>
                        <select name="block" id="block" class="form-control" required>
                            <option value="" selected disabled>Select Block</option>
                        </select><br>

                        <label for="room">Room:</label>
                        <select name="room" id="room" class="form-control" style="width: 100%" required>
                            <option value="" selected disabled>Available Rooms</option>
                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmation(event,'#addStudentForm','Add Student')"
                        form="addStudentForm" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove Student Modal -->
    <div class="modal fade" id="removeStudentModal" tabindex="-1" aria-labelledby="removeStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="removeStudentForm" method="post" action="php/Remove.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="removeStudentModalLabel">Remove Student(s)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Please select an option:</p>
                        <label>
                            <input type="radio" id="remove_4th" name="removechoice" value="option1" required> Remove
                            4th
                            Year
                        </label><br>
                        <label>
                            <input type="radio" id="remove_one" name="removechoice" value="option2" required> Remove
                            a student
                        </label><br><br>

                        <!-- Hidden fields that are shown based on radio selection -->
                        <div id="usnField" class="d-none">
                            <label for="usn">USN:</label>
                            <input type="text" name="usn" placeholder="Enter USN"><br><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" onclick="confirmation(event,'#removeStudentForm','remove student(s)')"
                            form="removeStudentForm" class="btn btn-danger">Remove</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editStudentForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Please select an option:</p>
                        <label>
                            <input type="radio" name="choice" value="updateStudent" required>
                            Update USN
                        </label><br>
                        <label>
                            <input type="radio" name="choice" value="editStudent" required>
                            Edit a student
                        </label><br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary closeEditModal"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button" id="continueEditBtn" class="btn btn-danger">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Import Data for updating the USN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateFileForm" method="POST" action="php/update.php" enctype="multipart/form-data">
                        <label for="ufile">Choose a text file:</label>
                        <input type="file" name="ufile" id="ufile" class="form-control" accept=".csv,.xlsx"
                            required><br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmation(event,'#updateFileForm','import file details')"
                        form="updateFileForm" class="btn btn-primary">Import</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EditOne Modal -->
    <div class="modal fade" id="editOneModal" tabindex="-1" aria-labelledby="editOneModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOneModalLabel">Edit Student Information</h5>
                    <button type="button" class="btn-close clearUsnField" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="editOneForm" method="post">
                        <div class="editUsnField">
                            <label for="usn">USN:</label>
                            <input type="text" name="usn" class="form-control edit_usn" style="width: 100%"
                                placeholder="Enter USN"><br>
                            <button type="button" id="processUSN" class="btn btn-primary">Proceed</button>
                        </div>
                        <div class="edit-one-modal d-none">
                            <div class="form-group mb-3">
                                <label for="name">Name:</label>
                                <input type="text" id="name_edit" name="name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="cyear">Year:</label>
                                <select name="cyear" id="cyear_edit" class="form-control" required>
                                    <option value="" selected disabled>Select Year</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phno">Contact no.:</label>
                                <input type="text" id="phno_edit" name="phno" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="block">Block:</label>
                                <select name="block" id="block_edit" class="form-control" required>
                                    <option value="" selected disabled>Select Block</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="room_edit">Room:</label>
                                <select name="room" id="room_edit" class="form-control" style="width: 100%" required>
                                    <option value="" selected disabled>Available Rooms</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer edit-one-footer d-none">
                            <button type="button" class="btn btn-secondary clearUsnField"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="submit_edit_btn"
                                onclick="confirmation(event,'#editOneForm','edit student details')"
                                class="btn btn-danger">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Format Modal -->
    <div class="modal" id="formatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Download Format</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div style="margin-left:15px">
                        <label for="importStudentFormat">Import Students Format:</label><br>
                        <img style="width: 250px; height: auto;margin-left:15px;"
                            src="res/import-students-format-ex.png">
                        <button type="button" style="margin-left:10px;margin-top:30px;" class="btn btn-primary"
                            id="importStudentFormat">Download</button>
                    </div><br>
                    <div style="margin-left:15px">
                        <label for="updateUsnFormat">Update USN Format:</label><br>
                        <img style="width: 250px; height: 77px;margin-left:15px" src="res/update-usn-format-ex.png">
                        <button type="button" style="margin-left:10px;margin-top:30px;" class="btn btn-primary"
                            id="updateUSNFormat">Download</button>
                    </div><br>
                    <div style="margin-left:15px">
                        <label for="importRoomFormat">Import Room Format:</label><br>
                        <img style="width: 250px; height: 77px;margin-left:15px" src="res/import-room-format-ex.png">
                        <button type="button" style="margin-left:10px;margin-top:30px;" class="btn btn-primary"
                            id="importRoomFormat">Download</button>
                    </div><br>
                </form>
                <div class="modal-footer">
                    <button type="button" id="closeFormatBtn" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/highcharts.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/warden_scripts.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap5.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/accessibility.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";

            if (message) {
                alert(message);
                <?php unset($_SESSION['message']); ?>  // Clear the session message
            }
        });
    </script>

</body>

</html>