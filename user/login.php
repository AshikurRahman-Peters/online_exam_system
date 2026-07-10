<?php
require_once '../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);

    $email = $conn->real_escape_string($email);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Online Exam System</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #16a34a;
            --primary-dark: #15803d;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --danger-soft: #fef2f2;
            --danger-text: #b91c1c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(34, 197, 94, 0.18), transparent 30%),
                radial-gradient(circle at bottom right, rgba(22, 163, 74, 0.16), transparent 30%),
                linear-gradient(135deg, #f0fdf4, #f8fafc, #ecfdf5);
            color: var(--text-dark);
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
        }

        .auth-shell {
            width: 100%;
            max-width: 1220px;
            min-height: 720px;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.65);
            border-radius: 28px;
            box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14);
            overflow: hidden;
            display: grid;
            grid-template-columns: 520px 1fr;
        }

        .auth-left {
            background: rgba(255, 255, 255, 0.94);
            padding: 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 26px;
        }

        .brand-logo {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            background: linear-gradient(135deg, #16a34a, #15803d);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            box-shadow: 0 16px 36px rgba(22, 163, 74, 0.28);
        }

        .brand-text h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
        }

        .brand-text p {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .auth-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 16px;
        }

        .auth-title {
            font-size: 34px;
            line-height: 1.15;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .auth-subtitle {
            color: var(--text-muted);
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 28px;
            max-width: 420px;
        }

        .login-card {
            background: #fff;
            border-radius: 24px;
            padding: 28px;
            border: 1px solid #eef2f7;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
        }

        .login-card h3 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 800;
        }

        .login-card .card-subtext {
            margin: 0 0 22px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .alert-soft-danger {
            background: var(--danger-soft);
            color: var(--danger-text);
            border: 1px solid #fecaca;
            border-radius: 16px;
            padding: 12px 14px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .form-label {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            pointer-events: none;
        }

        .form-control {
            min-height: 54px;
            border-radius: 16px;
            border: 1px solid #dbe3ee;
            padding: 12px 16px 12px 46px;
            font-size: 15px;
            box-shadow: none !important;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.10) !important;
        }

        .btn-login {
            min-height: 54px;
            border-radius: 16px;
            font-weight: 800;
            font-size: 15px;
            margin-top: 6px;
            box-shadow: 0 14px 28px rgba(22, 163, 74, 0.18);
            background: #16a34a;
            border-color: #16a34a;
        }

        .btn-login:hover {
            background: #15803d;
            border-color: #15803d;
        }

        .switch-auth {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 16px;
        }

        .switch-auth .btn {
            border-radius: 14px;
            min-height: 48px;
            font-weight: 700;
        }

        .auth-note {
            margin-top: 22px;
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .auth-right {
            position: relative;
            min-height: 720px;
            overflow: hidden;
            background: #0f172a;
        }

        .auth-right::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(15, 23, 42, 0.15), rgba(15, 23, 42, 0.74)),
                url("https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1400&q=80") center center / cover no-repeat;
            transform: scale(1.02);
        }

        .auth-right-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 34px;
            color: #fff;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 24px;
            max-width: 560px;
            box-shadow: 0 18px 42px rgba(0, 0, 0, 0.18);
        }

        .feature-card .mini-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .feature-card h4 {
            font-size: 30px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .feature-card p {
            margin: 0;
            color: rgba(255, 255, 255, 0.92);
            line-height: 1.75;
            font-size: 15px;
        }

        .feature-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .feature-pill {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff;
            padding: 10px 12px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 700;
        }

        @media (max-width: 1100px) {
            .auth-shell {
                grid-template-columns: 1fr;
                max-width: 760px;
            }

            .auth-right {
                min-height: 360px;
            }
        }

        @media (max-width: 767px) {
            .auth-page {
                padding: 16px;
            }

            .auth-left,
            .auth-right-content {
                padding: 22px;
            }

            .login-card {
                padding: 22px;
            }

            .auth-title {
                font-size: 28px;
            }

            .switch-auth {
                grid-template-columns: 1fr;
            }
        }
    </style>
    

</head>

<body>

    <div class="auth-page">
        <div class="auth-shell">

            
            <div class="auth-left">
                <div class="brand-row">
                    <div class="brand-logo">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <div class="brand-text">
                        <h1>Online Exam System</h1>
                        <p>User exam portal for joining and completing exams</p>
                    </div>
                </div>

                <div class="auth-badge">
                    <i class="fa-solid fa-user"></i> User Access
                </div>

                <div class="auth-title">Join exams, answer questions, and view your results.</div>
                <div class="auth-subtitle">
                    Sign in as a user to attend available exams, answer MCQ and True/False questions, and check your
                    score and result history from your dashboard.
                </div>

                <div class="login-card">
                    <h3>User Login</h3>
                    <p class="card-subtext">Use your registered email and password to continue.</p>

                    <?php if ($error): ?>
                        <div class="alert-soft-danger">
                            <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-wrap">
                                <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-wrap">
                                <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Enter password"
                                    required>
                            </div>
                        </div>

                        <button class="btn btn-success w-100 btn-login">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Login as User
                        </button>
                    </form>

                    <div class="switch-auth">
                        <a href="../admin/login.php" class="btn btn-outline-primary">
                            <i class="fa-solid fa-user-shield me-2"></i>Login as Admin
                        </a>
                        <a href="../index.php" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-house me-2"></i>Back to Home
                        </a>
                    </div>

                    <div class="auth-note">
                        Users can join active exams, submit answers, and review their exam results from their dashboard.
                    </div>
                </div>
            </div>

            <div class="auth-right">
                <div class="auth-right-content">
                    <div class="feature-card">
                        <div class="mini-badge">
                            <i class="fa-solid fa-pen-to-square"></i> Student Exam Portal
                        </div>

                        <h4>Take exams online with a clean, simple, and focused interface.</h4>

                        <p>
                            Access available exams, complete timed question sets, and instantly view your results in a
                            user-friendly exam environment.
                        </p>

                        <div class="feature-list">
                            <div class="feature-pill">📝 Easy Exam Access</div>
                            <div class="feature-pill">⏱ Timed Tests</div>
                            <div class="feature-pill">📊 Result View</div>
                            <div class="feature-pill">🎯 Random Questions</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        

    </div>

</body>

</html>