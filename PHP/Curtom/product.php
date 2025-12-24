<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "btl_php"; 
    $conn = new mysqli($servername, $username, $password, $dbname);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Baby Three Shop</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff; 
            margin-top: 200px;
        }
        .td{
            width: 20%;
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
<main class="">
    <div class="product-list">
        <?php
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $priceRange = isset($_GET['priceRange']) ? $_GET['priceRange'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        if ($conn->connect_error) {
            die("Kết nối thất bại: " . $conn->connect_error);
        }
        $sql = "SELECT masp, tensp, dongia, image FROM db_sanpham WHERE 1=1";

        if ($category) {
            $sql .= " AND loaisp = '" . $conn->real_escape_string($category) . "'";
        }
        if ($priceRange) {
            $sql .= " AND dongia < " . intval($priceRange);
        }  
        if ($search) {
            $sql .= " AND tensp LIKE '%" . $conn->real_escape_string($search) . "%'";
        } 
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="product-item">';
                echo '<a href="product_details.php?masp=' . $row["masp"] . '"><img src="' . $row["image"] . '" alt="' . $row["tensp"] . '" /></a>';
                echo '<h3>' . $row["tensp"] . '</h3>';
                echo '<p>' . number_format($row["dongia"], 0, ',', '.') . ' đ</p>';
                echo '</div>';
            }
        } else {
            echo "<p>Không có sản phẩm nào.</p>";
        }
        ?>
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
                            <td><img src="../Picture/icon/icon-tiktok.png" style="margin-right: 5px"  alt="Phone Icon" class="icon1" /></td>
                            <td><img src="../Picture/icon/icon-ig.png" style="margin-right: 5px;" alt="Phone Icon" class="icon1" /></td>
                            <td><img src="../Picture/icon/icon-shopee.png" style="margin-right: 5px;" alt="Phone Icon" class="icon1" /></td>
                            <td><img src="../Picture/   icon/icon-zalo.png" style="margin-right: 45px;" alt="Phone Icon" class="icon1" /></td>
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