<?php include 'includes/header.php'; ?>

<style>
    .home-page {
        padding: 30px 0 60px;
        background:
            radial-gradient(circle at top left, rgba(37, 99, 235, 0.10), transparent 28%),
            radial-gradient(circle at bottom right, rgba(22, 163, 74, 0.10), transparent 30%);
    }

    .hero-card {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 42px;
        background: linear-gradient(135deg, #0f172a, #1d4ed8);
        color: #fff;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
        margin-bottom: 30px;
    }

    .hero-card::before {
        content: "";
        position: absolute;
        width: 380px;
        height: 380px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        top: -120px;
        right: -80px;
    }

    .hero-card::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        bottom: -70px;
        left: -70px;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.14);
        color: #fff;
        padding: 9px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 18px;
    }

    .hero-title {
        font-size: 44px;
        line-height: 1.15;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .hero-subtitle {
        font-size: 16px;
        line-height: 1.8;
        color: rgba(255, 255, 255, 0.92);
        max-width: 760px;
        margin-bottom: 24px;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .btn-hero {
        min-height: 50px;
        border-radius: 14px;
        padding: 12px 20px;
        font-weight: 800;
        font-size: 15px;
    }

    .btn-hero-outline {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.16);
    }

    .btn-hero-outline:hover {
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #fff;
        border-radius: 22px;
        padding: 24px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        right: -16px;
        top: -16px;
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

    .stat-orange::after {
        background: #ea580c;
    }

    .stat-purple::after {
        background: #7c3aed;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 16px;
        color: #fff;
    }

    .bg-blue {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .bg-green {
        background: linear-gradient(135deg, #16a34a, #15803d);
    }

    .bg-orange {
        background: linear-gradient(135deg, #ea580c, #c2410c);
    }

    .bg-purple {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
    }

    .stat-title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }

    .stat-text {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.7;
        margin: 0;
    }

    .section-heading {
        margin-bottom: 20px;
    }

    .section-heading h2 {
        font-size: 30px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }

    .section-heading p {
        color: #6b7280;
        margin: 0;
        font-size: 15px;
    }

    .portal-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
        margin-bottom: 34px;
    }

    .portal-card {
        background: #fff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .portal-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 22px 46px rgba(15, 23, 42, 0.10);
    }

    .portal-icon {
        width: 66px;
        height: 66px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 26px;
        margin-bottom: 18px;
    }

    .portal-card h3 {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 10px;
    }

    .portal-card p {
        color: #6b7280;
        font-size: 15px;
        line-height: 1.8;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .portal-list {
        list-style: none;
        padding: 0;
        margin: 0 0 22px;
    }

    .portal-list li {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #374151;
        font-size: 14px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .portal-list li i {
        color: #16a34a;
    }

    .portal-btn {
        border-radius: 14px;
        min-height: 48px;
        font-weight: 800;
        font-size: 15px;
    }

    .feature-showcase {
        background: #fff;
        border-radius: 28px;
        padding: 34px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-top: 22px;
    }

    .feature-item {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 18px;
        padding: 20px;
    }

    .feature-item h4 {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 10px;
        color: #111827;
    }

    .feature-item p {
        margin: 0;
        color: #6b7280;
        line-height: 1.7;
        font-size: 14px;
    }

    @media (max-width: 1199px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .portal-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .home-page {
            padding: 18px 0 40px;
        }

        .hero-card,
        .feature-showcase,
        .portal-card,
        .stat-card {
            padding: 22px;
        }

        .hero-title {
            font-size: 32px;
        }

        .stats-grid,
        .feature-grid {
            grid-template-columns: 1fr;
        }

        .hero-actions {
            flex-direction: column;
        }

        .btn-hero {
            width: 100%;
        }
    }
</style>

<div class="home-page">
    <div class="container">

        
        <!-- HERO -->
        <div class="hero-card">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fa-solid fa-graduation-cap"></i>
                    Smart Online Examination Platform
                </div>

                <h1 class="hero-title">Create, manage, and attend online exams with a clean modern system.</h1>

                <p class="hero-subtitle">
                    This Online Exam System lets administrators create exams, add MCQ and True/False questions,
                    manage results, and lets users join timed exams, submit answers, and instantly view performance.
                </p>

                <div class="hero-actions">
                    <a href="admin/login.php" class="btn btn-light btn-hero">
                        <i class="fa-solid fa-user-shield me-2"></i>Admin Login
                    </a>

                    <a href="user/login.php" class="btn btn-success btn-hero">
                        <i class="fa-solid fa-user me-2"></i>User Login
                    </a>

                    <a href="user/register.php" class="btn btn-hero btn-hero-outline">
                        <i class="fa-solid fa-user-plus me-2"></i>User Register
                    </a>
                </div>
            </div>
        </div>

        <!-- QUICK FEATURES -->
        <div class="stats-grid">
            <div class="stat-card stat-blue">
                <div class="stat-icon bg-blue">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <div class="stat-title">Create Exams</div>
                <p class="stat-text">
                    Admin can create exams with title, description, duration, and full exam settings from the dashboard.
                </p>
            </div>

            <div class="stat-card stat-green">
                <div class="stat-icon bg-green">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="stat-title">MCQ + True/False</div>
                <p class="stat-text">
                    Add multiple-choice and true/false questions with marks, correct answers, and organized exam sets.
                </p>
            </div>

            <div class="stat-card stat-orange">
                <div class="stat-icon bg-orange">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <div class="stat-title">Timed Exams</div>
                <p class="stat-text">
                    Students can attend timed exams with countdown support, random question display, and easy
                    submission.
                </p>
            </div>

            <div class="stat-card stat-purple">
                <div class="stat-icon bg-purple">
                    <i class="fa-solid fa-square-poll-vertical"></i>
                </div>
                <div class="stat-title">Instant Results</div>
                <p class="stat-text">
                    Track attempts, score, correct answers, wrong answers, and full result summaries for every exam.
                </p>
            </div>
        </div>

        <!-- PORTALS -->
        <div class="section-heading">
            <h2>Choose Your Portal</h2>
            <p>Access the right panel based on your role in the Online Exam System.</p>
        </div>

        <div class="portal-grid">
            <!-- ADMIN -->
            <div class="portal-card">
                <div class="portal-icon bg-blue">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3>Admin Panel</h3>
                <p>
                    Manage the complete exam system from one place — create exams, add questions, review results,
                    and monitor exam activity through the admin dashboard.
                </p>

                <ul class="portal-list">
                    <li><i class="fa-solid fa-circle-check"></i> Create and manage exams</li>
                    <li><i class="fa-solid fa-circle-check"></i> Add MCQ and True/False questions</li>
                    <li><i class="fa-solid fa-circle-check"></i> View user exam results</li>
                    <li><i class="fa-solid fa-circle-check"></i> Control exam workflow</li>
                </ul>

                <a href="admin/login.php" class="btn btn-primary portal-btn">
                    <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Go to Admin Login
                </a>
            </div>

            <!-- USER -->
            <div class="portal-card">
                <div class="portal-icon bg-green">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h3>User Panel</h3>
                <p>
                    Users can log in to see available exams, start timed tests, answer questions, and view results
                    after submission from a clean exam interface.
                </p>

                <ul class="portal-list">
                    <li><i class="fa-solid fa-circle-check"></i> Attend available exams</li>
                    <li><i class="fa-solid fa-circle-check"></i> Answer MCQ and True/False</li>
                    <li><i class="fa-solid fa-circle-check"></i> Submit and view results</li>
                    <li><i class="fa-solid fa-circle-check"></i> Track exam performance</li>
                </ul>

                <a href="user/login.php" class="btn btn-success portal-btn">
                    <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Go to User Login
                </a>
            </div>

            <!-- REGISTER -->
            <div class="portal-card">
                <div class="portal-icon bg-orange">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h3>User Registration</h3>
                <p>
                    New users can create an account to access the exam portal, join active exams, and view their exam
                    history and results after logging in.
                </p>

                <ul class="portal-list">
                    <li><i class="fa-solid fa-circle-check"></i> Quick account registration</li>
                    <li><i class="fa-solid fa-circle-check"></i> Join the exam portal easily</li>
                    <li><i class="fa-solid fa-circle-check"></i> Access personal exam dashboard</li>
                    <li><i class="fa-solid fa-circle-check"></i> View result history</li>
                </ul>

                <a href="user/register.php" class="btn btn-warning text-white portal-btn">
                    <i class="fa-solid fa-user-plus me-2"></i>Go to User Register
                </a>
            </div>
        </div>

        <!-- FEATURES SECTION -->
        <div class="feature-showcase">
            <div class="section-heading mb-0">
                <h2>What this Online Exam System can do</h2>
                <p>A quick overview of the features available in your PHP + MySQL exam project.</p>
            </div>

            <div class="feature-grid">
                <div class="feature-item">
                    <h4>Exam Creation & Management</h4>
                    <p>
                        Admin can create multiple exams with custom duration, descriptions, marks, and question sets
                        for different tests or subjects.
                    </p>
                </div>

                <div class="feature-item">
                    <h4>Question Bank Support</h4>
                    <p>
                        Supports both MCQ and True/False questions, with answer options, correct answer selection,
                        and marks per question.
                    </p>
                </div>

                <div class="feature-item">
                    <h4>Random Question Display</h4>
                    <p>
                        Questions can be shown in random order during the exam so each user gets a more dynamic exam
                        experience.
                    </p>
                </div>

                <div class="feature-item">
                    <h4>Result Tracking</h4>
                    <p>
                        Each attempt stores score, total questions, correct answers, wrong answers, and submission time
                        for reporting.
                    </p>
                </div>
            </div>
        </div>

    </div>
    

</div>

<?php include 'includes/footer.php'; ?>