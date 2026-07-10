<?php
require_once '../includes/user_auth.php';

if (!isset($_GET['attempt_id'])) {
    die("Attempt ID missing.");
}

$attempt_id = (int) $_GET['attempt_id'];

$sql = "SELECT ea.*, e.title AS exam_title
        FROM exam_attempts ea
        JOIN exams e ON ea.exam_id = e.id
        WHERE ea.id = $attempt_id AND ea.user_id = " . (int)$_SESSION['user_id'];

$result = $conn->query($sql)->fetch_assoc();

if (!$result) {
    die("Result not found.");
}

include '../includes/user_header.php';
?>

<div class="result-box">
    <div class="text-center mb-4">
        <h2 class="mb-2">Exam Result</h2>
        <p class="text-muted mb-0"><?= htmlspecialchars($result['exam_title']) ?></p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-mini">
                <h4><?= (int)$result['score'] ?></h4>
                <p>Score</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-mini">
                <h4><?= (int)$result['total_questions'] ?></h4>
                <p>Total Questions</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-mini">
                <h4><?= (int)$result['correct_answers'] ?></h4>
                <p>Correct Answers</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-mini">
                <h4><?= (int)$result['wrong_answers'] ?></h4>
                <p>Wrong Answers</p>
            </div>
        </div>
    </div>

    <div class="card-box border">
        <p><strong>Submitted At:</strong> <?= htmlspecialchars($result['submitted_at']) ?></p>

        <div class="d-flex gap-2 flex-wrap">
            <a href="review.php?attempt_id=<?= $attempt_id ?>" class="btn btn-success btn-rounded">
                Review Answers
            </a>

            <a href="dashboard.php" class="btn btn-primary btn-rounded">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

<?php include '../includes/user_footer.php'; ?>