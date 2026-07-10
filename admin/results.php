<?php
require_once '../includes/admin_auth.php';

$sql = "SELECT ea.*, u.name AS user_name, e.title AS exam_title
        FROM exam_attempts ea
        JOIN users u ON ea.user_id = u.id
        JOIN exams e ON ea.exam_id = e.id
        ORDER BY ea.id DESC";

$results = $conn->query($sql);

include '../includes/admin_header.php';
?>

<div class="card-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title mb-0">Exam Results</h2>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Exam</th>
                    <th>Score</th>
                    <th>Correct</th>
                    <th>Wrong</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($results && $results->num_rows > 0): ?>
                    <?php while ($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= htmlspecialchars($row['exam_title']) ?></td>
                            <td><span class="badge bg-primary"><?= $row['score'] ?></span></td>
                            <td><span class="badge bg-success"><?= $row['correct_answers'] ?></span></td>
                            <td><span class="badge bg-danger"><?= $row['wrong_answers'] ?></span></td>
                            <td><?= $row['submitted_at'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>