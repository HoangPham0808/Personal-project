<?php
session_start();
$servername = "localhost"; // Thay đổi nếu cần
$username = "root"; // Thay đổi nếu cần
$password = ""; // Thay đổi nếu cần
$dbname = "btl_php"; // Tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    $stmt = $conn->prepare("SELECT password, chucvu FROM db_taikhoan WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['username'] = $username;
            $_SESSION['chucvu'] = $row['chucvu'];
            include 'Curtom/username.php';
            if ($row['chucvu'] === 'Khách hàng') {
                header("Location:Curtom/Home.php");
            } elseif ($row['chucvu'] === 'Quản lý') {
                header("Location: admin/qlyproduct.php");
            }
            exit();
        }
    }
    echo "<script>alert('Tên đăng nhập hoặc mật khẩu không đúng.');</script>";
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: rgb(255, 3, 3);
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ccc;
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
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: white;
            box-sizing: border-box;
            font-size: 16px;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .forgot-password {
            color: #666;
            text-decoration: none;
        }
        .login-button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            margin: 15px 0;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Đăng nhập</div>
        <form method="post" action="">
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder= "Tên đăng nhập" required value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Mật khẩu" required value="<?php echo htmlspecialchars($password); ?>">
            </div>
            <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember" <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?>>
                    <label for="remember">Nhớ mật khẩu</label>
                </div>
                <a href="forgot.php" class="forgot-password">Quên mật khẩu ?</a>
            </div>
            <button type="submit" class="login-button" >Đăng Nhập</button>
            <div class="register-link">
                <span>Bạn chưa có tài khoản? </span>
                <a href="register.php">Đăng ký</a>
            </div>
        </form>
    </div>
</body>
</html>