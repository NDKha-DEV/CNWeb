<?php
$listHoa = [
    [
        'id' => 1,
        'name' => 'Hoa Đỗ quyên',
        'description' => 'Tượng trưng cho sự kiên định và lạc quan, Đỗ Quyên khoe sắc rực rỡ dưới ánh nắng hè. Đây là loài hoa lý tưởng để tạo điểm nhấn ấm áp cho khu vườn.',
        'image' => 'images/doquyen.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Hoa Hải Đường',
        'description' => 'Loài hoa vương giả, nở rộ nhất vào mùa xuân và đầu hè. Hải Đường đa dạng về màu sắc, mỗi màu mang một ý nghĩa riêng về tình yêu và lòng ngưỡng mộ.',
        'image' => 'images/haiduong.jpg'
    ],
    [
        'id' => 3,
        'name' => 'Hoa Mai',
        'description' => 'Với màu tím lãng mạn và hương thơm dịu nhẹ, Maikhông chỉ đẹp mà còn có tác dụng thư giãn. Phù hợp trồng ở nơi có nhiều nắng.',
        'image' => 'images/mai.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Hoa Tường Vy',
        'description' => 'Mang ý nghĩa về lòng biết ơn và vẻ đẹp lộng lẫy, Tường Vy đổi màu tùy thuộc vào độ pH của đất, tạo nên sự thú vị cho người trồng.',
        'image' => 'images/tuongvy.jpg'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bai Tap 1</title>
</head>

<body>
    <h2>Day la danh sach nhung loai hoa dep</h2>
    
    <?php
    echo "<ol>";
        foreach($listHoa as $hoa)
        {
            echo "<li>" ;
                echo "<span style=\"font-weight: bold;\">" . htmlspecialchars($hoa["name"]) . "</span>";
                echo "<br>";
                echo "<p>" . htmlspecialchars($hoa["description"]). "</p>";
                echo "<br>";
                echo "<img src=\"" . htmlspecialchars($hoa["image"]) . "\">";
            echo "</li>";
        }
    echo "</ol>";
    ?> 
</body>
</html>