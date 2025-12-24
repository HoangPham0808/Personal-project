<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
    <link rel="stylesheet" href="../CSS/sanpham.css">
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
            margin: 20px 0px 0px 0px ;
        }
        h3{
            margin-top: -10px;
            color:rgb(245, 158, 202);
        }
        a{
            text-decoration: none;
            color:rgb(245, 158, 202);
        }
        .active {
            color: #ff69b4;
            border-bottom: 3px solid #ff69b4;}
    </style>
</head>
<body>
    <nav class="nav">
        <a href="qlyproduct.php" class="nav-item">SẢN PHẨM</a>
        <a href="curtom.php" class="nav-item">KHÁCH HÀNG</a>
        <a href="qlydonhang.php" class="nav-item active">ĐƠN HÀNG</a>
        <a href="report_dtn.php" class="nav-item">BÁO CÁO-THỐNG KÊ</a>
    </nav>
    <div class="container">
    <h1>THÔNG TIN ĐƠN HÀNG</h1>
        <h3><a href="qlydonhang.php" class="active">MUA LẺ</a><a href="qlydonhang_cart.php">MUA NHIỀU</a></h3>
        <?php
        $connect = mysqli_connect('localhost', 'root', '', 'btl_php');
        if (!$connect) {
            die("Không kết nối: " . mysqli_connect_error());
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $action = $_POST['action'];

            if ($action === 'approve') {
                $orderQuery = "SELECT * FROM db_donhang WHERE mahd = '$orderId'";
                $orderResult = mysqli_query($connect, $orderQuery);
                $order = mysqli_fetch_assoc($orderResult);
                if ($order) {
                    $masp = $order['masp'];
                    $repairQuery = "SELECT * FROM db_baocaosp WHERE masp = '$masp'";
                    $repairResult = mysqli_query($connect, $repairQuery);
                    $repair = mysqli_fetch_assoc($repairResult);
                    $soluong = $order['soluong'];
                    $soluongdaban = $soluong; 
                    $soluongnhap=$repair['soluongnhap'];
                    $soluongcon=$repair['soluongcon'];
                    $soluongcon1 = $soluongcon- $soluongdaban; 
                    $dongia=$repair['dongia'];
                    $doanhthusp = $soluongdaban * $dongia;
                    $updateReportQuery = "
                        UPDATE db_baocaosp 
                        SET soluongnhap = '$soluongnhap', 
                            soluongdaban = soluongdaban + '$soluongdaban', 
                            soluongcon = '$soluongcon1', 
                            doanhthusp = doanhthusp + '$doanhthusp' 
                        WHERE masp = '$masp'";
                    mysqli_query($connect, $updateReportQuery);
                    $updateOrderQuery = "UPDATE db_donhang SET trangthai = 'Chờ giao hàng' WHERE mahd = '$orderId'";
                    mysqli_query($connect, $updateOrderQuery);
                }
            } elseif ($action === 'payment') {
                $orderQuery = "SELECT * FROM db_donhang WHERE mahd = '$orderId'";
                $orderResult = mysqli_query($connect, $orderQuery);
                $order = mysqli_fetch_assoc($orderResult);
                if ($order) {
                    $masp = $order['masp'];
                    $repairQuery = "SELECT * FROM db_baocaosp WHERE masp = '$masp'";
                    $repairResult = mysqli_query($connect, $repairQuery);
                    $repair = mysqli_fetch_assoc($repairResult);
                    $soluong = $order['soluong'];
                    $soluongdaban = $soluong; 
                    $soluongnhap=$repair['soluongnhap'];
                    $soluongcon=$repair['soluongcon'];
                    $soluongcon1 = $soluongcon- $soluongdaban; 
                    $dongia=$repair['dongia'];
                    $doanhthusp = $soluongdaban * $dongia;
                    $updateReportQuery = "
                        UPDATE db_baocaosp 
                        SET soluongnhap = '$soluongnhap', 
                            soluongdaban = soluongdaban + '$soluongdaban', 
                            soluongcon = '$soluongcon1', 
                            doanhthusp = '$doanhthusp' 
                        WHERE masp = '$masp'";
                    mysqli_query($connect, $updateReportQuery);
                $updateQuery = "UPDATE db_donhang SET trangthai = 'Chờ giao hàng' WHERE mahd = '$orderId'";
                mysqli_query($connect, $updateQuery);
            }
        } elseif ($action === 'deliver') {
            $orderQuery = "SELECT * FROM db_donhang WHERE mahd = '$orderId'";
            $orderResult = mysqli_query($connect, $orderQuery);
            $order = mysqli_fetch_assoc($orderResult);
            if ($order) {
                $thanhtien = $order["thanhtien"];
                $ngay=$order['ngaylap'];
                $username= $order["username"];
                $updatecurtomQuery="UPDATE db_khachhang SET diemso=diemso+'$thanhtien'/1000 WHERE username='$username' ";
                mysqli_query($connect, $updatecurtomQuery);
                $doanhthungayQuery = "SELECT doanhthungay FROM db_baocao_doanhthungay WHERE ngay = '$ngay'";
                $doanhthungayResult = mysqli_query($connect, $doanhthungayQuery);
                if ($doanhthungayResult) {
                    $doanhthungayRow = mysqli_fetch_assoc($doanhthungayResult);
                    if ($doanhthungayRow) {
                        $newdoanhthu = $doanhthungayRow['doanhthungay'] + $thanhtien;
                        $updateSql = "UPDATE db_baocao_doanhthungay SET doanhthungay = ? WHERE ngay = ?";
                        $updateStmt = $connect->prepare($updateSql);
                        $updateStmt->bind_param("ds", $newdoanhthu, $ngay);
                        
                        if ($updateStmt->execute()) {
                            echo "Cập nhật doanh thu thành công.";
                        } else {
                            echo "Lỗi cập nhật: " . $updateStmt->error;
                        }
                    } else {
                        $insertSql = "INSERT INTO db_baocao_doanhthungay (ngay, doanhthungay) VALUES (?, ?)";
                        $insertStmt = $connect->prepare($insertSql);
                        $insertStmt->bind_param("sd", $ngay, $thanhtien);        
                        if ($insertStmt->execute()) {
                            echo "Chèn doanh thu thành công.";
                        } else {
                            echo "Lỗi chèn: " . $insertStmt->error;
                        }
                    }

                } else {
                    echo "Lỗi truy vấn: " . mysqli_error($connect);
                }
                $updateQuery = "UPDATE db_donhang SET trangthai = 'Đã giao hàng' WHERE mahd = '$orderId'";
                mysqli_query($connect, $updateQuery);
            }
        }
        }
        $query = "SELECT * FROM db_donhang";
        $result = mysqli_query($connect, $query);
        if (!$result) {
            die("Query failed: " . mysqli_error($connect));
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>Mã Đơn hàng</th>
                    <th>Username</th>
                    <th>Mã sản phẩm</th>
                    <th>Họ tên đặt</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Ngày lập</th>
                    <th>Ghi chú</th>
                    <th>Phí Ship</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thực thi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['mahd']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['masp']); ?></td>
                    <td><?php echo htmlspecialchars($row['hotendat']); ?></td>
                    <td><?php echo htmlspecialchars($row['sdt']); ?></td>
                    <td><?php echo htmlspecialchars($row['diachi']); ?></td>
                    <td><?php echo htmlspecialchars($row['tensp']); ?></td>
                    <td><?php echo htmlspecialchars($row['soluong']); ?></td>
                    <td><?php echo htmlspecialchars($row['ngaylap']); ?></td>
                    <td><?php echo htmlspecialchars($row['ghichu']); ?></td>
                    <td><?php echo htmlspecialchars($row['phiship']); ?></td>
                    <td><?php echo htmlspecialchars($row['thanhtien']); ?></td>
                    <td><?php echo htmlspecialchars($row['trangthai']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['mahd']); ?>">
                            <?php if ($row['trangthai'] === 'Chờ duyệt') { ?>
                                <button type="submit" name="action" value="approve">Duyệt</button>
                            <?php } elseif ($row['trangthai'] === 'Đang thanh toán') { ?>
                                <button type="submit" name="action" value="payment">Đã thanh toán</button>
                            <?php } elseif ($row['trangthai'] === 'Chờ giao hàng') { ?>
                                <button type="submit" name="action" value="deliver">Đã giao hàng</button>
                            <?php } ?>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>