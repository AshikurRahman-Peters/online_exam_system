<?php
require_once '../includes/admin_auth.php';

$sql = "SELECT ea.*, u.name AS user_name, e.title AS exam_title
        FROM exam_attempts ea
        JOIN users u ON ea.user_id = u.id
        JOIN exams e ON ea.exam_id = e.id
        ORDER BY ea.id DESC";

$results = $conn->query($sql);

$result_rows = [];
$total_attempts = 0;
$total_score = 0;
$total_correct = 0;
$total_wrong = 0;

if ($results && $results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
        $result_rows[] = $row;
        $total_attempts++;
        $total_score += (int) $row['score'];
        $total_correct += (int) $row['correct_answers'];
        $total_wrong += (int) $row['wrong_answers'];
    }
}

include '../includes/admin_header.php';
?>

<style>
    .results-page-wrap {
        max-width: 1450px;
        margin: 0 auto;
    }

    .results-hero-card {
        background: linear-gradient(135deg, #0f172a, #1d4ed8);
        color: #fff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 18px 40px rgba(29, 78, 216, 0.18);
        margin-bottom: 24px;
    }

    .results-hero-card h2 {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .results-hero-card p {
        margin: 0;
        max-width: 760px;
        color: rgba(255, 255, 255, 0.92);
    }

    .results-hero-meta {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .results-hero-badge {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 14px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 700;
    }

    .results-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .result-stat-card {
        background: #fff;
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        position: relative;
        overflow: hidden;
    }

    .result-stat-card::after {
        content: "";
        position: absolute;
        right: -18px;
        top: -18px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        opacity: 0.08;
    }

    .stat-blue::after {
        background: #2563eb;
    }

    .stat-green::after {
        background: #16a34a;
    }

    .stat-red::after {
        background: #dc2626;
    }

    .stat-purple::after {
        background: #7c3aed;
    }

    .result-stat-label {
        font-size: 13px;
        font-weight: 800;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .result-stat-value {
        font-size: 30px;
        font-weight: 800;
        color: #111827;
        line-height: 1;
        margin-bottom: 8px;
    }

    .result-stat-subtext {
        font-size: 14px;
        color: #6b7280;
    }

    .results-table-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        overflow: hidden;
    }

    .results-table-head {
        padding: 22px 24px;
        border-bottom: 1px solid #eef2f7;
    }

    .results-table-head h4 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: #111827;
    }

    .results-table-head p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .results-table {
        margin: 0;
    }

    .results-table thead th {
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

    .results-table tbody td {
        padding: 16px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .results-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .results-table tbody tr:hover {
        background: #fafcff;
    }

    .result-id-badge {
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

    .user-info-title,
    .exam-info-title {
        font-size: 15px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }

    .user-info-sub,
    .exam-info-sub {
        font-size: 13px;
        color: #6b7280;
    }

    .score-pill,
    .correct-pill,
    .wrong-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 800;
        white-space: nowrap;
    }

    .score-pill {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .correct-pill {
        background: #ecfdf5;
        color: #166534;
    }

    .wrong-pill {
        background: #fef2f2;
        color: #b91c1c;
    }

    .date-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        padding: 9px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        color: #111827;
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
        margin: 0 auto;
        color: #6b7280;
        line-height: 1.6;
    }

    @media (max-width: 1200px) {
        .results-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .results-stats-grid {
            grid-template-columns: 1fr;
        }

        .results-hero-card,
        .result-stat-card,
        .results-table-head {
            padding: 20px;
        }

        .results-hero-card h2 {
            font-size: 24px;
        }
    }
</style>

<div class="results-page-wrap">

    
    <div class="results-hero-card">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <h2>Exam Results</h2>
                <p>
                    Track submitted exam attempts, monitor user performance, and review score statistics across all
                    exams
                    from one place.
                </p>

                <div class="results-hero-meta">
                    <div class="results-hero-badge">📊 Live Attempt Records</div>
                    <div class="results-hero-badge">✅ Correct / Wrong Summary</div>
                    <div class="results-hero-badge">🧾 Performance Monitoring</div>
                </div>
            </div>
        </div>
    </div>

    <div class="results-stats-grid">
        <div class="result-stat-card stat-blue">
            <div class="result-stat-label">Total Attempts</div>
            <div class="result-stat-value"><?= $total_attempts ?></div>
            <div class="result-stat-subtext">All submitted exam attempts</div>
        </div>

        <div class="result-stat-card stat-purple">
            <div class="result-stat-label">Total Score</div>
            <div class="result-stat-value"><?= $total_score ?></div>
            <div class="result-stat-subtext">Combined score from all attempts</div>
        </div>

        <div class="result-stat-card stat-green">
            <div class="result-stat-label">Correct Answers</div>
            <div class="result-stat-value"><?= $total_correct ?></div>
            <div class="result-stat-subtext">Total correct answers by all users</div>
        </div>

        <div class="result-stat-card stat-red">
            <div class="result-stat-label">Wrong Answers</div>
            <div class="result-stat-value"><?= $total_wrong ?></div>
            <div class="result-stat-subtext">Total wrong answers across attempts</div>
        </div>
    </div>

    <div class="results-table-card">
        <div class="results-table-head">
            <h4>Result Records</h4>
            <p>Review each exam submission with user, exam, score, correct, wrong, and submission date details.</p>
        </div>

        <?php if (!empty($result_rows)): ?>
            <div class="table-responsive">
                <table class="table results-table align-middle">
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
                        <?php foreach ($result_rows as $row): ?>
                            <tr>
                                <td>
                                    <span class="result-id-badge">#<?= (int) $row['id'] ?></span>
                                </td>

                                <td>
                                    <div class="user-info-title"><?= htmlspecialchars($row['user_name']) ?></div>
                                    <div class="user-info-sub">Exam participant</div>
                                </td>

                                <td>
                                    <div class="exam-info-title"><?= htmlspecialchars($row['exam_title']) ?></div>
                                    <div class="exam-info-sub">Submitted attempt record</div>
                                </td>

                                <td>
                                    <span class="score-pill">
                                        <i class="fa-solid fa-star"></i>
                                        <?= (int) $row['score'] ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="correct-pill">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <?= (int) $row['correct_answers'] ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="wrong-pill">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                        <?= (int) $row['wrong_answers'] ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="date-pill">
                                        <i class="fa-regular fa-calendar"></i>
                                        <?= htmlspecialchars($row['submitted_at']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
                <h5>No Results Found</h5>
                <p>
                    No user has submitted an exam yet. Once users start taking exams, their attempt records will appear
                    here.
                </p>
            </div>
        <?php endif; ?>
    </div>
    

</div>

<?php include '../includes/admin_footer.php'; ?>