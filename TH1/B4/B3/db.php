<?php
$mysqli = new mysqli("localhost", "root", "Ngdinhkha@1", "sinhvien"); 
$mysqli->set_charset("utf8mb4");

if ($mysqli->connect_errno) {
    die("Lỗi kết nối MySQL: " . $mysqli->connect_error);
}
?>
