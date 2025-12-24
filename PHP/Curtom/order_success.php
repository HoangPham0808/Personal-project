<?php
    session_start();
    include 'username.php';
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "btl_php";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $madonhang = isset($_GET['madonhang']) ? $_GET['madonhang'] : '';
    if(empty($madonhang)) {
        header("Location: Home.php");
        exit();
    }
    $sql_order = "SELECT * FROM db_donhang_giohang WHERE madonhang = '$madonhang'";
    $result_order = $conn->query($sql_order);
    if($result_order->num_rows == 0) {
        header("Location: Home.php");
        exit();
    }
    $order = $result_order->fetch_assoc();
    $sql_items = "SELECT * FROM db_chitietdonhang WHERE madonhang = '$madonhang'";
    $result_items = $conn->query($sql_items);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Đặt hàng thành công</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin-top: 200px;
            line-height: 1.5;
        }
        .order-success {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ff3f8f;
            border-radius: 10px;
            background-color: white;
            margin-bottom: 10px;
        }
        .order-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .order-items th, .order-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .order-items th {
            background-color: #ff3f8f;
            color: white;
        }
        .success-icon {
            color: #4CAF50;
            font-size: 48px;
            text-align: center;
            margin-bottom: 20px;
        }
        .continue-shopping {
            text-align: center;
            margin-top: 20px;
        }
        .continue-btn {
            background-color: #ff3f8f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<header>
    <table>
        <tr>
            <td>
                <div class="logo">
                    <img src="../babythree.png" alt="Baby Three Shop logo"/>
                </div>
            </td>
            <td>
                <div class="search-container" style="margin-right: -50px; margin-top: 10px;">  
                    <form method="GET" action="product.php" id="searchForm">
                        <div class="search-bar">
                            <input type="text" name="search" class="search-input" placeholder="Nhập sản phẩm cần tìm" required>
                            <span class="search-icon" onclick="document.getElementById('searchForm').submit();">🔍</span>
                        </div>
                    </form>
                    <span class="phone-icon"><img src="../Picture/icon/phonePink.png" style="width: 40px; height: auto;" alt=""></span>
                    <span class="phone-number">096.451.73340</span>
                </div>
            </td>
            <td class="td1" >
                <table style="margin-top: -40px; margin-right: 120px;">
                    <tr>
                        <td >
                            <div class="user-dropdown">
                                <button class="end">
                                    <input type="type" value="<?php include 'username.php'; if (!empty($userData['username'])) {echo "Chào mừng, " . htmlspecialchars($userData['username']);} else {echo "Đăng nhập";} ?>">
                                </button>
                                <div class="dropdown-content">
                                    <?php if (!empty($userData['username'])): ?>
                                        <a href="order.php">Trạng thái đơn hàng</a>
                                        <a href="login.php">Đăng xuất</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="cart.php"><img src="../Picture/icon/shopingPink.png" style="margin-top: -20px; width: 40px; height: auto; margin-left: -90px;" alt="Payment Methods" /></a>
                        </td>
                    </tr>
                </table>
                </td>
        </tr>
    </table>
    <div class="navbar">
        <a class="nav-item" href="Home.php">TRANG CHỦ</a>
        <a href="product.php" class="nav-item">SẢN PHẨM</a>
        <a href="introduce.php" class="nav-item">GIỚI THIỆU</a>
        <a href="sevice.php" class="nav-item">DỊCH VỤ</a>
        <a href="contact.php" class="nav-item">LIÊN HỆ</a>
    </div>
    <div class="notification">
        <span class="highlight">Baby Three chính hãng từ 350 – 1m2 – 1m5 đến 2m tại Baby Three Store. Khám phá bộ sưu tập gấu Baby Three đẹp và chất lượng cao, món quà tuyệt vời cho người thân yêu của bạn.</span>
    </div>
</header>
<body>
    <div class="order-success">
        <div class="order-header">
            <div class="success-icon">✓</div>
            <h2>Đặt hàng thành công!</h2>
            <p>Mã đơn hàng của bạn: <strong><?php echo $madonhang; ?></strong></p>
        </div>
        
        <div class="order-details">
            <h3>Thông tin đơn hàng</h3>
            <p><strong>Người nhận:</strong> <?php echo $order['tennguoinhan']; ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo $order['sodienthoai']; ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo $order['diachi']; ?>
            <p><strong>Phương thức vận chuyển:</strong> <?php echo $order['phuongthucgiaohang']; ?></p>
            <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['ngaydat'])); ?></p>
        </div>
        
        <h3>Sản phẩm đã đặt</h3>
        <table class="order-items">
            <tr>
                <th>Sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
            <?php while($item = $result_items->fetch_assoc()): ?>
            <tr>
                <td><?php echo $item['tensp']; ?></td>
                <td><?php echo number_format($item['dongia'], 0, ',', '.') . ' đ'; ?></td>
                <td><?php echo $item['soluong']; ?></td>
                <td><?php echo number_format($item['thanhtien'], 0, ',', '.') . ' đ'; ?></td>
            </tr>
            <?php endwhile; ?>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Tổng tiền sản phẩm:</strong></td>
                <td><?php echo number_format($order['tongtien'], 0, ',', '.') . ' đ'; ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Phí vận chuyển:</strong></td>
                <td><?php echo number_format($order['phiship'], 0, ',', '.') . ' đ'; ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Tổng thanh toán:</strong></td>
                <td><strong><?php echo number_format($order['thanhtien'], 0, ',', '.') . ' đ'; ?></strong></td>
            </tr>
        </table>
        
        <div class="continue-shopping">
            <p style="padding: 20px;">Cảm ơn bạn đã mua hàng tại Baby Three! Chúng tôi sẽ sớm liên hệ để xác nhận đơn hàng của bạn.</p>
            <a style="margin-top: 50px;" href="Home.php" class="continue-btn">Tiếp tục mua sắm</a>
        </div>
    </div>
