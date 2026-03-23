<?php
session_start();
include '../db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/dashboard.php"); exit();
}

$error = '';
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user']    = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
        header("Location: ../dashboard/dashboard.php"); exit();
    } else {
        $error = "Incorrect email or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — Skill Academy</title>
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
    <a href="register.php" class="nav-cta">Register →</a>
  </div>
</nav>

<div class="auth-layout">
  <div class="auth-box">

    <div class="auth-logo-row">
      <div class="nav-icon">⚡</div>
      <span>Skill Academy</span>
    </div>

    <h2>Welcome Back 👋</h2>
    <p class="auth-sub">Sign in to continue your learning journey.</p>

    <?php if($error): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if(isset($_GET['registered'])): ?>
    <div class="alert alert-success">✅ Account created! Please sign in below.</div>
    <?php endif; ?>

    <form method="POST">
      <div class="field">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required
               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
      </div>

      <div class="field">
        <label for="pass">Password</label>
        <div class="pw-wrap">
          <input type="password" id="pass" name="password" placeholder="Enter your password" required>
          <button type="button" class="pw-toggle" onclick="togglePw('pass',this)">👁</button>
        </div>
      </div>

      <button type="submit" name="login" class="btn-submit">Sign In →</button>
    </form>

    <p class="auth-divider">Don't have an account? <a href="register.php">Register free</a></p>
    <a href="../index.php" class="back-link" style="display:block;text-align:center;margin-top:14px;">← Back to Home</a>
  </div>
</div>

<script src="../assets/app.js"></script>
</body>
</html>