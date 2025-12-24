<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btl_php";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$editProduct = null;
$action = $_POST['action'] ?? null;
$maSanPham = $_POST['maSanPham'] ?? null;
$tenSanPham = $_POST['tenSanPham'] ?? null;
$soLuong = $_POST['soLuong'] ?? null;
$donGia = $_POST['giaSanPham'] ?? null;
$moTa = $_POST['moTaSanPham'] ?? null;
$loaiSanPham = $_POST['loaiSanPham'] ?? null;
$soluongdaban = 0;
$doanhthusp = 0;

// ----------------------------- THÊM SẢN PHẨM -----------------------------
if ($action == 'add') {
    $checkSql = "SELECT * FROM db_sanpham WHERE masp = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $maSanPham);
    $stmt->execute();
    $checkResult = $stmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Mã sản phẩm đã tồn tại. Vui lòng nhập mã khác.');</script>";
    } else {
        if ($soLuong <= 0 || $donGia <= 0) {
            echo "<script>alert('Số lượng và đơn giá phải lớn hơn 0!');</script>";
        } else {
            $hinhAnh = '';
            if (isset($_FILES['themAnh']) && $_FILES['themAnh']['error'] == 0) {
                $hinhAnh = '../Picture/uploads/' . basename($_FILES['themAnh']['name']);
                move_uploaded_file($_FILES['themAnh']['tmp_name'], $hinhAnh);
            }

            $sql = "INSERT INTO db_sanpham (masp, tensp, soluong, dongia, image, mota, loaisp) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissss", $maSanPham, $tenSanPham, $soLuong, $donGia, $hinhAnh, $moTa, $loaiSanPham);

            if ($stmt->execute()) {
                $sql_baocaosp = "INSERT INTO db_baocaosp (masp, tensp, dongia, soluongnhap, soluongdaban, soluongcon, doanhthusp) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_baocaosp = $conn->prepare($sql_baocaosp);
                $stmt_baocaosp->bind_param("ssiiiii", $maSanPham, $tenSanPham, $donGia, $soLuong, $soluongdaban, $soLuong, $doanhthusp);
                $stmt_baocaosp->execute();

                header("Location: qlyproduct.php");
                exit();
            } else {
                echo "Lỗi khi thêm sản phẩm: " . $stmt->error;
            }
        }
    }
}

// ----------------------------- SỬA SẢN PHẨM -----------------------------
if ($action == 'edit') {
    $sql_get_current = "SELECT soluong FROM db_sanpham WHERE masp = ?";
    $stmt_current = $conn->prepare($sql_get_current);
    $stmt_current->bind_param("s", $maSanPham);
    $stmt_current->execute();
    $result_current = $stmt_current->get_result();
    $current_data = $result_current->fetch_assoc();
    $soLuongCu = $current_data['soluong'] ?? 0;
    $chenhLechSoLuong = $soLuong - $soLuongCu;

    $sql_select = "SELECT image FROM db_sanpham WHERE masp = ?";
    $stmt = $conn->prepare($sql_select);
    $stmt->bind_param("s", $maSanPham);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $hinhAnh = $row['image'] ?? '';

    if (isset($_FILES['themAnh']) && $_FILES['themAnh']['error'] == 0) {
        if (!empty($hinhAnh) && file_exists($hinhAnh)) unlink($hinhAnh);
        $hinhAnh = '../Picture/uploads/' . basename($_FILES['themAnh']['name']);
        move_uploaded_file($_FILES['themAnh']['tmp_name'], $hinhAnh);
    }

    if (!empty($hinhAnh)) {
        $sql = "UPDATE db_sanpham SET tensp=?, soluong=?, dongia=?, image=?, mota=?, loaisp=? WHERE masp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siissss", $tenSanPham, $soLuong, $donGia, $hinhAnh, $moTa, $loaiSanPham, $maSanPham);
    } else {
        $sql = "UPDATE db_sanpham SET tensp=?, soluong=?, dongia=?, mota=?, loaisp=? WHERE masp=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siisss", $tenSanPham, $soLuong, $donGia, $moTa, $loaiSanPham, $maSanPham);
    }

    if ($stmt->execute()) {
        $sql_update_baocaosp = "UPDATE db_baocaosp 
                                SET tensp=?, dongia=?, 
                                    soluongnhap=soluongnhap+?, 
                                    soluongcon=soluongcon+? 
                                WHERE masp=?";
        $stmt_baocaosp = $conn->prepare($sql_update_baocaosp);
        $stmt_baocaosp->bind_param("siiss", $tenSanPham, $donGia, $chenhLechSoLuong, $chenhLechSoLuong, $maSanPham);
        $stmt_baocaosp->execute();

        header("Location: qlyproduct.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật sản phẩm: " . $stmt->error;
    }
}

