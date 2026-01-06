<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "ayurpredict"; // Make sure this matches your DB name

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}