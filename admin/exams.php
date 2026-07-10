<?php
require_once '../includes/admin_auth.php';

$exams = $conn->query("
    SELECT e.*,
           (SELECT COUNT(*) FROM questions q WHERE q.exam_id = e.id) AS total_questions
    FROM exams e
    ORDER BY e.id DESC
");

include '../includes/admin_header.php';
?>

<div class="card-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title mb-0">Manage Exams</h2>
        <a href="create_exam.php" class="btn btn-primary btn-rounded">+ Create New Exam</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Exam Title</th>
                    <th>Duration</th>
                    <th>Total Marks</th>
                    <th>Questions</th>
                    <th>Status</th>
                    <th style="width: 180px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($exams && $exams->num_rows > 0): ?>
                    <?php while ($exam = $exams->fetch_assoc()): ?>
                        <tr>
                            <td><?= $exam['id'] ?></td>
                            <td><?= htmlspecialchars($exam['title']) ?></td>
                            <td><?= $exam['duration'] ?> min</td>
                            <td><?= $exam['total_marks'] ?></td>
                            <td><?= $exam['total_questions'] ?></td>
                            <td>
                                <?php if ((int)$exam['status'] === 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                               <a href="question_list.php?exam_id=<?= $exam['id'] ?>" class="btn btn-sm btn-success">Questions</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No exams found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>