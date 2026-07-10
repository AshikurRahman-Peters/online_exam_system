<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$user_name = $_SESSION['user_name'] ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel - Online Exam System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .user-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #0f172a, #1e293b);
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar .brand {
            padding: 22px 20px;
            font-size: 22px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-align: center;
        }

        .sidebar .brand span {
            color: #60a5fa;
        }

        .sidebar .nav-link {
            color: #d1d5db;
            padding: 12px 20px;
            border-radius: 10px;
            margin: 6px 12px;
            display: block;
            text-decoration: none;
            transition: 0.2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #2563eb;
            color: #fff;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .page-content {
            padding: 24px;
        }

        .welcome-card {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            border-radius: 18px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.18);
        }

        .card-box {
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            border: 0;
        }

        .exam-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            height: 100%;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            border: 1px solid #eef2f7;
            transition: 0.2s ease;
        }

        .exam-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 32px rgba(0,0,0,0.08);
        }

        .exam-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .exam-meta {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .badge-soft {
            background: #e0ecff;
            color: #1d4ed8;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-rounded {
            border-radius: 10px;
            padding: 10px 16px;
        }

        .form-control,
        .form-select,
        textarea {
            border-radius: 10px !important;
            padding: 10px 12px;
        }

        .result-box {
            border-radius: 18px;
            background: #fff;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        .stat-mini {
            background: #f8fafc;
            border-radius: 14px;
            padding: 16px;
            text-align: center;
            border: 1px solid #edf2f7;
        }

        .stat-mini h4 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .stat-mini p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 14px;
        }

        @media (max-width: 991px) {
            .user-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="user-wrapper">
    <aside class="sidebar">
        <div class="brand">
            Exam <span>Portal</span>
        </div>

        <div class="py-3">
            <a class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
            <a class="nav-link <?= $current_page == 'result.php' ? 'active' : '' ?>" href="dashboard.php">My Exams</a>
            <a class="nav-link" href="logout.php">Logout</a>
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h4>Student Dashboard</h4>
            <div>
                <span class="badge-soft"><?= htmlspecialchars($user_name) ?></span>
            </div>
        </div>

        <div class="page-content"></div>