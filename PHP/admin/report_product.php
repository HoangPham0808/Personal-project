<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê sản phẩm</title>
    <style>
         * {
            padding: 10px;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .nav {
            display: flex;
            gap: 20px;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        .nav-item {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            padding: 10px 15px;
            transition: color 0.3s;
        }
        .nav-item.active {
            color: #ff69b4;
            border-bottom: 2px solid #ff69b4;
        }
        .nav-item:hover {
            color: #ff69b4;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 5px;
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        .tab.active {
            border-bottom: 2px solid #ff69b4;
            color: #ff69b4;
            font-weight: bold;
        }
        .page-title {
            color: #ff69b4;
            margin: 20px 0 30px 0;
            font-size: 28px;
            font-weight: 600;
        }
        .data-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background-color: #f3f4f6;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
        }
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .data-table tr:hover {
            background-color: #f9fafb;
        }
        @media (max-width: 768px) {
            .nav {
                flex-direction: column;
                gap: 5px;
                padding: 10px;
            }
            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <nav class="nav">
    <a href="qlyproduct.php" class="nav-item">SẢN PHẨM</a>
        <a href="curtom.php" class="nav-item ">KHÁCH HÀNG</a>
        <a href="qlydonhang.php" class="nav-item">ĐƠN HÀNG</a>
        <a href="report_dtn.php" class="nav-item active">BÁO CÁO-THỐNG KÊ</a>
    </nav>
    <div class="container">
        <h1 class="page-title">Thống kê sản phẩm</h1>
        <div class="tabs">
            <div class="tab " onclick="window.location.href='report_dtn.php'">Thống kê doanh thu</div>
            <div class="tab active" onclick="window.location.href='report_product.php'">Thống kê sản phẩm</div>
        </div>
        <div class="data-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng nhập</th>
                        <th>Đơn giá</th>
                        <th>Số lượng bán</th>
                        <th>Số lượng còn</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $connect = mysqli_connect('localhost', 'root', '', 'btl_php');
                    if (!$connect) {
                        die("Không kết nối: " . mysqli_connect_error());
                    }
                    $sql = "SELECT * FROM db_baocaosp";
                    $result = mysqli_query($connect, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['masp']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tensp']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['soluongnhap']) . "</td>";
                            echo "<td>" . number_format($row['dongia'], 0, ',', '.') . " đ</td>";
                            echo "<td>" . htmlspecialchars($row['soluongdaban']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['soluongcon']) . "</td>";
                            echo "<td>" . number_format($row['doanhthusp'], 0, ',', '.') . " đ</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Không có dữ liệu nào.</td></tr>";
                    }
                    mysqli_close($connect);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>