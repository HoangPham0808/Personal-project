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
    $username = "root";
    $password = "";
    $dbname = "btl_php"; 
    $conn = new mysqli($servername, $username, $password, $dbname);
    $images = [
        '../Picture/baner.png',
        '../Picture/Anh1.png',
        '../Picture/anh12.png',
        '../Picture/anh13.png',
        '../Picture/anh14.png'
    ];
    $query = "SELECT masp, image, tensp, dongia FROM db_sanpham LIMIT 4";
    $result = $conn->query($query);
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baby Three Store</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/> 
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff; 
            margin-top: 200px;
        }
        nav {
            display: flex;
            justify-content: center;
            padding: 16px 0;
            background-color: white;
            color: #ff69b4;
            font-weight: bold;
        }
        nav a {
            margin: 0 16px;
            text-decoration: none;
            color: inherit;
        }
        nav a:hover {
            color: #ff1493;
        }
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px;
        }
        main .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 1200px;
        }
        main .image-container {
            width: 100%;
            max-width: 500px;
            padding: 16px;
        }
        main .image-container img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        main .contact-form {
            width: 100%;
            max-width: 500px;
            padding: 16px;
        }
        main .contact-form h2 {
            font-size: 2.0rem;
            font-weight: bold;
            margin-bottom: 16px;
            text-align: center;
        }
        main .contact-form form {
            display: flex;
            flex-direction: column;
        }
        main .contact-form form .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        main .contact-form form .form-group label {
            width: 48%;
            display: flex;
            flex-direction: column;
        }
        main .contact-form form .form-group input {
            width: 100%;
            border: 1px solid #ff69b4;
            border-radius: 8px;
            padding: 8px 16px;
        }
        main .contact-form form label {
            margin-bottom: 8px;
            font-weight: bold;
        }
        main .contact-form form input,
        main .contact-form form textarea {
            width: 100%;
            border: 1px solid #ff69b4;
            border-radius: 8px;
            padding: 8px 16px;
            margin-bottom: 16px;
        }
        main .contact-form form textarea {
            height: 128px;
        }
        main .contact-form form button {
            background-color: #ff69b4;
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        width: fit-content;
        border: none;
        cursor: pointer;
        margin: 0 auto; 
        }
        @media (min-width: 768px) {
            main {
                flex-direction: row;
                justify-content: center;
                align-items: flex-start;
            }
            main .content {
                flex-direction: row;
                justify-content: space-between;
            }
            main .image-container, main .contact-form {
                max-width: 50%;
            }
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
    <main>
        <div class="content">
            <div class="image-container">
                <img src="../Picture/images/AnhCat/Baby-three-12-con-giap-2.png" alt="Baby Three Blind Box 12 Con Giáp">
            </div>
            <div class="contact-form">
                <h2>LIÊN HỆ VỚI CHÚNG TÔI</h2>
                <form>
                    <div class="form-group">
                        <label>
                            *Họ tên
                            <input type="text" placeholder="Tên đầy đủ">
                        </label>
                        <label>
                            *Điện thoại
                            <input type="text" placeholder="Số điện thoại">
                        </label>
                    </div>
                    <label class="email-group">
                        *Email
                        <input type="email" placeholder="Địa chỉ email">
                    </label>
                    <label>
                        *Nội dung
                        <textarea placeholder="Nhập nội dung tại đây..."></textarea>
                    </label>
                    <button type="submit">Gửi</button>
                </form>
            </div>
        </div>
    </main>
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