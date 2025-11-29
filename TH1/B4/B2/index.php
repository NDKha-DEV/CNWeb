<?php
// Hàm phân tích cú pháp tệp data.txt
function parse_quiz_data($filename = 'data.txt') {
    // Đọc nội dung tệp
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
        $lines = array_filter($lines); // Lọc bỏ dòng trống

        if (count($lines) < 2) continue;

        $question_text = '';
        $options = [];
        $answer = '';
        $is_reading_question = true;

        foreach ($lines as $line) {
            if (empty($line)) continue;

            if (strpos($line, 'ANSWER:') === 0) {
                $answer = trim(str_replace('ANSWER:', '', $line));
                $is_reading_question = false; // Ngừng đọc phần câu hỏi/tùy chọn
                break;
            }

            // Nếu đang đọc câu hỏi (thường là dòng đầu tiên)
            if ($is_reading_question && empty($options)) {
                $question_text = $line;
            } 
            
            // Lấy Tùy chọn (A., B., C., D., ...)
            if (preg_match('/^([A-Z]+)\.\s*(.*)/', $line, $matches)) {
                $key = $matches[1];
                $value = $matches[2];
                $options[$key] = $value;
                // Nếu sau khi lấy tùy chọn mà $question_text vẫn chưa được gán, gán nó.
                if (empty($question_text)) {
                    // Đây là logic phức tạp nếu câu hỏi bị trải dài, nhưng dựa trên định dạng 
                    // mẫu, ta giữ lại dòng logic ở trên. Ta chỉ cần đảm bảo options được điền.
                }
            }
        }

        // Loại bỏ các dòng options khỏi $question_text nếu chúng bị thêm vào nhầm
        if (!empty($options)) {
             $question_text = array_shift($lines);
        }


        if (!empty($options) && !empty($answer)) {
            // Xác định loại input: checkbox (multiple answers) hoặc radio (single answer)
            $input_type = (strpos($answer, ',') !== false) ? 'checkbox' : 'radio';

            $questions[] = [
                'id' => $question_id++,
                'question_text' => $question_text,
                'options' => $options,
                'correct_answer' => $answer, // Giữ lại để đối chiếu ở submit.php
                'input_type' => $input_type
            ];
        }
    }
    return $questions;
}

$quiz_questions = parse_quiz_data();

if ($quiz_questions === false) {
    die("Lỗi: Không thể đọc tệp data.txt.");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài Thi Trắc Nghiệm Android</title>
    <style>
        /* CSS được giữ nguyên */
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .quiz-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .question { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #fafafa; }
        .question p { font-weight: bold; margin-top: 0; }
        .option-item { margin: 5px 0; }
        .submit-btn { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .submit-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h1>Bài Thi Trắc Nghiệm Android</h1>
        <form action="submit.php" method="post">
            <?php foreach ($quiz_questions as $q): ?>
                <div class="question" id="q_<?php echo $q['id']; ?>">
                    <p>
                        **Câu <?php echo $q['id']; ?>:** <?php echo nl2br(htmlspecialchars($q['question_text'])); ?>
                        <?php if ($q['input_type'] === 'checkbox'): ?>
                            <small style="color: #dc3545;">(Chọn nhiều đáp án)</small>
                        <?php endif; ?>
                    </p>
                    <?php foreach ($q['options'] as $key => $value): ?>
                        <div class="option-item">
                            <input 
                                type="<?php echo $q['input_type']; ?>" 
                                id="q<?php echo $q['id'] . $key; ?>" 
                                name="answer[<?php echo $q['id']; ?>]<?php echo $q['input_type'] === 'checkbox' ? '[]' : ''; ?>" 
                                value="<?php echo $key; ?>"
                            >
                            <label for="q<?php echo $q['id'] . $key; ?>">
                                **<?php echo $key; ?>.** <?php echo htmlspecialchars($value); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <input type="hidden" name="total_questions" value="<?php echo count($quiz_questions); ?>">
            <button type="submit" class="submit-btn">Nộp Bài</button>
        </form>
    </div>
</body>
</html>