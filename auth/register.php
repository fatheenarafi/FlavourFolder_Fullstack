<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';

startSession();

if (isLoggedIn()) {
    header('Location: ../dashboard.php');
    exit;
}

$errors = [];
$old = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username        = trim($_POST['username'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirm         = $_POST['confirm_password'] ?? '';

    $old['username'] = sanitize($username);
    $old['email']    = sanitize($email);

    // Validate username
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    } elseif (strlen($username) < 3 || strlen($username) > 30) {
        $errors['username'] = 'Username must be 3–30 characters.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = 'Only letters, numbers, and underscores allowed.';
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!isValidEmail($email)) {
        $errors['email'] = 'Enter a valid email address.';
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    // Confirm password
    if ($password !== $confirm) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $db = getDB();

        // Check uniqueness
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1');
        $stmt->execute([$email, $username]);
        $existing = $stmt->fetch();

        if ($existing) {
            $errors['general'] = 'An account with this email or username already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt   = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $hashed]);

            $userId = $db->lastInsertId();
            $_SESSION['user_id']  = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['email']    = $email;

            setFlash('success', 'Account created! Welcome to FlavourFolder, ' . $username . ' 🎉');
            header('Location: ../dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — FlavourFolder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .auth-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #fff8f2 0%, #fdebd5 50%, #f9d8b8 100%);
      padding: 40px 16px;
    }
    .auth-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 8px 40px rgba(210,105,30,0.13);
      padding: 48px 44px;
      width: 100%;
      max-width: 500px;
    }
    .auth-brand {
      font-family: var(--font-heading,'Playfair Display',serif);
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--color-primary,#D2691E);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 28px;
    }
    .auth-title { font-family: var(--font-heading,'Playfair Display',serif); font-size: 2rem; font-weight: 700; color: #1a1a1a; margin-bottom: 6px; }
    .auth-subtitle { color: #6b6b6b; font-size: 0.97rem; margin-bottom: 32px; }
    .form-label { font-weight: 600; font-size: 0.9rem; color: #2d2d2d; margin-bottom: 6px; }
    .form-control {
      border: 1.5px solid #e8e8e8;
      border-radius: 10px;
      padding: 11px 14px;
      font-size: 0.97rem;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus { border-color: #D2691E; box-shadow: 0 0 0 3px rgba(210,105,30,0.12); }
    .input-group .form-control { border-right: none; }
    .input-group .btn-outline-secondary {
      border: 1.5px solid #e8e8e8;
      border-left: none;
      border-radius: 0 10px 10px 0;
      background: #fafafa;
      color: #6b6b6b;
    }
    .input-group .btn-outline-secondary:hover { background: #f0e8e0; color: #D2691E; }
    .btn-auth {
      background: linear-gradient(135deg, #D2691E 0%, #A0522D 100%);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 13px;
      font-size: 1rem;
      font-weight: 700;
      width: 100%;
      transition: all 0.3s;
    }
    .btn-auth:hover {
      background: linear-gradient(135deg, #A0522D 0%, #7a3d1e 100%);
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 4px 16px rgba(210,105,30,0.3);
    }
    .auth-divider { display: flex; align-items: center; gap: 12px; color: #aaa; font-size: 0.85rem; margin: 20px 0; }
    .auth-divider::before, .auth-divider::after { content:''; flex:1; height:1px; background:#e8e8e8; }
    .auth-footer { text-align: center; margin-top: 20px; font-size: 0.93rem; color: #6b6b6b; }
    .auth-footer a { color: #D2691E; font-weight: 600; }
    .auth-footer a:hover { color: #A0522D; }
    .alert-auth { border-radius: 10px; padding: 12px 16px; font-size: 0.93rem; }
    .invalid-feedback { display: block; font-size: 0.83rem; }
    .is-invalid { border-color: #dc3545 !important; }
    .password-strength { height: 4px; border-radius: 4px; transition: all 0.3s; margin-top: 6px; }
    .strength-weak { background: #dc3545; width: 33%; }
    .strength-medium { background: #ffc107; width: 66%; }
    .strength-strong { background: #28a745; width: 100%; }
  </style>
</head>
<body>
<div class="auth-wrapper">
  <div class="auth-card">
    <a class="auth-brand" href="../index.php">🍴 FlavourFolder</a>
    <h1 class="auth-title">Create account</h1>
    <p class="auth-subtitle">Join the FlavourFolder community and start sharing recipes.</p>

    <?php if (!empty($errors['general'])): ?>
      <div class="alert alert-danger alert-auth mb-4">
        <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($errors['general']) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="register.php" novalidate>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
          id="username"
          name="username"
          value="<?= htmlspecialchars($old['username']) ?>"
          placeholder="e.g. chef_mario"
          autocomplete="username"
          required
        >
        <?php if (!empty($errors['username'])): ?>
          <div class="invalid-feedback"><?= htmlspecialchars($errors['username']) ?></div>
        <?php else: ?>
          <div class="form-text" style="font-size:0.82rem;">Letters, numbers, underscores. 3–30 characters.</div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input
          type="email"
          class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
          id="email"
          name="email"
          value="<?= htmlspecialchars($old['email']) ?>"
          placeholder="you@example.com"
          autocomplete="email"
          required
        >
        <?php if (!empty($errors['email'])): ?>
          <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
          <input
            type="password"
            class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
            id="password"
            name="password"
            placeholder="Minimum 8 characters"
            autocomplete="new-password"
            required
          >
          <button class="btn btn-outline-secondary" type="button" id="togglePassword">
            <i class="bi bi-eye" id="toggleIcon"></i>
          </button>
          <?php if (!empty($errors['password'])): ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
          <?php endif; ?>
        </div>
        <div id="passwordStrengthBar" class="password-strength mt-2" style="display:none;"></div>
        <div id="passwordStrengthText" style="font-size:0.8rem; margin-top:4px; color:#6b6b6b;"></div>
      </div>

      <div class="mb-4">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <div class="input-group">
          <input
            type="password"
            class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
            id="confirm_password"
            name="confirm_password"
            placeholder="Re-enter your password"
            autocomplete="new-password"
            required
          >
          <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
            <i class="bi bi-eye" id="toggleConfirmIcon"></i>
          </button>
          <?php if (!empty($errors['confirm_password'])): ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors['confirm_password']) ?></div>
          <?php endif; ?>
        </div>
      </div>

      <button type="submit" class="btn-auth">
        <i class="bi bi-person-plus me-2"></i>Create Account
      </button>
    </form>

    <div class="auth-divider">or</div>

    <div class="auth-footer">
      Already have an account? <a href="login.php">Sign in</a>
    </div>
    <div class="auth-footer mt-2">
      <a href="../index.php" style="color:#6b6b6b; font-weight:400;">
        <i class="bi bi-arrow-left me-1"></i>Back to home
      </a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Password visibility toggles
  function makeToggle(btnId, iconId, inputId) {
    document.getElementById(btnId).addEventListener('click', function () {
      const pw   = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (pw.type === 'password') {
        pw.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
      } else {
        pw.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
      }
    });
  }
  makeToggle('togglePassword', 'toggleIcon', 'password');
  makeToggle('toggleConfirm', 'toggleConfirmIcon', 'confirm_password');

  // Password strength
  const pwInput   = document.getElementById('password');
  const bar       = document.getElementById('passwordStrengthBar');
  const barText   = document.getElementById('passwordStrengthText');

  pwInput.addEventListener('input', function () {
    const val = this.value;
    if (!val) { bar.style.display = 'none'; barText.textContent = ''; return; }
    bar.style.display = 'block';

    let score = 0;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^a-zA-Z0-9]/.test(val)) score++;

    bar.className = 'password-strength mt-2';
    if (score <= 1) {
      bar.classList.add('strength-weak');
      barText.textContent = 'Weak password';
      barText.style.color = '#dc3545';
    } else if (score <= 2) {
      bar.classList.add('strength-medium');
      barText.textContent = 'Medium strength';
      barText.style.color = '#856404';
    } else {
      bar.classList.add('strength-strong');
      barText.textContent = 'Strong password ✓';
      barText.style.color = '#28a745';
    }
  });
</script>
</body>
</html>
