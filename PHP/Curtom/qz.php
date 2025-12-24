<?php
session_start();
include 'username.php'; 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btl_php"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'do_something') {
        $username1=$userData['username'];
        $masp = isset($_POST['masp1']) ? $_POST['masp1'] : null;
        $tensp = isset($_POST['tensp1']) ? $_POST['tensp1'] : null;
        $dongia = isset($_POST['dongia1']) ? $_POST['dongia1'] : null;
        $soluong = isset($_POST['soluong1']) ? $_POST['soluong1'] : null;
        $hotendat = isset($_POST['hoten']) ? $_POST['hoten'] : null;
        $sdt = isset($_POST['sdt']) ? $_POST['sdt'] : null;
        $diachicuthe = isset($_POST['diachi']) ? $_POST['diachi'] : null;
        $ward = isset($_POST['ward']) ? $_POST['ward'] : null;
        $district = isset($_POST['district']) ? $_POST['district'] : null;
        $city = isset($_POST['city']) ? $_POST['city'] : null;
        $diachi = "($diachicuthe) $ward, $district, $city";
        $ngaylap = Date('Y-m-d H:i:s');
        $ghichu = isset($_POST['ghichu']) ? $_POST['ghichu'] : null;
        $phiship = isset($_POST['phiship']) ? $_POST['phiship'] : null;
        $initialTotal = $dongia * $soluong + $phiship;
        $trangthai="Đang thanh toán";
        $insertSql = "INSERT INTO db_donhang (username, masp, hotendat, sdt, diachi, tensp, soluong, ngaylap, ghichu, phiship, thanhtien,trangthai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ssssssissdds", $username1, $masp, $hotendat, $sdt, $diachi, $tensp, $soluong, $ngaylap, $ghichu, $phiship, $initialTotal,$trangthai);
            if ($insertStmt->execute()) {
                header("Location: Home.php");
            } else {
                echo "Lỗi: " . $insertStmt->error;
            }
            $insertStmt->close();
    }
    else{
            echo "hh";
    }
}