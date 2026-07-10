<?php
require_once '../includes/user_auth.php';

if (!isset($_GET['exam_id'])) {
    die("Exam ID missing.");
}

$exam_id = (int) $_GET['exam_id'];
$user_id = $_SESSION['user_id'];

$exam = $conn->query("SELECT * FROM exams WHERE id=$exam_id AND status=1")->fetch_assoc();
if (!$exam) {
    die("Exam not found.");
}

$questions = $conn->query("SELECT * FROM questions WHERE exam_id=$exam_id ORDER BY RAND()");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $questionData = $conn->query("SELECT * FROM questions WHERE exam_id=$exam_id");
    $score = 0;
    $correct = 0;
    $wrong = 0;
    $total_questions = 0;

    $conn->query("INSERT INTO exam_attempts (user_id, exam_id) VALUES ($user_id, $exam_id)");
    $attempt_id = $conn->insert_id;

    while ($q = $questionData->fetch_assoc()) {
        $total_questions++;
        $question_id = $q['id'];
        $correct_answer = trim(strtolower($q['correct_answer']));
        $selected = isset($_POST['answers'][$question_id]) ? trim($_POST['answers'][$question_id]) : '';
        $selected_check = strtolower($selected);

        $is_correct = ($selected_check == $correct_answer) ? 1 : 0;

        if ($is_correct) {
            $score += $q['marks'];
            $correct++;
        } else {
            $wrong++;
        }

        $selected_safe = $conn->real_escape_string($selected);

        $conn->query("INSERT INTO exam_answers (attempt_id, question_id, selected_answer, is_correct)
                      VALUES ($attempt_id, $question_id, '$selected_safe', $is_correct)");
    }

    $conn->query("UPDATE exam_attempts 
                  SET score=$score, total_questions=$total_questions, correct_answers=$correct, wrong_answers=$wrong
                  WHERE id=$attempt_id");

    echo "<script>localStorage.removeItem('exam_timer_{$exam_id}_{$user_id}');</script>";

    header("Location: result.php?attempt_id=$attempt_id");
    exit;
}

include '../includes/user_header.php';
?>

<style>
    .exam-hero {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: #fff;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 14px 36px rgba(37, 99, 235, 0.22);
        margin-bottom: 24px;
    }

    .exam-hero h2 {
        margin-bottom: 8px;
        font-size: 30px;
        font-weight: 700;
    }

    .exam-hero p {
        margin-bottom: 0;
        opacity: 0.95;
    }

    .exam-meta-wrap {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .exam-meta-box {
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.16);
        padding: 12px 16px;
        border-radius: 14px;
        min-width: 150px;
    }

    .exam-meta-box small {
        display: block;
        opacity: 0.85;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .exam-meta-box strong {
        font-size: 16px;
    }

    .exam-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 24px;
    }

    .question-card {
        background: #fff;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.05);
        border: 1px solid #eef2f7;
        margin-bottom: 20px;
    }

    .question-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .question-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #dbeafe;
        color: #1d4ed8;
        font-weight: 700;
        font-size: 15px;
    }

    .question-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        line-height: 1.5;
        flex: 1;
    }

    .question-tags {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .option-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-top: 18px;
    }

    .option-item {
        position: relative;
    }

    .option-item input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .option-label {
        display: block;
        background: #f8fafc;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 16px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 72px;
    }

    .option-label:hover {
        border-color: #93c5fd;
        background: #eff6ff;
    }

    .option-item input[type="radio"]:checked+.option-label {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
    }

    .option-code {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e5e7eb;
        font-weight: 700;
        margin-right: 10px;
        color: #111827;
    }

    .option-item input[type="radio"]:checked+.option-label .option-code {
        background: #2563eb;
        color: #fff;
    }

    .option-text {
        font-size: 15px;
        color: #111827;
        line-height: 1.5;
    }

    .exam-sidebar {
        position: sticky;
        top: 20px;
        align-self: start;
    }

    .sidebar-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.05);
        border: 1px solid #eef2f7;
        margin-bottom: 20px;
    }

    .sidebar-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .timer-box {
        background: linear-gradient(135deg, #111827, #1f2937);
        color: #fff;
        border-radius: 16px;
        padding: 18px;
        text-align: center;
    }

    .timer-box h3 {
        margin: 0;
        font-size: 30px;
        font-weight: 700;
    }

    .timer-box p {
        margin: 6px 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .timer-warning {
        background: #fef3c7 !important;
        color: #92400e !important;
    }

    .timer-danger {
        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        color: #fff !important;
        animation: pulseTimer 1s infinite;
    }

    @keyframes pulseTimer {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.03);
        }

        100% {
            transform: scale(1);
        }
    }

    .info-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .info-list li {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eef2f7;
        font-size: 14px;
    }

    .info-list li:last-child {
        border-bottom: 0;
    }

    .submit-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.05);
        border: 1px solid #eef2f7;
        margin-top: 24px;
    }

    @media (max-width: 991px) {
        .exam-layout {
            grid-template-columns: 1fr;
        }

        .exam-sidebar {
            position: static;
        }

        .option-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="exam-hero">
    <h2><?= htmlspecialchars($exam['title']) ?></h2>
    <p><?= htmlspecialchars($exam['description']) ?></p>

    
    <div class="exam-meta-wrap">
        <div class="exam-meta-box">
            <small>Duration</small>
            <strong><?= (int) $exam['duration'] ?> Minutes</strong>
        </div>

        <div class="exam-meta-box">
            <small>Total Marks</small>
            <strong><?= (int) $exam['total_marks'] ?></strong>
        </div>

        <div class="exam-meta-box">
            <small>Question Order</small>
            <strong>Random</strong>
        </div>
    </div>
    

</div>

<form method="POST" id="examForm">
    <div class="exam-layout">
        <div>
            <?php $serial = 1;
            while ($q = $questions->fetch_assoc()): ?>
                <div class="question-card">
                    <div class="question-top">
                        <div class="d-flex align-items-start gap-3 flex-grow-1">
                            <div class="question-number"><?= $serial ?></div>

                            
                            <div class="question-title">
                                <?= htmlspecialchars($q['question_text']) ?>
                            </div>
                        </div>

                        <div class="question-tags">
                            <?php if ($q['question_type'] === 'mcq'): ?>
                                <span class="badge bg-primary">MCQ</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">True / False</span>
                            <?php endif; ?>

                            <span class="badge bg-dark"><?= (int) $q['marks'] ?> Mark</span>
                        </div>
                    </div>

                    <?php if ($q['question_type'] == 'mcq'): ?>
                        <div class="option-grid">
                            <div class="option-item">
                                <input type="radio" name="answers[<?= $q['id'] ?>]" value="A" id="q<?= $q['id'] ?>a">
                                <label class="option-label" for="q<?= $q['id'] ?>a">
                                    <span class="option-code">A</span>
                                    <span class="option-text"><?= htmlspecialchars($q['option_a']) ?></span>
                                </label>
                            </div>

                            <div class="option-item">
                                <input type="radio" name="answers[<?= $q['id'] ?>]" value="B" id="q<?= $q['id'] ?>b">
                                <label class="option-label" for="q<?= $q['id'] ?>b">
                                    <span class="option-code">B</span>
                                    <span class="option-text"><?= htmlspecialchars($q['option_b']) ?></span>
                                </label>
                            </div>

                            <div class="option-item">
                                <input type="radio" name="answers[<?= $q['id'] ?>]" value="C" id="q<?= $q['id'] ?>c">
                                <label class="option-label" for="q<?= $q['id'] ?>c">
                                    <span class="option-code">C</span>
                                    <span class="option-text"><?= htmlspecialchars($q['option_c']) ?></span>
                                </label>
                            </div>

                            <div class="option-item">
                                <input type="radio" name="answers[<?= $q['id'] ?>]" value="D" id="q<?= $q['id'] ?>d">
                                <label class="option-label" for="q<?= $q['id'] ?>d">
                                    <span class="option-code">D</span>
                                    <span class="option-text"><?= htmlspecialchars($q['option_d']) ?></span>
                                </label>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="option-grid">
                            <div class="option-item">
                                <input type="radio" name="answers[<?= $q['id'] ?>]" value="True" id="q<?= $q['id'] ?>true">
                                <label class="option-label" for="q<?= $q['id'] ?>true">
                                    <span class="option-code">T</span>
                                    <span class="option-text">True</span>
                                </label>
                            </div>

                            <div class="option-item">
                                <input type="radio" name="answers[<?= $q['id'] ?>]" value="False" id="q<?= $q['id'] ?>false">
                                <label class="option-label" for="q<?= $q['id'] ?>false">
                                    <span class="option-code">F</span>
                                    <span class="option-text">False</span>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php $serial++; endwhile; ?>

            <div class="submit-card">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">Ready to submit?</h5>
                        <p class="text-muted mb-0">Make sure you have reviewed your answers before submitting the exam.
                        </p>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="dashboard.php" class="btn btn-outline-secondary btn-rounded">Back</a>
                        <button type="submit" class="btn btn-success btn-rounded px-4" id="submitBtn">Submit
                            Exam</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="exam-sidebar">
            <div class="sidebar-card">
                <div class="sidebar-title">Exam Info</div>

                <ul class="info-list">
                    <li>
                        <span>Exam Title</span>
                        <strong><?= htmlspecialchars($exam['title']) ?></strong>
                    </li>
                    <li>
                        <span>Duration</span>
                        <strong><?= (int) $exam['duration'] ?> min</strong>
                    </li>
                    <li>
                        <span>Total Marks</span>
                        <strong><?= (int) $exam['total_marks'] ?></strong>
                    </li>
                </ul>
            </div>

            <div class="sidebar-card">
                <div class="sidebar-title">Time Remaining</div>
                <div class="timer-box" id="timerBox">
                    <h3 id="examTimer">00:00</h3>
                    <p id="timerText">Exam timer is running</p>
                </div>
            </div>

            <div class="sidebar-card">
                <div class="sidebar-title">Instructions</div>
                <ul class="mb-0 ps-3">
                    <li class="mb-2">Read each question carefully before answering.</li>
                    <li class="mb-2">MCQ questions have only one correct answer.</li>
                    <li class="mb-2">True/False questions require one selection.</li>
                    <li>Exam will auto-submit when time is over.</li>
                </ul>
            </div>
        </div>
    </div>
    

</form>

<script>
    (function () {
        const examId = <?= (int) $exam_id ?>;
        const userId = <?= (int) $user_id ?>;
        const durationMinutes = <?= (int) $exam['duration'] ?>;
        const totalSeconds = durationMinutes * 60;

        const storageKey = 'exam_timer_' + examId + '_' + userId;
        const timerEl = document.getElementById('examTimer');
        const timerBox = document.getElementById('timerBox');
        const timerText = document.getElementById('timerText');
        const examForm = document.getElementById('examForm');

        let endTime = localStorage.getItem(storageKey);

        if (!endTime) {
            endTime = Math.floor(Date.now() / 1000) + totalSeconds;
            localStorage.setItem(storageKey, endTime);
        } else {
            endTime = parseInt(endTime, 10);
        }

        let oneMinuteWarningShown = false;

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }

        function updateTimer() {
            const now = Math.floor(Date.now() / 1000);
            let remaining = endTime - now;

            if (remaining < 0) remaining = 0;

            timerEl.textContent = formatTime(remaining);

            if (remaining <= 60 && remaining > 0) {
                timerBox.classList.add('timer-danger');
                timerBox.classList.remove('timer-warning');

                if (!oneMinuteWarningShown) {
                    oneMinuteWarningShown = true;
                    alert('Warning: Only 1 minute left! Please submit your exam soon.');
                }
            } else if (remaining <= 300) {
                timerBox.classList.add('timer-warning');
                timerBox.classList.remove('timer-danger');
                timerText.textContent = 'Less than 5 minutes remaining';
            }

            if (remaining === 0) {
                timerEl.textContent = '00:00';
                timerText.textContent = 'Time is over. Submitting exam...';
                localStorage.removeItem(storageKey);
                clearInterval(timerInterval);
                examForm.submit();
            }
        }

        updateTimer();
        const timerInterval = setInterval(updateTimer, 1000);

        examForm.addEventListener('submit', function () {
            localStorage.removeItem(storageKey);
        });
    })();
</script>

<?php include '../includes/user_footer.php'; ?>