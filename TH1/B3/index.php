<?php
// index.php
// Nhận file CSV: nếu có upload sẽ lưu vào "uploads/accounts.csv", ngược lại nếu file "accounts.csv" tồn tại thì đọc file đó.

$uploadDir = __DIR__ . '/uploads';
@mkdir($uploadDir, 0755, true);

$csvPath = __DIR__ . '/uploads/65HTTT_Danh_sach_diem_danh.csv'; // mặc định nếu bạn đã đặt sẵn
$usedFile = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvfile'])) {
    if ($_FILES['csvfile']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['csvfile']['tmp_name'];
        $name = basename($_FILES['csvfile']['name']);
        // bảo đảm phần mở rộng .csv
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            $error = "Vui lòng upload file .csv";
        } else {
            $dest = $uploadDir . '/uploaded.csv';
            if (!move_uploaded_file($tmp, $dest)) {
                $error = "Lưu file thất bại.";
            } else {
                $usedFile = $dest;
            }
        }
    } else {
        $error = "Upload lỗi (code: {$_FILES['csvfile']['error']})";
    }
}

// Nếu không upload, dùng accounts.csv nếu tồn tại
if (!$usedFile) {
    if (file_exists($csvPath)) {
        $usedFile = $csvPath;
    }
}

// Hàm đọc CSV trả về [headers, rows]
function read_csv_file($path, $delimiter = ',', $maxRows = 1000) {
    $rows = [];
    if (!file_exists($path)) return [[], $rows];

    $handle = fopen($path, 'r');
    if (!$handle) return [[], $rows];

    // detect BOM and remove from first field if cần
    $firstLine = fgets($handle);
    if ($firstLine === false) {
        fclose($handle);
        return [[], $rows];
    }
    // rewind and use fgetcsv normally but handle BOM
    rewind($handle);

    $headers = [];
    $lineNo = 0;
    while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
        $lineNo++;
        // skip empty lines
        $allEmpty = true;
        foreach ($data as $c) if (trim($c) !== '') { $allEmpty = false; break; }
        if ($allEmpty) continue;

        // remove BOM on first cell (UTF-8 BOM)
        if ($lineNo === 1 && isset($data[0])) {
            $data[0] = preg_replace('/^\x{FEFF}/u', '', $data[0]);
        }

        if (empty($headers)) {
            // treat first non-empty line as header if plausible (contains non-numeric or many columns)
            $headers = $data;
        } else {
            // if row has fewer fields than headers, pad with empty strings
            if (count($data) < count($headers)) {
                $data = array_merge($data, array_fill(0, count($headers) - count($data), ''));
            }
            $rows[] = $data;
        }

        if (count($rows) >= $maxRows) break;
    }
    fclose($handle);
    return [$headers, $rows];
}

$headers = $rows = [];
if ($usedFile) {
    list($headers, $rows) = read_csv_file($usedFile);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Hiển thị CSV — Danh sách tài khoản</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; max-width: 1200px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background: #f7f7f7; }
    .meta { margin: 10px 0 20px; }
    .error { color: #a00; font-weight: bold; }
    .controls { margin-bottom: 16px; }
    pre.sql { background:#f4f4f4; padding:10px; border:1px solid #ddd; overflow:auto; max-height:300px; }
</style>
</head>
<body>

<h1>Đọc & Hiển thị tệp CSV — Danh sách tài khoản</h1>

<div class="controls">
    <form method="post" enctype="multipart/form-data">
        <label>Upload file CSV: <input type="file" name="csvfile" accept=".csv"></label>
        <button type="submit">Upload / Preview</button>
        &nbsp;&nbsp;
        <!-- <a href="?use_default=1">Dùng file accounts.csv mặc định (nếu có)</a> -->
    </form>
</div>

<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!$usedFile): ?>
    <p>Không tìm thấy tệp CSV. Bạn có thể upload file CSV hoặc tạo file <code>accounts.csv</code> cạnh file <code>index.php</code>.</p>
<?php else: ?>
    <div class="meta">
        <!-- <strong>Đang đọc:</strong> <?= htmlspecialchars($usedFile) ?><br> -->
        <strong>Số cột:</strong> <?= count($headers) ?> — <strong>Số bản ghi:</strong> <?= count($rows) ?>
    </div>

    <?php if (empty($headers)): ?>
        <p class="error">Không tìm thấy header trong file CSV hoặc file rỗng.</p>
    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <?php foreach ($headers as $h): ?>
                        <th><?= htmlspecialchars($h) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $i => $r): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <?php
                        foreach ($r as $c) {
                            echo '<td>' . nl2br(htmlspecialchars($c)) . '</td>';
                        }
                        ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- <h3 style="margin-top:20px;">Quick SQL preview (INSERT statements)</h3>
        <p>Nhấn sao chép & dán vào SQL client / script để import - điều chỉnh tên bảng/kiểu dữ liệu theo CSDL của bạn.</p>
        <pre class="sql"><?php
            // Tạo 1 số INSERT (chỉ để demo). Không chạy tự động.
            $table = 'accounts';
            $cols = array_map(function($c){ return trim($c); }, $headers);
            $colList = implode(', ', array_map(function($c){ return "`".str_replace("`","``",$c)."`"; }, $cols));

            $max = min(50, count($rows)); // chỉ hiện tối đa 50 dòng cho preview
            for ($i=0;$i<$max;$i++) {
                $r = $rows[$i];
                // escape single quotes
                $vals = array_map(function($v){ return "'" . str_replace("'", "''", $v) . "'"; }, $r);
                echo "INSERT INTO `{$table}` ({$colList}) VALUES (".implode(", ", $vals).");\n";
            }
            if (count($rows) > $max) echo "-- ... (" . (count($rows)-$max) . " dòng tiếp theo không hiển thị)\n";
        ?></pre> -->

    <?php endif; ?>
<?php endif; ?>

</body>
</html>
