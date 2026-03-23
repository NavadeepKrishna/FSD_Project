<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php"); exit();
}

$uid  = $_SESSION['user_id'];
$role = $_SESSION['role'];
$name = $_SESSION['name'];

$enrolled_count = 0;
$created_count  = 0;

if ($role === 'student') {
    $r = $conn->prepare("SELECT COUNT(*) AS c FROM enrollments WHERE user_id = ?");
    $r->bind_param("i", $uid); $r->execute();
    $enrolled_count = $r->get_result()->fetch_assoc()['c'];
}
if ($role === 'instructor') {
    $r = $conn->prepare("SELECT COUNT(*) AS c FROM courses WHERE instructor_id = ?");
    $r->bind_param("i", $uid); $r->execute();
    $created_count = $r->get_result()->fetch_assoc()['c'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Skill Academy</title>
<link rel="stylesheet" href="../assets/style.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="bg-dots"></div>

<nav>
  <a href="../index.php" class="nav-brand">
    <div class="nav-icon">⚡</div>
    <span>Skill Academy</span>
  </a>
  <div class="nav-right">
    <span class="nav-role-badge"><?= $role === 'instructor' ? '🎓 Instructor' : '📚 Student' ?></span>
    <a href="../courses/list.php" class="nav-link">Courses</a>
    <a href="../auth/logout.php" class="nav-link nav-cta">Logout</a>
  </div>
</nav>

<div class="page-wrapper">
  <div class="dashboard-hero">
    <h1>Welcome back, <span class="grad-text"><?= htmlspecialchars($name) ?></span> 👋</h1>
    <p>Here's a summary of your Skill Academy account.</p>
  </div>

  <div class="dashboard-body">
    <div class="stats-row">
      <?php if($role === 'student'): ?>
      <div class="stat-card d1">
        <div class="sc-label">Courses Enrolled</div>
        <div class="sc-val green"><?= $enrolled_count ?></div>
      </div>
      <div class="stat-card d2">
        <div class="sc-label">Account Type</div>
        <div class="sc-val cyan" style="font-size:1.4rem;">Student</div>
      </div>
      <?php else: ?>
      <div class="stat-card d1">
        <div class="sc-label">Courses Created</div>
        <div class="sc-val green"><?= $created_count ?></div>
      </div>
      <div class="stat-card d2">
        <div class="sc-label">Account Type</div>
        <div class="sc-val cyan" style="font-size:1.4rem;">Instructor</div>
      </div>
      <?php endif; ?>
    </div>

    <div class="quick-section">
      <h3>Quick Actions</h3>
      <div class="actions-row">
        <a href="../courses/list.php" class="action-btn primary">📚 Browse Courses</a>
        <?php if($role === 'instructor'): ?>
          <a href="../courses/create.php" class="action-btn ghost">➕ Create New Course</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<footer>
  <a href="../index.php" class="nav-brand"><div class="nav-icon">⚡</div><span>Skill Academy</span></a>
  <p>© 2025 Skill Academy</p>
</footer>

<script src="../assets/app.js"></script>
</body>
</html>