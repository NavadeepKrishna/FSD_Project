<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit(); }
if ($_SESSION['role'] !== 'instructor') { header("Location: ../dashboard/dashboard.php"); exit(); }

$error = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc  = trim($_POST['description']);
    $slots = (int)$_POST['slots'];
    $image = trim($_POST['image']);
    $uid   = $_SESSION['user_id'];

    if (strlen($title) < 3)      $error = "Title must be at least 3 characters.";
    elseif (strlen($desc) < 10)  $error = "Please write a meaningful description.";
    elseif ($slots < 1)          $error = "Slots must be at least 1.";
    else {
        $stmt = $conn->prepare("INSERT INTO courses (title, description, instructor_id, slots, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiis", $title, $desc, $uid, $slots, $image);
        if ($stmt->execute()) $success = "Course published successfully!";
        else $error = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Course — Skill Academy</title>
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
    <a href="list.php" class="nav-link">Courses</a>
    <a href="../dashboard/dashboard.php" class="nav-link">Dashboard</a>
    <a href="../auth/logout.php" class="nav-link nav-cta">Logout</a>
  </div>
</nav>

<div class="page-wrapper">
  <div class="create-layout">
    <div class="create-box">

      <a href="../dashboard/dashboard.php" class="back-link">← Back to Dashboard</a>

      <h2>Create a Course ✏️</h2>
      <p class="auth-sub">Share your expertise with thousands of learners.</p>

      <?php if($error):   ?><div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if($success): ?>
        <div class="alert alert-success">
          ✅ <?= htmlspecialchars($success) ?>
          <a href="list.php" style="color:var(--cyan);font-weight:700;"> View Courses →</a>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="field">
          <label>Course Title</label>
          <input type="text" name="title" placeholder="e.g. Full-Stack Web Development" required
                 value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>">
        </div>

        <div class="field">
          <label>Description</label>
          <textarea name="description" placeholder="What will students learn?" required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
        </div>

        <div class="field">
          <label>Available Slots</label>
          <input type="number" name="slots" placeholder="e.g. 50" min="1" max="1000" required
                 value="<?= isset($_POST['slots']) ? (int)$_POST['slots'] : '' ?>">
        </div>

        <div class="field">
          <label>Image URL <span style="color:var(--muted);font-weight:400;text-transform:none;">(optional)</span></label>
          <input type="url" name="image" placeholder="https://example.com/image.png"
                 value="<?= isset($_POST['image']) ? htmlspecialchars($_POST['image']) : '' ?>">
        </div>

        <button type="submit" class="btn-submit">🚀 Publish Course</button>
      </form>
    </div>
  </div>
</div>

<script src="../assets/app.js"></script>
</body>
</html>