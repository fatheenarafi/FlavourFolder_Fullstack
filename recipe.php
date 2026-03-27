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
  <meta name="description" content="Recipe details on FlavourFolder.">
  <title id="recipe-page-title">Recipe Details — FlavourFolder</title>
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
          <li class="nav-item"><a class="nav-link" href="index.php#recipes-section">Browse</a></li>
          <li class="nav-item"><a class="nav-link" href="visitor-recipes.php">Community</a></li>
          <li class="nav-item"><a class="nav-link" href="submit.php">Submit</a></li>
        </ul>
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

  <section class="recipe-header">
    <div class="container">
      <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb justify-content-center" style="font-size: 0.9rem;">
          <li class="breadcrumb-item"><a href="index.php" style="color: var(--color-primary);">Home</a></li>
          <li class="breadcrumb-item"><a href="index.php#recipes-section" style="color: var(--color-primary);">Recipes</a></li>
          <li class="breadcrumb-item active" aria-current="page" id="breadcrumb-current">Recipe</li>
        </ol>
      </nav>
      <h1 class="fade-in">Recipe Details</h1>
      <p class="fade-in fade-in-delay-1">Discover how to make this delicious meal!</p>
      <span class="badge bg-dark rounded-pill px-3 py-2 fade-in fade-in-delay-2" style="font-size: 0.85rem;" id="recipe-category-badge">
        <i class="bi bi-tag me-1"></i>Recipe
      </span>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-md-5 animate-on-scroll">
          <div class="recipe-detail-img">
            <img src="default_recipe.png" alt="Recipe Image" class="rounded" id="recipe-main-image">
          </div>
        </div>
        <div class="col-md-7 animate-on-scroll">
          <div class="recipe-info">
            <h2 id="recipe-title">Recipe Title</h2>
            <p id="recipe-description">Recipe description here.</p>
            <div class="d-flex flex-wrap gap-3 mt-3">
              <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background: var(--color-bg-warm);">
                <i class="bi bi-tag" style="color: var(--color-primary);"></i>
                <span style="font-weight: 600; font-size: 0.9rem;" id="recipe-category-pill">Recipe</span>
              </div>
              <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background: var(--color-bg-warm);">
                <i class="bi bi-image" style="color: var(--color-primary);"></i>
                <span style="font-weight: 600; font-size: 0.9rem;">Photo Included</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-4">
    <div class="container">
      <div class="recipe-tabs animate-on-scroll">
        <ul class="nav nav-tabs mb-4">
          <li class="nav-item">
            <a class="nav-link active" href="#" id="ingredients-tab">
              <i class="bi bi-basket me-1"></i>Ingredients
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" id="steps-tab">
              <i class="bi bi-list-ol me-1"></i>Cooking Steps
            </a>
          </li>
        </ul>
      </div>

      <div id="ingredients-section" class="animate-on-scroll">
        <div class="row">
          <div class="col-lg-8">
            <h3 style="font-family: var(--font-heading); font-weight: 700; margin-bottom: 20px;">
              <i class="bi bi-basket2 me-2" style="color: var(--color-primary);"></i>Ingredients
            </h3>
            <ul class="list-unstyled" id="ingredients-list"></ul>
          </div>
        </div>
      </div>

      <div id="steps-section" style="display: none;" class="animate-on-scroll">
        <h3 style="font-family: var(--font-heading); font-weight: 700; margin-bottom: 20px;">
          <i class="bi bi-list-check me-2" style="color: var(--color-primary);"></i>Instructions
        </h3>
        <div class="row">
          <div class="col-lg-8" id="steps-list"></div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5" style="background-color: var(--color-bg-warm);">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="chef-notes-section animate-on-scroll">
            <h3>
              <i class="bi bi-journal-text me-2" style="color: var(--color-primary);"></i>Personal Chef Notes
            </h3>
            <textarea id="chef-notes-textarea" class="form-control" rows="4" placeholder="Write your notes here... e.g. Add extra chili 🌶️"></textarea>
            <small class="form-text text-muted d-block mt-1 mb-3">Add your cooking tips and tweaks for next time.</small>
            <button id="save-notes-btn" class="btn btn-dark-custom w-100 w-md-auto">
              <i class="bi bi-check2-circle me-1"></i>Save Notes
            </button>
            <div id="saved-notes-display" class="mt-3"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="bg-dark text-light">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <h5>🍴 FlavourFolder</h5>
          <p>Your go-to digital recipe book. Discover, cook, and share your favourite recipes.</p>
        </div>
        <div class="col-md-4 mb-4 mb-md-0">
          <h5>Quick Links</h5>
          <ul>
            <li><a href="index.php"><i class="bi bi-chevron-right me-1"></i>Home</a></li>
            <li><a href="visitor-recipes.php"><i class="bi bi-chevron-right me-1"></i>Community</a></li>
            <li><a href="submit.php"><i class="bi bi-chevron-right me-1"></i>Submit</a></li>
          </ul>
        </div>
        <div class="col-md-4 text-md-end">
          <h5>Stay Connected</h5>
          <p>© 2025 FlavourFolder</p>
        </div>
      </div>
      <div class="footer-bottom text-center">
        <a href="#" class="me-3">Privacy Policy</a>
        <a href="#">Terms of Service</a>
      </div>
    </div>
  </footer>

  <div class="mobile-bottom-nav fixed-bottom d-flex d-md-none justify-content-around align-items-center py-2">
    <a href="index.php" class="text-center">
      <span class="nav-icon">🏠</span>
      <small class="d-block">Home</small>
    </a>
    <a href="index.php#recipes-section" class="text-center">
      <span class="nav-icon">🔍</span>
      <small class="d-block">Search</small>
    </a>
    <a href="visitor-recipes.php" class="text-center active">
      <span class="nav-icon">⭐</span>
      <small class="d-block">Community</small>
    </a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="main.js"></script>
</body>
</html>
