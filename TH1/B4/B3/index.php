<?php
session_start();   // ← thêm dòng này
require 'db.php'; // Kết nối MySQL

$uploadDir = __DIR__ . '/uploads';
@mkdir($uploadDir, 0755, true);

$csvPath = __DIR__ . '/uploads/65HTTT_Danh_sach_diem_danh.csv';
$usedFile = null;
$error = "";
$importMessage = "";

// ─────────────────────────────────────────────
// 1) Upload CSV
// ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvfile'])) {
    if ($_FILES['csvfile']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['csvfile']['tmp_name'];
        $name = basename($_FILES['csvfile']['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if ($ext !== 'csv') {
            $error = "Vui lòng upload file CSV!";
        } else {
            $dest = $uploadDir . '/uploaded.csv';
            if (move_uploaded_file($tmp, $dest)) {
                $usedFile = $dest;
                $_SESSION['csv_used_file'] = $dest; // ← lưu vào session
            } else {
                $error = "Không thể lưu file CSV!";
            }
        }
    }
}

// Nếu không upload → kiểm tra session, rồi mới dùng file mặc định
if (!$usedFile) {
    if (isset($_SESSION['csv_used_file']) && file_exists($_SESSION['csv_used_file'])) {
        $usedFile = $_SESSION['csv_used_file'];
    } elseif (file_exists($csvPath)) {
        $usedFile = $csvPath;
    }
}

// ─────────────────────────────────────────────
// 2) Hàm đọc file CSV
// ─────────────────────────────────────────────
function read_csv_file($path, $delimiter = ',', $maxRows = 2000) {
    $rows = [];
    if (!file_exists($path)) return [[], $rows];
    $handle = fopen($path, 'r');
    if (!$handle) return [[], $rows];

    $headers = [];
    $line = 0;

    while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
        $line++;
        if ($line === 1) {
            $data[0] = preg_replace('/^\x{FEFF}/u', '', $data[0]); 
            $headers = $data;
            continue;
        }
        if (count($data) < count($headers)) {
            $data = array_merge($data, array_fill(0, count($headers) - count($data), ""));
        }
        $rows[] = $data;
        if (count($rows) >= $maxRows) break;
    }

    fclose($handle);
    return [$headers, $rows];
}

$headers = $rows = [];
if ($usedFile) {
    list($headers, $rows) = read_csv_file($usedFile);
}

// ─────────────────────────────────────────────
// 3) Import vào MySQL
// ─────────────────────────────────────────────
if (isset($_POST["import_db"])) {
    // Dùng file vừa upload (hoặc file mặc định)
    if (isset($_SESSION['csv_used_file']) && file_exists($_SESSION['csv_used_file'])) {
        $usedFile = $_SESSION['csv_used_file'];
    } elseif (file_exists($csvPath)) {
        $usedFile = $csvPath;
    }

    list($headers, $rows) = read_csv_file($usedFile);

    $sql = "INSERT INTO ds_sinhvien1 
            (username, password, lastname, firstname, city, email, course1)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    $count = 0;

    foreach ($rows as $r) {
        if (count($r) < 7) continue;

        $stmt->bind_param("sssssss",
            $r[0], $r[1], $r[2], $r[3], $r[4], $r[5], $r[6]
        );

        $stmt->execute();
        $count++;
    }

    $importMessage = "✔ Đã import thành công <b>$count</b> dòng vào CSDL!";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
table{border-collapse:collapse}td,th{border:1px solid #ccc;padding:5px}
</style>
</head>
<body>

<h2>Upload & Hiển thị CSV</h2>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="csvfile" accept=".csv">
    <button type="submit">Upload / Preview</button>
</form>

<?php if ($error): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<?php if ($importMessage): ?>
    <p style="color:green"><?= $importMessage ?></p>
<?php endif; ?>

<?php if ($usedFile && !empty($headers)): ?>
    <h3>Bảng dữ liệu CSV</h3>

    <form method="post">
        <button name="import_db" value="1">📥 Import vào MySQL</button>
    </form>

    <table>
        <tr>
            <th>#</th>
            <?php foreach ($headers as $h): ?>
                <th><?= htmlspecialchars($h) ?></th>
            <?php endforeach; ?>
        </tr>

        <?php foreach ($rows as $i => $r): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <?php foreach ($r as $c): ?>
                <td><?= htmlspecialchars($c) ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>

</body>
</html>
