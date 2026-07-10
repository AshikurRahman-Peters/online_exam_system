<?php
require_once '../includes/admin_auth.php';

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $duration = (int) $_POST['duration'];

    $title = $conn->real_escape_string($title);
    $description = $conn->real_escape_string($description);

    $sql = "INSERT INTO exams (title, description, duration) VALUES ('$title', '$description', '$duration')";
    if ($conn->query($sql)) {
        $message = "Exam created successfully!";
        $message_type = "success";
    } else {
        $message = "Error: " . $conn->error;
        $message_type = "danger";
    }
}

include '../includes/admin_header.php';
?>

<style>
    .create-exam-wrap {
        max-width: 950px;
        margin: 0 auto;
    }

    .exam-hero-card {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: #fff;
        border-radius: 22px;
        padding: 28px;
        box-shadow: 0 14px 36px rgba(37, 99, 235, 0.22);
        margin-bottom: 24px;
    }

    .exam-hero-card h2 {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .exam-hero-card p {
        margin-bottom: 0;
        opacity: 0.95;
        max-width: 700px;
    }

    .exam-hero-meta {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .exam-hero-badge {
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.16);
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
    }

    .exam-form-card {
        background: #fff;
        border-radius: 22px;
        padding: 28px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
    }

    .exam-form-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .exam-form-head h4 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #111827;
    }

    .exam-form-head p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .form-label {
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select,
    textarea.form-control {
        border-radius: 14px;
        border: 1px solid #dbe3ee;
        padding: 13px 15px;
        min-height: 52px;
        box-shadow: none !important;
        transition: all 0.2s ease;
        font-size: 15px;
    }

    textarea.form-control {
        min-height: 130px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus,
    textarea.form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10) !important;
    }

    .input-helper {
        font-size: 13px;
        color: #6b7280;
        margin-top: 7px;
    }

    .input-icon-wrap {
        position: relative;
    }

    .input-icon-wrap .form-control {
        padding-left: 48px;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: #6b7280;
        pointer-events: none;
    }

    .alert-custom {
        border: 0;
        border-radius: 16px;
        padding: 14px 16px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .alert-success-soft {
        background: #ecfdf5;
        color: #166534;
    }

    .alert-danger-soft {
        background: #fef2f2;
        color: #b91c1c;
    }

    .exam-preview-box {
        background: #f8fafc;
        border: 1px dashed #dbe3ee;
        border-radius: 18px;
        padding: 18px;
        margin-top: 22px;
    }

    .exam-preview-box h6 {
        margin-bottom: 12px;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .preview-item {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #e9eef5;
        font-size: 14px;
    }

    .preview-item:last-child {
        border-bottom: 0;
    }

    .preview-item span:first-child {
        color: #6b7280;
    }

    .preview-item strong {
        color: #111827;
        text-align: right;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: flex-end;
        margin-top: 24px;
    }

    .btn-create-exam {
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 700;
        min-width: 170px;
    }

    @media (max-width: 767px) {

        .exam-hero-card,
        .exam-form-card {
            padding: 20px;
        }

        .exam-hero-card h2 {
            font-size: 24px;
        }

        .exam-form-head h4 {
            font-size: 20px;
        }

        .form-actions {
            justify-content: stretch;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="create-exam-wrap">
    <div class="exam-hero-card">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <h2>Create New Exam</h2>
                <p>
                    Add a new exam with a title, short description, and duration. After creating it,
                    you can add True/False and MCQ questions from the admin panel.
                </p>

                
                <div class="exam-hero-meta">
                    <div class="exam-hero-badge">🎯 MCQ + True/False Supported</div>
                    <div class="exam-hero-badge">⏱ Duration Based Exam</div>
                    <div class="exam-hero-badge">📊 User Result Tracking</div>
                </div>
            </div>

            <div>
                <a href="dashboard.php" class="btn btn-light btn-rounded px-4">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <div class="exam-form-card">
        <div class="exam-form-head">
            <div>
                <h4>Exam Information</h4>
                <p>Fill in the exam details below to create a new exam.</p>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert-custom <?= $message_type === 'success' ? 'alert-success-soft' : 'alert-danger-soft' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="createExamForm">
            <div class="mb-4">
                <label class="form-label">Exam Title</label>
                <input type="text" name="title" id="title" class="form-control"
                    placeholder="e.g. PHP Final Test, Web Development Midterm" required>
                <div class="input-helper">Use a clear title so users can easily identify the exam.</div>
            </div>

            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4"
                    placeholder="Write a short description about the exam, topics covered, or instructions for students..."></textarea>
                <div class="input-helper">Optional, but helpful for users to understand the exam topic and instructions.
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Duration (minutes)</label>
                <div class="input-icon-wrap">
                    <span class="input-icon">⏱</span>
                    <input type="number" name="duration" id="duration" class="form-control" min="1"
                        placeholder="Enter exam duration in minutes" required>
                </div>
                <div class="input-helper">Example: 30, 45, 60, 90</div>
            </div>

            <div class="exam-preview-box">
                <h6>Live Preview</h6>

                <div class="preview-item">
                    <span>Exam Title</span>
                    <strong id="previewTitle">Not set yet</strong>
                </div>

                <div class="preview-item">
                    <span>Description</span>
                    <strong id="previewDescription">No description added</strong>
                </div>

                <div class="preview-item">
                    <span>Duration</span>
                    <strong id="previewDuration">0 minutes</strong>
                </div>
            </div>

            <div class="form-actions">
                <a href="dashboard.php" class="btn btn-outline-secondary btn-rounded btn-create-exam">Cancel</a>
                <button type="submit" class="btn btn-primary btn-rounded btn-create-exam">Create Exam</button>
            </div>
        </form>
    </div>
    

</div>

<script>
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const durationInput = document.getElementById('duration');

    const previewTitle = document.getElementById('previewTitle');
    const previewDescription = document.getElementById('previewDescription');
    const previewDuration = document.getElementById('previewDuration');

    function updatePreview() {
        previewTitle.textContent = titleInput.value.trim() || 'Not set yet';
        previewDescription.textContent = descriptionInput.value.trim() || 'No description added';
        previewDuration.textContent = (durationInput.value.trim() || 0) + ' minutes';
    }

    titleInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    durationInput.addEventListener('input', updatePreview);

    updatePreview();
</script>

<?php include '../includes/admin_footer.php'; ?>