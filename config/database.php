<?php
// config/database.php — not used yet. Required from the database stage onward.
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'itc_ratnadipa';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
