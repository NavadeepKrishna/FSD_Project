<?php
session_start();
include '../db.php';

$res   = $conn->query("SELECT * FROM courses ORDER BY id DESC");
$icons = ['🌐','⚛️','🐍','🎨','🛡️','📱','☁️','🤖','🗄️','📊','🔧','📐'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Courses — Skill Academy</title>
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
    <?php if(isset($_SESSION['user'])): ?>
      <?php if($_SESSION['role'] === 'instructor'): ?>
        <a href="create.php" class="nav-link">+ Create</a>
      <?php endif; ?>
      <a href="../dashboard/dashboard.php" class="nav-link">Dashboard</a>
      <a href="../auth/logout.php" class="nav-link nav-cta">Logout</a>
    <?php else: ?>
      <a href="../auth/login.php" class="nav-link">Sign In</a>
      <a href="../auth/register.php" class="nav-cta">Join Free →</a>
    <?php endif; ?>
  </div>
</nav>

<div class="page-wrapper">
  <div class="page-header">
    <div class="section-eyebrow">Library</div>
    <h2>Explore <span class="grad-text">All Courses</span></h2>
    <p>Pick a course and start building real skills today.</p>
  </div>

  <div class="courses-grid">
    <?php
    if ($res && $res->num_rows > 0):
      $i = 0;
      while ($c = $res->fetch_assoc()):
        $icon = $icons[$i % count($icons)];
        $i++;
    ?>
    <div class="course-card d<?= ($i % 4) + 1 ?>">
      <!-- Thumb: fixed 130px height, emoji fallback -->
      <div class="course-thumb">
        <?php if (!empty($c['image'])): ?>
          <img src="<?= htmlspecialchars($c['image']) ?>"
               alt=""
               onerror="this.style.display='none'">
        <?php endif; ?>
        <?= $icon ?>
      </div>

      <div class="course-body">
        <h3><?= htmlspecialchars($c['title']) ?></h3>
        <p><?= htmlspecialchars($c['description']) ?></p>
        <div class="course-tags">
          <span class="tag">⏱ 3 Months</span>
          <span class="tag">🎓 Beginner</span>
        </div>
      </div>

      <div class="course-footer">
        <span class="slot-info">
          <span class="slot-dot"></span>
          <?= (int)$c['slots'] ?> slots left
        </span>
        <?php if(isset($_SESSION['user'])): ?>
          <a href="course_detail.php?id=<?= (int)$c['id'] ?>" class="btn-card">View →</a>
        <?php else: ?>
          <a href="../auth/login.php" class="btn-card-ghost">Login to Enroll</a>
        <?php endif; ?>
      </div>
    </div>
    <?php
      endwhile;
    else: ?>
    <div class="empty">
      <div class="empty-icon">📚</div>
      <h3>No courses yet</h3>
      <p>Instructors are uploading courses. Check back soon!</p>
    </div>
    <?php endif; ?>
  </div>
</div>

<footer>
  <a href="../index.php" class="nav-brand"><div class="nav-icon">⚡</div><span>Skill Academy</span></a>
  <p>© 2025 Skill Academy</p>
</footer>

<script src="../assets/app.js"></script>
</body>
</html>