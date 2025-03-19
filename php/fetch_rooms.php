<?php
include 'db_connection.php';

$sql = 'SELECT DISTINCT room FROM rooms';
$result = $conn->query($sql);

if($result->num_rows>0){
    while($row = $result->fetch_assoc()){
        echo '<option value="'.$row['room'].'">'.$row['room'].'<option>';
    }
}