</body>
<footer>
    <table>
        <tr>
            <td class="td1">
                <table class="td1">
                    <tr>
                        <td><img src="../babythree.png" style="margin-left: 45px; height: 100px" alt="Baby Three Shop Logo" /></td>
                        <td><a style="margin-left: -280px;">"Mở hộp là yêu - Sự tầm là mê"</a></td>
                    </tr>
                    <tr> 
                        <td colspan="2">
                        <p class="info-description">
                            Baby Three, là dòng búp bê sưu tầm xuất xứ từ Trung Quốc, ra mắt lần đầu vào tháng 5 năm 2024. Sản phẩm này nhanh chóng thu hút sự quan tâm của giới trẻ Việt Nam nhờ thiết kế độc đáo và hình thức bán hàng "hộp mù" (blind box), nơi người mua không biết trước mình sẽ nhận được mẫu búp bê nào.
                        </p>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <div class="contact-info">
                    <h3 style="margin-bottom: 10px;">GIỚI THIỆU VÀ LIÊN HỆ</h3>
                    <div class="contact-item">
                        <img src="../Picture/icon/white-phone.png"  alt="Phone Icon" class="icon" />
                        <span>096.451.7330</span>
                    </div>
                    <div class="contact-item">
                        <img src="../Picture/icon/email-white.png" alt="Email Icon" class="icon" />
                        <span>shopbb3.cskh@gmail.com</span>
                    </div>
                    <table style="margin-top: 20px;">
                        <tr>
                            <td><img src="../Picture/icon/icon-fb.png" style="margin-right: 5px" alt="Phone Icon" class="icon1" /></td>
                            <td><img src="../Picture/icon/icon-tiktok.png" style="margin-right: 5px;"  alt="Phone Icon" class="icon1" /></td>
                            <td><img src="../Picture/icon/icon-ig.png" style="margin-right: 5px;" alt="Phone Icon" class="icon1" /></td>
                            <td><img src="../Picture/icon/icon-shopee.png" style="margin-right: 5px;" alt="Phone Icon" class="icon1" /></td>
                            <td><img src="../Picture/icon/icon-zalo.png" style="margin-right: 45px;" alt="Phone Icon" class="icon1" /></td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                <div class="contact-info1">
                    <h3 style="margin-bottom: 10px;">HỖ TRỢ KHÁCH HÀNG</h3>
                    <div class="contact-item">
                    <p>Trung tâm hỗ trợ: 096.451.7330</p>
                    </div>
                    <div class="contact-item">
                    <p>Mua hàng Bảo hành: 096.131.8988</p>
                </div>
            </td>
            <td>
                <div class="contact-info1">
                    <h3 style="margin-bottom: 10px;">PHƯƠNG THỨC THANH TOÁN</h3>
                    <div class="contact-item" style="margin-left: 80px;">
                        <img src="../Picture/icon/icon-card.png" alt="Payment Methods" />
                        <img src="../Picture/icon/icon-money.png" style="margin-left:20px;" alt="Payment Methods" />
                    </div>
                </div>
            </td>
        </tr>
    </table>
</footer>
</html>
<?php
$conn->close();
?>