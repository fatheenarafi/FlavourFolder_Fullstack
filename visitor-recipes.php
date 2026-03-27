<?php
require_once 'includes/functions.php';
startSession();
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Community Recipes — FlavourFolder</title>
  <meta name="description" content="Browse visitor submitted recipes on FlavourFolder.">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">🍴 FlavourFolder</a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="visitor-recipes.php">Community</a></li>
          <li class="nav-item"><a class="nav-link" href="submit.php">Submit</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
        <form class="d-flex me-3" role="search" onsubmit="return false;">
          <div class="input-group">
            <input id="visitor-search-input" class="form-control" type="search" placeholder="Search community recipes...">
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
          </div>
        </form>
        <div class="d-flex align-items-center gap-2">
          <?php if (isLoggedIn()): ?>
            <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">
              <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($user['username']) ?>
            </a>
            <a href="auth/logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
          <?php else: ?>
            <a href="auth/login.php" class="btn btn-sm btn-outline-secondary">Login</a>
            <a href="auth/register.php" class="btn btn-sm" style="background:#D2691E;color:#fff;border-radius:8px;padding:6px 14px;">Register</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <section class="submit-header">
    <div class="container">
      <h1 class="fade-in">Community Recipes</h1>
      <p class="fade-in fade-in-delay-1">Browse delicious recipes shared by our community.</p>
      <a href="submit.php" class="btn btn-primary-custom fade-in fade-in-delay-2">
        <i class="bi bi-plus-circle me-1"></i>Submit Your Recipe
      </a>
    </div>
  </section>

  <section class="py-5" id="visitor-recipes-section">
    <div class="container">
      <h2 class="section-title animate-on-scroll">👩‍🍳 Community Recipes</h2>
      <p class="section-subtitle animate-on-scroll">Fresh recipes submitted by our visitors</p>
      <div class="row" id="visitor-recipe-grid"></div>
      <div id="visitor-no-results" style="display:none;">
        <div class="text-center py-5">
          <span style="font-size: 4rem; display:block; margin-bottom:16px;">🍽️</span>
          <h4 style="font-family: var(--font-heading); color: var(--color-text-light);">No community recipes found</h4>
          <p style="color: var(--color-text-light);">Try a different search term or be the first to submit a recipe.</p>
          <a href="submit.php" class="btn btn-primary-custom mt-3">
            <i class="bi bi-plus-circle me-1"></i>Submit Recipe
          </a>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="cta-section animate-on-scroll">
        <h2 class="section-title" style="margin-bottom: 8px;">Share Your Favourite Dish</h2>
        <p class="section-subtitle" style="margin-bottom: 30px;">Add your own recipe and inspire other food lovers</p>
        <div class="d-grid d-md-flex justify-content-md-center gap-3">
          <a href="submit.php" class="btn btn-primary-custom btn-lg px-4">
            <i class="bi bi-plus-circle me-2"></i>Submit Recipe
          </a>
          <a href="index.php" class="btn btn-outline-custom btn-lg px-4">
            <i class="bi bi-house me-2"></i>Back to Home
          </a>
        </div>
      </div>
    </div>
  </section>

  <footer class="bg-dark text-light">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <h5>🍴 FlavourFolder</h5>
          <p>Your go-to digital recipe book. Discover, cook, and share your favourite recipes with our growing community.</p>
        </div>
        <div class="col-md-4 mb-4 mb-md-0">
          <h5>Quick Links</h5>
          <ul>
            <li><a href="index.php"><i class="bi bi-chevron-right me-1"></i>Home</a></li>
            <li><a href="visitor-recipes.php"><i class="bi bi-chevron-right me-1"></i>Community</a></li>
            <li><a href="submit.php"><i class="bi bi-chevron-right me-1"></i>Submit</a></li>
            <li><a href="contact.php"><i class="bi bi-chevron-right me-1"></i>Contact</a></li>
          </ul>
        </div>
        <div class="col-md-4 text-md-end">
          <h5>Stay Connected</h5>
          <p>© 2025 FlavourFolder</p>
          <p style="font-size: 0.85rem;">Made with ❤️ for food lovers everywhere.</p>
        </div>
      </div>
      <div class="footer-bottom text-center">
        <a href="#" class="me-3">Privacy Policy</a>
        <a href="#">Terms of Service</a>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="main.js"></script>
</body>
</html>
