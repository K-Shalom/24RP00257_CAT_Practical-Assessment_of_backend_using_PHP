<?php

$conn = new mysqli("localhost", "root", "", "library_ms");
if ($conn->connect_error) {
    die('Database connection failed');
}
$conn->set_charset('utf8mb4');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>