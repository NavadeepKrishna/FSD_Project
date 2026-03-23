<?php
session_start();
include '../db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list.php"); exit();
}

$id   = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();
if (!$course) { header("Location: list.php"); exit(); }

$enrolled = false;
if (isset($_SESSION['user_id'])) {
    $chk = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $chk->bind_param("ii", $_SESSION['user_id'], $id);
    $chk->execute();
    $enrolled = $chk->get_result()->num_rows > 0;
}

$icons = ['🌐','⚛️','🐍','🎨','🛡️','📱','☁️','🤖','🗄️','📊'];
$icon  = $icons[$course['id'] % count($icons)];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($course['title']) ?> — Skill Academy</title>
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
    <a href="list.php" class="nav-link">← All Courses</a>
    <?php if(isset($_SESSION['user'])): ?>
      <a href="../dashboard/dashboard.php" class="nav-link">Dashboard</a>
      <a href="../auth/logout.php" class="nav-link nav-cta">Logout</a>
    <?php else: ?>
      <a href="../auth/register.php" class="nav-cta">Join Free →</a>
    <?php endif; ?>
  </div>
</nav>

<div class="page-wrapper">
  <div class="detail-wrapper">
    <div class="detail-card">

      <div class="detail-banner"><?= $icon ?></div>

      <div class="detail-content">
        <h2><?= htmlspecialchars($course['title']) ?></h2>
        <p class="desc"><?= htmlspecialchars($course['description']) ?></p>

        <div class="meta-pills">
          <div class="pill">⏱ <strong>3 Months</strong></div>
          <div class="pill">🪑 <strong><?= (int)$course['slots'] ?> slots</strong></div>
          <div class="pill">💼 <strong>Job Ready</strong></div>
          <div class="pill">🏆 <strong>Certificate</strong></div>
        </div>

        <div class="enroll-panel">
          <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($enrolled): ?>
              <div class="alert alert-success" style="margin-bottom:14px;">✅ You're enrolled in this course!</div>
              <a href="../dashboard/dashboard.php" class="action-btn primary">Go to Dashboard</a>
            <?php elseif($course['slots'] <= 0): ?>
              <div class="alert alert-error">❌ This course is fully booked. Check back later.</div>
            <?php else: ?>
              <h3>Ready to start?</h3>
              <form method="POST" action="enroll.php" style="margin-top:14px;">
                <input type="hidden" name="course_id" value="<?= (int)$course['id'] ?>">
                <button type="submit" class="btn-submit" style="width:auto;padding:12px 28px;">Enroll Now — It's Free →</button>
              </form>
            <?php endif; ?>
          <?php else: ?>
            <h3>Start learning today</h3>
            <p style="color:var(--sub);font-size:0.88rem;margin:8px 0 18px;">Create a free account to enroll in this course.</p>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
              <a href="../auth/register.php" class="action-btn primary">Create Account</a>
              <a href="../auth/login.php" class="action-btn ghost">Sign In</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../assets/app.js"></script>
</body>
</html>