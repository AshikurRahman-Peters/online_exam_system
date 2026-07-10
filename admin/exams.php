<?php
require_once '../includes/admin_auth.php';

$exams = $conn->query("
    SELECT e.*,
           (SELECT COUNT(*) FROM questions q WHERE q.exam_id = e.id) AS total_questions
    FROM exams e
    ORDER BY e.id DESC
");

$total_exams = 0;
$active_exams = 0;
$inactive_exams = 0;
$total_questions_all = 0;

$exam_rows = [];

if ($exams && $exams->num_rows > 0) {
    while ($row = $exams->fetch_assoc()) {
        $exam_rows[] = $row;
        $total_exams++;
        $total_questions_all += (int) $row['total_questions'];

        if ((int) $row['status'] === 1) {
            $active_exams++;
        } else {
            $inactive_exams++;
        }
    }
}

include '../includes/admin_header.php';
?>

<style>
    .exams-page-wrap {
        max-width: 1400px;
        margin: 0 auto;
    }

    .exams-hero-card {
        background: linear-gradient(135deg, #0f172a, #1d4ed8);
        color: #fff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 18px 40px rgba(29, 78, 216, 0.18);
        margin-bottom: 24px;
    }

    .exams-hero-card h2 {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .exams-hero-card p {
        margin: 0;
        max-width: 760px;
        color: rgba(255, 255, 255, 0.9);
    }

    .hero-meta-wrap {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .hero-meta-badge {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 14px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #fff;
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        right: -20px;
        top: -20px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        opacity: 0.08;
    }

    .stat-card.stat-blue::after {
        background: #2563eb;
    }

    .stat-card.stat-green::after {
        background: #16a34a;
    }

    .stat-card.stat-orange::after {
        background: #ea580c;
    }

    .stat-card.stat-purple::after {
        background: #7c3aed;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 30px;
        font-weight: 800;
        color: #111827;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-subtext {
        font-size: 14px;
        color: #6b7280;
    }

    .table-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        overflow: hidden;
    }

    .table-card-header {
        padding: 22px 24px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .table-card-header h4 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: #111827;
    }

    .table-card-header p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .btn-create-exam {
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 700;
    }

    .exams-table {
        margin: 0;
    }

    .exams-table thead th {
        background: #f8fafc;
        color: #111827;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 16px 18px;
        border-bottom: 1px solid #eef2f7;
        white-space: nowrap;
    }

    .exams-table tbody td {
        padding: 16px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .exams-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .exams-table tbody tr:hover {
        background: #fafcff;
    }

    .exam-id-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 800;
        font-size: 14px;
    }

    .exam-title {
        font-size: 16px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }

    .exam-desc {
        font-size: 13px;
        color: #6b7280;
        max-width: 380px;
        line-height: 1.45;
    }

    .info-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        padding: 9px 12px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 800;
    }

    .status-active {
        background: #ecfdf5;
        color: #166534;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #4b5563;
    }

    .action-btn {
        border-radius: 12px;
        padding: 9px 14px;
        font-weight: 700;
        min-width: 110px;
    }

    .empty-state {
        padding: 50px 24px;
        text-align: center;
    }

    .empty-state-icon {
        width: 82px;
        height: 82px;
        margin: 0 auto 16px;
        border-radius: 24px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        color: #2563eb;
    }

    .empty-state h5 {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 10px;
    }

    .empty-state p {
        max-width: 560px;
        margin: 0 auto 18px;
        color: #6b7280;
        line-height: 1.6;
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {

        .exams-hero-card,
        .table-card-header,
        .stat-card {
            padding: 20px;
        }

        .exams-hero-card h2 {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .table-card-header {
            align-items: flex-start;
        }
    }
</style>

<div class="exams-page-wrap">

    
    <div class="exams-hero-card">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <h2>Manage Exams</h2>
                <p>
                    View all created exams, monitor their status, check total questions, and open each exam’s question
                    list.
                    This panel helps you control exam content from one place.
                </p>

                <div class="hero-meta-wrap">
                    <div class="hero-meta-badge">📝 MCQ + True/False Exams</div>
                    <div class="hero-meta-badge">📊 Question Count Tracking</div>
                    <div class="hero-meta-badge">⚡ Quick Question Management</div>
                </div>
            </div>

            <div>
                <a href="create_exam.php" class="btn btn-light btn-create-exam">
                    <i class="fa-solid fa-plus me-2"></i>Create New Exam
                </a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <div class="stat-label">Total Exams</div>
            <div class="stat-value"><?= $total_exams ?></div>
            <div class="stat-subtext">All exams currently created in the system</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Active Exams</div>
            <div class="stat-value"><?= $active_exams ?></div>
            <div class="stat-subtext">Exams available for users to attend</div>
        </div>

        <div class="stat-card stat-orange">
            <div class="stat-label">Inactive Exams</div>
            <div class="stat-value"><?= $inactive_exams ?></div>
            <div class="stat-subtext">Draft or disabled exams not visible to users</div>
        </div>

        <div class="stat-card stat-purple">
            <div class="stat-label">Total Questions</div>
            <div class="stat-value"><?= $total_questions_all ?></div>
            <div class="stat-subtext">Questions added across all exams</div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div>
                <h4>Exam List</h4>
                <p>Browse your exams, review their status, and open the question manager for each one.</p>
            </div>
        </div>

        <?php if (!empty($exam_rows)): ?>
            <div class="table-responsive">
                <table class="table exams-table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Exam</th>
                            <th>Duration</th>
                            <th>Total Marks</th>
                            <th>Questions</th>
                            <th>Status</th>
                            <th style="width: 170px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exam_rows as $exam): ?>
                            <tr>
                                <td>
                                    <span class="exam-id-badge">#<?= (int) $exam['id'] ?></span>
                                </td>

                                <td>
                                    <div class="exam-title"><?= htmlspecialchars($exam['title']) ?></div>
                                    <div class="exam-desc">
                                        <?= !empty($exam['description']) ? htmlspecialchars($exam['description']) : 'No description available for this exam yet.' ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="info-pill">
                                        <i class="fa-regular fa-clock text-primary"></i>
                                        <?= (int) $exam['duration'] ?> min
                                    </span>
                                </td>

                                <td>
                                    <span class="info-pill">
                                        <i class="fa-solid fa-award text-warning"></i>
                                        <?= (int) $exam['total_marks'] ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="info-pill">
                                        <i class="fa-solid fa-list-check text-success"></i>
                                        <?= (int) $exam['total_questions'] ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ((int) $exam['status'] === 1): ?>
                                        <span class="status-badge status-active">
                                            <i class="fa-solid fa-circle-check"></i> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">
                                            <i class="fa-solid fa-circle-pause"></i> Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="question_list.php?exam_id=<?= (int) $exam['id'] ?>"
                                        class="btn btn-success action-btn">
                                        <i class="fa-solid fa-list-ul me-2"></i>Questions
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fa-solid fa-file-circle-xmark"></i>
                </div>
                <h5>No Exams Found</h5>
                <p>
                    You haven’t created any exams yet. Start by creating a new exam, then add MCQ or True/False questions
                    and publish it for users.
                </p>
                <a href="create_exam.php" class="btn btn-primary btn-create-exam">
                    <i class="fa-solid fa-plus me-2"></i>Create Your First Exam
                </a>
            </div>
        <?php endif; ?>
    </div>
    

</div>

<?php include '../includes/admin_footer.php'; ?>