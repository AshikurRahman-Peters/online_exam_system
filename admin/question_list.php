<?php
require_once '../includes/admin_auth.php';

if (!isset($_GET['exam_id']) || empty($_GET['exam_id'])) {
    die("Exam ID missing.");
}

$exam_id = (int) $_GET['exam_id'];

$exam = $conn->query("SELECT * FROM exams WHERE id = $exam_id")->fetch_assoc();
if (!$exam) {
    die("Exam not found.");
}

$questions = $conn->query("SELECT * FROM questions WHERE exam_id = $exam_id ORDER BY id DESC");

include '../includes/admin_header.php';
?>

<div class="card-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="section-title mb-1">Question List</h2>
            <p class="text-muted mb-0">
                Exam: <strong><?= htmlspecialchars($exam['title']) ?></strong>
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="exams.php" class="btn btn-outline-secondary btn-rounded">Back to Exams</a>
            <a href="add_question.php?exam_id=<?= $exam['id'] ?>" class="btn btn-primary btn-rounded">+ Add Question</a>
        </div>
    </div>

    <div class="mb-3">
        <span class="badge bg-dark">Total Questions: <?= $questions->num_rows ?></span>
        <span class="badge bg-success ms-2">Total Marks: <?= $exam['total_marks'] ?></span>
        <span class="badge bg-info text-dark ms-2">Duration: <?= $exam['duration'] ?> min</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-bordered">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Question</th>
                    <th style="width: 120px;">Type</th>
                    <th>Options</th>
                    <th style="width: 140px;">Correct Answer</th>
                    <th style="width: 90px;">Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($questions && $questions->num_rows > 0): ?>
                    <?php $sl = 1; ?>
                    <?php while ($q = $questions->fetch_assoc()): ?>
                        <tr>
                            <td><?= $sl++ ?></td>
                            <td><?= htmlspecialchars($q['question_text']) ?></td>

                            <td>
                                <?php if ($q['question_type'] === 'mcq'): ?>
                                    <span class="badge bg-primary">MCQ</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">True/False</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($q['question_type'] === 'mcq'): ?>
                                    <div><strong>A:</strong> <?= htmlspecialchars($q['option_a']) ?></div>
                                    <div><strong>B:</strong> <?= htmlspecialchars($q['option_b']) ?></div>
                                    <div><strong>C:</strong> <?= htmlspecialchars($q['option_c']) ?></div>
                                    <div><strong>D:</strong> <?= htmlspecialchars($q['option_d']) ?></div>
                                <?php else: ?>
                                    <span class="text-muted">True / False</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="badge bg-success"><?= htmlspecialchars($q['correct_answer']) ?></span>
                            </td>

                            <td><?= $q['marks'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No questions found for this exam.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>