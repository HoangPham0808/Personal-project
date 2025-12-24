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
$passwordMessage = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $result = $conn->query("SELECT password FROM db_taikhoan WHERE username = '$username'");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $passwordMessage = "Mật khẩu của bạn là: <strong>{$row['password']}</strong>"; 
    } else {
        $passwordMessage = "<strong style='color: red;'>Tên đăng nhập không đúng.</strong>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lấy Lại Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: rgb(242, 166, 219);
            background-image: url('picture/ss.png'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
         }
        .container {
            width: 350px;
            padding: 20px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            background-color: #f8f8f8;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: white;
            padding: 10px;
            background-color: #ff6798;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 20px -20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            font-weight: bold;
        }
        input {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
            box-sizing: border-box;
        }
        button {
            padding: 10px;
            font-size: 16px;
            color: white;
            background-color: #4caf50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message {
            margin-top: 20px;
            text-align: center;
        }
        .back-button {
            margin-top: 15px;
            text-align: center;
        }
        .back-button a {
            text-decoration: none;
            color: #4caf50;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">Lấy Lại Mật Khẩu</div>
    <form method="post">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Lấy Lại Mật Khẩu</button>
    </form>
    
    <div class="message">
        <?php if ($passwordMessage): ?>
            <?php echo $passwordMessage; ?>
        <?php endif; ?>
    </div>

    <div class="back-button">
        <a href="login.php">Quay lại trang đăng nhập</a>
    </div>
</div>

</body>
</html>