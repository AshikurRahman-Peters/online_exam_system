<?php
require_once '../includes/user_auth.php';

$user_id = $_SESSION['user_id'];

$exams = $conn->query("SELECT * FROM exams WHERE status = 1 ORDER BY id DESC");
$attempt_count = $conn->query("SELECT COUNT(*) AS total FROM exam_attempts WHERE user_id = $user_id")->fetch_assoc()['total'] ?? 0;

include '../includes/user_header.php';
?>

<style>
    .dashboard-hero {
        position: relative;
        overflow: hidden;
        border-radius: 26px;
        padding: 34px;
        margin-bottom: 28px;
        background: linear-gradient(135deg, #0f172a, #2563eb);
        color: #fff;
        box-shadow: 0 18px 45px rgba(37, 99, 235, 0.18);
    }

    .dashboard-hero::before {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        top: -90px;
        right: -80px;
    }

    .dashboard-hero::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        bottom: -60px;
        left: -50px;
    }

    .dashboard-hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #fff;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .dashboard-hero h2 {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .dashboard-hero p {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.92);
        margin-bottom: 0;
        max-width: 720px;
        line-height: 1.8;
    }

    .stats-card {
        background: #fff;
        border-radius: 22px;
        padding: 26px 24px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
    }

    .stats-card::after {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        right: -20px;
        top: -20px;
        opacity: 0.08;
    }

    .stats-blue::after {
        background: #2563eb;
    }

    .stats-green::after {
        background: #16a34a;
    }

    .stats-purple::after {
        background: #7c3aed;
    }

    .stats-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        margin-bottom: 18px;
    }

    .icon-blue {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .icon-green {
        background: linear-gradient(135deg, #16a34a, #15803d);
    }

    .icon-purple {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
    }

    .stats-number {
        font-size: 32px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
        line-height: 1;
    }

    .stats-label {
        font-size: 15px;
        color: #6b7280;
        margin-bottom: 0;
        font-weight: 600;
    }

    .exam-section {
        background: #fff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
    }

    .section-title {
        font-size: 28px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 0;
    }

    .section-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-top: 6px;
    }

    .exam-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e9eef5;
        border-radius: 22px;
        padding: 24px;
        height: 100%;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .exam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
        border-color: #dbe6f5;
    }

    .exam-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 5px;
        background: linear-gradient(90deg, #2563eb, #16a34a);
    }

    .exam-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e0ecff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 800;
        padding: 7px 12px;
        border-radius: 999px;
        margin-bottom: 16px;
    }

    .exam-title {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 14px;
        line-height: 1.35;
    }

    .exam-meta-box {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .exam-meta-pill {
        background: #f3f6fb;
        border: 1px solid #e6edf6;
        color: #374151;
        border-radius: 14px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
    }

    .exam-desc {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.8;
        min-height: 92px;
        margin-bottom: 22px;
    }

    .btn-exam {
        min-height: 48px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 15px;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.18);
    }

    .empty-box {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px dashed #cbd5e1;
        border-radius: 20px;
        padding: 36px 20px;
        text-align: center;
    }

    .empty-box i {
        font-size: 42px;
        color: #94a3b8;
        margin-bottom: 12px;
    }

    .empty-box h5 {
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }

    .empty-box p {
        color: #6b7280;
        margin: 0;
    }

    @media (max-width: 767px) {
        .dashboard-hero {
            padding: 24px;
        }

        .dashboard-hero h2 {
            font-size: 26px;
        }

        .exam-section {
            padding: 20px;
        }

        .section-title {
            font-size: 24px;
        }

        .exam-desc {
            min-height: auto;
        }
    }
</style>

<div class="dashboard-hero">
    <div class="dashboard-hero-content">
        <div class="hero-badge">
            <i class="fa-solid fa-graduation-cap"></i>
            Student Exam Portal
        </div>

        
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h2>
        <p>
            Start your available exams, answer MCQ and True/False questions, track your attempts,
            and see your results instantly after submission.
        </p>
    </div>
    

</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-4">
        <div class="stats-card stats-blue">
            <div class="stats-icon icon-blue">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div class="stats-number"><?= $attempt_count ?></div>
            <p class="stats-label">Your Total Attempts</p>
        </div>
    </div>

    
    <div class="col-md-6 col-xl-4">
        <div class="stats-card stats-green">
            <div class="stats-icon icon-green">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div class="stats-number"><?= $exams->num_rows ?></div>
            <p class="stats-label">Available Exams</p>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="stats-card stats-purple">
            <div class="stats-icon icon-purple">
                <i class="fa-solid fa-wifi"></i>
            </div>
            <div class="stats-number">Online</div>
            <p class="stats-label">Exam Mode</p>
        </div>
    </div>
    

</div>

<div class="exam-section">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div>
            <h2 class="section-title">Available Exams</h2>
            <div class="section-subtitle">Choose an active exam and start answering questions.</div>
        </div>
    </div>

    
    <div class="row g-4">
        <?php if ($exams && $exams->num_rows > 0): ?>
            <?php while ($exam = $exams->fetch_assoc()): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="exam-card">
                        <div class="exam-badge">
                            <i class="fa-solid fa-circle-check"></i>
                            Active Exam
                        </div>

                        <div class="exam-title"><?= htmlspecialchars($exam['title']) ?></div>

                        <div class="exam-meta-box">
                            <div class="exam-meta-pill">
                                <i class="fa-regular fa-clock me-1"></i>
                                <?= (int) $exam['duration'] ?> Minutes
                            </div>

                            <div class="exam-meta-pill">
                                <i class="fa-solid fa-award me-1"></i>
                                <?= (int) $exam['total_marks'] ?> Marks
                            </div>
                        </div>

                        <div class="exam-desc">
                            <?= !empty($exam['description'])
                                ? nl2br(htmlspecialchars($exam['description']))
                                : 'No description available for this exam.' ?>
                        </div>

                        <a href="start_exam.php?exam_id=<?= $exam['id'] ?>" class="btn btn-primary btn-exam w-100">
                            <i class="fa-solid fa-play me-2"></i>Start Exam
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-box">
                    <i class="fa-solid fa-file-circle-xmark"></i>
                    <h5>No Exams Available</h5>
                    <p>There are currently no active exams. Please check again later.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    

</div>

<?php include '../includes/user_footer.php'; ?>