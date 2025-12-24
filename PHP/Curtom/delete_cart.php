<?php 
session_start();
include 'username.php'; 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btl_php"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $masp = $_POST['masp'];
    $username1 = $userData['username']; 
    if (isset($masp) && !empty($masp)) {
        $sql_delete = "DELETE FROM db_giohang WHERE masp = '$masp' AND username = '$username1'";
        if ($conn->query($sql_delete) === TRUE) {
            header("Location: cart.php");
        } else {
            echo "Lỗi: " . $conn->error;
        }
    }
}
$conn->close();
?>