<?php
// Tệp: index.php (Controller)

// TODO 1: Import Model
require 'models/SinhVienModel.php';

// === THIẾT LẬP KẾT NỐI PDO ===
$host = '127.0.0.1';
$dbname = 'cse485_web';
$username = 'root';
$password = '';

// Lưu ý: Cổng (port) có thể khác tùy cấu hình của bạn
$dsn = "mysql:host=$host;port=3307;dbname=$dbname;charset=utf8mb4";

try {
    // TODO 2: Kết nối PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
// === KẾT THÚC KẾT NỐI PDO ===


// === LOGIC CỦA CONTROLLER ===

/**
 * TODO 8: Kiểm tra xem có hành động POST (thêm sinh viên) không
 * Dùng isset($_POST['ten_sinh_vien'])
 */
if (isset($_POST['ten_sinh_vien']) && isset($_POST['email'])) {

    // TODO 9: Nếu có, lấy $ten và $email từ $_POST
    $ten = $_POST['ten_sinh_vien'];
    $email = $_POST['email'];

    // TODO 10: Gọi hàm addSinhVien() từ Model
    // (Truyền $pdo, $ten, $email vào hàm)
    addSinhVien($pdo, $ten, $email);

    // TODO 11: Chuyển hướng về index.php để "làm mới" trang
    // Điều này ngăn chặn việc gửi lại form khi người dùng refresh (Pattern Post/Redirect/Get - PRG)
    header('Location: index.php');
    exit;
}

// TODO 12: (Luôn luôn) Gọi hàm getAllSinhVien() từ Model
// (Truyền $pdo vào hàm)
// Lưu kết quả trả về vào một biến
$danh_sach_sv = getAllSinhVien($pdo);

/**
 * TODO 13: (Rất quan trọng) Import (include) tệp View ở cuối cùng
 * Tệp View sẽ tự động "nhìn thấy" biến $danh_sach_sv mà ta vừa tạo
 */
include 'views/sinhvien_view.php';
?>