<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Online Exam System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #111827;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar .brand {
            padding: 20px;
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
            border-radius: 8px;
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

        .sidebar .nav-link i {
            width: 22px;
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

        .card-box {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
            border: 0;
        }

        .stats-card {
            border-radius: 16px;
            color: #fff;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            height: 100%;
        }

        .stats-card h3 {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
        }

        .stats-card p {
            margin: 8px 0 0;
            font-size: 15px;
            opacity: 0.95;
        }

        .bg-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
        .bg-green { background: linear-gradient(135deg, #16a34a, #15803d); }
        .bg-orange { background: linear-gradient(135deg, #ea580c, #c2410c); }
        .bg-purple { background: linear-gradient(135deg, #7c3aed, #6d28d9); }

        .table thead th {
            background: #f3f4f6;
            border-bottom: 0;
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

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .welcome-box {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .badge-soft {
            background: #e0ecff;
            color: #1d4ed8;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        @media (max-width: 991px) {
            .admin-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="brand">
            Exam <span>Admin</span>
        </div>

        <div class="py-3">
            <a class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>

            <a class="nav-link <?= $current_page == 'create_exam.php' ? 'active' : '' ?>" href="create_exam.php">
                <i class="fa-solid fa-file-circle-plus"></i> Create Exam
            </a>

            <a class="nav-link <?= ($current_page == 'exams.php' || $current_page == 'add_question.php') ? 'active' : '' ?>" href="exams.php">
                <i class="fa-solid fa-list-check"></i> Manage Exams
            </a>

            <a class="nav-link <?= $current_page == 'results.php' ? 'active' : '' ?>" href="results.php">
                <i class="fa-solid fa-square-poll-vertical"></i> Results
            </a>

            <a class="nav-link" href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h4>Online Exam Admin Panel</h4>
            <div>
                <span class="badge-soft">
                    <i class="fa-solid fa-user-shield me-1"></i> <?= htmlspecialchars($admin_name) ?>
                </span>
            </div>
        </div>

        <div class="page-content"></div>