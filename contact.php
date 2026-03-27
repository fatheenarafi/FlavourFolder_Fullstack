<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

startSession();

$errors  = [];
$success = false;
$old     = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

// Pre-fill if logged in
$currentUser = getCurrentUser();
if ($currentUser && empty($_POST)) {
    $old['name']  = $currentUser['username'];
    $old['email'] = $currentUser['email'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $old = [
        'name'    => sanitize($name),
        'email'   => sanitize($email),
        'subject' => sanitize($subject),
        'message' => sanitize($message),
    ];

    if (empty($name))             $errors['name']    = 'Name is required.';
    if (empty($email))            $errors['email']   = 'Email is required.';
    elseif (!isValidEmail($email)) $errors['email']  = 'Enter a valid email address.';
    if (empty($subject))          $errors['subject'] = 'Subject is required.';
    if (empty($message))          $errors['message'] = 'Message is required.';
    elseif (strlen($message) < 10) $errors['message'] = 'Message must be at least 10 characters.';

    if (empty($errors)) {
        $db   = getDB();
        $stmt = $db->prepare('INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $subject, $message]);
        $success = true;
        $old     = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us — FlavourFolder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    .contact-hero {
      background: linear-gradient(135deg, #D2691E 0%, #A0522D 100%);
      color: #fff;
      padding: 64px 0 48px;
      text-align: center;
    }
    .contact-hero h1 { font-family: var(--font-heading); font-size: 2.6rem; font-weight: 800; }
    .contact-hero p  { font-size: 1.05rem; opacity: 0.9; margin-bottom: 0; }
    .contact-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 6px 30px rgba(0,0,0,0.09);
      padding: 44px 40px;
    }
    .contact-info-card {
      background: linear-gradient(135deg, #fff8f2, #fdebd5);
      border-radius: 16px;
      padding: 32px 28px;
      height: 100%;
    }
    .contact-info-card h4 { font-family: var(--font-heading); font-weight: 700; margin-bottom: 24px; color: #1a1a1a; }
    .info-item { display: flex; gap: 14px; align-items: flex-start; margin-bottom: 20px; }
    .info-icon { width: 42px; height: 42px; background: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #D2691E; font-size: 1.1rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(210,105,30,0.12); }
    .info-text strong { font-weight: 700; font-size: 0.9rem; color: #1a1a1a; display: block; margin-bottom: 2px; }
    .info-text span { font-size: 0.87rem; color: #6b6b6b; }
    .form-label { font-weight: 600; font-size: 0.9rem; color: #2d2d2d; }
    .form-control, .form-select {
      border: 1.5px solid #e8e8e8;
      border-radius: 10px;
      padding: 11px 14px;
      font-size: 0.97rem;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus { border-color: #D2691E; box-shadow: 0 0 0 3px rgba(210,105,30,0.12); }
    .btn-contact {
      background: linear-gradient(135deg, #D2691E 0%, #A0522D 100%);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 13px 32px;
      font-size: 1rem;
      font-weight: 700;
      transition: all 0.3s;
    }
    .btn-contact:hover { background: linear-gradient(135deg,#A0522D,#7a3d1e); color:#fff; transform:translateY(-1px); box-shadow:0 4px 16px rgba(210,105,30,0.3); }
    .is-invalid { border-color: #dc3545 !important; }
    .invalid-feedback { display: block; font-size: 0.83rem; }
    .success-box { background: #edfbf0; border-radius: 14px; padding: 32px; text-align: center; border: 1.5px solid #b2e8c1; }
    .success-box .check { font-size: 3rem; margin-bottom: 12px; display: block; }
    .char-count { font-size: 0.8rem; color: #999; text-align: right; margin-top: 4px; }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="index.php">🍴 FlavourFolder</a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="visitor-recipes.php">Community</a></li>
          <li class="nav-item"><a class="nav-link" href="submit.php">Submit</a></li>
          <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        </ul>
        <div class="d-flex align-items-center gap-2">
          <?php if (isLoggedIn()): ?>
            <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">
              <i class="bi bi-person me-1"></i><?= htmlspecialchars($currentUser['username']) ?>
            </a>
            <a href="auth/logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
          <?php else: ?>
            <a href="auth/login.php" class="btn btn-sm btn-outline-secondary">Login</a>
            <a href="auth/register.php" class="btn btn-sm" style="background:#D2691E;color:#fff;border-radius:8px;">Register</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <section class="contact-hero">
    <div class="container">
      <h1>📬 Contact Us</h1>
      <p>Have a question, suggestion, or just want to say hi? We'd love to hear from you.</p>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row g-5">

        <!-- Contact Info -->
        <div class="col-md-4">
          <div class="contact-info-card">
            <h4>Get in Touch</h4>
            <div class="info-item">
              <div class="info-icon"><i class="bi bi-envelope"></i></div>
              <div class="info-text">
                <strong>Email</strong>
                <span>hello@flavourfolder.com</span>
              </div>
            </div>
            <div class="info-item">
              <div class="info-icon"><i class="bi bi-clock"></i></div>
              <div class="info-text">
                <strong>Response Time</strong>
                <span>Usually within 24 hours</span>
              </div>
            </div>
            <div class="info-item">
              <div class="info-icon"><i class="bi bi-geo-alt"></i></div>
              <div class="info-text">
                <strong>Based In</strong>
                <span>Sri Lanka 🇱🇰</span>
              </div>
            </div>
            <hr style="border-color:#e8e8e8; margin:24px 0;">
            <p style="font-size:0.88rem; color:#6b6b6b; margin-bottom:0;">
              FlavourFolder is a community-driven recipe platform. Your feedback helps us grow and improve!
            </p>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="col-md-8">
          <div class="contact-card">
            <?php if ($success): ?>
              <div class="success-box">
                <span class="check">✅</span>
                <h4 style="font-family:var(--font-heading); font-weight:700; margin-bottom:8px;">Message Sent!</h4>
                <p style="color:#6b6b6b; margin-bottom:20px;">Thanks for reaching out. We'll get back to you soon.</p>
                <a href="contact.php" class="btn btn-contact">Send Another Message</a>
              </div>
            <?php else: ?>
              <h4 style="font-family:var(--font-heading); font-weight:700; margin-bottom:24px;">
                <i class="bi bi-chat-dots me-2" style="color:#D2691E;"></i>Send a Message
              </h4>

              <form method="POST" action="contact.php" novalidate>
                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                      id="name" name="name" value="<?= htmlspecialchars($old['name']) ?>" placeholder="Your name" required>
                    <?php if (!empty($errors['name'])): ?>
                      <div class="invalid-feedback"><?= htmlspecialchars($errors['name']) ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                      id="email" name="email" value="<?= htmlspecialchars($old['email']) ?>" placeholder="you@example.com" required>
                    <?php if (!empty($errors['email'])): ?>
                      <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="mb-3">
                  <label for="subject" class="form-label">Subject</label>
                  <select class="form-select <?= isset($errors['subject']) ? 'is-invalid' : '' ?>" id="subject" name="subject">
                    <option value="">Select a subject...</option>
                    <option value="General Enquiry"    <?= $old['subject']==='General Enquiry'    ? 'selected':'' ?>>General Enquiry</option>
                    <option value="Recipe Suggestion"  <?= $old['subject']==='Recipe Suggestion'  ? 'selected':'' ?>>Recipe Suggestion</option>
                    <option value="Bug Report"         <?= $old['subject']==='Bug Report'         ? 'selected':'' ?>>Bug Report</option>
                    <option value="Partnership"        <?= $old['subject']==='Partnership'        ? 'selected':'' ?>>Partnership</option>
                    <option value="Other"              <?= $old['subject']==='Other'              ? 'selected':'' ?>>Other</option>
                  </select>
                  <?php if (!empty($errors['subject'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['subject']) ?></div>
                  <?php endif; ?>
                </div>

                <div class="mb-4">
                  <label for="message" class="form-label">Message</label>
                  <textarea
                    class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>"
                    id="message" name="message" rows="5"
                    placeholder="Write your message here..."
                    maxlength="1000"
                    required
                  ><?= htmlspecialchars($old['message']) ?></textarea>
                  <div class="char-count"><span id="charCount">0</span>/1000</div>
                  <?php if (!empty($errors['message'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['message']) ?></div>
                  <?php endif; ?>
                </div>

                <button type="submit" class="btn-contact w-100">
                  <i class="bi bi-send me-2"></i>Send Message
                </button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="bg-dark text-light py-4">
    <div class="container text-center">
      <p class="mb-0">© 2025 FlavourFolder — Made with ❤️ for food lovers.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const msgArea  = document.getElementById('message');
    const counter  = document.getElementById('charCount');
    if (msgArea && counter) {
      counter.textContent = msgArea.value.length;
      msgArea.addEventListener('input', () => counter.textContent = msgArea.value.length);
    }
  </script>
</body>
</html>
