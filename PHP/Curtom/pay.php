<?php
    session_start();
    include 'username.php'; 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "btl_php"; 
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $masp = isset($_POST['masp']) ? $_POST['masp'] : null;
        $tensp = isset($_POST['tensp']) ? $_POST['tensp'] : null;
        $dongia = isset($_POST['dongia']) ? $_POST['dongia'] : null;
        $soluong = isset($_POST['soluong1']) ? $_POST['soluong1'] : null;
        if (empty($tensp) || empty($soluong) || empty($dongia)) {
            echo "Tên sản phẩm, số lượng, và đơn giá không được để trống.";
            return;
        }
        $image = isset($_GET['image']) ? $_POST['image'] : null;
        $username = isset($_GET['username']) ? $_GET['username'] : null;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            line-height: 1.5;
            margin-top: 200px;
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
            width: 100%;
        }
        .con1 , con2 {
            display: inline-block;
        }
        .con1{
            width: 30%;
        }
        .con2{
            width: 40%;
        }
        h2,h1{
            color:  #ff69b4;
        }
        .text55{
            color:  #ff69b4;
            font-size: 18px;
            margin-bottom: 3px;
        }
        .inputtt{
            width: 250px;
            padding: 3px 10px;
            font-size: 16px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        select{
            width: 250px;
            padding: 3px 10px;
            font-size: 16px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        label{
            font-size: 12px;
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
<script>
    function updateTotal() {
        const dongia = parseInt(document.querySelector('input[name="dongia"]').value, 10); 
        const soluong = parseInt(document.querySelector('input[name="soluong"]').value, 10); 
        const initialTotal = dongia * soluong; 
        const shippingOptions = document.getElementsByName("phiship");
        let shippingCost = 0;

        for (const option of shippingOptions) {
            if (option.checked) {
                shippingCost = parseInt(option.value, 10);
                break;
            }
        }

        // Tính tổng cuối cùng
        const finalTotal = initialTotal + shippingCost; 
        document.getElementById("finalTotal").innerText = finalTotal.toLocaleString('vi-VN') + ' đ'; 
    }
    document.addEventListener('DOMContentLoaded', (event) => {
        updateTotal();
    });
</script>
<body>
    <form action="add_donhang.php" method="post" style="padding: 0px 130px; background-color: white;">
        <h1>THANH TOÁN</h1>
        <div class="line"></div>
        <h2>Thông tin thanh toán</h2>
        <div class="cha">
            <div class="con1">
                <p class="text55">*Họ và tên</p>
                <input class="inputtt" type="text" id="hoten" name="hoten" placeholder="Nhập họ và tên">
                <p class="text55">*Tỉnh/Thành Phố</p>
                <select id="city" name="city">
                    <option value="">Thành Phố</option>
                    <?php
                        $sql_select1 = "SELECT city FROM db_diachicity";
                        $result1 = $conn->query($sql_select1);
                        if ($result1->num_rows > 0) {
                            while($row = $result1->fetch_assoc()) {
                                echo "<option value='" . $row['city'] . "'>" . $row['city'] . "</option>";
                            }
                        }
                    ?>
                </select><br>
                <p class="text55">*Xã/Phường/Thị Trấn</p>
                <select id="ward" name="ward">
                    <option value="">Xã/Phường/Thị trấn</option>
                </select><br>
            </div>
            <div class="con1">
                <p class="text55">*Số điện thoại</p>
                <input class="inputtt" type="text" id="sdt" name="sdt" placeholder="Nhập số điện thoại">
                <p class="text55">*Quận/Huyện</p>
                <select id="district" name="district">
                    <option value="">Quận/Huyện</option>
                </select><br>
                <p class="text55">*Địa chỉ</p>
                <input class="inputtt" type="text" id="diachi" name="diachi" placeholder="Nhập địa chỉ cụ thể">
            </div>
            <div class="con2">
                <p class="text55">*Dịch vụ kèm theo</p>
                <input  type="radio" name="sevice" value="túi kính buộc nơ"><a style="font-size: 14px; margin-right: 15px;">Túi kính, buộc nơ: Miễn phí</a>
                <input type="radio" name="sevice" value="Tặng thiệp ý nghĩa"><a style="font-size: 14px;">Tặng thiệp ý nghĩa: Miễn phí</a><br>
                <p class="text55" style="margin-top: 20px;">Ghi chú đơn hàng</p>
                <textarea style="height: 100px; width: 400px;" name="ghichu" placeholder="Ghi chú về đơn hàng, ví dụ: thời gian giao hàng hay chỉ dẫn địa chỉ chi tiết hơn"></textarea>
            </div>
        </div>
        <h2>Đơn hàng của bạn</h2>
        <table style="color: #ff69b4;">
            <tr>
                <td style="width: 50%;">SẢN PHẨM</td>
                <td>TẠM TÍNH</td>            
            </tr>
            <tr><td colspan="2"><div class="line"></div></td></tr>
            <tr>
                
                <td><?php echo $tensp."<br>";
                  echo "Số Lượng: ".$soluong;?></td>
                <td><?php
                $tong=$dongia*$soluong;
                 echo $tong."đ"?></td>
            </tr>
            <tr><td colspan="2"><div class="line"></div></td></tr>
            <tr>
                <td><p>GIAO HÀNG</p></td>
                <td>
                    <input  type="radio" name="phiship" value="22000" onchange="updateTotal()"><label> Giaohangnhanh - Tiêu chuẩn: 22.000đ</label><br>
                    <input style="margin-top: 5px;" type="radio" name="phiship" value="15000" onchange="updateTotal()"><label> SPX Express - Tiêu chuẩn: 15.000đ</label><br>
                    <input style="margin-top: 5px;" type="radio" name="phiship" value="24000" onchange="updateTotal()"><label> EPX Express - Tiêu chuẩn: 24.000đ</label><br>
                </td>
            </tr>      
            <input type="hidden" name="soluong" value="<?php echo $soluong; ?>">
            <tr><td colspan="2"><div class="line"></div></td></tr>
            <tr>
                <td><p>TỔNG</p></td>
                <td id="finalTotal"  class="textcolor">
                    <?php 
                        $initialTotal = $dongia * $soluong;
                        echo number_format($initialTotal, 0, ',', '.') . ' đ'; 
                    ?>
                </td>
            </tr>
            <tr><td colspan="2"><div class="line"></div></td></tr>
            <tr>
                <td colspan="2">
                    <table style="width: 100%;padding: 20px;border: 2px solid darkgray;">
                        <tr>
                            <td>
                                <div>
                                    <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" onchange="updatePaymentMethod()">
                                    <label for="bank_transfer" style="color: #ff69b4;">Chuyển khoản ngân hàng</label>
                                </div>
                                <div>
                                    <input type="radio" id="cod" name="payment_method" value="cod" onchange="updatePaymentMethod()">
                                    <label for="cod" style="color: #ff69b4;">Trả tiền mặt khi nhận hàng (SHIP COD)</label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>Cảm ơn Quý khách đã đặt hàng! BaByThree Shop sẽ liên hệ Quý khách xác nhận dơn hàng sớm nhất!</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="hidden" name="masp1" value="<?php echo $masp; ?>">
                    <input type="hidden" name="tensp1" value="<?php echo $tensp; ?>">
                    <input type="hidden" name="dongia1" value="<?php echo $dongia; ?>">
                    <input type="hidden" name="soluong1" value="<?php echo $soluong; ?>">
                    <input style="width: 100%; padding: 5px 20px;font-size: 20px; margin-top: 10px;background-color: #ff69b4; color: white;border: 0px; margin-bottom: 20px;" type="submit" name="dathang" value="Đặt hàng">
                </td>
            </tr>
        </table>
    </form>
    <script>
        document.getElementById('city').addEventListener('change', function() {
            var city = this.value;
            var districtSelect = document.getElementById('district');
            districtSelect.innerHTML = '<option value="">Quận/Huyện</option>';
            
            if (city) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_districts.php?city=' + encodeURIComponent(city), true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        var districts = JSON.parse(this.responseText);
                        districts.forEach(function(district) {
                            var option = document.createElement('option');
                            option.value = district;
                            option.textContent = district;
                            districtSelect.appendChild(option);
                        });
                    }
                };
                xhr.send();
            }
        });
        document.getElementById('district').addEventListener('change', function() {
            var district = this.value;
            var wardSelect = document.getElementById('ward');
            wardSelect.innerHTML = '<option value="">Xã/Phường/Thị trấn</option>';
            
            if (district) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_wards.php?district=' + encodeURIComponent(district), true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        var wards = JSON.parse(this.responseText);
                        wards.forEach(function(ward) {
                            var option = document.createElement('option');
                            option.value = ward;
                            option.textContent = ward;
                            wardSelect.appendChild(option);
                        });
                    }
                };
                xhr.send();
            }
        });
    </script>
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