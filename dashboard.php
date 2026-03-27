<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

startSession();
requireLogin('auth/login.php');

$user  = getCurrentUser();
$flash = getFlash();
$db    = getDB();

// Get user's submitted recipes count
$stmt = $db->prepare('SELECT COUNT(*) FROM recipes WHERE user_id = ?');
$stmt->execute([$user['id']]);
$myRecipeCount = $stmt->fetchColumn();

// Get recent submitted recipes for this user
$stmt = $db->prepare('SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC LIMIT 6');
$stmt->execute([$user['id']]);
$myRecipes = $stmt->fetchAll();

// Total community recipes
$totalRecipes = $db->query('SELECT COUNT(*) FROM recipes')->fetchColumn();

// Total messages (contact submissions)
$totalMessages = $db->query('SELECT COUNT(*) FROM messages')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — FlavourFolder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    .dashboard-hero {
      background: linear-gradient(135deg, #D2691E 0%, #A0522D 60%, #7a3d1e 100%);
      color: #fff;
      padding: 56px 0 40px;
    }
    .dashboard-hero h1 { font-family: var(--font-heading); font-size: 2.4rem; font-weight: 800; }
    .dashboard-hero p { opacity: 0.88; font-size: 1.05rem; }
    .stat-card {
      background: #fff;
      border-radius: 16px;
      padding: 28px 24px;
      box-shadow: 0 4px 18px rgba(0,0,0,0.07);
      border: none;
      transition: transform 0.25s, box-shadow 0.25s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(210,105,30,0.14); }
    .stat-icon {
      width: 52px; height: 52px;
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 14px;
    }
    .stat-icon.orange { background: #fff3ea; color: #D2691E; }
    .stat-icon.green  { background: #edfbf0; color: #28a745; }
    .stat-icon.blue   { background: #eaf3ff; color: #0d6efd; }
    .stat-number { font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: #1a1a1a; }
    .stat-label  { color: #6b6b6b; font-size: 0.88rem; margin-top: 2px; }
    .recipe-card-mini {
      background: #fff;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 3px 12px rgba(0,0,0,0.07);
      transition: transform 0.25s, box-shadow 0.25s;
      height: 100%;
    }
    .recipe-card-mini:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(210,105,30,0.14); }
    .recipe-card-mini img { width: 100%; height: 160px; object-fit: cover; }
    .recipe-card-mini .card-body { padding: 16px; }
    .recipe-card-mini .card-title { font-family: var(--font-heading); font-size: 1rem; font-weight: 700; margin-bottom: 6px; color: #1a1a1a; }
    .badge-category { background: #fff3ea; color: #D2691E; font-size: 0.75rem; border-radius: 20px; padding: 3px 10px; font-weight: 600; }
    .section-heading { font-family: var(--font-heading); font-size: 1.5rem; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
    .empty-state { text-align: center; padding: 48px 24px; color: #6b6b6b; }
    .empty-state .icon { font-size: 3rem; margin-bottom: 14px; display: block; }
    .quick-action {
      background: #fff;
      border-radius: 14px;
      padding: 22px 20px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.07);
      display: flex;
      align-items: center;
      gap: 16px;
      text-decoration: none;
      color: #1a1a1a;
      transition: transform 0.25s, box-shadow 0.25s;
      border: 1.5px solid transparent;
    }
    .quick-action:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(210,105,30,0.15); border-color: #D2691E; color: #D2691E; }
    .quick-action .qa-icon { font-size: 1.6rem; }
    .quick-action .qa-label { font-weight: 700; font-size: 0.97rem; }
    .quick-action .qa-desc { font-size: 0.82rem; color: #6b6b6b; }
  </style>
</head>
<body>

  <!-- Navbar -->
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
          <li class="nav-item"><a class="nav-link" href="submit.php">Submit Recipe</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
        <div class="d-flex align-items-center gap-3">
          <span class="text-muted" style="font-size:0.9rem;">
            <i class="bi bi-person-circle me-1"></i>
            <?= htmlspecialchars($user['username']) ?>
          </span>
          <a href="auth/logout.php" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Flash message -->
  <?php if ($flash): ?>
  <div class="container mt-3">
    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($flash['message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
  <?php endif; ?>

  <!-- Hero -->
  <section class="dashboard-hero">
    <div class="container">
      <h1>Welcome back, <?= htmlspecialchars($user['username']) ?>! 👨‍🍳</h1>
      <p>Here's a summary of your FlavourFolder activity.</p>
    </div>
  </section>

  <!-- Stats -->
  <section class="py-5" style="background:#fafafa;">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-journal-richtext"></i></div>
            <div class="stat-number"><?= $myRecipeCount ?></div>
            <div class="stat-label">Your Submitted Recipes</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-people"></i></div>
            <div class="stat-number"><?= $totalRecipes ?></div>
            <div class="stat-label">Total Community Recipes</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-envelope"></i></div>
            <div class="stat-number"><?= $totalMessages ?></div>
            <div class="stat-label">Contact Messages Received</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Quick Actions -->
  <section class="py-4">
    <div class="container">
      <h2 class="section-heading mb-4">Quick Actions</h2>
      <div class="row g-3">
        <div class="col-md-3 col-sm-6">
          <a href="submit.php" class="quick-action">
            <span class="qa-icon">📝</span>
            <div>
              <div class="qa-label">Submit Recipe</div>
              <div class="qa-desc">Share a new recipe</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-sm-6">
          <a href="visitor-recipes.php" class="quick-action">
            <span class="qa-icon">🍽️</span>
            <div>
              <div class="qa-label">Browse Community</div>
              <div class="qa-desc">Explore all recipes</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-sm-6">
          <a href="contact.php" class="quick-action">
            <span class="qa-icon">✉️</span>
            <div>
              <div class="qa-label">Contact Us</div>
              <div class="qa-desc">Send a message</div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-sm-6">
          <a href="index.php" class="quick-action">
            <span class="qa-icon">🏠</span>
            <div>
              <div class="qa-label">Home</div>
              <div class="qa-desc">Back to main page</div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- My Recipes -->
  <section class="py-5" style="background:#fff8f2;">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-heading mb-0">My Recipes</h2>
        <a href="submit.php" class="btn btn-sm" style="background:#D2691E; color:#fff; border-radius:8px;">
          <i class="bi bi-plus me-1"></i>Add New
        </a>
      </div>

      <?php if (empty($myRecipes)): ?>
        <div class="empty-state">
          <span class="icon">🍳</span>
          <h5 style="font-family:var(--font-heading);">No recipes yet</h5>
          <p>You haven't submitted any recipes. Share your first one!</p>
          <a href="submit.php" class="btn mt-2" style="background:#D2691E;color:#fff;border-radius:10px;">
            <i class="bi bi-plus-circle me-2"></i>Submit a Recipe
          </a>
        </div>
      <?php else: ?>
        <div class="row g-4">
          <?php foreach ($myRecipes as $r): ?>
          <div class="col-md-4 col-sm-6">
            <div class="recipe-card-mini">
              <?php
                $imgSrc = '';
                if (!empty($r['image'])) {
                  // Handle different path formats
                  if (str_starts_with($r['image'], 'uploads/')) {
                    $imgSrc = 'img/' . $r['image'];
                  } elseif (str_starts_with($r['image'], 'img/')) {
                    $imgSrc = $r['image'];
                  } else {
                    $imgSrc = 'img/' . $r['image'];
                  }
                }
              ?>
              <?php if ($imgSrc && file_exists($imgSrc)): ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($r['title']) ?>">
              <?php else: ?>
                <div style="height:160px;background:linear-gradient(135deg,#fff3ea,#fdebd5);display:flex;align-items:center;justify-content:center;font-size:2.5rem;">🍽️</div>
              <?php endif; ?>
              <div class="card-body">
                <h6 class="card-title"><?= htmlspecialchars($r['title']) ?></h6>
                <span class="badge-category"><?= htmlspecialchars($r['category']) ?></span>
                <p style="font-size:0.82rem;color:#6b6b6b;margin-top:8px;margin-bottom:0;">
                  <?= htmlspecialchars(mb_substr($r['description'], 0, 80)) ?>…
                </p>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <footer class="bg-dark text-light py-4">
    <div class="container text-center">
      <p class="mb-0">© 2025 FlavourFolder — Made with ❤️ for food lovers.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
