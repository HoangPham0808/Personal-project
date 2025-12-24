<?php
$connect = mysqli_connect('localhost', 'root', '', 'btl_php');
if (!$connect) {
    die("Không kết nối: " . mysqli_connect_error());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $export_type = isset($_POST['export_type']) ? $_POST['export_type'] : 'excel';
    $view_mode = isset($_POST['view_mode']) ? $_POST['view_mode'] : 'day';
    $selected_year = isset($_POST['year']) ? $_POST['year'] : date('Y');
    $filename = 'Bao_cao_doanh_thu_' . $selected_year;
    if ($view_mode == 'month') {
        $filename .= '_theo_thang';
    } else {
        $filename .= '_theo_ngay';
    }
    if ($export_type == 'excel') {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        echo '<table border="1">';
        echo '<tr><th colspan="2">BÁO CÁO DOANH THU NĂM ' . $selected_year . '</th></tr>';
        if ($view_mode == 'month') {
            $sql_summary = "SELECT 
                            SUM(monthly_revenue) as total_revenue,
                            AVG(monthly_revenue) as avg_revenue,
                            MAX(monthly_revenue) as max_revenue,
                            MIN(CASE WHEN monthly_revenue > 0 THEN monthly_revenue ELSE NULL END) as min_revenue
                            FROM (
                                SELECT 
                                    DATE_FORMAT(ngay, '%Y-%m') as month,
                                    SUM(doanhthungay) as monthly_revenue
                                FROM db_baocao_doanhthungay
                                WHERE YEAR(ngay) = '$selected_year'
                                GROUP BY DATE_FORMAT(ngay, '%Y-%m')
                            ) as monthly_data";
        } else {
            $sql_summary = "SELECT 
                            SUM(doanhthungay) as total_revenue,
                            AVG(doanhthungay) as avg_revenue,
                            MAX(doanhthungay) as max_revenue,
                            MIN(CASE WHEN doanhthungay > 0 THEN doanhthungay ELSE NULL END) as min_revenue
                            FROM db_baocao_doanhthungay
                            WHERE YEAR(ngay) = '$selected_year'";
        }
        
        $result_summary = mysqli_query($connect, $sql_summary);
        if (!$result_summary) {
            die("Lỗi truy vấn: " . mysqli_error($connect));
        }
        
        $summary = mysqli_fetch_assoc($result_summary);
        
        echo '<tr><td>Tổng doanh thu năm ' . $selected_year . '</td><td>' . number_format($summary['total_revenue'], 0, ',', '.') . ' đ</td></tr>';
        if ($view_mode == 'month') {
            echo '<tr><td>Trung bình mỗi tháng</td><td>' . number_format($summary['avg_revenue'], 0, ',', '.') . ' đ</td></tr>';
        } else {
            echo '<tr><td>Trung bình mỗi ngày</td><td>' . number_format($summary['avg_revenue'], 0, ',', '.') . ' đ</td></tr>';
        }
        echo '<tr><td>Cao nhất</td><td>' . number_format($summary['max_revenue'], 0, ',', '.') . ' đ</td></tr>';
        echo '<tr><td>Thấp nhất</td><td>' . number_format($summary['min_revenue'], 0, ',', '.') . ' đ</td></tr>';
        echo '<tr><td colspan="2"></td></tr>';
        if ($view_mode == 'month') {
            $sql = "SELECT DATE_FORMAT(ngay, '%Y-%m') as month, 
                    SUM(doanhthungay) as total_revenue 
                    FROM db_baocao_doanhthungay 
                    WHERE YEAR(ngay) = '$selected_year'
                    GROUP BY DATE_FORMAT(ngay, '%Y-%m') 
                    ORDER BY month";
            $result = mysqli_query($connect, $sql);
            if (!$result) {
                die("Lỗi truy vấn: " . mysqli_error($connect));
            }
            echo '<tr><th>Tháng</th><th>Doanh thu</th></tr>';
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $month_date = explode('-', $row['month']);
                    $month_name = 'Tháng ' . intval($month_date[1]) . '/' . $month_date[0];
                    echo '<tr>';
                    echo '<td>' . $month_name . '</td>';
                    echo '<td>' . number_format($row['total_revenue'], 0, ',', '.') . ' đ</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="2">Không có dữ liệu doanh thu cho năm ' . $selected_year . '.</td></tr>';
            }
        } else {
            $sql = "SELECT ngay, doanhthungay FROM db_baocao_doanhthungay 
                   WHERE YEAR(ngay) = '$selected_year'
                   ORDER BY ngay";
            
            $result = mysqli_query($connect, $sql);
            if (!$result) {
                die("Lỗi truy vấn: " . mysqli_error($connect));
            }
            echo '<tr><th>Ngày</th><th>Doanh thu</th></tr>';
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . date('d/m/Y', strtotime($row['ngay'])) . '</td>';
                    echo '<td>' . number_format($row['doanhthungay'], 0, ',', '.') . ' đ</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="2">Không có dữ liệu doanh thu cho năm ' . $selected_year . '.</td></tr>';
            }
        }
        
        echo '</table>';
    }
    else if ($export_type == 'word') {
        header('Content-Type: application/msword');
        header('Content-Disposition: attachment; filename="' . $filename . '.doc"');
        header('Cache-Control: max-age=0');
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        echo '<title>' . $filename . '</title>';
        echo '<!--[if gte mso 9]>';
        echo '<xml>';
        echo '<w:WordDocument>';
        echo '<w:View>Print</w:View>';
        echo '<w:Zoom>100</w:Zoom>';
        echo '<w:DoNotOptimizeForBrowser/>';
        echo '</w:WordDocument>';
        echo '</xml>';
        echo '<![endif]-->';
        echo '<style>';
        echo 'body { font-family: Arial, sans-serif; }';
        echo 'table { border-collapse: collapse; width: 100%; }';
        echo 'th, td { border: 1px solid #ddd; padding: 8px; }';
        echo 'th { background-color: #f2f2f2; text-align: left; }';
        echo 'h1 { color: #ff69b4; text-align: center; }';
        echo '.summary { margin-bottom: 20px; }';
        echo '.summary td:first-child { font-weight: bold; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        echo '<h1>BÁO CÁO DOANH THU NĂM ' . $selected_year . '</h1>';
        if ($view_mode == 'month') {
            $sql_summary = "SELECT 
                            SUM(monthly_revenue) as total_revenue,
                            AVG(monthly_revenue) as avg_revenue,
                            MAX(monthly_revenue) as max_revenue,
                            MIN(CASE WHEN monthly_revenue > 0 THEN monthly_revenue ELSE NULL END) as min_revenue
                            FROM (
                                SELECT 
                                    DATE_FORMAT(ngay, '%Y-%m') as month,
                                    SUM(doanhthungay) as monthly_revenue
                                FROM db_baocao_doanhthungay
                                WHERE YEAR(ngay) = '$selected_year'
                                GROUP BY DATE_FORMAT(ngay, '%Y-%m')
                            ) as monthly_data";
        } else {
            $sql_summary = "SELECT 
                            SUM(doanhthungay) as total_revenue,
                            AVG(doanhthungay) as avg_revenue,
                            MAX(doanhthungay) as max_revenue,
                            MIN(CASE WHEN doanhthungay > 0 THEN doanhthungay ELSE NULL END) as min_revenue
                            FROM db_baocao_doanhthungay
                            WHERE YEAR(ngay) = '$selected_year'";
        }
        $result_summary = mysqli_query($connect, $sql_summary);
        if (!$result_summary) {
            die("Lỗi truy vấn: " . mysqli_error($connect));
        }
        $summary = mysqli_fetch_assoc($result_summary);
        echo '<table class="summary">';
        echo '<tr><td>Tổng doanh thu năm ' . $selected_year . '</td><td>' . number_format($summary['total_revenue'], 0, ',', '.') . ' đ</td></tr>';
        if ($view_mode == 'month') {
            echo '<tr><td>Trung bình mỗi tháng</td><td>' . number_format($summary['avg_revenue'], 0, ',', '.') . ' đ</td></tr>';
        } else {
            echo '<tr><td>Trung bình mỗi ngày</td><td>' . number_format($summary['avg_revenue'], 0, ',', '.') . ' đ</td></tr>';
        }
        echo '<tr><td>Cao nhất</td><td>' . number_format($summary['max_revenue'], 0, ',', '.') . ' đ</td></tr>';
        echo '<tr><td>Thấp nhất</td><td>' . number_format($summary['min_revenue'], 0, ',', '.') . ' đ</td></tr>';
        echo '</table>';
        echo '<br>';
        if ($view_mode == 'month') {
            $sql = "SELECT DATE_FORMAT(ngay, '%Y-%m') as month, 
                    SUM(doanhthungay) as total_revenue 
                    FROM db_baocao_doanhthungay 
                    WHERE YEAR(ngay) = '$selected_year'
                    GROUP BY DATE_FORMAT(ngay, '%Y-%m') 
                    ORDER BY month";
            $result = mysqli_query($connect, $sql);
            if (!$result) {
                die("Lỗi truy vấn: " . mysqli_error($connect));
            }
            echo '<h2>Doanh thu theo tháng</h2>';
            echo '<table>';
            echo '<tr><th>Tháng</th><th>Doanh thu</th></tr>';
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $month_date = explode('-', $row['month']);
                    $month_name = 'Tháng ' . intval($month_date[1]) . '/' . $month_date[0];
                    echo '<tr>';
                    echo '<td>' . $month_name . '</td>';
                    echo '<td>' . number_format($row['total_revenue'], 0, ',', '.') . ' đ</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="2">Không có dữ liệu doanh thu cho năm ' . $selected_year . '.</td></tr>';
            }
            echo '</table>';
        } else {
            $sql = "SELECT ngay, doanhthungay FROM db_baocao_doanhthungay 
                   WHERE YEAR(ngay) = '$selected_year'
                   ORDER BY ngay";
            
            $result = mysqli_query($connect, $sql);
            if (!$result) {
                die("Lỗi truy vấn: " . mysqli_error($connect));
            }
            echo '<h2>Doanh thu theo ngày</h2>';
            echo '<table>';
            echo '<tr><th>Ngày</th><th>Doanh thu</th></tr>';
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . date('d/m/Y', strtotime($row['ngay'])) . '</td>';
                    echo '<td>' . number_format($row['doanhthungay'], 0, ',', '.') . ' đ</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="2">Không có dữ liệu doanh thu cho năm ' . $selected_year . '.</td></tr>';
            }
            echo '</table>';
        }
        echo '<p style="text-align: right; margin-top: 30px;">Ngày tạo báo cáo: ' . date('d/m/Y') . '</p>';
        echo '</body>';
        echo '</html>';
    }
    exit;
} else {
    echo '<p>Không có dữ liệu form được gửi.</p>';
}
mysqli_close($connect);
?>