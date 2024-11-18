<?php

$servername = "localhost";
$username = "skalic06"; 
$password = "kuBkO7426951+"; 
$dbname = "skalic06";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    exit("Připojení k databázi selhalo: " . $conn->connect_error);
}
?>