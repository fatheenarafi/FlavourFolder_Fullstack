<?php
require_once '../includes/functions.php';
require_once '../includes/db.php';

startSession();

if (isLoggedIn()) {
    header('Location: ../dashboard.php');
    exit;
}

$errors = [];
$old_email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $old_email = sanitize($email);

    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!isValidEmail($email)) {
        $errors['email'] = 'Enter a valid email address.';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    if (empty($errors)) {
        $db   = getDB();
        $stmt = $db->prepare('SELECT id, username, email, password FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email']    = $user['email'];

            setFlash('success', 'Welcome back, ' . $user['username'] . '! 👋');
            header('Location: ../dashboard.php');
            exit;
        } else {
            $errors['general'] = 'Invalid email or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — FlavourFolder</title>
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
      max-width: 460px;
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
    .auth-title {
      font-family: var(--font-heading,'Playfair Display',serif);
      font-size: 2rem;
      font-weight: 700;
      color: #1a1a1a;
      margin-bottom: 6px;
    }
    .auth-subtitle {
      color: #6b6b6b;
      font-size: 0.97rem;
      margin-bottom: 32px;
    }
    .form-label { font-weight: 600; font-size: 0.9rem; color: #2d2d2d; margin-bottom: 6px; }
    .form-control {
      border: 1.5px solid #e8e8e8;
      border-radius: 10px;
      padding: 11px 14px;
      font-size: 0.97rem;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
      border-color: #D2691E;
      box-shadow: 0 0 0 3px rgba(210,105,30,0.12);
    }
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
      letter-spacing: 0.3px;
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
    .remember-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .form-check-input:checked { background-color: #D2691E; border-color: #D2691E; }
    .forgot-link { font-size: 0.87rem; color: #D2691E; font-weight: 600; }
    .forgot-link:hover { color: #A0522D; }
  </style>
</head>
<body>
<div class="auth-wrapper">
  <div class="auth-card">
    <a class="auth-brand" href="../index.php">🍴 FlavourFolder</a>
    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-subtitle">Sign in to your account to continue.</p>

    <?php if (!empty($errors['general'])): ?>
      <div class="alert alert-danger alert-auth mb-4">
        <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($errors['general']) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input
          type="email"
          class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
          id="email"
          name="email"
          value="<?= htmlspecialchars($old_email) ?>"
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
            placeholder="Enter your password"
            autocomplete="current-password"
            required
          >
          <button class="btn btn-outline-secondary" type="button" id="togglePassword">
            <i class="bi bi-eye" id="toggleIcon"></i>
          </button>
          <?php if (!empty($errors['password'])): ?>
            <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
          <?php endif; ?>
        </div>
      </div>

      <div class="remember-row">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember" name="remember">
          <label class="form-check-label" for="remember" style="font-size:0.88rem;">Remember me</label>
        </div>
        <a href="#" class="forgot-link">Forgot password?</a>
      </div>

      <button type="submit" class="btn-auth">
        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
      </button>
    </form>

    <div class="auth-divider">or</div>

    <div class="auth-footer">
      Don't have an account? <a href="register.php">Create one</a>
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
  // Toggle password visibility
  document.getElementById('togglePassword').addEventListener('click', function () {
    const pw   = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (pw.type === 'password') {
      pw.type = 'text';
      icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
      pw.type = 'password';
      icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
  });
</script>
</body>
</html>
