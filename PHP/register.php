<?php
session_start();
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "btl_php"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['reg_username'];
    $password = $_POST['reg_password'];
    $makh = $_POST['my_id'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $chucvu="Khách hàng";
    $stmt = $conn->prepare("SELECT * FROM db_taikhoan WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Tên đăng nhập đã được sử dụng.');</script>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM db_khachhang WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "<script>alert('Email đã được sử dụng.');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO db_taikhoan (username, password,chucvu) VALUES (?, ?,?)");
            $stmt->bind_param("sss", $username, $password,$chucvu);
            if ($stmt->execute()) {
                $stmt = $conn->prepare("INSERT INTO db_khachhang (makh, username, hoten, sdt, email) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $makh, $username, $fullname, $phone, $email);

                if ($stmt->execute()) {
                    echo "<script>alert('Đăng ký thành công!'); location.href='login.php';</script>";
                } else {
                    echo "<script>alert('Lỗi khi thêm vào bảng khách hàng.');</script>";
                }
            } else {
                echo "<script>alert('Lỗi khi thêm vào bảng tài khoản.');</script>";
            }
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: rgb(242, 166, 219);
            background-image: url('Picture/ss.png'); 
            background-size: cover; 
            background-position: center;
            background-repeat: no-repeat;
         }
        .container {
            width: 400px;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #FF69B4;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 2em;
            font-weight: bold;
        }
        form {
            padding: 20px;
            background-color: #e6e6e6;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        label {
            width: 140px;
            text-align: right;
            margin-right: 15px;
            color: #333;
            font-size: 1em;
        }
        input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        button[type="submit"] {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1em;
            margin-bottom: 15px;
        }
        .toggle-login {
            text-align: center;
            margin-top: 10px;
        }
        .toggle-login button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 0.9em;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Đăng ký</div>
        <form method="post" action="">
            <div class="form-group">
                <label for="reg_username">Tên đăng nhập:</label>
                <input type="text" id="reg_username" name="reg_username" required>
            </div>

            <div class="form-group">
                <label for="reg_password">Mật khẩu:</label>
                <input type="password" id="reg_password" name="reg_password" required>
            </div>

            <div class="form-group">
                <label for="my_id">Mã khách hàng:</label>
                <input type="text" id="my_id" name="my_id" required>
            </div>

            <div class="form-group">
                <label for="fullname">Họ tên:</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="buttons">
                <button type="submit">Đăng ký</button>
                <div class="toggle-login">
                    <span>Đã có tài khoản?</span>
                    <button type="button" onclick="location.href='login.php'">Đăng nhập</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>