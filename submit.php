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
  <title>Submit a Recipe — FlavourFolder</title>
  <meta name="description" content="Submit your recipe to FlavourFolder.">
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
          <li class="nav-item"><a class="nav-link" href="visitor-recipes.php">Community</a></li>
          <li class="nav-item"><a class="nav-link active" href="submit.php">Submit</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
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

  <section class="submit-header">
    <div class="container">
      <h1 class="fade-in">Submit Your Recipe</h1>
      <p class="fade-in fade-in-delay-1">Share your delicious recipe with our community.</p>
      <a href="index.php" class="btn btn-dark-custom fade-in fade-in-delay-2">
        <i class="bi bi-arrow-left me-1"></i>Home
      </a>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="submit-form-section animate-on-scroll">
            <h3>
              <i class="bi bi-pencil-square me-2" style="color: var(--color-primary);"></i>
              Recipe Details
            </h3>

            <form id="recipe-submit-form" novalidate enctype="multipart/form-data">
              <div class="mb-4">
                <label for="recipe-title" class="form-label">Recipe Title</label>
                <input type="text" class="form-control" id="recipe-title" name="title" placeholder="Enter your recipe title here">
                <small class="form-text">Give your recipe a catchy and descriptive name.</small>
                <div class="invalid-feedback">Please enter a recipe title.</div>
                <div class="valid-feedback">Looks great!</div>
              </div>

              <div class="mb-4">
                <label for="recipe-email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="recipe-email" name="email"
                  placeholder="youremail@example.com"
                  value="<?= isLoggedIn() ? htmlspecialchars($user['email']) : '' ?>">
                <small class="form-text">We'll use this to contact you about your submission.</small>
                <div class="invalid-feedback">Please enter a valid email address.</div>
                <div class="valid-feedback">Valid email!</div>
              </div>

              <div class="mb-4">
                <label class="form-label">Category Selection</label>
                <div class="category-btn-group d-flex flex-wrap">
                  <button type="button" class="btn category-btn" data-category="Appetizer"><i class="bi bi-egg-fried me-1"></i>Appetizer</button>
                  <button type="button" class="btn category-btn" data-category="Main Course"><i class="bi bi-cup-hot me-1"></i>Main Course</button>
                  <button type="button" class="btn category-btn" data-category="Dessert"><i class="bi bi-cake2 me-1"></i>Dessert</button>
                  <button type="button" class="btn category-btn" data-category="Soup"><i class="bi bi-droplet me-1"></i>Soup</button>
                  <button type="button" class="btn category-btn" data-category="Salad"><i class="bi bi-flower1 me-1"></i>Salad</button>
                  <button type="button" class="btn category-btn" data-category="Italian"><i class="bi bi-circle-fill me-1"></i>Italian</button>
                  <button type="button" class="btn category-btn" data-category="Asian"><i class="bi bi-circle-fill me-1"></i>Asian</button>
                </div>
                <input type="hidden" id="recipe-category-hidden" name="category">
                <small class="form-text">Select one category.</small>
                <div class="category-error" id="category-error">Please select a category.</div>
              </div>

              <div class="mb-4">
                <label for="recipe-description" class="form-label">Short Description</label>
                <textarea class="form-control" id="recipe-description" name="description" rows="3" placeholder="Write a short recipe description"></textarea>
                <small class="form-text">Give a short summary of your recipe.</small>
                <div class="invalid-feedback">Please enter a short description.</div>
                <div class="valid-feedback">Nice description!</div>
              </div>

              <div class="mb-4">
                <label for="recipe-ingredients" class="form-label">Ingredients</label>
                <textarea class="form-control" id="recipe-ingredients" name="ingredients" rows="5" placeholder="One ingredient per line"></textarea>
                <small class="form-text">List each ingredient on a new line.</small>
                <div class="invalid-feedback">Please enter the ingredients.</div>
                <div class="valid-feedback">Looks good!</div>
              </div>

              <div class="mb-4">
                <label for="recipe-steps" class="form-label">Steps</label>
                <textarea class="form-control" id="recipe-steps" name="steps" rows="6" placeholder="One step per line"></textarea>
                <small class="form-text">Write each step on a new line.</small>
                <div class="invalid-feedback">Please enter the cooking steps.</div>
                <div class="valid-feedback">Great detail!</div>
              </div>

              <div class="row">
                <div class="col-md-4 mb-4">
                  <label for="recipe-time" class="form-label">Cooking Time</label>
                  <input type="text" class="form-control" id="recipe-time" name="cooking_time" placeholder="30 min">
                </div>
                <div class="col-md-4 mb-4">
                  <label for="recipe-servings" class="form-label">Servings</label>
                  <input type="text" class="form-control" id="recipe-servings" name="servings" placeholder="Serves 4">
                </div>
                <div class="col-md-4 mb-4">
                  <label for="recipe-difficulty" class="form-label">Difficulty</label>
                  <select class="form-select" id="recipe-difficulty" name="difficulty">
                    <option value="">Select difficulty</option>
                    <option value="Easy">Easy</option>
                    <option value="Medium">Medium</option>
                    <option value="Hard">Hard</option>
                  </select>
                </div>
              </div>

              <div class="mb-4">
                <label for="recipe-image" class="form-label">Recipe Image</label>
                <input type="file" class="form-control" id="recipe-image" name="image" accept=".jpg,.jpeg,.png,.webp">
                <small class="form-text">Upload JPG, PNG, or WEBP image.</small>
                <div id="image-preview-wrapper" class="mt-3" style="display:none;">
                  <img id="image-preview" src="" alt="Recipe Preview" class="img-fluid rounded shadow-sm" style="max-height:220px; object-fit:cover;">
                </div>
              </div>

              <?php if (isLoggedIn()): ?>
                <!-- Pass user_id to JS for submission -->
                <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
              <?php endif; ?>

              <button type="submit" id="submit-recipe-btn" class="btn btn-dark-custom btn-lg w-100">
                <i class="bi bi-send me-2"></i>Submit Recipe
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Recipe Submitted Successfully!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <span class="success-icon">🎉</span>
          <h4 style="font-family: var(--font-heading); margin-bottom: 12px;">Thank You!</h4>
          <p style="color: var(--color-text-light); font-size: 1.05rem;">Your recipe has been submitted successfully.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-custom" id="submit-another-btn">
            <i class="bi bi-plus-circle me-1"></i>Submit Another
          </button>
          <a href="visitor-recipes.php" class="btn btn-primary-custom">
            <i class="bi bi-grid me-1"></i>View Community Recipes
          </a>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-dark text-light py-4">
    <div class="container text-center">
      <p class="mb-0">© 2025 FlavourFolder</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="main.js"></script>
</body>
</html>
