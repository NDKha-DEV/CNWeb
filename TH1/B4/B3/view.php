<!-- <?php
require 'db.php'; // chứa biến $mysqli

// Chuẩn bị câu lệnh SELECT
$sql = "SELECT * FROM ds_sinhvien ORDER BY id DESC";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Lỗi prepare: " . $mysqli->error);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách sinh viên</title>
<style>
table { border-collapse: collapse; width: 100%; }
td, th { border: 1px solid #ccc; padding: 8px; }
th { background: #eee; }
</style>
</head>
<body>

<h2>Dữ liệu sinh viên đã import</h2>
<a href="index.php">← Quay lại upload</a>
<br><br>

<table>
    <tr>
        <th>ID</th>
        <th>#</th>
        <th>Username</th>
        <th>Password</th>
        <th>Lastname</th>
        <th>Firstname</th>
        <th>City</th>
        <th>Email</th>
        <th>Course 1</th>
    </tr>

    <?php foreach ($data as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['so_thu_tu']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['password']) ?></td>
        <td><?= htmlspecialchars($row['lastname']) ?></td>
        <td><?= htmlspecialchars($row['firstname']) ?></td>
        <td><?= htmlspecialchars($row['city']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['course1']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html> -->
