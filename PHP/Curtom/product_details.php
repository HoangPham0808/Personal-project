<?php 
session_start();
include 'username.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btl_php"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$masp = isset($_GET['masp']) ? $_GET['masp'] : '';
$product = null;

if ($masp) {
    $sql = "SELECT masp, tensp, soluong, dongia, image, mota, loaisp FROM db_sanpham WHERE masp = '" . $conn->real_escape_string($masp) . "'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<p>Sản phẩm không tồn tại.</p>";
    }
} else {
    echo "<p>Không có sản phẩm được chọn.</p>";
}
if ($product) {
    $masp = $product['masp'];
    $tensp = $product['tensp'];
    $dongia = $product['dongia'];
    $soluong = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
    $image = $product['image'];
    $username1 = $userData['username']; 
    if (isset($_POST['add-to-cart'])) {
        $checkSql = "SELECT soluong FROM db_giohang WHERE username = ? AND masp = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("ss", $username1, $masp);
        $stmt->execute();
        $checkResult = $stmt->get_result();
        if ($checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            $newQuantity = $row['soluong'] + $soluong;
            $updateSql = "UPDATE db_giohang SET soluong = ? WHERE username = ? AND masp = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("iss", $newQuantity, $username1, $masp);
            $updateStmt->execute();
        } else {
            $insertSql = "INSERT INTO db_giohang (masp, tensp, soluong, dongia, image, username) VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ssidss", $masp, $tensp, $soluong, $dongia, $image, $username1);
            
            if (!$insertStmt->execute()) {
                echo "Lỗi: " . $insertStmt->error;
            } else {
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Chi Tiết Sản Phẩm</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin-top: 200px;
        }
        .cart {
            max-width: 780px;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #ff3f8f;
            border-radius: 10px;
            background-color:rgb(247, 204, 222);
            text-align: center;
        }
        .cart img {
            width: 340px;
            height: auto;
            border-radius: 10px;
        }
        .product-title {
            font-size: 24px;
            color: darkviolet;
            margin: 10px 0;
            margin-left: 15px;
        }
        .product-price {
            font-size: 20px;
            color: #ff3f8f;
            margin-left: 15px;
        }
        .product-desc {
            font-size: 14px;
            color: #ff3f8f;
            margin: 10px 0;
            margin-left: 15px;
        }
        .quantity {
            margin-left: 15px; 
            background-color: rgb(247, 204, 222);       
        }
        .mua{
            background-color:rgb(247, 110, 194);
            color: white;
            border: none;
            padding: 5px 25px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 20px;
            font:bold;
            margin-left: -30px;
            margin-top: 10px;
        }
        .mua:hover{
            background-color:rgb(225, 115, 128);
        }
        .add-to-cart {
            background-color:rgb(247, 204, 222);
            color: #ff3f8f;
            border: none;
            padding: 5px 10px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 14px;
            margin-left: -50px;
            border: 2px solid #ff3f8f;
        }
        .add-to-cart:hover {
            background-color:rgb(255, 255, 255);

        }
        .quantity-selector {
            display: flex;
            border: 2px solid #ff3f8f;
            border-radius: 10px;
            width: 100px;
            background-color: rgb(247, 204, 222);
            margin-left: 20px;
        }

        .quantity-selector button {
            background-color: rgb(247, 204, 222);
            border: none;
            color: #ff3f8f;
            font-size: 20px;
            cursor: pointer;
            margin: 0px 15px 0px 5px;
            padding: 0px 5px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .quantity-selector button:hover {
            background-color: #ff3f8f;
            color: white;
        }

        .quantity-selector input {
            width: 40px;
            text-align: center;
            border: none;
            font-size: 20px;
            background-color: transparent;
        }
        .line {
            width: 100%;
            height: 1.5px;
            background-color: #ff3f8f;
            border-radius: 5px; 
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .textctsp{
            color: #ff3f8f;
            font-size: 13px;
        }
        .textcolor{
            color: darkblue;
            line-height: 1.7;
        }
        .boxkm{
            border-radius: 10px;
            width: 310px;
            background-color:rgb(247, 159, 196);
            color: #ff3f8f;
            text-align: start;
            padding: 10px;
            justify-self: center;
            margin-top: 30px;
            margin-right: 20px;
        }
        .boxkm p, h3{
            margin-left: 5px;
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
    <div class="cart">
        <?php if ($product): ?>
            <form method="post" >
            <table>
                    <tr>
                        <td>
                        <img style="margin-right: 20px;" src="<?php echo $product['image']; ?>" alt="<?php echo $product['tensp']; ?>" /><br>
                        <div class="boxkm">
                            <h3>KHUYẾN MÃI</h3>
                            <P>- Tặng kèm thiệp ý nghĩa: Thiệp sinh nhật, tình yêu, cảm ơn, ngày lễ</P>
                            <p>- Gói túi, buộc nơ siêu xinh</p>
                        </div>
                        </td>
                        <td style="text-align: left;">
                            <h1 class="product-title"><?php echo $product['tensp']; ?></h1> 
                            <p class="product-price"><?php echo number_format($product['dongia'], 0, ',', '.') . ' đ'; ?></p>
                            <p class="quantity">Số lượng: <?php echo $product['soluong']; ?></p>
                            <p class="product-desc"><?php echo $product['mota']; ?></p>
                            <table>
                                <tr>
                                    <td>
                                        <?php
                                            if (!isset($_SESSION['quantity'])) {
                                                $_SESSION['quantity'] = 1; 
                                            }
                                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                                if (isset($_POST['increase'])) {
                                                    $_SESSION['quantity']++; 
                                                } elseif (isset($_POST['decrease']) && $_SESSION['quantity'] > 1) {
                                                    $_SESSION['quantity']--;
                                                }
                                            }
                                        ?>
                                        <table>
                                            <tr >
                                                <td colspan="2">
                                                <form method="POST" action="">
                                                    <div class="quantity-selector">        
                                                        <button type="submit" name="decrease" value="1" class="decrease">-</button>
                                                        <input type="number" name="quantity" value="<?php echo $_SESSION['quantity']; ?>" min="1" readonly />
                                                        <button style="margin-left: -5px;" type="submit" name="increase" value="1" class="increase">+</button>
                                                    </div>    
                                                </form>
                                                </td>
                                                <td colspan="4">
                                                    <input class="add-to-cart" type="submit" name="add-to-cart" value="Thêm vào giỏ hàng">
                                                </td>
                                            </tr>
                                            <tr>
                                                <form method="post" action="pay.php">
                                                    <input type="hidden" name="masp" value="<?php echo $product['masp']; ?>">
                                                    <input type="hidden" name="tensp" value="<?php echo $product['tensp']; ?>">
                                                    <input type="hidden" name="dongia" value="<?php echo $product['dongia']; ?>">
                                                    <input type="hidden" name="soluong1" value="<?php echo $_SESSION['quantity']; ?>">
                                                    <input type="hidden" name="image" value="<?php echo $product['image']; ?>">
                                                    <input type="hidden" name="username" value="<?php echo $userData['username']; ?>">
                                                    <td colspan="4" style="text-align: center;"><input class="mua" type="submit" name="buy-now" value="Mua Ngay"></td>
                                                </form>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                <div class="line"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%;text-align: center;">
                                                    <img style="width: 35px;" src="../Picture/icon/may.png" alt="">
                                                </td>
                                                <td style="width: 25%;text-align: center;">
                                                    <img style="width: 35px;" src="../Picture/icon/cam.png" alt="">
                                                </td>
                                                <td style="width: 25%;text-align: center;">
                                                    <img style="width: 35px;" src="../Picture/icon/tim.png" alt="">
                                                </td>
                                                <td style="width: 25%;text-align: center;">
                                                    <img style="width: 35px;" src="../Picture/icon/Artboard4.png" alt="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <p class="textctsp">100% bông trắng tinh khiết</p>
                                                </td>
                                                <td style="text-align: center;">
                                                    <p class="textctsp">100% ảnh chụp tại shop</p>
                                                </td>
                                                <td style="text-align: center;">
                                                    <p class="textctsp">Bảo hành đường khâu chọn đời</p>
                                                </td>
                                                <td style="text-align: center;">
                                                    <p class="textctsp">Đóng gói nhỏ gọn, an toàn</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                <div class="line"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <h3 class="textcolor" style="margin-top: 5px;" >ĐẶC ĐIỂM NỔI BẬT</h3>
                                                    <p class="textcolor" style="font-size: 14px;">- Chất liệu mềm mại, đảm bảo an toàn</p>
                                                    <p class="textcolor" style="font-size: 14px;">- Đường may tỉ mỉ chắc chắn</p>
                                                    <p class="textcolor" style="font-size: 14px;">- Màu sắc tươi tắn</p>
                                                    <p class="textcolor" style="font-size: 14px;">- Bông polyester 3D trắng cao cấp, đàn hồi cao</p>
                                                </td>
                                            </tr>
                                        </table>     
                                    </td>
                                </tr>
                            </table> 
                        </td>
                    </tr>
            </table>
            </form>
        <?php else: ?>
            <p>Không có thông tin sản phẩm.</p>
        <?php endif; ?>
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
<?php
    $conn->close();
 ?>
</html>