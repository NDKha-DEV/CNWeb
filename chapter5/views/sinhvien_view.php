<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PHP Chương 5 - MVC Quản Lý Sinh Viên</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2>Thêm Sinh Viên Mới</h2>
    <form action="index.php" method="POST">
        Tên sinh viên: <input type="text" name="ten_sinh_vien" required>
        Email: <input type="email" name="email" required>
        <button type="submit">Thêm</button>
    </form>

    <hr>

    <h2>Danh Sách Sinh Viên</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên Sinh Viên</th>
            <th>Email</th>
            <th>Ngày Tạo</th>
        </tr>

        <?php
        // TODO 9 + 10: Lặp và in dữ liệu
        // Biến $danh_sach_sv được Controller (index.php) truyền vào
        // Dùng foreach để lặp qua mảng dữ liệu đã fetch
        if (!empty($danh_sach_sv)) {
            foreach ($danh_sach_sv as $sv) {
                // Đảm bảo sử dụng htmlspecialchars để chống XSS
                echo "<tr>";
                echo "<td>" . htmlspecialchars($sv['id']) . "</td>";
                echo "<td>" . htmlspecialchars($sv['ten_sinh_vien']) . "</td>";
                echo "<td>" . htmlspecialchars($sv['email']) . "</td>";
                echo "<td>" . htmlspecialchars($sv['ngay_tao']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Chưa có dữ liệu sinh viên.</td></tr>";
        }
        ?>
    </table>

</body>
</html>