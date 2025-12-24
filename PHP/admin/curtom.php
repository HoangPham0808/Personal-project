<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin khách hàng</title>
    <link rel="stylesheet" href="CSS/sanpham.css">
    <style>
        * {
            padding: 10px;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .nav {
            display: flex;
            gap: 20px;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        .nav-item {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            padding: 10px 15px;
            transition: color 0.3s;
        }
        .nav-item.active {
            color: #ff69b4;
            border-bottom: 2px solid #ff69b4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 5px;
        }
        h1 {
            color: #ff69b4;
            margin: 20px 0;
        }
        .delete-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .delete-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <nav class="nav">
        <a href="qlyproduct.php" class="nav-item">SẢN PHẨM</a>
        <a href="curtom.php" class="nav-item active">KHÁCH HÀNG</a>
        <a href="qlydonhang.php" class="nav-item">ĐƠN HÀNG</a>
        <a href="report_dtn.php" class="nav-item">BÁO CÁO-THỐNG KÊ</a>
    </nav>
    <div class="container">
        <h1>Thông tin khách hàng</h1>
        <?php
        $connect = mysqli_connect('localhost', 'root', '', 'btl_php');
        if (!$connect) {
            die("Không kết nối: " . mysqli_connect_error());
        }
        if (isset($_POST['delete']) && isset($_POST['makh'])) {
            $makh = $_POST['makh'];
            $sql = "SELECT username FROM db_khachhang WHERE makh = '$makh'";
            $result = mysqli_query($connect, $sql);
            $row = mysqli_fetch_assoc($result);
            $username = $row['username'];
            mysqli_query($connect, "DELETE FROM db_khachhang WHERE makh = '$makh'");
            if ($username) {
                mysqli_query($connect, "DELETE FROM db_taikhoan WHERE username = '$username'");
            }
            header("Location: khachhang.php");
            exit();
        }
        $sql = "SELECT kh.makh, kh.hoten, kh.sdt, kh.email, kh.diemso, kh.username, tk.password
                FROM db_khachhang kh
                LEFT JOIN db_taikhoan tk ON kh.username = tk.username";
        $result = mysqli_query($connect, $sql);
        ?>
        <table>
            <thead>
                <tr>
                    <th>Mã khách hàng</th>
                    <th>Họ tên</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th>Điểm số</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['makh']); ?></td>
                    <td><?php echo htmlspecialchars($row['hoten']); ?></td>
                    <td><?php echo htmlspecialchars($row['sdt']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['diemso']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['password']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="makh" value="<?php echo htmlspecialchars($row['makh']); ?>">
                            <button type="submit" name="delete" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
mysqli_close($connect);
?>