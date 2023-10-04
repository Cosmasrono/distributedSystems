<?php
$db_host = "localhost"; // or the hostname of your database server
$db_user = "root";
$db_pass = "";
$db_name = "distributed"; // the name of your database

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
