<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btl_php";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $action = $_POST['action'];
    if ($action === 'approve') {
        $updateOrderQuery = "UPDATE db_donhang_giohang SET trangthai = 'Chờ giao hàng' WHERE madonhang = '$orderId'";
        if ($conn->query($updateOrderQuery) === TRUE) {
            $productsQuery = "SELECT * FROM db_chitietdonhang WHERE madonhang = '$orderId'";
            $productsResult = $conn->query($productsQuery);        
            if ($productsResult->num_rows > 0) {
                while ($product = $productsResult->fetch_assoc()) {
                    $masp = $product['masp'];
                    $soluong = $product['soluong'];
                    $dongia = $product['dongia'];
                    $doanhthusp = $soluong * $dongia;
                    $repairQuery = "SELECT * FROM db_baocaosp WHERE masp = '$masp'";
                    $repairResult = $conn->query($repairQuery);
                    if ($repairResult->num_rows > 0) {
                        $repair = $repairResult->fetch_assoc();
                        $soluongnhap = $repair['soluongnhap'];
                        $soluongcon = $repair['soluongcon'];
                        $soluongcon1 = $soluongcon - $soluong;
                        $updateReportQuery = "
                            UPDATE db_baocaosp 
                            SET soluongnhap = '$soluongnhap', 
                                soluongdaban = soluongdaban + '$soluong', 
                                soluongcon = '$soluongcon1', 
                                doanhthusp = doanhthusp + '$doanhthusp' 
                            WHERE masp = '$masp'";
                        $conn->query($updateReportQuery);
                    }
                }
            }
        }
    } elseif ($action === 'deliver') {
        $orderQuery = "SELECT * FROM db_donhang_giohang WHERE madonhang = '$orderId'";
        $orderResult = $conn->query($orderQuery);      
        if ($orderResult->num_rows > 0) {
            $order = $orderResult->fetch_assoc();
            $thanhtien = $order["thanhtien"];
            $ngaydat = $order['ngaydat'];
            $username = $order["username"];
            $updateCustomerQuery = "UPDATE db_khachhang SET diemso = diemso + '$thanhtien'/1000 WHERE username = '$username'";
            $conn->query($updateCustomerQuery);
            $ngay = date('Y-m-d', strtotime($ngaydat));
            $doanhthungayQuery = "SELECT doanhthungay FROM db_baocao_doanhthungay WHERE ngay = '$ngay'";
            $doanhthungayResult = $conn->query($doanhthungayQuery); 
            if ($doanhthungayResult->num_rows > 0) {
                $doanhthungayRow = $doanhthungayResult->fetch_assoc();
                $newdoanhthu = $doanhthungayRow['doanhthungay'] + $thanhtien;
                $updateSql = "UPDATE db_baocao_doanhthungay SET doanhthungay = '$newdoanhthu' WHERE ngay = '$ngay'";
                $conn->query($updateSql);
            } else {
                $insertSql = "INSERT INTO db_baocao_doanhthungay (ngay, doanhthungay) VALUES ('$ngay', '$thanhtien')";
                $conn->query($insertSql);
            }
            $updateQuery = "UPDATE db_donhang_giohang SET trangthai = 'Đã giao hàng' WHERE madonhang = '$orderId'";
            $conn->query($updateQuery);
        }
    }
    header("Location: qlydonhang_cart.php");
    exit();
}
$sql = "SELECT dh.id_donhang, dh.madonhang, dh.username, dh.ngaydat, dh.tongtien, 
               dh.phiship, dh.thanhtien, dh.trangthai, dh.tennguoinhan, dh.sodienthoai, 
               dh.diachi, dh.phuongthucgiaohang, dh.ghichu,
               ct.id_chitiet, ct.madonhang as ct_madonhang, ct.masp, ct.tensp, ct.soluong, 
               ct.dongia, ct.thanhtien as ct_thanhtien
        FROM db_donhang_giohang dh
        JOIN db_chitietdonhang ct ON dh.madonhang = ct.madonhang
        ORDER BY dh.ngaydat DESC";
$result = $conn->query($sql);
$orderItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!isset($orderItems[$row['madonhang']])) {
            $orderItems[$row['madonhang']] = [
                'info' => [
                    'madonhang' => $row['madonhang'],
                    'username' => $row['username'],
                    'ngaydat' => $row['ngaydat'],
                    'tongtien' => $row['tongtien'],
                    'phiship' => $row['phiship'],
                    'thanhtien' => $row['thanhtien'],
                    'tennguoinhan' => $row['tennguoinhan'],
                    'sodienthoai' => $row['sodienthoai'],
                    'diachi' => $row['diachi'],
                    'phuongthucgiaohang' => $row['phuongthucgiaohang'],
                    'trangthai' => $row['trangthai'],
                    'ghichu' => $row['ghichu']
                ],
                'products' => []
            ];
        }     
        $orderItems[$row['madonhang']]['products'][] = [
            'masp' => $row['masp'],
            'tensp' => $row['tensp'],
            'soluong' => $row['soluong'],
            'dongia' => $row['dongia'],
            'thanhtien' => $row['ct_thanhtien']
        ];
    }
}
?>
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
        }
        th, td {
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
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
        <h3><a href="qlydonhang.php">MUA LẺ</a><a class="active" href="qlydonhang_cart.php">MUA NHIỀU</a></h3>
        <?php if (!empty($orderItems)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Mã Đơn hàng</th>
                    <th>Username</th>
                    <th>Họ tên đặt</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Phương thức giao hàng</th>
                    <th>Ngày lập</th>
                    <th style="width: 300px;">Thông tin đơn hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thực thi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $orderCode => $order) { ?>
                <tr>
                    <td><?php echo $order['info']['madonhang']; ?></td>
                    <td><?php echo $order['info']['username']; ?></td>
                    <td><?php echo $order['info']['tennguoinhan']; ?></td>
                    <td><?php echo $order['info']['sodienthoai']; ?></td>
                    <td><?php echo $order['info']['diachi']; ?></td>
                    <td><?php echo $order['info']['phuongthucgiaohang']; ?></td>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($order['info']['ngaydat'])); ?></td>
                    <td>
                        <table>
                            <thead>
                                <th>Mã SP</th>
                                <th>Tên SP</th>
                                <th>SL</th>
                                <th>Đơn giá</th>
                            </thead>
                            <tbody>
                                <?php foreach ($order['products'] as $product) { ?>
                                <tr>
                                    <td><?php echo $product['masp']; ?></td>
                                    <td><?php echo $product['tensp']; ?></td>
                                    <td><?php echo $product['soluong']; ?></td>
                                    <td><?php echo number_format($product['dongia'], 0, ',', '.'); ?> ₫</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </td>
                    <td><?php echo number_format($order['info']['thanhtien'], 0, ',', '.'); ?> ₫</td>
                    <td><?php echo $order['info']['trangthai']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $order['info']['madonhang']; ?>">
                            <?php if ($order['info']['trangthai'] === 'Chờ xác nhận') { ?>
                                <button type="submit" name="action" value="approve">Duyệt</button>
                            <?php } elseif ($order['info']['trangthai'] === 'Chờ giao hàng') { ?>
                                <button type="submit" name="action" value="deliver">Đã giao hàng</button>
                            <?php } ?>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="no-orders">
            <p>Không có đơn hàng nào.</p>
        </div>
        <?php } ?>
    </div>
    <?php $conn->close(); ?>
</body>
</html>