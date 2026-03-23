<?php
session_start();
include 'db.php';

// Live stats from DB
$course_count    = $conn->query("SELECT COUNT(*) AS c FROM courses")->fetch_assoc()['c'];
$enrollment_count = $conn->query("SELECT COUNT(*) AS c FROM enrollments")->fetch_assoc()['c'];
$category_count  = 8; // Update when you add a categories table
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Skill Academy — Master In-Demand Skills</title>
<link rel="stylesheet" href="assets/style.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="bg-dots"></div>

<nav>
  <a href="index.php" class="nav-brand">
    <div class="nav-icon">⚡</div>
    <span>Skill Academy</span>
  </a>
  <div class="nav-right">
    <a href="courses/list.php" class="nav-link">Explore</a>
    <?php if(isset($_SESSION['user'])): ?>
      <a href="dashboard/dashboard.php" class="nav-link">Dashboard</a>
      <a href="auth/logout.php" class="nav-link nav-cta">Logout</a>
    <?php else: ?>
      <a href="auth/login.php" class="nav-link">Sign In</a>
      <a href="auth/register.php" class="nav-cta">Get Started →</a>
    <?php endif; ?>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-glow"></div>
  <canvas id="heroCanvas" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;"></canvas>

  <div class="hero-badge" style="position:relative;z-index:2;">
    <span class="pulse-dot"></span> <?= $course_count ?> Course<?= $course_count != 1 ? 's' : '' ?> Available Now
  </div>

  <h1 style="position:relative;z-index:2;">
    Master <span class="grad-text">In-Demand</span><br>Skills Today
  </h1>

  <p style="position:relative;z-index:2;">
    Learn from industry experts, build real projects,<br>and get job-ready faster than ever.
  </p>

  <div class="hero-btns" style="position:relative;z-index:2;">
    <a href="courses/list.php"><button class="btn-primary">📚 Explore Courses</button></a>
    <a href="auth/register.php"><button class="btn-outline">Join Free →</button></a>
  </div>

  <div class="hero-stats" style="position:relative;z-index:2;">
    <div class="stat-item"><strong><?= $course_count ?></strong><span>Courses</span></div>
    <div class="stat-sep"></div>
    <div class="stat-item"><strong><?= $enrollment_count ?>+</strong><span>Enrollments</span></div>
    <div class="stat-sep"></div>
    <div class="stat-item"><strong><?= $category_count ?></strong><span>Categories</span></div>
  </div>
</section>

<!-- FEATURES -->
<section class="section">
  <div class="section-eyebrow">Why Us</div>
  <h2 class="section-title">Everything you need to <span class="grad-text">succeed</span></h2>
  <div class="features-grid">
    <div class="feat-card d1">
      <div class="feat-icon">🎯</div>
      <h3>Project-Based</h3>
      <p>Build real projects employers actually care about.</p>
    </div>
    <div class="feat-card d2">
      <div class="feat-icon">⚡</div>
      <h3>Learn Your Way</h3>
      <p>Study at your own pace, on any device, anytime.</p>
    </div>
    <div class="feat-card d3">
      <div class="feat-icon">🏆</div>
      <h3>Certificates</h3>
      <p>Earn industry-recognized certificates on completion.</p>
    </div>
    <div class="feat-card d4">
      <div class="feat-icon">👥</div>
      <h3>Expert Teachers</h3>
      <p>Learn from professionals at top tech companies.</p>
    </div>
  </div>
</section>

<footer>
  <a href="index.php" class="nav-brand" style="text-decoration:none;">
    <div class="nav-icon">⚡</div>
    <span>Skill Academy</span>
  </a>
  <p>© 2025 Skill Academy · Built for the next generation of developers</p>
</footer>

<script src="assets/app.js"></script>
</body>
</html>