<?php
session_start();
include '../db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/dashboard.php"); exit();
}

$error = '';
if (isset($_POST['register'])) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $pass    = $_POST['password'];
    $confirm = $_POST['confirm'];
    $role    = $_POST['role'];

    if (strlen($name) < 2) {
        $error = "Name must be at least 2 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($pass !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $chk = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $chk->bind_param("s", $email);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) {
            $error = "This email is already registered. Try signing in.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $ins  = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $ins->bind_param("ssss", $name, $email, $hash, $role);
            $ins->execute();
            header("Location: login.php?registered=1"); exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account — Skill Academy</title>
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
    <a href="../courses/list.php" class="nav-link">Explore</a>
    <a href="login.php" class="nav-cta">Sign In →</a>
  </div>
</nav>

<div class="auth-layout">
  <div class="auth-box" style="max-width:440px;">

    <div class="auth-logo-row">
      <div class="nav-icon">⚡</div>
      <span>Skill Academy</span>
    </div>

    <h2>Create Account 🚀</h2>
    <p class="auth-sub">Join thousands of learners — completely free.</p>

    <?php if($error): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="field">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="John Doe" required
               value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
      </div>

      <div class="field">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required
               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
      </div>

      <div class="field">
        <label for="pass">Password</label>
        <div class="pw-wrap">
          <input type="password" id="pass" name="password" placeholder="Min. 6 characters" required>
          <button type="button" class="pw-toggle" onclick="togglePw('pass',this)">👁</button>
        </div>
      </div>

      <div class="field">
        <label for="confirm">Confirm Password</label>
        <div class="pw-wrap">
          <input type="password" id="confirm" name="confirm" placeholder="Repeat your password" required>
          <button type="button" class="pw-toggle" onclick="togglePw('confirm',this)">👁</button>
        </div>
      </div>

      <div class="field">
        <label for="role">I want to</label>
        <select id="role" name="role">
          <option value="student">📚 Student — I want to learn</option>
          <option value="instructor">🎓 Instructor — I want to teach</option>
        </select>
      </div>

      <button type="submit" name="register" class="btn-submit">Create Account →</button>
    </form>

    <p class="auth-divider">Already have an account? <a href="login.php">Sign in</a></p>
    <a href="../index.php" class="back-link" style="display:block;text-align:center;margin-top:14px;">← Back to Home</a>
  </div>
</div>

<script src="../assets/app.js"></script>
</body>
</html>