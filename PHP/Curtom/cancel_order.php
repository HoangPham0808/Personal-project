<?php
session_start();
include 'username.php';
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['madonhang'])) {
    $madonhang = $_POST['madonhang'];
    $servername = "localhost";
    $db_username = "root";
    $password = "";
    $dbname = "btl_php";
    $conn = new mysqli($servername, $db_username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    $query_check = "SELECT * FROM db_donhang_giohang WHERE madonhang = ? AND username = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("ss", $madonhang, $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        $order = $result_check->fetch_assoc();
        if ($order['trangthai'] == 'Chờ xác nhận') {
            $query_update = "UPDATE db_donhang_giohang SET trangthai = 'Đã hủy' WHERE madonhang = ? AND username = ?";
            $stmt_update = $conn->prepare($query_update);
            $stmt_update->bind_param("ss", $madonhang, $username);
            if ($stmt_update->execute()) {
                $_SESSION['success_message'] = "Đơn hàng đã được hủy thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi hủy đơn hàng. Vui lòng thử lại sau!";
            }
            $stmt_update->close();
        } else {
            $_SESSION['error_message'] = "Chỉ có thể hủy đơn hàng ở trạng thái chờ xác nhận!";
        }
    } else {
        $_SESSION['error_message'] = "Không tìm thấy đơn hàng hoặc bạn không có quyền hủy đơn hàng này!";
    }
    $stmt_check->close();
    $conn->close();
    header("Location: order.php");
    exit();
} else {
    header("Location: order.php");
    exit();
}
?>