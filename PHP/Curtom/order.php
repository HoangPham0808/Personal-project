<?php 
    session_start();
    include 'username.php'; 
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        header("Location: login.php");
        exit();
    }   
    $servername = "localhost";
    $db_username = "root";
    $password = "";
    $dbname = "btl_php"; 
    
    $conn = new mysqli($servername, $db_username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    $query = "SELECT * FROM db_donhang_giohang WHERE username = ? ORDER BY ngaydat DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Đơn Hàng Của Tôi - Baby Three Shop</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff; 
            margin-top: 170px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }      
        .page-title {
            color: #ff3f8f;
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }   
        .order-list {
            width: 100%;
        }       
        .order-item {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }      
        .order-header {
            background-color: #ffebf3;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ffcce0;
        }       
        .order-id {
            font-weight: bold;
            color: #ff3f8f;
        }       
        .order-date {
            color: #666;
            font-size: 14px;
        }     
        .order-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background-color: #e0f7ff;
            color: #0088cc;
        }   
        .status-pending {
            background-color: #fff0e0;
            color: #ff8800;
        }     
        .status-confirmed {
            background-color: #e0f7ff;
            color: #0088cc;
        }   
        .status-shipping {
            background-color: #e0f0ff;
            color: #0044cc;
        }     
        .status-delivered {
            background-color: #e0ffe0;
            color: #00aa00;
        }    
        .status-cancelled {
            background-color: #ffe0e0;
            color: #cc0000;
        }    
        .order-body {
            padding: 20px;
        }   
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }    
        .order-details, .shipping-details {
            width: 48%;
        }       
        .section-title {
            color: #ff3f8f;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        } 
        .info-row {
            display: flex;
            margin-bottom: 8px;
            font-size: 14px;
        }     
        .info-label {
            width: 140px;
            color: #666;
        }    
        .info-value {
            flex: 1;
            font-weight: 500;
        }   
        .order-items {
            margin-top: 20px;
        }  
        .items-header {
            display: flex;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }       
        .product-col {
            flex: 3;
        }
        
        .quantity-col, .price-col, .total-col {
            flex: 1;
            text-align: center;
        }     
        .item-row {
            display: flex;
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            align-items: center;
        }  
        .item-row:last-child {
            border-bottom: none;
        }
        .order-summary {
            margin-top: 20px;
            text-align: right;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }    
        .summary-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 8px;
        }  
        .summary-label {
            width: 140px;
            text-align: left;
            color: #666;
        } 
        .summary-value {
            width: 100px;
            text-align: right;
            font-weight: 500;
        } 
        .total-row {
            font-size: 16px;
            font-weight: bold;
            color: #ff3f8f;
        }    
        .no-orders {
            text-align: center;
            padding: 50px 0;
            color: #666;
        }  
        .view-details-btn {
            display: inline-block;
            margin-top: 15px;
            background-color: #ff3f8f;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 20px;
            font-size: 14px;
            transition: background-color 0.3s;
        }  
        .view-details-btn:hover {
            background-color: #e02573;
        }   
        .order-action {
            text-align: center;
            margin-top: 15px;
        }  
        .cancel-btn {
            display: inline-block;
            background-color: #f44336;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 20px;
            font-size: 14px;
            transition: background-color 0.3s;
            margin-left: 10px;
            border: none;
            cursor: pointer;
        }  
        .cancel-btn:hover {
            background-color: #d32f2f;
        }
        .product-details {
            display: flex;
            align-items: center;
        }  
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
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
    <div class="navbar" >
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
    <div class="container">      
        <div class="order-list">
            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-item">
                        <div class="order-header">
                            <div class="order-id">Mã đơn hàng: <?php echo htmlspecialchars($order['madonhang']); ?></div>
                            <div class="order-date">Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($order['ngaydat'])); ?></div>
                            <div class="order-status <?php 
                                switch($order['trangthai']) {
                                    case 'Chờ xác nhận': echo 'status-pending'; break;
                                    case 'Đã xác nhận': echo 'status-confirmed'; break;
                                    case 'Đang giao hàng': echo 'status-shipping'; break;
                                    case 'Đã giao hàng': echo 'status-delivered'; break;
                                    case 'Đã hủy': echo 'status-cancelled'; break;
                                    default: echo '';
                                }
                            ?>">
                                <?php echo htmlspecialchars($order['trangthai']); ?>
                            </div>
                        </div>
                        
                        <div class="order-body">
                            <div class="order-info">
                                <div class="shipping-details">
                                    <div class="section-title">Thông tin giao hàng</div>
                                    <div class="info-row">
                                        <div class="info-label">Người nhận:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($order['tennguoinhan']); ?></div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Số điện thoại:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($order['sodienthoai']); ?></div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Địa chỉ:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($order['diachi']); ?></div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Phương thức:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($order['phuongthucgiaohang']); ?></div>
                                    </div>
                                    <?php if (!empty($order['ghichu'])): ?>
                                    <div class="info-row">
                                        <div class="info-label">Ghi chú:</div>
                                        <div class="info-value"><?php echo htmlspecialchars($order['ghichu']); ?></div>
                                    </div>
                                    <?php endif; ?>
                                </div>          
                                <div class="order-summary">
                                    <div class="summary-row">
                                        <div class="summary-label">Tổng tiền hàng:</div>
                                        <div class="summary-value"><?php echo number_format($order['tongtien'], 0, ',', '.'); ?> đ</div>
                                    </div>
                                    <div class="summary-row">
                                        <div class="summary-label">Phí vận chuyển:</div>
                                        <div class="summary-value"><?php echo number_format($order['phiship'], 0, ',', '.'); ?> đ</div>
                                    </div>
                                    <div class="summary-row total-row">
                                        <div class="summary-label">Thành tiền:</div>
                                        <div class="summary-value"><?php echo number_format($order['thanhtien'], 0, ',', '.'); ?> đ</div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $query_detail = "SELECT * FROM db_chitietdonhang WHERE madonhang = ?";
                                $stmt_detail = $conn->prepare($query_detail);
                                $stmt_detail->bind_param("s", $order['madonhang']);
                                $stmt_detail->execute();
                                $result_detail = $stmt_detail->get_result();
                                $order_details = [];
                                while ($row_detail = $result_detail->fetch_assoc()) {
                                    $order_details[] = $row_detail;
                                }
                            ?>
                            <div class="order-items">
                                <div class="section-title">Chi tiết sản phẩm</div>
                                <div class="items-header">
                                    <div class="product-col">Sản phẩm</div>
                                    <div class="quantity-col">Số lượng</div>
                                    <div class="price-col">Đơn giá</div>
                                    <div class="total-col">Thành tiền</div>
                                </div>
                                <?php foreach ($order_details as $item): ?>
                                    <div class="item-row">
                                        <div class="product-col">
                                            <div class="product-details">
                                                <?php
                                                    $query_img = "SELECT image FROM db_sanpham WHERE masp = ?";
                                                    $stmt_img = $conn->prepare($query_img);
                                                    $stmt_img->bind_param("s", $item['masp']);
                                                    $stmt_img->execute();
                                                    $result_img = $stmt_img->get_result();
                                                    $product_img = $result_img->fetch_assoc();
                                                ?>
                                                <?php if (!empty($product_img)): ?>
                                                    <img src="<?php echo htmlspecialchars($product_img['image']); ?>" class="product-image" alt="<?php echo htmlspecialchars($item['tensp']); ?>">
                                                <?php else: ?>
                                                    <div style="width: 60px; height: 60px; background-color: #f0f0f0; border-radius: 5px; margin-right: 10px;"></div>
                                                <?php endif; ?>
                                                <div><?php echo htmlspecialchars($item['tensp']); ?></div>
                                            </div>
                                        </div>
                                        <div class="quantity-col"><?php echo $item['soluong']; ?></div>
                                        <div class="price-col"><?php echo number_format($item['dongia'], 0, ',', '.'); ?> đ</div>
                                        <div class="total-col"><?php echo number_format($item['thanhtien'], 0, ',', '.'); ?> đ</div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="order-action">                    
                                <?php if ($order['trangthai'] == 'Chờ xác nhận'): ?>
                                <form method="post" action="cancel_order.php" style="display: inline;">
                                    <input type="hidden" name="madonhang" value="<?php echo $order['madonhang']; ?>">
                                    <button type="submit" class="cancel-btn" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">Hủy đơn hàng</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-orders">
                    <p>Bạn chưa có đơn hàng nào.</p>
                    <a href="product.php" class="view-details-btn">Mua sắm ngay</a>
                </div>
            <?php endif; ?>
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
                            <td><img src="../Picture/icon/icon-tiktok.png" style="margin-right: 5px"  alt="Phone Icon" class="icon1" /></td>
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
<?php
$conn->close();
?>
</html>