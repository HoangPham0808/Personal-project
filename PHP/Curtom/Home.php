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
<script>
        function loadContent(service) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'load_content.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('content').innerHTML = xhr.responseText;
                }
            };
            xhr.send('service=' + service);
        }
    </script>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Baby Three Shop</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/>
    <script>
            let currentIndex = 0;
            const images = <?php echo json_encode($images); ?>; 
            function showSlide(index) {
                const slide = document.getElementById('slide');
                slide.src = images[index];
            }
            function changeSlide(direction) {
                currentIndex += direction;
                if (currentIndex < 0) {
                    currentIndex = images.length - 1;
                } else if (currentIndex >= images.length) {
                    currentIndex = 0;
                }
                showSlide(currentIndex);
            }
            showSlide(currentIndex);
        </script>
        <style>
            body {
                font-family: 'Roboto', sans-serif;
                background-color: #ffffff; 
                margin-top: 200px;
            }
            .td{
                width: 20%;
            }
            .text{
                color: #ff3f8f;
                justify-self: center;
                margin-top: 30px;
            }
            .dichvu{
                text-align: center;
            }
            .dichvu button{
                border: 0px;
                background-color: #ffffff;
                color: #ff3f8f;
                font-size: 18px;
                margin-top: 10px;
            }
            .user-dropdown {
                position: relative;
                display: inline-block;
            }
            .dropdown-content {
                display: none;
                position: absolute;
                background-color: white;
                min-width: 180px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 1000;
                border-radius: 8px;
                overflow: hidden;
                top: 100%;
                right: 0;
            }
            .dropdown-content a {
                color: #ff3f8f;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
                text-align: left;
                transition: background-color 0.3s;
            }
            .dropdown-content a:hover {
                background-color: #ffebf3;
            }
            .user-dropdown:hover .dropdown-content {
                display: block;
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
                                       <a href="../login.php">Đăng xuất</a>
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
    <form method="GET" action="product.php">
        <table>
            <tr>
                <td>
                    <select id="loaisp" name="category" class="combo-box">
                        <option value="" disabled selected>Phân loại</option>
                        <option value=""><a href="produrt.php">Tất cả</a></option>
                        <?php
                        if ($conn->connect_error) {
                            die("Kết nối thất bại: " . $conn->connect_error);
                        }
                        $sql = "SELECT loaisp FROM db_loaiSP";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['loaisp'] . "'>" . $row['loaisp'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Không có loại sản phẩm</option>";
                        }
                        ?>
                    </select>
                    <select id="gia" name="priceRange" class="combo-box2">
                        <option value="" disabled selected>Khoảng giá</option>
                        <option value="300000">Dưới 300.000đ</option>
                        <option value="400000">Dưới 400.000đ</option>
                        <option value="500000">Dưới 500.000đ</option>
                        <option value="1000000">Dưới 1.000.000đ</option>
                    </select>
                    <button type="submit">Lọc</button>
                </td>
            </tr>
        </table>
    </form>
</header>
<body>
    <div>
        <div class="slider">
            <img id="slide" src="<?php echo $images[0]; ?>" alt="Slider Image" />
        </div>
        <div class="controls">
            <button class="button1" onclick="changeSlide(-1)">&#10094;</button>
            <button class="button1" onclick="changeSlide(1)">&#10095;</button>
        </div>
    </div>
    <table style="margin-left: 50px;">
        <tr>
            <td class="td"><img style="width: 100px;height: auto;margin-left: 25px;" src="../Picture/icon/Artboard.png" alt=""></td>
            <td class="td"><img style="width: 100px;height: auto;margin-left: 25px;" src="../Picture/icon/Artboard1.png" alt=""></td>
            <td class="td"><img style="width: 100px;height: auto;margin-left: 25px;" src="../Picture/icon/Artboard2.png" alt=""></td>
            <td class="td"><img style="width: 100px;height: auto;margin-left: 25px;" src="../Picture/icon/Artboard3.png" alt=""></td>
            <td class="td"><img style="width: 100px;height: auto;margin-left: 25px;" src="../Picture/icon/Artboard4.png" alt=""></td>
        </tr>
        <tr>
            <td class="td"><a style="margin-left: 0px;color: #ff3f8f;">GIAO HÀNG SIÊU TỐC</a></td>
            <td class="td"><a style="color: #ff3f8f;margin-left: 0px;">BÓC HỘP QUÀ XINH</a></td>
            <td class="td"><a style="color: #ff3f8f; margin-left:0px;">TẶNG THIỆP Ý NGHĨA</a></td>
            <td class="td"><a style="color: #ff3f8f;">GIẶT GẤU CHUYÊN NGHIỆP</a></td>
            <td><a style="color: #ff3f8f; margin-left:0px;">NÉN NHỎ GẤU BÔNG</a></td>
        </tr>
    </table>
    <div>
    <h2 class="text">SẢN PHẨM NỔI BẬT</h2>
    <div style="display: flex; justify-content: center; margin-top: 20px;">
        <?php foreach ($products as $product): ?>
            <div class="product-item" style="text-align: center; margin: 0 10px;">
                <a href="product_details.php?masp=<?php echo $product['masp']; ?>">
                    <?php if (isset($product['image'])): ?>
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo isset($product['tensp']) ? $product['tensp'] : 'Sản phẩm'; ?>" style="width: 250px; height: auto;">
                    <?php endif; ?>
                    <h3><?php echo isset($product['tensp']) ? $product['tensp'] : 'Tên sản phẩm không xác định'; ?></h3>
                    <p>Giá: <?php echo isset($product['dongia']) ? number_format($product['dongia'], 0, ',', '.') . ' đ' : 'Giá không xác định'; ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</div>
</div>
    </div>
    <div class="dichvu">
        <h2 class="text">DỊCH VỤ TẠI CỬA HÀNG</h2>
        <button onclick="loadContent('giatGau')">Giặt Gấu</button>
        <button style="margin-left: 20px;" onclick="loadContent('bocQua')">Bóc Quà - Tặng Thiệp</button>
        <div id="content" class="content">
            <table style="width: 800px; margin: 20px;justify-self: center;">
                <tr>
                    <td>
                        <img style="height: 230px;" src="../Picture/icon/giatgau.png" alt="">
                    </td>
                    <td>
                        <div style="padding: 58px;background-color:rgb(251, 182, 211);border-radius: 0px 30px 30px 0px;">
                            <p style="color: #ff3f8f;">Việc tắm giặt cho các em ý là điều hoàn toàn đơn giản. Bạn chỉ cần cho em đó vào một chiếc vỏ gối hay một chiếc túi vải, cuốn chặt lại, sau đó cho vào máy giặt. Sau khi giặt xong, bạn lấy sấy khô hoặc phơi dưới nắng to để đảm bảo em gấu được thơm mùi nắng nhé!</p>
                        </div>
                    </td>
                </tr>
            </table>
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