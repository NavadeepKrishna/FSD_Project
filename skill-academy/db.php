<?php
$conn = new mysqli("localhost", "root", "", "skill_academy", 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>