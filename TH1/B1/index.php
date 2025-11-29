<?php
$flowers = [
    [
        'name' => 'Đỗ Quyên',
        'description' => 'Mang vẻ đẹp kiêu sa, hương thơm nồng nàn, thích hợp trồng cổng ngõ, hàng rào.',
        'image' => 'images/doquyen.jpg'
    ],
    [
        'name' => 'Hải Dương',
        'description' => 'Loài hoa rực rỡ, dễ trồng, chịu hạn tốt, nở rộ vào mùa hè tạo bóng mát.',
        'image' => 'images/haiduong.jpg'
    ],
    [
        'name' => 'Mai',
        'description' => 'Nhỏ nhắn, xinh xắn, nở rộ vào khoảng 10 giờ sáng, rất dễ chăm sóc.',
        'image' => 'images/mai.jpg'
    ],
    [
        'name' => 'Tường Vy',
        'description' => 'Màu sắc đa dạng, sai hoa, thường được trồng trong chậu treo rủ xuống rất đẹp.',
        'image' => 'images/tuongvy.jpg'
    ]
];

$view = isset($_GET['view']) ? $_GET['view'] : 'guest';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>14 Loại Hoa Đẹp Xuân Hè</title>
    <!-- Sử dụng Bootstrap 5 để trang trí nhanh -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-img-top { height: 200px; object-fit: cover; }
        .admin-img-thumb { width: 80px; height: 80px; object-fit: cover; border-radius: 5px; }
        .page-header { background: linear-gradient(135deg, #16a085, #f4d03f); color: white; padding: 40px 0; margin-bottom: 30px; }
        .nav-buttons .btn { margin-right: 10px; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="page-header text-center shadow-sm">
        <h1>🌸 4 Loại Hoa Tuyệt Đẹp Khoe Sắc Dịp Xuân Hè 🌸</h1>
        <p class="lead">Tuyển tập những loài hoa dễ trồng và rực rỡ nhất</p>
    </div>

    <div class="container mb-5">
        <!-- Thanh điều hướng -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded shadow-sm">
            <h4 class="mb-0 text-secondary">
                Chế độ xem: <span class="badge bg-<?php echo $view == 'admin' ? 'danger' : 'success'; ?>">
                    <?php echo $view == 'admin' ? 'Quản trị viên (Admin)' : 'Khách (Guest)'; ?>
                </span>
            </h4>
            <div class="nav-buttons">
                <a href="?view=guest" class="btn btn-outline-success <?php echo $view == 'guest' ? 'active' : ''; ?>">
                    <i class="bi bi-grid"></i> Xem dạng Khách
                </a>
                <a href="?view=admin" class="btn btn-outline-danger <?php echo $view == 'admin' ? 'active' : ''; ?>">
                    <i class="bi bi-table"></i> Xem dạng Quản trị
                </a>
            </div>
        </div>

        <!-- PHẦN HIỂN THỊ DÀNH CHO KHÁCH (GUEST) -->
        <?php if ($view == 'guest'): ?>
            <div class="row g-4">
                <?php foreach ($flowers as $flower): ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card h-100 shadow-sm border-0 hover-effect">
                            <img src="<?php echo $flower['image']; ?>" class="card-img-top" alt="<?php echo $flower['name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title text-success"><?php echo $flower['name']; ?></h5>
                                <p class="card-text text-muted"><?php echo $flower['description']; ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <button class="btn btn-sm btn-success w-100">Chi tiết</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        
        <!-- PHẦN HIỂN THỊ DÀNH CHO QUẢN TRỊ VIÊN (ADMIN) -->
        <?php elseif ($view == 'admin'): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Quản Lý Danh Sách Hoa</h5>
                    <button class="btn btn-sm btn-light">+ Thêm mới</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    
                                    <th scope="col">Tên loài hoa</th>
                                    <th scope="col">Mô tả</th>
                                    <th scope="col" class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($flowers as $index => $flower): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo $index + 1; ?></td>
                                        
                                        <td class="fw-bold text-danger"><?php echo $flower['name']; ?></td>
                                        <td><?php echo $flower['description']; ?></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-primary">Sửa</button>
                                                <button type="button" class="btn btn-sm btn-warning">Ẩn</button>
                                                <button type="button" class="btn btn-sm btn-danger">Xóa</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-muted text-end">
                    Tổng số: <?php echo count($flowers); ?> loài hoa
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer class="text-center py-4 text-muted border-top mt-5">
        <p>© 2025 Thế Giới Các Loài Hoa. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>