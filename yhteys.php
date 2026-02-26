<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drinkityan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>