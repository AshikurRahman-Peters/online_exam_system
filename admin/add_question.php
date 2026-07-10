<?php
require_once '../includes/admin_auth.php';

if (!isset($_GET['exam_id'])) {
    die("Exam ID missing.");
}

$exam_id = (int) $_GET['exam_id'];

$exam = $conn->query("SELECT * FROM exams WHERE id = $exam_id")->fetch_assoc();
if (!$exam) {
    die("Exam not found.");
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = trim($_POST['question_text']);
    $question_type = trim($_POST['question_type']);
    $marks = (int) $_POST['marks'];

    $question_text = $conn->real_escape_string($question_text);
    $question_type = $conn->real_escape_string($question_type);

    $option_a = null;
    $option_b = null;
    $option_c = null;
    $option_d = null;
    $correct_answer = '';

    if ($question_type === 'mcq') {
        $option_a = trim($_POST['option_a'] ?? '');
        $option_b = trim($_POST['option_b'] ?? '');
        $option_c = trim($_POST['option_c'] ?? '');
        $option_d = trim($_POST['option_d'] ?? '');
        $correct_answer = strtoupper(trim($_POST['correct_answer_mcq'] ?? ''));

        if ($option_a === '' || $option_b === '' || $option_c === '' || $option_d === '' || !in_array($correct_answer, ['A', 'B', 'C', 'D'])) {
            $message = "Please fill all MCQ options and choose a valid correct answer (A/B/C/D).";
        } else {
            $option_a = $conn->real_escape_string($option_a);
            $option_b = $conn->real_escape_string($option_b);
            $option_c = $conn->real_escape_string($option_c);
            $option_d = $conn->real_escape_string($option_d);
            $correct_answer = $conn->real_escape_string($correct_answer);
        }
    } elseif ($question_type === 'true_false') {
        $correct_answer = trim($_POST['correct_answer_tf'] ?? '');

        if (!in_array($correct_answer, ['True', 'False'])) {
            $message = "Please select True or False as the correct answer.";
        } else {
            $correct_answer = $conn->real_escape_string($correct_answer);
        }
    } else {
        $message = "Invalid question type.";
    }

    if ($message === "") {
        $sql = "INSERT INTO questions 
                (exam_id, question_text, question_type, option_a, option_b, option_c, option_d, correct_answer, marks)
                VALUES 
                ($exam_id, '$question_text', '$question_type', " .
                ($option_a !== null ? "'$option_a'" : "NULL") . ", " .
                ($option_b !== null ? "'$option_b'" : "NULL") . ", " .
                ($option_c !== null ? "'$option_c'" : "NULL") . ", " .
                ($option_d !== null ? "'$option_d'" : "NULL") . ", " .
                "'$correct_answer', $marks)";

        if ($conn->query($sql)) {
            $conn->query("UPDATE exams SET total_marks = total_marks + $marks WHERE id = $exam_id");
            $message = "Question added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

$questions = $conn->query("SELECT * FROM questions WHERE exam_id = $exam_id ORDER BY id DESC");

include '../includes/admin_header.php';
?>

<div class="card-box mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="section-title mb-1">Add Questions</h2>
            <p class="text-muted mb-0">Exam: <strong><?= htmlspecialchars($exam['title']) ?></strong></p>
        </div>
        <a href="exams.php" class="btn btn-outline-secondary btn-rounded">Back to Exams</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Question</label>
            <textarea name="question_text" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Question Type</label>
            <select name="question_type" class="form-select" id="question_type" required onchange="toggleFields()">
                <option value="mcq">MCQ</option>
                <option value="true_false">True / False</option>
            </select>
        </div>

        <div id="mcq_fields">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Option A</label>
                    <input type="text" name="option_a" id="option_a" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Option B</label>
                    <input type="text" name="option_b" id="option_b" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Option C</label>
                    <input type="text" name="option_c" id="option_c" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Option D</label>
                    <input type="text" name="option_d" id="option_d" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Correct Answer</label>
                <select name="correct_answer_mcq" id="correct_answer_mcq" class="form-select">
                    <option value="">Select correct answer</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
        </div>

        <div id="tf_fields" style="display:none;">
            <div class="mb-3">
                <label class="form-label">Correct Answer</label>
                <select name="correct_answer_tf" id="correct_answer_tf" class="form-select">
                    <option value="True">True</option>
                    <option value="False">False</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Marks</label>
            <input type="number" name="marks" class="form-control" value="1" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary btn-rounded">Add Question</button>
    </form>
</div>

<div class="card-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Existing Questions</h4>
        <span class="badge bg-dark"><?= $questions->num_rows ?> Questions</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Type</th>
                    <th>Correct Answer</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($questions && $questions->num_rows > 0): ?>
                    <?php while ($q = $questions->fetch_assoc()): ?>
                        <tr>
                            <td><?= $q['id'] ?></td>
                            <td><?= htmlspecialchars($q['question_text']) ?></td>
                            <td>
                                <?php if ($q['question_type'] === 'mcq'): ?>
                                    <span class="badge bg-primary">MCQ</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">True/False</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($q['correct_answer']) ?></td>
                            <td><?= $q['marks'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No questions added yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleFields() {
    const type = document.getElementById('question_type').value;

    const mcqFields = document.getElementById('mcq_fields');
    const tfFields = document.getElementById('tf_fields');

    const optionA = document.getElementById('option_a');
    const optionB = document.getElementById('option_b');
    const optionC = document.getElementById('option_c');
    const optionD = document.getElementById('option_d');
    const correctMcq = document.getElementById('correct_answer_mcq');
    const correctTf = document.getElementById('correct_answer_tf');

    if (type === 'mcq') {
        mcqFields.style.display = 'block';
        tfFields.style.display = 'none';

        optionA.required = true;
        optionB.required = true;
        optionC.required = true;
        optionD.required = true;
        correctMcq.required = true;

        correctTf.required = false;
    } else {
        mcqFields.style.display = 'none';
        tfFields.style.display = 'block';

        optionA.required = false;
        optionB.required = false;
        optionC.required = false;
        optionD.required = false;
        correctMcq.required = false;

        correctTf.required = true;
    }
}

document.addEventListener('DOMContentLoaded', toggleFields);
</script>

<?php include '../includes/admin_footer.php'; ?>