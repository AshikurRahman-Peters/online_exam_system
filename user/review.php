<?php
require_once '../includes/user_auth.php';

if (!isset($_GET['attempt_id']) || empty($_GET['attempt_id'])) {
    die("Attempt ID missing.");
}

$attempt_id = (int) $_GET['attempt_id'];
$user_id = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Get attempt info and verify this result belongs to logged-in user
|--------------------------------------------------------------------------
*/
$attempt_sql = "
    SELECT ea.*, e.title AS exam_title, e.description AS exam_description
    FROM exam_attempts ea
    INNER JOIN exams e ON ea.exam_id = e.id
    WHERE ea.id = $attempt_id AND ea.user_id = $user_id
    LIMIT 1
";
$attempt = $conn->query($attempt_sql)->fetch_assoc();

if (!$attempt) {
    die("Result not found or access denied.");
}

/*
|--------------------------------------------------------------------------
| Load all answered questions for this attempt
|--------------------------------------------------------------------------
*/
$review_sql = "
    SELECT 
        q.id,
        q.question_text,
        q.question_type,
        q.option_a,
        q.option_b,
        q.option_c,
        q.option_d,
        q.correct_answer,
        q.marks,
        ans.selected_answer,
        ans.is_correct
    FROM exam_answers ans
    INNER JOIN questions q ON ans.question_id = q.id
    WHERE ans.attempt_id = $attempt_id
    ORDER BY q.id ASC
";
$review_result = $conn->query($review_sql);

include '../includes/user_header.php';

/*
|--------------------------------------------------------------------------
| Helper function to convert answer code to readable text
|--------------------------------------------------------------------------
*/
function displayAnswerText($question, $answer)
{
    $answer = trim((string)$answer);

    if ($question['question_type'] === 'mcq') {
        $map = [
            'A' => 'A. ' . $question['option_a'],
            'B' => 'B. ' . $question['option_b'],
            'C' => 'C. ' . $question['option_c'],
            'D' => 'D. ' . $question['option_d'],
        ];

        return $map[$answer] ?? $answer;
    }

    // true/false
    return $answer !== '' ? $answer : 'Not Answered';
}
?>

<style>
.review-summary-card {
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    color: #fff;
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 10px 30px rgba(37, 99, 235, 0.18);
    margin-bottom: 24px;
}

.review-stat {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 14px;
    padding: 16px;
    text-align: center;
    height: 100%;
}

.review-stat h4 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.review-stat p {
    margin: 6px 0 0;
    font-size: 14px;
    opacity: 0.95;
}

.review-question-card {
    background: #fff;
    border-radius: 16px;
    padding: 22px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
    border: 1px solid #eef2f7;
    margin-bottom: 20px;
}

.review-question-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 14px;
}

.option-box {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 14px;
    margin-bottom: 10px;
}

.answer-box {
    border-radius: 14px;
    padding: 14px 16px;
    margin-top: 12px;
}

.answer-correct {
    background: #ecfdf5;
    border: 1px solid #bbf7d0;
}

.answer-wrong {
    background: #fef2f2;
    border: 1px solid #fecaca;
}

.answer-label {
    font-weight: 700;
    display: block;
    margin-bottom: 6px;
}

.question-badge {
    font-size: 13px;
    padding: 7px 12px;
    border-radius: 20px;
}

.marks-badge {
    font-size: 13px;
    padding: 7px 12px;
    border-radius: 20px;
}
</style>

<div class="review-summary-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="mb-1">Exam Review</h2>
            <p class="mb-1"><strong><?= htmlspecialchars($attempt['exam_title']) ?></strong></p>
            <?php if (!empty($attempt['exam_description'])): ?>
                <p class="mb-0"><?= htmlspecialchars($attempt['exam_description']) ?></p>
            <?php endif; ?>
        </div>

        <div class="text-end">
            <div class="mb-1"><strong>Submitted:</strong> <?= htmlspecialchars($attempt['submitted_at']) ?></div>
            <a href="result.php?attempt_id=<?= $attempt_id ?>" class="btn btn-light btn-rounded btn-sm">Back to Result</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="review-stat">
                <h4><?= (int)$attempt['score'] ?></h4>
                <p>Score</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="review-stat">
                <h4><?= (int)$attempt['total_questions'] ?></h4>
                <p>Total Questions</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="review-stat">
                <h4><?= (int)$attempt['correct_answers'] ?></h4>
                <p>Correct Answers</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="review-stat">
                <h4><?= (int)$attempt['wrong_answers'] ?></h4>
                <p>Wrong Answers</p>
            </div>
        </div>
    </div>
</div>

<?php if ($review_result && $review_result->num_rows > 0): ?>
    <?php $serial = 1; ?>
    <?php while ($row = $review_result->fetch_assoc()): ?>
        <?php
            $selected_answer = trim((string)$row['selected_answer']);
            $correct_answer  = trim((string)$row['correct_answer']);

            $selected_display = $selected_answer !== ''
                ? displayAnswerText($row, $selected_answer)
                : 'Not Answered';

            $correct_display = displayAnswerText($row, $correct_answer);

            $is_correct = (int)$row['is_correct'] === 1;
        ?>

        <div class="review-question-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div class="review-question-title">
                    Q<?= $serial++ ?>. <?= htmlspecialchars($row['question_text']) ?>
                </div>

                <div class="d-flex gap-2">
                    <?php if ($row['question_type'] === 'mcq'): ?>
                        <span class="badge bg-primary question-badge">MCQ</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark question-badge">True / False</span>
                    <?php endif; ?>

                    <span class="badge bg-dark marks-badge"><?= (int)$row['marks'] ?> Mark</span>

                    <?php if ($is_correct): ?>
                        <span class="badge bg-success question-badge">Correct</span>
                    <?php else: ?>
                        <span class="badge bg-danger question-badge">Wrong</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($row['question_type'] === 'mcq'): ?>
                <div class="option-box"><strong>A.</strong> <?= htmlspecialchars($row['option_a']) ?></div>
                <div class="option-box"><strong>B.</strong> <?= htmlspecialchars($row['option_b']) ?></div>
                <div class="option-box"><strong>C.</strong> <?= htmlspecialchars($row['option_c']) ?></div>
                <div class="option-box"><strong>D.</strong> <?= htmlspecialchars($row['option_d']) ?></div>
            <?php else: ?>
                <div class="option-box"><strong>Available options:</strong> True / False</div>
            <?php endif; ?>

            <div class="answer-box <?= $is_correct ? 'answer-correct' : 'answer-wrong' ?>">
                <span class="answer-label">
                    <?= $is_correct ? '✓ Your Answer' : '✗ Your Answer' ?>
                </span>
                <?= htmlspecialchars($selected_display) ?>
            </div>

            <?php if (!$is_correct): ?>
                <div class="answer-box answer-correct">
                    <span class="answer-label">✓ Correct Answer</span>
                    <?= htmlspecialchars($correct_display) ?>
                </div>
            <?php else: ?>
                <div class="answer-box answer-correct">
                    <span class="answer-label">Correct Answer</span>
                    <?= htmlspecialchars($correct_display) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="card-box">
        <div class="alert alert-info mb-0">No review data found for this attempt.</div>
    </div>
<?php endif; ?>

<div class="card-box">
    <div class="d-flex gap-2 flex-wrap">
        <a href="result.php?attempt_id=<?= $attempt_id ?>" class="btn btn-primary btn-rounded">Back to Result</a>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-rounded">Back to Dashboard</a>
    </div>
</div>

<?php include '../includes/user_footer.php'; ?>