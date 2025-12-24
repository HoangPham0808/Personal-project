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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $pttt = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;
        if($pttt==='bank_transfer'){
            echo '<div style="text-align: center; margin-top: 20px;">';
            echo '<img src="../Picture/img/qz.png" alt="Hình ảnh mô tả" style="max-width: 100%; height: auto;">';
            echo '<br>';
            echo '<form action="qz.php" method="post">';
            echo '<input type="hidden" name="action" value="do_something">';
            echo '<input type="hidden" name="masp1" value="' . htmlspecialchars($masp) . '">';
                echo '<input type="hidden" name="tensp1" value="' . htmlspecialchars($tensp) . '">';
                echo '<input type="hidden" name="dongia1" value="' . htmlspecialchars($dongia) . '">';
                echo '<input type="hidden" name="soluong1" value="' . htmlspecialchars($soluong) . '">';
                echo '<input type="hidden" name="hoten" value="' . htmlspecialchars($hotendat) . '">';
                echo '<input type="hidden" name="sdt" value="' . htmlspecialchars($sdt) . '">';
                echo '<input type="hidden" name="diachi" value="' . htmlspecialchars($diachicuthe) . '">';
                echo '<input type="hidden" name="ward" value="' . htmlspecialchars($ward) . '">';
                echo '<input type="hidden" name="district" value="' . htmlspecialchars($district) . '">';
                echo '<input type="hidden" name="city" value="' . htmlspecialchars($city) . '">';
                echo '<input type="hidden" name="ghichu" value="' . htmlspecialchars($ghichu) . '">';
                echo '<input type="hidden" name="phiship" value="' . htmlspecialchars($phiship) . '">';
            echo '<button type="submit" style="padding: 10px 20px; background-color: #ff69b4; color: white; border: none; border-radius: 5px; cursor: pointer;">Thanh toán thành công</button>';
            echo '</form>';
            echo '</div>';
        }
        elseif($pttt==='cod'){
            $trangthai="Chờ duyệt";
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
}

$conn->close();
?>