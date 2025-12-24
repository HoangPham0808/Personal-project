<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);  
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
    $username1 = $userData['username'] ?? ''; 
    if(empty($username1)) {
        echo "<script>alert('Vui lòng đăng nhập để tiếp tục.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();
    }
    
    $sql_select = "SELECT masp, tensp, soluong, dongia, image FROM db_giohang WHERE username = '$username1'";
    $result = $conn->query($sql_select);
    if(isset($_POST['thanhtoan'])) {
        if(!isset($_POST['phiship'])) {
            echo "<script>alert('Vui lòng chọn phương thức giao hàng');</script>";
        } else {
            $quocgia = $_POST['national'] ?? 'Việt Nam';
            $thanhpho = $_POST['city'] ?? '';
            $quanhuyen = $_POST['district'] ?? '';
            $diachi = $quanhuyen.",".$thanhpho.",".$quocgia;
            $phiship = $_POST['phiship'];
            
            switch($phiship) {
                case "22000":
                    $phuongthucgiaohang = "Giaohangnhanh - Tiêu chuẩn";
                    break;
                case "15000":
                    $phuongthucgiaohang = "SPX Express - Tiêu chuẩn";
                    break;
                case "24000":
                    $phuongthucgiaohang = "EPX Express - Tiêu chuẩn";
                    break;
                default:
                    $phuongthucgiaohang = "Chưa chọn";
            }
            $madonhang = "DH" . date("YmdHis") . rand(100, 999);
            $sql_total = "SELECT SUM(dongia * soluong) as total FROM db_giohang WHERE username = '$username1'";
            $result_total = $conn->query($sql_total);
            if($result_total && $result_total->num_rows > 0) {
                $row_total = $result_total->fetch_assoc();
                $tongtien = $row_total['total'];
                $thanhtien = $tongtien + intval($phiship);
                $sql_user = "SELECT * FROM db_khachhang WHERE username = '$username1'";
                $result_user = $conn->query($sql_user);
                if($result_user && $result_user->num_rows > 0) {
                    $user_data = $result_user->fetch_assoc();
                    $tennguoinhan = $user_data['hoten'] ?? $username1;
                    $sodienthoai = $user_data['sdt'] ?? '';
                    $diachi = !empty($diachi) ? $diachi : ($user_data['address'] ?? '');
                } else {
                    $tennguoinhan = $username1;
                    $sodienthoai = '';
                }
                $tennguoinhan = $conn->real_escape_string($tennguoinhan);
                $sodienthoai = $conn->real_escape_string($sodienthoai);
                $diachi = $conn->real_escape_string($diachi);
                $phuongthucgiaohang = $conn->real_escape_string($phuongthucgiaohang);          
                $sql_insert_order = "INSERT INTO db_donhang_giohang 
                    (madonhang, username, ngaydat, tongtien, phiship, thanhtien, trangthai, 
                    tennguoinhan, sodienthoai, diachi, phuongthucgiaohang) 
                    VALUES 
                    ('$madonhang', '$username1', NOW(), $tongtien, $phiship, $thanhtien, 'Chờ xác nhận', 
                    '$tennguoinhan', '$sodienthoai', '$diachi', '$phuongthucgiaohang')";      
                if($conn->query($sql_insert_order) === TRUE) {
                    $sql_cart_items = "SELECT masp, tensp, soluong, dongia, image FROM db_giohang WHERE username = '$username1'";
                    $result_cart = $conn->query($sql_cart_items);                    
                    if($result_cart && $result_cart->num_rows > 0) {
                        $all_items_inserted = true;
                        while($item = $result_cart->fetch_assoc()) {
                            $masp = $conn->real_escape_string($item['masp']);
                            $tensp = $conn->real_escape_string($item['tensp']);
                            $soluong = intval($item['soluong']);
                            $dongia = floatval($item['dongia']);
                            $thanhtien_item = $dongia * $soluong;                      
                            $sql_insert_item = "INSERT INTO db_chitietdonhang 
                                (madonhang, masp, tensp, soluong, dongia, thanhtien) 
                                VALUES 
                                ('$madonhang', '$masp', '$tensp', $soluong, $dongia, $thanhtien_item)";                       
                            if(!$conn->query($sql_insert_item)) {
                                $all_items_inserted = false;
                                echo "<script>console.error('Error inserting order item: " . $conn->error . "');</script>";
                            }
                        }                     
                        if($all_items_inserted) {
                            $sql_clear_cart = "DELETE FROM db_giohang WHERE username = '$username1'";
                            if($conn->query($sql_clear_cart)) {
                                echo "<script>window.location.href = 'order_success.php?madonhang=$madonhang';</script>";
                                exit();
                            } else {
                                echo "<script>alert('Đặt hàng thành công nhưng không thể xóa giỏ hàng: " . $conn->error . "');</script>";
                            }
                        }
                    } else {
                        echo "<script>alert('Giỏ hàng trống hoặc lỗi truy vấn: " . $conn->error . "');</script>";
                    }
                } else {
                    echo "<script>alert('Lỗi khi tạo đơn hàng: " . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('Không thể tính tổng tiền: " . $conn->error . "');</script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Giỏ Hàng</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin-top: 200px;
        }
        .cart-summary {
            padding: 20px 0px;
            border: 0px solid #ff3f8f;
            border-radius: 10px;
            background-color: white;
            margin-left: 150px;
            max-width: 1100px;
        }
        .tabl1e {
            width: 100%;
            border-collapse: collapse;

        }
        .tabl1e td, th {
            border: 0px solid #ff3f8f;
            text-align: center;
            padding: 0px 0px 5px 0px;
        }
        .th1 {
            background-color: #ff3f8f;
            color: white;
        }
        .line {
            width: 100%;
            height: 2px;
            background-color: darkgray;
            border-radius: 5px; 
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .cha {
            display: flex;
            flex-wrap: wrap;
        }
        .con1 , con2 {
            display: inline-block;
        }
        .con1{
            width: 70%;
            width: 750px;
        }
        .con2{
            width: 25%;
            width: 350px;
        }
        select{
            margin-top: 10px;
            padding: 2px 5px 2px 20px;
            width: 130px;
        }
        .td2{
            text-align: end;
        }
        .textradio{
            font-size: 12px;
        }
        .textcolor{
            color: #ff3f8f;
        }
        .delete-button {
            background-color: #ff3f8f;
            color: white;
            border: none;
            padding: 3px 8px;
            cursor: pointer;
            border-radius: 3px;
        }
        .checkout-button {
            background-color: #ff3f8f;
            color: white;
            padding: 5px 10px;
            border: 0px;
            cursor: pointer;
        }
        .checkout-button:hover {
            background-color: #e52677;
        }
    </style>
    <script>
        function updateTotal() {
            const total = parseInt(document.getElementById("total").value, 10);
            const shippingOptions = document.getElementsByName("phiship");
            let shippingCost = 0;

            for (const option of shippingOptions) {
                if (option.checked) {
                    shippingCost = parseInt(option.value, 10);
                    break;
                }
            }

            const finalTotal = total + shippingCost;
            document.getElementById("finalTotal").innerText = finalTotal.toLocaleString('vi-VN') + ' đ';
        }

        function validateForm() {
            const shippingSelected = document.querySelector('input[name="phiship"]:checked');
            if (!shippingSelected) {
                alert('Vui lòng chọn phương thức giao hàng');
                return false;
            }
            return true;
        }
    </script>
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
    <div class="cart-summary" >
    <h2 class="textcolor">Giỏ Hàng</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="line"></div>
            <div class="cha">
                <div class="con1">
                    <table class="tabl1e"> 
                            <tr>
                                <th></th>
                                <th class="textcolor">Sản phẩm</th>
                                <th class="textcolor">Giá</th>
                                <th class="textcolor">Số lượng</th>
                                <th class="textcolor">Tạm tính</th>
                                <th></th>
                            </tr> 
                            <?php 
                            $total = 0;
                            while ($row = $result->fetch_assoc()): 
                                $item_total = $row['dongia'] * $row['soluong'];
                                $total += $item_total;
                            ?>
                                <tr>
                                    <td colspan="6"><div class="line"></div></td>
                                </tr>
                                <tr>
                                    <td class="textcolor"><img style="width: 100px;" src="<?php echo $row['image']; ?>"></td>
                                    <td class="textcolor"><?php echo $row['tensp']; ?></td>
                                    <td class="textcolor"><?php echo number_format($row['dongia'], 0, ',', '.') . ' đ'; ?></td>
                                    <td class="textcolor"><?php echo $row['soluong']; ?></td>
                                    <td class="textcolor"><?php echo number_format($item_total, 0, ',', '.') . ' đ'; ?></td>
                                    <td>
                                        <form method="POST" action="delete_cart.php">
                                            <input type="hidden" name="masp" value="<?php echo $row['masp']; ?>">
                                            <input type="submit" name="xoa" value="Xóa" class="delete-button">
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <tr>
                                <td colspan="6"><div class="line"></div></td>
                            </tr>
                        </table>
                </div>
                <div class="con2">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
                        <table style="padding: 0px 0px 0px 20px;">
                            <tr>
                                <td><p class="textcolor">Thanh toán</p></td>
                            </tr>
                            <tr>
                                <td colspan="2"><div class="line"></div></td>
                            </tr>
                            <tr>
                                <td style="padding: 0px 30px 0px 0px;"><p class="textcolor">Tạm tính</p></td>
                                <td class="td2 textcolor">
                                    <?php echo number_format($total, 0, ',', '.') . ' đ'; ?>
                                    <input type="hidden" id="total" value="<?php echo $total; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td><p class="textcolor">Giao hàng</p></td>
                                <td class="td2">
                                    <select style="margin-top: 25px;" id="natonal" name="national">
                                        <option value="Việt Nam">Quốc gia</option>
                                        <?php
                                            $sql_select1 = "SELECT national FROM db_diachicity" ;
                                            $result1 = $conn->query($sql_select1);
                                            if ($result1 && $result1->num_rows > 0) {
                                                while($row = $result1->fetch_assoc()) {
                                                    echo "<option value='" . $row['national'] . "'>" . $row['national'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select><br>
                                    <select id="city" name="city">
                                        <option value="city">Thành Phố</option>
                                        <?php
                                            $sql_select2 = "SELECT city FROM db_diachicity " ;
                                            $result2 = $conn->query($sql_select2);
                                            if ($result2 && $result2->num_rows > 0) {
                                                while($row = $result2->fetch_assoc()) {
                                                    echo "<option value='" . $row['city'] . "'>" . $row['city'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select><br>
                                    <select id="district" name="district">
                                        <option value="district">Quận Huyện</option>
                                        <?php
                                            $sql_select2 = "SELECT district FROM db_diachidistrict" ;
                                            $result2 = $conn->query($sql_select2);
                                            if ($result2 && $result2->num_rows > 0) {
                                                while($row = $result2->fetch_assoc()) {
                                                    echo "<option value='" . $row['district'] . "'>" . $row['district'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="textcolor">Giao hàng</p></td>
                                <td >
                                    <input style="margin-top: 25px;" type="radio" name="phiship" value="22000" onchange="updateTotal()"><pr class="textradio"> Giaohangnhanh - Tiêu chuẩn: 22.000đ</pr><br>
                                    <input style="margin-top: 5px;" type="radio" name="phiship" value="15000" onchange="updateTotal()"><pr class="textradio"> SPX Express - Tiêu chuẩn: 15.000đ</pr><br>
                                    <input style="margin-top: 5px;" type="radio" name="phiship" value="24000" onchange="updateTotal()"><pr class="textradio"> EPX Express - Tiêu chuẩn: 24.000đ</pr><br>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align: center;"><input style="margin-top: 15px; padding: 10px;" type="text" id="voucher" placeholder="Nhập mã giảm giá nếu có"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><div class="line"></div></td>
                            </tr>  
                            <tr>
                                <td><p class="textcolor">Tổng</p></td>
                                <td id="finalTotal" style="text-align: end;" class="textcolor">
                                    <?php echo number_format($total, 0, ',', '.') . ' đ'; ?>
                                </td>
                            </tr> 
                            <tr>
                                <td colspan="2"><div class="line"></div></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <input class="checkout-button" type="submit" id="thanhtoan" name="thanhtoan" value="TIẾN HÀNH THANH TOÁN">
                                </td>
                            </tr>                    
                        </table>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>Giỏ hàng trống.</p>
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
</html>
<?php
$conn->close();
?>