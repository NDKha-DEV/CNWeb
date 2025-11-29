<?php
// Hàm phân tích cú pháp tệp data.txt (cần thiết để lấy đáp án đúng)
function parse_quiz_data($filename = 'data.txt') {
    $data = file_get_contents($filename);
    if ($data === false) {
        return false;
    }

    // Tách các khối câu hỏi bằng cách sử dụng hai dòng xuống liên tiếp (\n\n)
    $questions_raw = preg_split('/(?:\r?\n){2,}/', trim($data));
    $questions = [];
    $question_id = 1;

    foreach ($questions_raw as $raw_question) {
        $lines = array_map('trim', explode("\n", $raw_question));
        $lines = array_filter($lines);

        if (count($lines) < 2) continue;

        $question_text = '';
        $options = [];
        $answer = '';
        $is_reading_question = true;

        foreach ($lines as $line) {
            if (empty($line)) continue;

            if (strpos($line, 'ANSWER:') === 0) {
                $answer = trim(str_replace('ANSWER:', '', $line));
                $is_reading_question = false;
                break;
            }

            if ($is_reading_question && empty($options)) {
                $question_text = $line;
            } 
            
            if (preg_match('/^([A-Z]+)\.\s*(.*)/', $line, $matches)) {
                $key = $matches[1];
                $value = $matches[2];
                $options[$key] = $value;
            }
        }
        
        if (!empty($options)) {
             $question_text = array_shift($lines);
        }

        if (!empty($options) && !empty($answer)) {
            $questions[] = [
                'id' => $question_id++,
                'question_text' => $question_text,
                'options' => $options,
                // Chuyển đáp án đúng thành mảng chuẩn hóa (luôn là mảng)
                'correct_answer' => array_map('trim', explode(',', $answer))
            ];
        }
    }
    return $questions;
}

$quiz_questions = parse_quiz_data();
$user_answers = isset($_POST['answer']) ? $_POST['answer'] : [];
$total_questions = count($quiz_questions);
$score = 0;
$results = [];

if ($quiz_questions === false) {
    die("Lỗi: Không thể đọc tệp data.txt.");
}

// Logic chấm điểm
foreach ($quiz_questions as $q) {
    $q_id = $q['id'];
    $correct_answers = $q['correct_answer'];
    $user_selection = isset($user_answers[$q_id]) ? $user_answers[$q_id] : [];

    // Chuẩn hóa câu trả lời của người dùng: luôn là mảng
    if (!is_array($user_selection)) {
        $user_selection = [$user_selection];
    }

    // Sắp xếp và chuyển thành chuỗi để so sánh (đảm bảo thứ tự không quan trọng)
    sort($correct_answers);
    sort($user_selection);
    
    $is_correct = false;
    
    // Logic chấm điểm: Phải chọn ĐÚNG TẤT CẢ các đáp án đúng và KHÔNG THÊM đáp án sai.
    if ($correct_answers == $user_selection) {
        $is_correct = true;
        $score++;
    }
    
    $results[] = [
        'id' => $q_id,
        'question_text' => $q['question_text'],
        'is_correct' => $is_correct,
        'correct_answers' => implode(', ', $correct_answers),
        'user_selection' => implode(', ', $user_selection)
    ];
}

$percentage = ($total_questions > 0) ? round(($score / $total_questions) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết Quả Bài Thi</title>
    <style>
        /* CSS được giữ nguyên */
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .result-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .summary { text-align: center; margin-bottom: 30px; padding: 15px; border-radius: 5px; background-color: #e9ecef; }
        .question-result { margin-bottom: 15px; padding: 10px; border-bottom: 1px dashed #ccc; }
        .correct { color: #28a745; font-weight: bold; }
        .incorrect { color: #dc3545; font-weight: bold; }
        .question-result p { margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="result-container">
        <h1>Kết Quả Bài Thi</h1>
        <div class="summary">
            <h2>Điểm của bạn: <?php echo $score; ?>/<?php echo $total_questions; ?></h2>
            <p>Tỷ lệ đúng: **<?php echo $percentage; ?>%**</p>
        </div>

        <?php foreach ($results as $res): ?>
            <div class="question-result">
                <p><strong>Câu <?php echo $res['id']; ?>:</strong> <?php echo nl2br(htmlspecialchars($res['question_text'])); ?></p>
                <p>
                    **Kết quả:** <?php if ($res['is_correct']): ?>
                        <span class="correct">Đúng ✅</span>
                    <?php else: ?>
                        <span class="incorrect">Sai ❌</span>
                    <?php endif; ?>
                </p>
                <p>
                    **Đáp án của bạn:** **<?php echo empty($res['user_selection']) ? 'Chưa trả lời' : htmlspecialchars($res['user_selection']); ?>**
                </p>
                <p>
                    **Đáp án đúng:** <span class="correct"><?php echo htmlspecialchars($res['correct_answers']); ?></span>
                </p>
            </div>
        <?php endforeach; ?>
        <a href="index.php">Làm lại bài thi</a>
    </div>
</body>
</html>