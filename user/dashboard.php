<?php
require_once '../includes/user_auth.php';

$user_id = $_SESSION['user_id'];

$exams = $conn->query("SELECT * FROM exams WHERE status = 1 ORDER BY id DESC");
$attempt_count = $conn->query("SELECT COUNT(*) AS total FROM exam_attempts WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0;

include '../includes/user_header.php';
?>

<div class="welcome-card">
    <h2 class="mb-2">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h2>
    <p class="mb-0">Choose an available exam, submit your answers, and see your result instantly.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="card-box text-center">
            <h3 class="mb-1"><?= $attempt_count ?></h3>
            <p class="text-muted mb-0">Your Total Attempts</p>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card-box text-center">
            <h3 class="mb-1"><?= $exams->num_rows ?></h3>
            <p class="text-muted mb-0">Available Exams</p>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card-box text-center">
            <h3 class="mb-1">Online</h3>
            <p class="text-muted mb-0">Exam Mode</p>
        </div>
    </div>
</div>

<div class="card-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Available Exams</h2>
    </div>

    <div class="row g-4">
        <?php if ($exams && $exams->num_rows > 0): ?>
            <?php while ($exam = $exams->fetch_assoc()): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="exam-card">
                        <div class="exam-title"><?= htmlspecialchars($exam['title']) ?></div>

                        <div class="exam-meta">
                            <strong>Duration:</strong> <?= $exam['duration'] ?> minutes
                        </div>

                        <div class="exam-meta">
                            <strong>Total Marks:</strong> <?= $exam['total_marks'] ?>
                        </div>

                        <div class="exam-meta mb-3">
                            <strong>Description:</strong><br>
                            <?= nl2br(htmlspecialchars($exam['description'])) ?>
                        </div>

                        <a href="start_exam.php?exam_id=<?= $exam['id'] ?>" class="btn btn-primary btn-rounded w-100">
                            Start Exam
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">No exams are available right now.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/user_footer.php'; ?>