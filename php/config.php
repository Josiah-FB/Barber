<?php

$db_name ="mysql:host=localhost;dbname=booking";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username, $password);

if (!$conn) {
    die("Connection Failed". mysqli_connect_error());
}

?>