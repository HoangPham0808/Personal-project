<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baby Three</title>
    <link rel="stylesheet" href="../CSS/BABY.css"/>
    <style>
        body {
                font-family: 'Roboto', sans-serif;
                background-color: #ffffff; 
                margin-top: 200px;
            }
        .container1 {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .container1 p{
            text-align: justify;
            line-height: 1.6; 
        }
        .main {
            padding: 20px 0;
        }
        h1, h2 {
            color: #ff69b4;
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
        }
        h2 {
            font-size: 20px;
            font-weight: bold;
        }
        ul {
            list-style-type: disc;
            padding-left: 20px;
        }
        ul li {
            margin-bottom: 10px;
        }
        .img1 {
            width: 100%;
            max-width: 600px;
            margin: 20px 0;
        }
        .hidden-content {
            display: none;
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
</header>
<body>
<main class="container1">
        <h1 >Baby Three – Cơn sốt búp bê mới khiến giới trẻ mê mẩn</h1>
        <p ><b style="margin-left: 30px;">Baby Three</b> là một dòng búp bê mới xuất hiện từ khoảng tháng 5 năm 2024 và nhanh chóng trở thành trào lưu trong giới trẻ Việt Nam. Với khuôn mặt tròn trịa, đôi mắt long lanh cùng biểu cảm đáng yêu, búp bê này không chỉ thu hút sự quan tâm của các bạn trẻ mà còn khiến nhiều người sưu tầm mê mẩn.</p>
        <h2>Điểm nổi bật của Baby Three</h2>
        <p><a style="margin-left: 30px;"> Điều khiến</a> Baby Three trở nên khác biệt so với các dòng búp bê khác chính là sự đa dạng về thiết kế và biểu cảm. Búp bê có nhiều phiên bản khác nhau theo từng chủ đề như:</p>
        <ul>
            <li><b>Macaron</b> – với tông màu pastel dịu nhẹ, đáng yêu</li>
            <li><b>Lucky Cat</b> – lấy cảm hứng từ mèo thần tài may mắn</li>
            <li><b>12 con giáp</b> – mô phỏng linh vật truyền thống của Châu Á</li>
            <li><b>12 cung hoàng đạo</b> – thiết kế theo tính cách của từng chòm sao</li>
            <li ><b>Phiên bản đặc biệt</b> – như Giáng sinh, Halloween hay các bộ sưu tập giới hạn</li>
        </ul>
        <img class="img1" src="../Picture/icon/bb3.png" alt="Hình ảnh Baby Three đẹp nhất" style=" margin-left: 25%;">
        <p>Không chỉ có tạo hình độc đáo, Baby Three còn gây ấn tượng với đôi mắt có nhiều biểu cảm phong phú như: mắt thường, mắt lé, mắt rưng rưng, mắt Dora nước,… Mỗi con búp bê khi mở hộp còn tỏa ra một mùi hương riêng biệt, làm tăng thêm sự hấp dẫn đối với người chơi.</p>
        <h2>Sự bùng nổ của trào lưu Baby Three</h2>
        <p><a style="margin-left: 30px;">Ngay</a> từ khi vừa ra mắt, Baby Three đã nhanh chóng tạo nên một cơn sốt trên mạng xã hội. Những hội nhóm sưu tầm búp bê Baby Three liên tục mọc lên, thu hút hàng chục nghìn thành viên tham gia để trao đổi, mua bán và chia sẻ kinh nghiệm chơi búp bê. Nhiều người không chỉ sưu tầm mà còn tổ chức các buổi offline, sự kiện đấu giá để sở hữu những phiên bản hiếm.
            Bên cạnh đó, Baby Three còn kéo theo nhiều dịch vụ sáng tạo khác. Một số bạn trẻ đã tận dụng sở thích này để kiếm tiền bằng cách vẽ mắt, trang điểm, làm tóc hoặc chế đồ thời trang riêng cho búp bê. Những dịch vụ này có giá từ 150.000 – 300.000 đồng/lần, thậm chí có những bản thiết kế độc quyền lên đến tiền triệu. Không ít người đã biến niềm đam mê thành công việc kinh doanh thực thụ, giúp họ kiếm thêm thu nhập đáng kể.</p>
        <p>Với những ưu điểm nổi bật, Baby Three đã nhanh chóng trở thành món đồ chơi được yêu thích nhất hiện nay. Nếu bạn đang tìm kiếm một món quà ý nghĩa cho con em mình, Baby Three chắc chắn là sự lựa chọn hoàn hảo.</p>
        <h2>Tranh cãi xoay quanh Baby Three</h2>
        <p><a style="margin-left: 30px;">Mặc</a> dù tạo ra sức hút lớn, Baby Three cũng gặp không ít tranh cãi. Một số ý kiến cho rằng việc sưu tầm búp bê với giá từ vài trăm nghìn đến hàng triệu đồng là một thú chơi lãng phí, không phù hợp với túi tiền của nhiều bạn trẻ. Một số phiên bản hiếm của Baby Three thậm chí bị đội giá lên gấp 3-4 lần so với giá gốc, tạo nên hiện tượng “săn hàng” không khác gì giày sneaker hay mô hình collectible.</p>
        <a href="#" id="show-more" style="color: #ff69b4; font-weight: bold;">Xem thêm</a>
        <div id="hidden-content" class="hidden-content">
            <h2>Phản hồi từ khách hàng</h2>
            <p><a style="margin-left: 30px;">Khách</a> hàng đã có những phản hồi rất tích cực về Baby Three. Họ đánh giá cao chất lượng, thiết kế và tính năng của sản phẩm. Nhiều người cho biết con em họ rất thích chơi với Baby Three và coi đó là người bạn thân thiết.</p>
            <h2>Hướng dẫn sử dụng</h2>
            <p><a style="margin-left: 30px;">Để </a> giữ cho Baby Three luôn mới và bền đẹp, bạn nên vệ sinh sản phẩm thường xuyên bằng cách lau nhẹ bằng khăn ẩm. Tránh để sản phẩm tiếp xúc với nhiệt độ cao hoặc các chất tẩy rửa mạnh.</p>
        </div>
    </main>
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
    <script>
        document.getElementById('show-more').addEventListener('click', function(event) {
            event.preventDefault();
            var hiddenContent = document.getElementById('hidden-content');
            if (hiddenContent.style.display === 'none' || hiddenContent.style.display === '') {
                hiddenContent.style.display = 'block';
                this.textContent = 'Ẩn bớt';
            } else {
                hiddenContent.style.display = 'none';
                this.textContent = 'Xem thêm';
            }
        });
    </script>
</body>
</html>