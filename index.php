<?php
require_once 'includes/functions.php';
startSession();
$user  = getCurrentUser();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="FlavourFolder — Your digital recipe book.">
  <title>FlavourFolder — Your Digital Recipe Book</title>
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
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#recipes-section">Browse</a></li>
          <li class="nav-item"><a class="nav-link" href="visitor-recipes.php">Community</a></li>
          <li class="nav-item"><a class="nav-link" href="submit.php">Submit</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
        <form class="d-flex me-3" role="search" onsubmit="return false;">
          <div class="input-group">
            <input id="nav-search-input" class="form-control" type="search" placeholder="Search recipes...">
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

  <?php if ($flash): ?>
  <div class="container mt-3">
    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
      <?= htmlspecialchars($flash['message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
  <?php endif; ?>

  <section class="hero-section">
    <div class="container">
      <h1 class="fade-in">Welcome to <span>FlavourFolder!</span></h1>
      <p class="fade-in fade-in-delay-1">What are we cooking today?</p>
      <div class="hero-search fade-in fade-in-delay-2">
        <div class="input-group input-group-lg">
          <input id="hero-search-input" type="text" class="form-control" placeholder="Search for recipes, cuisines, ingredients...">
          <button class="btn" type="button"><i class="bi bi-search"></i></button>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5" id="recipes-section">
    <div class="container">
      <h2 class="section-title animate-on-scroll">🍽️ Popular Recipes</h2>
      <p class="section-subtitle animate-on-scroll">Author recipes only</p>
      <div class="row" id="recipe-grid"></div>
      <div id="no-results" style="display:none;">
        <div class="text-center py-5">
          <span style="font-size: 4rem; display:block; margin-bottom:16px;">🍽️</span>
          <h4 style="font-family: var(--font-heading); color: var(--color-text-light);">No recipes found</h4>
          <p style="color: var(--color-text-light);">Try a different search term.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <h2 class="section-title animate-on-scroll">🌍 Explore Cuisines</h2>
      <p class="section-subtitle animate-on-scroll">Embark on a flavour journey around the world</p>
      <div id="cuisineCarousel" class="carousel slide cuisine-carousel animate-on-scroll" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#cuisineCarousel" data-bs-slide-to="0" class="active"></button>
          <button type="button" data-bs-target="#cuisineCarousel" data-bs-slide-to="1"></button>
          <button type="button" data-bs-target="#cuisineCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="img/italian_cuisine_banner.png" class="d-block w-100" alt="Italian Cuisine">
            <div class="carousel-caption">
              <h3>Explore Italian Cuisine →</h3>
              <p>From rustic pastas to elegant risottos, discover the heart of Italy.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="img/asian_cuisine_banner.png" class="d-block w-100" alt="Asian Cuisine">
            <div class="carousel-caption">
              <h3>Discover Asian Flavours →</h3>
              <p>Savour the rich spices and bold tastes of the East.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="img/desserts_banner.png" class="d-block w-100" alt="Desserts">
            <div class="carousel-caption">
              <h3>Indulge in Desserts →</h3>
              <p>Sweet creations that will satisfy every craving.</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#cuisineCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#cuisineCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </div>
  </section>

  <section class="py-5" style="background-color: var(--color-bg-warm);">
    <div class="container">
      <h2 class="section-title animate-on-scroll">📂 Browse by Category</h2>
      <p class="section-subtitle animate-on-scroll">Find your next favourite dish by category</p>
      <div class="row g-4" id="category-grid"></div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="cta-section animate-on-scroll">
        <h2 class="section-title" style="margin-bottom: 8px;">Ready to Get Cooking?</h2>
        <p class="section-subtitle" style="margin-bottom: 30px;">Join our community and share your culinary creations</p>
        <div class="d-grid d-md-flex justify-content-md-center gap-3">
          <a href="submit.php" class="btn btn-primary-custom btn-lg px-4">
            <i class="bi bi-plus-circle me-2"></i>Create New Recipe
          </a>
          <a href="visitor-recipes.php" class="btn btn-outline-custom btn-lg px-4">
            <i class="bi bi-people me-2"></i>Community Recipes
          </a>
          <?php if (!isLoggedIn()): ?>
          <a href="auth/register.php" class="btn btn-dark-custom btn-lg px-4">
            <i class="bi bi-person-plus me-2"></i>Join FlavourFolder
          </a>
          <?php else: ?>
          <a href="dashboard.php" class="btn btn-dark-custom btn-lg px-4">
            <i class="bi bi-grid me-2"></i>My Dashboard
          </a>
          <?php endif; ?>
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
            <li><a href="#recipes-section"><i class="bi bi-chevron-right me-1"></i>Browse</a></li>
            <li><a href="visitor-recipes.php"><i class="bi bi-chevron-right me-1"></i>Community</a></li>
            <li><a href="submit.php"><i class="bi bi-chevron-right me-1"></i>Submit</a></li>
            <li><a href="contact.php"><i class="bi bi-chevron-right me-1"></i>Contact</a></li>
          </ul>
        </div>
        <div class="col-md-4 text-md-end">
          <h5>Account</h5>
          <?php if (isLoggedIn()): ?>
            <p><a href="dashboard.php" style="color:#E8A259;">My Dashboard</a></p>
            <p><a href="auth/logout.php" style="color:#aaa;">Logout</a></p>
          <?php else: ?>
            <p><a href="auth/login.php" style="color:#E8A259;">Login</a></p>
            <p><a href="auth/register.php" style="color:#E8A259;">Register</a></p>
          <?php endif; ?>
          <p style="font-size:0.85rem; color:#aaa;">Made with ❤️ for food lovers everywhere.</p>
          <p style="font-size:0.85rem; color:#aaa;">© 2025 FlavourFolder</p>
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