// ----------------------------- XÓA SẢN PHẨM -----------------------------
if (isset($_GET['delete'])) {
    $maSanPham = $_GET['delete'];
    $sql_select = "SELECT image FROM db_sanpham WHERE masp = ?";
    $stmt = $conn->prepare($sql_select);
    $stmt->bind_param("s", $maSanPham);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!empty($row['image']) && file_exists($row['image'])) unlink($row['image']);

    $sql_delete = "DELETE FROM db_sanpham WHERE masp = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("s", $maSanPham);
    if ($stmt->execute()) {
        $sql_delete_baocaosp = "DELETE FROM db_baocaosp WHERE masp = ?";
        $stmt_baocaosp = $conn->prepare($sql_delete_baocaosp);
        $stmt_baocaosp->bind_param("s", $maSanPham);
        $stmt_baocaosp->execute();

        header("Location: qlyproduct.php");
        exit();
    }
}

// ----------------------------- LẤY DỮ LIỆU EDIT -----------------------------
if (isset($_GET['edit'])) {
    $maSanPham = $_GET['edit'];
    $sql = "SELECT * FROM db_sanpham WHERE masp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $maSanPham);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) $editProduct = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="../CSS/sanpham.css">
</head>
<body>
    <nav class="nav">
        <a href="qlyproduct.php" class="nav-item active">SẢN PHẨM</a>
        <a href="curtom.php" class="nav-item">KHÁCH HÀNG</a>
        <a href="qlydonhang.php" class="nav-item">ĐƠN HÀNG</a>
        <a href="report_dtn.php" class="nav-item">BÁO CÁO-THỐNG KÊ</a>
    </nav>

    <h2>Quản lý sản phẩm</h2>
    <form id="productForm" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo isset($editProduct) ? 'edit' : 'add'; ?>">

        <label for="maSanPham">Mã sản phẩm:</label>
        <input type="text" id="maSanPham" name="maSanPham" 
               value="<?php echo $editProduct['masp'] ?? ''; ?>" 
               <?php echo isset($editProduct) ? 'readonly' : ''; ?> required>

        <label for="tenSanPham">Tên sản phẩm:</label>
        <input type="text" id="tenSanPham" name="tenSanPham"
               value="<?php echo $editProduct['tensp'] ?? ''; ?>" required>

        <label for="soLuong">Số lượng:</label>
        <input type="number" id="soLuong" name="soLuong" 
               value="<?php echo $editProduct['soluong'] ?? ''; ?>" required>

        <label for="giaSanPham">Đơn giá:</label>
        <input type="number" id="giaSanPham" name="giaSanPham" 
               value="<?php echo $editProduct['dongia'] ?? ''; ?>" required>
        <label for="loaiSanPham">Loại sản phẩm:</label>
        <select id="loaiSanPham" name="loaiSanPham" required>
            <option value="" disabled selected>Phân loại</option>
            <?php
            $sql = "SELECT loaisp FROM db_loaiSP";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($editProduct) && $editProduct['loaisp'] == $row['loaisp']) ? 'selected' : '';
                    echo "<option value='{$row['loaisp']}' $selected>{$row['loaisp']}</option>";
                }
            } else {
                echo "<option value=''>Không có loại sản phẩm</option>";
            }
            ?>
        </select>

        <label for="moTaSanPham">Mô tả sản phẩm:</label>
        <textarea id="moTaSanPham" name="moTaSanPham" rows="2"><?php echo $editProduct['mota'] ?? ''; ?></textarea>

        <button type="submit"><?php echo isset($editProduct) ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm'; ?></button>
        <?php if (isset($editProduct)): ?>
            <a href="qlyproduct.php" class="button">Hủy</a>
        <?php endif; ?>
    </form>

    <h2>Danh sách sản phẩm</h2>
    <table id="bangSanPham">
        <thead>
            <tr>
                <th>Mã</th><th>Tên</th><th>Số lượng</th><th>Giá</th><th>Ảnh</th><th>Mô tả</th><th>Loại</th><th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM db_sanpham";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['masp']}</td>
                            <td>{$row['tensp']}</td>
                            <td>{$row['soluong']}</td>
                            <td>" . number_format($row['dongia'], 0) . " ₫</td>
                            <td><img src='{$row['image']}' style='width:50px;'></td>
                            <td>{$row['mota']}</td>
                            <td>{$row['loaisp']}</td>
                            <td>
                                <a href='?edit={$row['masp']}'>Sửa</a> |
                                <a href='?delete={$row['masp']}' onclick=\"return confirm('Xóa sản phẩm này?');\">Xóa</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Không có sản phẩm nào</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php $conn->close(); ?>
