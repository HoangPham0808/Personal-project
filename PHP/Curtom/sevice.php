<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baby Three Store Services</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/>
    <style>
        body {
                font-family: 'Roboto', sans-serif;
                background-color: #ffffff; 
                margin-top: 200px;
            }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .container h1 {
            text-align: center;
            color: #ff69b4;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media (min-width: 640px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .service {
            background-color: white;
            border: 1px solid #d9d9d9;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .service:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .service img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .service h2 {
            color: #ff69b4;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .service p {
            color: #4b5563;
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
    <div class="container">
        <h1>DỊCH VỤ CHỈ CÓ TẠI BABY THREE STORE</h1>
        <div class="grid">
            <div class="service">
                <img src="../Picture/img/doitra.png" alt="Dịch vụ đổi trả trong vòng 3 ngày">
                <h2>Dịch vụ đổi trả trong vòng 3 ngày</h2>
            </div>
            <div class="service">
                <img src="../Picture/img/khaugau.png" alt="Khâu gấu miễn phí">
                <h2>Khâu gấu miễn phí - Không lo rách chỉ</h2>
            </div>
            <div class="service">
                <img src="../Picture/img/giaogau.png" alt="Giao hàng siêu tốc">
                <h2>Giao hàng siêu tốc toàn quốc</h2>
            </div>
            <div class="service">
                <img src="../Picture/icon/tangthiep.png" alt="Tặng thiệp ý nghĩa">
                <h2>Tặng thiệp ý nghĩa - Gửi gắm yêu thương</h2>
            </div>
            <div class="service">
                <img src="../Picture/img/nennho.png" alt="Hút chân không gấu bông">
                <h2>Hút chân không gấu bông miễn phí</h2>
            </div>
            <div class="service">
                <img src="../Picture/img/bocqua.png" alt="Bọc quà giá rẻ">
                <h2>Bọc quà giá re - Bọc quà siêu xinh</h2>
            </div>
            <div class="service">
                <img src="../Picture/img/baohanh.png" alt="Bảo hành gấu bông trọn đời">
                <h2>Bảo hành gấu bông trọn đời</h2>
            </div>
            <div class="service">
                <img src="../Picture/img/lammoi.png" alt="Làm mới gấu bông">
                <h2>Làm mới gấu bông - Nhồi thêm bông</h2>
            </div>
            <div class="service">
                <img src="../Picture/img/giatgau.png" alt="Giặt gấu chuyên nghiệp">
                <h2>Giặt gấu chuyên nghiệp</h2>
            </div>
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