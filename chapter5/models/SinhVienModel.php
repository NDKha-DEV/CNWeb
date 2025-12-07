<?php
// Tệp: models/SinhVienModel.php

/**
 * TODO 1: Viết 1 hàm tên là getAllSinhVien()
 * Hàm này nhận 1 tham số là đối tượng PDO $pdo
 * Bên trong hàm, thực thi câu lệnh SELECT * và trả về kết quả
 */
function getAllSinhVien(PDO $pdo) {
    // Truy vấn SELECT *
    $sql = "SELECT id, ten_sinh_vien, email, ngay_tao FROM sinhvien ORDER BY ngay_tao DESC";
    
    // Thực thi và lấy tất cả kết quả
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * TODO 2: Viết 1 hàm tên là addSinhVien()
 * Hàm này nhận 3 tham số: $pdo, $ten, $email
 * Bên trong hàm, thực thi câu lệnh INSERT (dùng Prepared Statement)
 */
function addSinhVien(PDO $pdo, string $ten, string $email) {
    // SQL INSERT dùng Prepared Statement để ngăn chặn SQL Injection
    $sql = "INSERT INTO sinhvien (ten_sinh_vien, email) VALUES (?, ?)";
    
    // Chuẩn bị và thực thi
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ten, $email]);
    
    // Hàm này không cần trả về gì, chỉ thực hiện thao tác INSERT
}
?>