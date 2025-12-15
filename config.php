<?php
$host = 'sql201.infinityfree.com';
$user = 'if0_40667665';
$pass = 'AZ9zxaFqDVxds';
$db_name = 'if0_40667665_football';
error_reporting(E_ALL);
ini_set('display_errors', 1);
$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

session_start();

function formatPrice($price)
{
    return number_format($price, 0, '', '.') . ' VNĐ';
}
