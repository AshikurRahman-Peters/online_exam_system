<?php
require_once '../includes/admin_auth.php';

$total_exams = $conn->query("SELECT COUNT(*) AS total FROM exams")->fetch_assoc()['total'] ?? 0;
$total_questions = $conn->query("SELECT COUNT(*) AS total FROM questions")->fetch_assoc()['total'] ?? 0;
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
$total_attempts = $conn->query("SELECT COUNT(*) AS total FROM exam_attempts")->fetch_assoc()['total'] ?? 0;

$recent_exams = $conn->query("SELECT * FROM exams ORDER BY id DESC LIMIT 5");

include '../includes/admin_header.php';
?>

<div class="welcome-box">
    <h2 class="mb-2">Welcome back, <?= htmlspecialchars($_SESSION['admin_name']) ?> 👋</h2>
    <p class="mb-0">Manage exams, add questions, and monitor student results from one dashboard.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stats-card bg-blue">
            <h3><?= $total_exams ?></h3>
            <p>Total Exams</p>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stats-card bg-green">
            <h3><?= $total_questions ?></h3>
            <p>Total Questions</p>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stats-card bg-orange">
            <h3><?= $total_users ?></h3>
            <p>Registered Users</p>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stats-card bg-purple">
            <h3><?= $total_attempts ?></h3>
            <p>Total Attempts</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Recent Exams</h4>
                <a href="create_exam.php" class="btn btn-primary btn-sm btn-rounded">+ Create Exam</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Exam Title</th>
                            <th>Duration</th>
                            <th>Total Marks</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_exams && $recent_exams->num_rows > 0): ?>
                            <?php while ($exam = $recent_exams->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $exam['id'] ?></td>
                                    <td><?= htmlspecialchars($exam['title']) ?></td>
                                    <td><?= $exam['duration'] ?> min</td>
                                    <td><?= $exam['total_marks'] ?></td>
                                    <td>
                                        <?php if ((int)$exam['status'] === 1): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No exams found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-box">
            <h4 class="mb-3">Quick Actions</h4>

            <div class="d-grid gap-3">
                <a href="create_exam.php" class="btn btn-primary btn-rounded">
                    <i class="fa-solid fa-file-circle-plus me-2"></i> Create New Exam
                </a>

                <a href="exams.php" class="btn btn-success btn-rounded">
                    <i class="fa-solid fa-list-check me-2"></i> Manage Exams & Questions
                </a>

                <a href="results.php" class="btn btn-dark btn-rounded">
                    <i class="fa-solid fa-square-poll-vertical me-2"></i> View Results
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>