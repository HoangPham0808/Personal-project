<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê doanh thu</title>
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
            padding: 20px;
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
        .chart-container {
            width: 100%;
            height: 400px;
            margin-bottom: 30px;
        }
        .revenue-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary-card {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 15px;
            width: 23%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #4b5563;
        }
        .summary-card p {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: #ff69b4;
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
            .revenue-summary {
                flex-direction: column;
            }
            .summary-card {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        .sub-tabs {
            display: flex;
            margin-bottom: 20px;
            background-color: #f3f4f6;
            border-radius: 8px;
            overflow: hidden;
        }
        .sub-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            text-align: center;
            flex: 1;
        }
        .sub-tab.active {
            background-color: #ff69b4;
            color: white;
            font-weight: bold;
        }
        .year-selector {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .year-selector label {
            margin-right: 10px;
            font-weight: bold;
        }
        .year-selector select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .export-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .export-button {
            padding: 10px 15px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            text-align: center;
            gap: 5px;
            transition: background-color 0.3s;
        }
        .export-button:hover {
            background-color: #ff4aa5;
        }
        .export-button img {
            width: 20px;
            height: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav class="nav">
        <a href="qlyproduct.php" class="nav-item">SẢN PHẨM</a>
        <a href="curtom.php" class="nav-item">KHÁCH HÀNG</a>
        <a href="qlydonhang.php" class="nav-item">ĐƠN HÀNG</a>
        <a href="report_dtn.php" class="nav-item active">BÁO CÁO-THỐNG KÊ</a>
    </nav>
    
    <div class="container">
        <h1 class="page-title">Báo cáo doanh thu</h1>
        
        <div class="tabs">
            <div class="tab active" onclick="window.location.href='report_dtn.php'">Thống kê doanh thu</div>
            <div class="tab" onclick="window.location.href='report_product.php'">Thống kê sản phẩm</div>
        </div>
        
        <?php
        $connect = mysqli_connect('localhost', 'root', '', 'btl_php');
        if (!$connect) {
            die("Không kết nối: " . mysqli_connect_error());
        }
        $view_mode = isset($_GET['view']) ? $_GET['view'] : 'day';
        $current_year = date('Y');
        $selected_year = isset($_GET['year']) ? $_GET['year'] : $current_year;
        $sql_years = "SELECT DISTINCT YEAR(ngay) as year FROM db_baocao_doanhthungay ORDER BY year DESC";
        $result_years = mysqli_query($connect, $sql_years);
        $years = [];
        if (mysqli_num_rows($result_years) > 0) {
            while ($row = mysqli_fetch_assoc($result_years)) {
                $years[] = $row['year'];
            }
        }
        if (empty($years)) {
            $years[] = $current_year;
        }
        if (!in_array($selected_year, $years)) {
            $selected_year = $years[0];
        }
        echo '<div class="year-selector">
                <label for="year">Chọn năm:</label>
                <select id="year" onchange="changeYear(this.value, \''.$view_mode.'\')">'; 
        foreach ($years as $year) {
            $selected = ($year == $selected_year) ? 'selected' : '';
            echo '<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
        }
        echo '</select>
            </div>';
        echo '<div class="sub-tabs">
                <div class="sub-tab '.($view_mode == 'day' ? 'active' : '').'" onclick="changeView(\'day\', '.$selected_year.')">Theo ngày</div>
                <div class="sub-tab '.($view_mode == 'month' ? 'active' : '').'" onclick="changeView(\'month\', '.$selected_year.')">Theo tháng</div>
            </div>';
        echo '<div class="export-buttons">
                <form method="post" action="export_revenue.php">
                    <input type="hidden" name="export_type" value="excel">
                    <input type="hidden" name="view_mode" value="'.$view_mode.'">
                    <input type="hidden" name="year" value="'.$selected_year.'">
                    <button type="submit" class="export-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Xuất Excel
                    </button>
                </form>
                <form method="post" action="export_revenue.php">
                    <input type="hidden" name="export_type" value="word">
                    <input type="hidden" name="view_mode" value="'.$view_mode.'">
                    <input type="hidden" name="year" value="'.$selected_year.'">
                    <button type="submit" class="export-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Xuất Word
                    </button>
                </form>
            </div>';   
        if ($view_mode == 'month') {
            $sql = "SELECT DATE_FORMAT(ngay, '%Y-%m') as month, 
                    SUM(doanhthungay) as total_revenue 
                    FROM db_baocao_doanhthungay 
                    WHERE YEAR(ngay) = '$selected_year'
                    GROUP BY DATE_FORMAT(ngay, '%Y-%m') 
                    ORDER BY month";
            $result = mysqli_query($connect, $sql);    
            $months = [];
            $revenue = [];
            $totalRevenue = 0;
            $maxRevenue = 0;
            $minRevenue = PHP_INT_MAX;
            $monthCount = 0;        
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $month_date = explode('-', $row['month']);
                    $month_name = 'Tháng ' . intval($month_date[1]);
                    $months[] = $month_name;
                    $revenue[] = (float)$row['total_revenue'];
                    $totalRevenue += (float)$row['total_revenue'];
                    $maxRevenue = max($maxRevenue, (float)$row['total_revenue']);
                    $minRevenue = min($minRevenue, (float)$row['total_revenue']);
                    $monthCount++;
                }
            }
            if ($minRevenue == PHP_INT_MAX) {
                $minRevenue = 0;
            }
            $avgRevenue = $monthCount > 0 ? $totalRevenue / $monthCount : 0;
            $totalRevenue_formatted = number_format($totalRevenue, 0, ',', '.');
            $avgRevenue_formatted = number_format($avgRevenue, 0, ',', '.');
            $maxRevenue_formatted = number_format($maxRevenue, 0, ',', '.');
            $minRevenue_formatted = number_format($minRevenue, 0, ',', '.');
            $months_json = json_encode($months);
            $revenue_json = json_encode($revenue);
            echo '<div class="revenue-summary">
                <div class="summary-card">
                    <h3>Tổng doanh thu năm '.$selected_year.'</h3>
                    <p>'.$totalRevenue_formatted.' đ</p>
                </div>
                <div class="summary-card">
                    <h3>Trung bình mỗi tháng</h3>
                    <p>'.$avgRevenue_formatted.' đ</p>
                </div>
                <div class="summary-card">
                    <h3>Cao nhất</h3>
                    <p>'.$maxRevenue_formatted.' đ</p>
                </div>
                <div class="summary-card">
                    <h3>Thấp nhất</h3>
                    <p>'.$minRevenue_formatted.' đ</p>
                </div>
            </div>';
            echo '<div class="data-card">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>';
            echo '<div class="data-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tháng</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>';
            mysqli_data_seek($result, 0);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $month_date = explode('-', $row['month']);
                    $month_name = 'Tháng ' . intval($month_date[1]) . '/' . $month_date[0];
                    echo "<tr>";
                    echo "<td>" . $month_name . "</td>";
                    echo "<td>" . number_format($row['total_revenue'], 0, ',', '.') . " đ</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Không có dữ liệu doanh thu.</td></tr>";
            }
            echo '</tbody>
                </table>
            </div>';
            echo '<script>
            // Vẽ biểu đồ doanh thu theo tháng
            var ctx = document.getElementById("revenueChart").getContext("2d");
            var myChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: '.$months_json.',
                    datasets: [{
                        label: "Doanh thu (đ)",
                        data: '.$revenue_json.',
                        backgroundColor: "rgba(255, 105, 180, 0.7)",
                        borderColor: "rgba(255, 105, 180, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString("vi-VN") + " đ";
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ": " + context.raw.toLocaleString("vi-VN") + " đ";
                                }
                            }
                        }
                    }
                }
            });
            </script>';
            
        } else {
            $sql = "SELECT ngay, doanhthungay FROM db_baocao_doanhthungay 
                   WHERE YEAR(ngay) = '$selected_year'
                   ORDER BY ngay";
            $result = mysqli_query($connect, $sql);
            $dates = [];
            $revenue = [];
            $totalRevenue = 0;
            $maxRevenue = 0;
            $minRevenue = PHP_INT_MAX;
            $dayCount = 0;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $dates[] = date('d/m/Y', strtotime($row['ngay']));
                    $revenue[] = (float)$row['doanhthungay'];
                    $totalRevenue += (float)$row['doanhthungay'];
                    $maxRevenue = max($maxRevenue, (float)$row['doanhthungay']);
                    $minRevenue = min($minRevenue, (float)$row['doanhthungay']);
                    $dayCount++;
                }
            }
            if ($minRevenue == PHP_INT_MAX) {
                $minRevenue = 0;
            }
            $avgRevenue = $dayCount > 0 ? $totalRevenue / $dayCount : 0;
            $totalRevenue_formatted = number_format($totalRevenue, 0, ',', '.');
            $avgRevenue_formatted = number_format($avgRevenue, 0, ',', '.');
            $maxRevenue_formatted = number_format($maxRevenue, 0, ',', '.');
            $minRevenue_formatted = number_format($minRevenue, 0, ',', '.');
            $dates_json = json_encode($dates);
            $revenue_json = json_encode($revenue);
            echo '<div class="revenue-summary">
                <div class="summary-card">
                    <h3>Tổng doanh thu năm '.$selected_year.'</h3>
                    <p>'.$totalRevenue_formatted.' đ</p>
                </div>
                <div class="summary-card">
                    <h3>Trung bình mỗi ngày</h3>
                    <p>'.$avgRevenue_formatted.' đ</p>
                </div>
                <div class="summary-card">
                    <h3>Cao nhất</h3>
                    <p>'.$maxRevenue_formatted.' đ</p>
                </div>
                <div class="summary-card">
                    <h3>Thấp nhất</h3>
                    <p>'.$minRevenue_formatted.' đ</p>
                </div>
            </div>';
            echo '<div class="data-card">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>';
            echo '<div class="data-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>';
            mysqli_data_seek($result, 0);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . date('d/m/Y', strtotime($row['ngay'])) . "</td>";
                    echo "<td>" . number_format($row['doanhthungay'], 0, ',', '.') . " đ</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Không có dữ liệu doanh thu.</td></tr>";
            }
            echo '</tbody>
                </table>
            </div>';
            echo '<script>
            // Vẽ biểu đồ doanh thu theo ngày
            var ctx = document.getElementById("revenueChart").getContext("2d");
            var myChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: '.$dates_json.',
                    datasets: [{
                        label: "Doanh thu (đ)",
                        data: '.$revenue_json.',
                        backgroundColor: "rgba(255, 105, 180, 0.7)",
                        borderColor: "rgba(255, 105, 180, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString("vi-VN") + " đ";
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ": " + context.raw.toLocaleString("vi-VN") + " đ";
                                }
                            }
                        }
                    }
                }
            });
            </script>';
        }
        mysqli_close($connect);
        ?>
    </div>
    <script>
    function changeView(view, year) {
        window.location.href = 'report_dtn.php?view=' + view + '&year=' + year;
    }
    function changeYear(year, view) {
        window.location.href = 'report_dtn.php?view=' + view + '&year=' + year;
    }
    </script>
</body>
</html>