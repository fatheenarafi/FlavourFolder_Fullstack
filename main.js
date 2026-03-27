let selectedCategory = "";

const fallbackRecipes = [
  {
    id: "author-1",
    title: "Spaghetti Carbonara",
    category: "Italian",
    description: "A classic Italian pasta dish made with eggs, cheese, pancetta, and pepper. Rich, creamy, and utterly irresistible.",
    image: "img/spaghetti_carbonara.png",
    source: "author",
    cooking_time: "30 min",
    servings: "Serves 4",
    difficulty: "Medium",
    ingredients: [
      { emoji: "🧄", name: "Garlic", qty: "2 cloves" },
      { emoji: "🥓", name: "Pancetta", qty: "150g" },
      { emoji: "🍝", name: "Spaghetti", qty: "400g" },
      { emoji: "🥚", name: "Eggs", qty: "4" },
      { emoji: "🧀", name: "Parmesan Cheese", qty: "100g" }
    ],
    steps: [
      { title: "Boil the Pasta", text: "Boil pasta until al dente." },
      { title: "Cook Pancetta", text: "Cook pancetta until crispy." },
      { title: "Mix", text: "Combine pasta, pancetta, eggs, and cheese." },
      { title: "Serve", text: "Serve with pepper and parmesan." }
    ]
  },
  {
    id: "author-2",
    title: "Greek Salad",
    category: "Salad",
    description: "Fresh and vibrant Greek salad with tomatoes, cucumbers, feta cheese, olives, and olive oil.",
    image: "img/greek_salad.png",
    source: "author",
    cooking_time: "15 min",
    servings: "Serves 2",
    difficulty: "Easy",
    ingredients: [
      { emoji: "🍅", name: "Tomatoes", qty: "3 medium" },
      { emoji: "🥒", name: "Cucumber", qty: "1 large" },
      { emoji: "🧀", name: "Feta Cheese", qty: "120g" }
    ],
    steps: [
      { title: "Chop", text: "Chop the vegetables." },
      { title: "Combine", text: "Combine all ingredients in a bowl." },
      { title: "Serve", text: "Top with feta and serve." }
    ]
  },
  {
    id: "author-3",
    title: "Chocolate Lava Cake",
    category: "Dessert",
    description: "A decadent chocolate dessert with a molten center.",
    image: "img/chocolate_lava_cake.png",
    source: "author",
    cooking_time: "25 min",
    servings: "Serves 2",
    difficulty: "Medium",
    ingredients: [
      { emoji: "🍫", name: "Dark Chocolate", qty: "170g" },
      { emoji: "🧈", name: "Butter", qty: "110g" }
    ],
    steps: [
      { title: "Melt", text: "Melt chocolate and butter." },
      { title: "Bake", text: "Bake until edges are set." }
    ]
  },
  {
    id: "author-4",
    title: "Tom Yum Soup",
    category: "Asian",
    description: "A hot and sour Thai soup with aromatic spices.",
    image: "img/tom_yum_soup.png",
    source: "author",
    cooking_time: "35 min",
    servings: "Serves 4",
    difficulty: "Medium",
    ingredients: [
      { emoji: "🦐", name: "Shrimp", qty: "250g" },
      { emoji: "🍄", name: "Mushrooms", qty: "150g" }
    ],
    steps: [
      { title: "Boil", text: "Boil aromatic ingredients." },
      { title: "Add", text: "Add mushrooms and shrimp." }
    ]
  },
  {
    id: "author-5",
    title: "Margherita Pizza",
    category: "Italian",
    description: "Classic pizza with mozzarella, tomato, and basil.",
    image: "img/margherita_pizza.png",
    source: "author",
    cooking_time: "40 min",
    servings: "Serves 3",
    difficulty: "Medium",
    ingredients: [
      { emoji: "🍞", name: "Pizza Dough", qty: "1 ball" },
      { emoji: "🧀", name: "Mozzarella", qty: "150g" }
    ],
    steps: [
      { title: "Shape", text: "Stretch the dough." },
      { title: "Bake", text: "Bake until golden." }
    ]
  },
  {
    id: "author-6",
    title: "Tiramisu",
    category: "Dessert",
    description: "Coffee-soaked ladyfingers layered with mascarpone cream.",
    image: "img/tiramisu_dessert.png",
    source: "author",
    cooking_time: "30 min",
    servings: "Serves 6",
    difficulty: "Medium",
    ingredients: [
      { emoji: "☕", name: "Coffee", qty: "1 cup" },
      { emoji: "🧀", name: "Mascarpone", qty: "250g" }
    ],
    steps: [
      { title: "Layer", text: "Layer biscuits and cream." },
      { title: "Chill", text: "Chill before serving." }
    ]
  }
];

function normalizeDbRecipe(recipe) {
  return {
    ...recipe,
    id: String(recipe.id),
    source: "visitor",
    image: recipe.image || "img/default_recipe.png"
  };
}

function getRecipeLink(recipe) {
  return `recipe.php?id=${encodeURIComponent(recipe.id)}&source=${encodeURIComponent(recipe.source || "visitor")}`;
}

function renderRecipeCards(recipeList, recipeGrid, noResults) {
  if (!recipeGrid) return;

  recipeGrid.innerHTML = "";

  if (!recipeList || recipeList.length === 0) {
    if (noResults) noResults.style.display = "block";
    return;
  }

  if (noResults) noResults.style.display = "none";

  recipeList.forEach((recipe, index) => {
    const card = document.createElement("div");
    card.className = `col-sm-6 col-md-4 mb-4 recipe-card-wrapper fade-in fade-in-delay-${(index % 4) + 1}`;

    const sourceBadge = recipe.source === "author"
      ? `<span class="recipe-source-badge recipe-source-author">Author Recipe</span>`
      : `<span class="recipe-source-badge recipe-source-visitor">Visitor Recipe</span>`;

    card.innerHTML = `
      <div class="card recipe-card h-100">
        <div class="card-img-wrapper">
          <span class="badge-category">${recipe.category || "Recipe"}</span>
          <img src="${recipe.image || "img/default_recipe.png"}" class="card-img-top" alt="${recipe.title}" loading="lazy">
        </div>
        <div class="card-body d-flex flex-column">
          ${sourceBadge}
          <h5 class="card-title">${recipe.title}</h5>
          <p class="card-text flex-grow-1">${recipe.description || ""}</p>
          <a href="${getRecipeLink(recipe)}" class="btn btn-primary-custom mt-auto">View Recipe →</a>
        </div>
      </div>
    `;

    recipeGrid.appendChild(card);
  });
}

function getCategoryEmoji(category) {
  const map = {
    Italian: "🍝",
    Salad: "🥗",
    Dessert: "🍰",
    Soup: "🍜",
    "Main Course": "🍛",
    Appetizer: "🥟",
    Asian: "🍱"
  };
  return map[category] || "🍽️";
}

function renderCategories(recipes) {
  const categoryGrid = document.getElementById("category-grid");
  if (!categoryGrid) return;

  const uniqueCategories = [...new Set(
    recipes.map(r => r.category).filter(Boolean)
  )];

  categoryGrid.innerHTML = uniqueCategories.map(category => `
    <div class="col-6 col-md-3 animate-on-scroll">
      <div class="card category-tile" data-category-filter="${category}">
        <span class="emoji-icon">${getCategoryEmoji(category)}</span>
        <h5>${category}</h5>
      </div>
    </div>
  `).join("");

  categoryGrid.querySelectorAll("[data-category-filter]").forEach(tile => {
    tile.addEventListener("click", function () {
      const category = this.getAttribute("data-category-filter");
      const heroSearchInput = document.getElementById("hero-search-input");
      const navSearchInput = document.getElementById("nav-search-input");

      if (heroSearchInput) heroSearchInput.value = category;
      if (navSearchInput) navSearchInput.value = category;

      if (heroSearchInput) {
        heroSearchInput.dispatchEvent(new Event("keyup"));
      }
    });
  });
}

async function fetchVisitorRecipes() {
  try {
    const response = await fetch("api/recipes/list_submitted.php");
    const result = await response.json();

    if (result.success && Array.isArray(result.data)) {
      return result.data.map(normalizeDbRecipe);
    }
  } catch (error) {
    console.warn("Failed to fetch visitor recipes:", error);
  }

  return [];
}

async function fetchRecipeById(id, source = "visitor") {
  if (source === "author" || String(id).startsWith("author-")) {
    return fallbackRecipes.find(recipe => String(recipe.id) === String(id)) || null;
  }

  try {
    const response = await fetch(`api/recipes/get.php?id=${encodeURIComponent(id)}`);
    const result = await response.json();

    if (result.success && result.data) {
      return normalizeDbRecipe(result.data);
    }
  } catch (error) {
    console.warn("API unavailable while fetching recipe.", error);
  }

  return null;
}

async function initLiveSearch() {
  const heroSearchInput = document.getElementById("hero-search-input");
  const navSearchInput = document.getElementById("nav-search-input");
  const recipeGrid = document.getElementById("recipe-grid");
  const noResults = document.getElementById("no-results");

  if (!recipeGrid) return;

  let recipes = [...fallbackRecipes];
  renderRecipeCards(recipes, recipeGrid, noResults);
  renderCategories(recipes);

  function filterRecipes(searchTerm) {
    const term = searchTerm.trim().toLowerCase();

    if (term === "") {
      recipes = [...fallbackRecipes];
      renderRecipeCards(recipes, recipeGrid, noResults);
      return;
    }

    const filteredRecipes = recipes.filter(recipe =>
      (recipe.title || "").toLowerCase().includes(term) ||
      (recipe.category || "").toLowerCase().includes(term) ||
      (recipe.description || "").toLowerCase().includes(term)
    );

    renderRecipeCards(filteredRecipes, recipeGrid, noResults);
  }

  if (heroSearchInput) {
    heroSearchInput.addEventListener("keyup", function () {
      filterRecipes(this.value);
      if (navSearchInput) navSearchInput.value = this.value;
    });
  }

  if (navSearchInput) {
    navSearchInput.addEventListener("keyup", function () {
      filterRecipes(this.value);
      if (heroSearchInput) heroSearchInput.value = this.value;
    });
  }
}

async function initVisitorRecipesPage() {
  const recipeGrid = document.getElementById("visitor-recipe-grid");
  const noResults = document.getElementById("visitor-no-results");
  const searchInput = document.getElementById("visitor-search-input");

  if (!recipeGrid) return;

  let recipes = await fetchVisitorRecipes();
  renderRecipeCards(recipes, recipeGrid, noResults);

  function filterVisitorRecipes(term) {
    const value = term.trim().toLowerCase();

    if (value === "") {
      renderRecipeCards(recipes, recipeGrid, noResults);
      return;
    }

    const filtered = recipes.filter(recipe =>
      (recipe.title || "").toLowerCase().includes(value) ||
      (recipe.category || "").toLowerCase().includes(value) ||
      (recipe.description || "").toLowerCase().includes(value)
    );

    renderRecipeCards(filtered, recipeGrid, noResults);
  }

  if (searchInput) {
    searchInput.addEventListener("keyup", function () {
      filterVisitorRecipes(this.value);
    });
  }
}

async function initRecipeDetails() {
  const recipePageTitle = document.getElementById("recipe-page-title");
  const recipeHeroCategory = document.getElementById("recipe-category-badge");
  const recipeImage = document.getElementById("recipe-main-image");
  const recipeTitle = document.getElementById("recipe-title");
  const recipeDescription = document.getElementById("recipe-description");
  const breadcrumbCurrent = document.getElementById("breadcrumb-current");
  const ingredientsList = document.getElementById("ingredients-list");
  const stepsList = document.getElementById("steps-list");
  const recipeTime = document.getElementById("recipe-time");
  const recipeServings = document.getElementById("recipe-servings");
  const recipeDifficulty = document.getElementById("recipe-difficulty");

  if (!recipeTitle) return;

  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");
  const source = params.get("source") || "visitor";

  if (!id) {
    recipeTitle.textContent = "Recipe not found";
    return;
  }

  const recipe = await fetchRecipeById(id, source);

  if (!recipe) {
    recipeTitle.textContent = "Recipe not found";
    return;
  }

  if (recipePageTitle) recipePageTitle.textContent = `${recipe.title} - FlavourFolder`;
  if (recipeHeroCategory) recipeHeroCategory.innerHTML = `<i class="bi bi-tag me-1"></i>${recipe.category || "Recipe"}`;
  if (recipeImage) {
    recipeImage.src = recipe.image || "img/default_recipe.png";
    recipeImage.alt = recipe.title;
  }
  if (recipeTitle) recipeTitle.textContent = recipe.title;
  if (recipeDescription) recipeDescription.textContent = recipe.description || "";
  if (breadcrumbCurrent) breadcrumbCurrent.textContent = recipe.title;
  if (recipeTime) recipeTime.textContent = recipe.cooking_time || "—";
  if (recipeServings) recipeServings.textContent = recipe.servings || "—";
  if (recipeDifficulty) recipeDifficulty.textContent = recipe.difficulty || "—";

  if (ingredientsList) {
    if (Array.isArray(recipe.ingredients)) {
      ingredientsList.innerHTML = recipe.ingredients.map((ingredient, index) => `
        <li class="ingredient-row">
          <input class="form-check-input ingredient-checkbox" type="checkbox" id="ing-${index + 1}">
          <span class="ingredient-emoji">${ingredient.emoji || "🍴"}</span>
          <label class="ingredient-name" for="ing-${index + 1}">${ingredient.name || ""}</label>
          <span class="ingredient-qty">${ingredient.qty || ""}</span>
        </li>
      `).join("");
    } else {
      const ingredientsArray = String(recipe.ingredients || "")
        .split("\n")
        .map(item => item.trim())
        .filter(Boolean);

      ingredientsList.innerHTML = ingredientsArray.map((ingredient, index) => `
        <li class="ingredient-row">
          <input class="form-check-input ingredient-checkbox" type="checkbox" id="ing-${index + 1}">
          <span class="ingredient-emoji">🍴</span>
          <label class="ingredient-name" for="ing-${index + 1}">${ingredient}</label>
          <span class="ingredient-qty"></span>
        </li>
      `).join("");
    }
  }

  if (stepsList) {
    if (Array.isArray(recipe.steps)) {
      stepsList.innerHTML = recipe.steps.map((step, index) => `
        <div class="card step-card">
          <div class="card-body d-flex align-items-start gap-3 p-3">
            <div class="step-number">${index + 1}</div>
            <div class="step-content">
              <h6>${step.title || `Step ${index + 1}`}</h6>
              <p>${step.text || ""}</p>
            </div>
          </div>
        </div>
      `).join("");
    } else {
      const stepsArray = String(recipe.steps || "")
        .split("\n")
        .map(item => item.trim())
        .filter(Boolean);

      stepsList.innerHTML = stepsArray.map((step, index) => `
        <div class="card step-card">
          <div class="card-body d-flex align-items-start gap-3 p-3">
            <div class="step-number">${index + 1}</div>
            <div class="step-content">
              <h6>Step ${index + 1}</h6>
              <p>${step}</p>
            </div>
          </div>
        </div>
      `).join("");
    }
  }

  initIngredientCheckboxes();
}

function initCategoryToggle() {
  const categoryBtns = document.querySelectorAll(".category-btn");
  const hiddenCategoryInput = document.getElementById("recipe-category-hidden");
  const catError = document.getElementById("category-error");

  if (categoryBtns.length === 0) return;

  categoryBtns.forEach(btn => {
    btn.addEventListener("click", function () {
      categoryBtns.forEach(b => b.classList.remove("active"));
      this.classList.add("active");

      selectedCategory = this.getAttribute("data-category") || "";

      if (hiddenCategoryInput) {
        hiddenCategoryInput.value = selectedCategory;
      }

      if (catError) {
        catError.style.display = "none";
      }
    });
  });
}

function initImagePreview() {
  const imageInput = document.getElementById("recipe-image");
  const previewWrapper = document.getElementById("image-preview-wrapper");
  const previewImage = document.getElementById("image-preview");

  if (!imageInput || !previewWrapper || !previewImage) return;

  imageInput.addEventListener("change", function () {
    const file = this.files && this.files[0];

    if (!file) {
      previewWrapper.style.display = "none";
      previewImage.src = "";
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      previewImage.src = e.target.result;
      previewWrapper.style.display = "block";
    };
    reader.readAsDataURL(file);
  });
}

function showError(input, message) {
  if (!input) return;

  input.classList.add("is-invalid");
  input.classList.remove("is-valid");

  const feedback = input.parentElement.querySelector(".invalid-feedback");
  const validFeedback = input.parentElement.querySelector(".valid-feedback");

  if (feedback) {
    feedback.textContent = message;
    feedback.style.display = "block";
  }
  if (validFeedback) {
    validFeedback.style.display = "none";
  }
}

function showSuccess(input) {
  if (!input) return;

  input.classList.add("is-valid");
  input.classList.remove("is-invalid");

  const invalidFeedback = input.parentElement.querySelector(".invalid-feedback");
  const validFeedback = input.parentElement.querySelector(".valid-feedback");

  if (invalidFeedback) invalidFeedback.style.display = "none";
  if (validFeedback) validFeedback.style.display = "block";
}

function clearValidation(input) {
  if (!input) return;

  input.classList.remove("is-invalid", "is-valid");

  const invalidFb = input.parentElement.querySelector(".invalid-feedback");
  const validFb = input.parentElement.querySelector(".valid-feedback");

  if (invalidFb) invalidFb.style.display = "none";
  if (validFb) validFb.style.display = "none";
}

function initFormValidation() {
  const form = document.getElementById("recipe-submit-form");
  const submitBtn = document.getElementById("submit-recipe-btn");

  if (!form || !submitBtn) return;

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    const titleInput = document.getElementById("recipe-title");
    const emailInput = document.getElementById("recipe-email");
    const descriptionInput = document.getElementById("recipe-description");
    const ingredientsInput = document.getElementById("recipe-ingredients");
    const stepsInput = document.getElementById("recipe-steps");
    const imageInput = document.getElementById("recipe-image");
    const catError = document.getElementById("category-error");

    let isValid = true;

    [titleInput, emailInput, descriptionInput, ingredientsInput, stepsInput].forEach(input => clearValidation(input));
    if (catError) catError.style.display = "none";

    if (titleInput.value.trim() === "") {
      showError(titleInput, "Please enter a recipe title.");
      isValid = false;
    } else {
      showSuccess(titleInput);
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailInput.value.trim() === "") {
      showError(emailInput, "Please enter your email address.");
      isValid = false;
    } else if (!emailRegex.test(emailInput.value.trim())) {
      showError(emailInput, "Please enter a valid email address.");
      isValid = false;
    } else {
      showSuccess(emailInput);
    }

    if (!selectedCategory) {
      if (catError) catError.style.display = "block";
      isValid = false;
    }

    if (descriptionInput.value.trim() === "") {
      showError(descriptionInput, "Please enter a short description.");
      isValid = false;
    } else {
      showSuccess(descriptionInput);
    }

    if (ingredientsInput.value.trim() === "") {
      showError(ingredientsInput, "Please enter the ingredients.");
      isValid = false;
    } else {
      showSuccess(ingredientsInput);
    }

    if (stepsInput.value.trim() === "") {
      showError(stepsInput, "Please enter the cooking steps.");
      isValid = false;
    } else {
      showSuccess(stepsInput);
    }

    if (imageInput && imageInput.files.length > 0) {
      const file = imageInput.files[0];
      const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

      if (!allowedTypes.includes(file.type)) {
        alert("Please upload JPG, PNG, or WEBP image only.");
        isValid = false;
      }
    }

    if (!isValid) return;

    try {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';

      const formData = new FormData(form);
      formData.set("category", selectedCategory);

      const response = await fetch("api/recipes/create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        const successModalEl = document.getElementById("successModal");
        if (successModalEl) {
          const modal = new bootstrap.Modal(successModalEl);
          modal.show();
        }
      } else {
        alert(result.message || "Failed to submit recipe.");
      }
    } catch (error) {
      console.error("Submission error:", error);
      alert("Something went wrong while submitting the recipe.");
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Recipe';
    }
  });

  const submitAnotherBtn = document.getElementById("submit-another-btn");
  if (submitAnotherBtn) {
    submitAnotherBtn.addEventListener("click", function () {
      form.reset();
      selectedCategory = "";

      document.querySelectorAll(".category-btn").forEach(btn => btn.classList.remove("active"));
      form.querySelectorAll(".form-control, .form-select").forEach(clearValidation);

      const previewWrapper = document.getElementById("image-preview-wrapper");
      const previewImage = document.getElementById("image-preview");
      const catError = document.getElementById("category-error");
      const hiddenCategoryInput = document.getElementById("recipe-category-hidden");

      if (previewWrapper) previewWrapper.style.display = "none";
      if (previewImage) previewImage.src = "";
      if (catError) catError.style.display = "none";
      if (hiddenCategoryInput) hiddenCategoryInput.value = "";

      const modalEl = document.getElementById("successModal");
      const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
      if (modal) modal.hide();
    });
  }
}

function initTabToggle() {
  const ingredientsTab = document.getElementById("ingredients-tab");
  const stepsTab = document.getElementById("steps-tab");
  const ingredientsSection = document.getElementById("ingredients-section");
  const stepsSection = document.getElementById("steps-section");

  if (!ingredientsTab || !stepsTab || !ingredientsSection || !stepsSection) return;

  ingredientsTab.addEventListener("click", function (e) {
    e.preventDefault();
    ingredientsTab.classList.add("active");
    stepsTab.classList.remove("active");
    ingredientsSection.style.display = "block";
    stepsSection.style.display = "none";
  });

  stepsTab.addEventListener("click", function (e) {
    e.preventDefault();
    stepsTab.classList.add("active");
    ingredientsTab.classList.remove("active");
    stepsSection.style.display = "block";
    ingredientsSection.style.display = "none";
  });
}

function initSaveNotes() {
  const saveBtn = document.getElementById("save-notes-btn");
  const notesTextarea = document.getElementById("chef-notes-textarea");
  const notesDisplay = document.getElementById("saved-notes-display");

  if (!saveBtn || !notesTextarea || !notesDisplay) return;

  saveBtn.addEventListener("click", function () {
    const noteText = notesTextarea.value.trim();

    if (noteText === "") {
      notesDisplay.innerHTML = `
        <div class="alert alert-warning d-flex align-items-center fade-in" role="alert">
          <span>Please write a note first before saving.</span>
        </div>
      `;
      return;
    }

    notesDisplay.innerHTML = `
      <div class="saved-note fade-in">
        <div class="alert alert-success mb-2" role="alert">
          <strong>Note saved!</strong>
        </div>
        <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 8px; font-style: italic; color: #555;">
          "${noteText}"
        </div>
      </div>
    `;
  });
}

function initScrollAnimations() {
  const animatedElements = document.querySelectorAll(".animate-on-scroll");
  if (animatedElements.length === 0) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: "0px 0px -40px 0px"
  });

  animatedElements.forEach(el => {
    el.style.opacity = "0";
    el.style.transform = "translateY(30px)";
    el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    observer.observe(el);
  });
}

function initNavbarScroll() {
  const navbar = document.querySelector(".navbar");
  if (!navbar) return;

  window.addEventListener("scroll", function () {
    if (window.scrollY > 50) {
      navbar.classList.add("shadow-sm");
      navbar.style.padding = "6px 0";
    } else {
      navbar.classList.remove("shadow-sm");
      navbar.style.padding = "12px 0";
    }
  });
}

function initIngredientCheckboxes() {
  const checkboxes = document.querySelectorAll(".ingredient-checkbox");

  checkboxes.forEach(cb => {
    cb.addEventListener("change", function () {
      const row = this.closest(".ingredient-row");
      if (!row) return;

      const name = row.querySelector(".ingredient-name");
      if (!name) return;

      if (this.checked) {
        name.style.textDecoration = "line-through";
        name.style.color = "#999";
        row.style.opacity = "0.6";
      } else {
        name.style.textDecoration = "none";
        name.style.color = "";
        row.style.opacity = "1";
      }
    });
  });
}

document.addEventListener("DOMContentLoaded", function () {
  initLiveSearch();
  initVisitorRecipesPage();
  initRecipeDetails();
  initTabToggle();
  initSaveNotes();
  initCategoryToggle();
  initImagePreview();
  initFormValidation();
  initScrollAnimations();
  initNavbarScroll();
  initIngredientCheckboxes();
});

function getSourceBadgeHtml(source) {
  if (source === "author") {
    return `<span class="recipe-source-badge recipe-source-author">Author Recipe</span>`;
  }
  return `<span class="recipe-source-badge recipe-source-visitor">Visitor Recipe</span>`;
}

function prettifyDifficulty(value) {
  if (!value) return "—";
  return value;
}

function prettifyServings(value) {
  if (!value) return "—";
  return value;
}

function prettifyCookingTime(value) {
  if (!value) return "—";
  return value;
}

function buildIngredientRowsFromText(rawIngredients, recipeId) {
  const items = String(rawIngredients || "")
    .split("\n")
    .map(item => item.trim())
    .filter(Boolean);

  return items.map((ingredient, index) => `
    <li class="ingredient-row">
      <input class="form-check-input ingredient-checkbox" type="checkbox" id="ing-${recipeId}-${index + 1}">
      <span class="ingredient-emoji">🍴</span>
      <label class="ingredient-name" for="ing-${recipeId}-${index + 1}">${ingredient}</label>
      <span class="ingredient-qty"></span>
    </li>
  `).join("");
}

function buildStepCardsFromText(rawSteps) {
  const items = String(rawSteps || "")
    .split("\n")
    .map(item => item.trim())
    .filter(Boolean);

  return items.map((step, index) => `
    <div class="card step-card">
      <div class="card-body d-flex align-items-start gap-3 p-3">
        <div class="step-number">${index + 1}</div>
        <div class="step-content">
          <h6>Step ${index + 1}</h6>
          <p>${step}</p>
        </div>
      </div>
    </div>
  `).join("");
}

async function fetchRecipeById(id, source = "visitor") {
  if (source === "author" || String(id).startsWith("author-")) {
    return fallbackRecipes.find(recipe => String(recipe.id) === String(id)) || null;
  }

  try {
    const response = await fetch(`api/recipes/get.php?id=${encodeURIComponent(id)}`);
    const result = await response.json();

    if (result.success && result.data) {
      return {
        ...result.data,
        id: String(result.data.id),
        source: "visitor",
        image: result.data.image || "img/default_recipe.png"
      };
    }
  } catch (error) {
    console.warn("API unavailable while fetching recipe.", error);
  }

  return null;
}

async function initRecipeDetails() {
  const recipePageTitle = document.getElementById("recipe-page-title");
  const recipeHeroCategory = document.getElementById("recipe-category-badge");
  const recipeImage = document.getElementById("recipe-main-image");
  const recipeTitle = document.getElementById("recipe-title");
  const recipeDescription = document.getElementById("recipe-description");
  const breadcrumbCurrent = document.getElementById("breadcrumb-current");
  const ingredientsList = document.getElementById("ingredients-list");
  const stepsList = document.getElementById("steps-list");
  const recipeTime = document.getElementById("recipe-time");
  const recipeServings = document.getElementById("recipe-servings");
  const recipeDifficulty = document.getElementById("recipe-difficulty");
  const recipeSourceBadgeWrap = document.getElementById("recipe-source-badge-wrap");

  if (!recipeTitle) return;

  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");
  const source = params.get("source") || "visitor";

  if (!id) {
    recipeTitle.textContent = "Recipe not found";
    return;
  }

  const recipe = await fetchRecipeById(id, source);

  if (!recipe) {
    recipeTitle.textContent = "Recipe not found";
    return;
  }

  if (recipePageTitle) recipePageTitle.textContent = `${recipe.title} - FlavourFolder`;
  if (recipeHeroCategory) recipeHeroCategory.innerHTML = `<i class="bi bi-tag me-1"></i>${recipe.category || "Recipe"}`;
  if (recipeImage) {
    recipeImage.src = recipe.image || "img/default_recipe.png";
    recipeImage.alt = recipe.title || "Recipe";
  }

  if (recipeTitle) recipeTitle.textContent = recipe.title || "Recipe";
  if (recipeDescription) recipeDescription.textContent = recipe.description || "";
  if (breadcrumbCurrent) breadcrumbCurrent.textContent = recipe.title || "Recipe";

  if (recipeTime) recipeTime.textContent = prettifyCookingTime(recipe.cooking_time);
  if (recipeServings) recipeServings.textContent = prettifyServings(recipe.servings);
  if (recipeDifficulty) recipeDifficulty.textContent = prettifyDifficulty(recipe.difficulty);

  if (recipeSourceBadgeWrap) {
    recipeSourceBadgeWrap.innerHTML = getSourceBadgeHtml(recipe.source || source);
  }

  if (ingredientsList) {
    if (Array.isArray(recipe.ingredients)) {
      ingredientsList.innerHTML = recipe.ingredients.map((ingredient, index) => `
        <li class="ingredient-row">
          <input class="form-check-input ingredient-checkbox" type="checkbox" id="ing-${recipe.id}-${index + 1}">
          <span class="ingredient-emoji">${ingredient.emoji || "🍴"}</span>
          <label class="ingredient-name" for="ing-${recipe.id}-${index + 1}">${ingredient.name || ""}</label>
          <span class="ingredient-qty">${ingredient.qty || ""}</span>
        </li>
      `).join("");
    } else {
      ingredientsList.innerHTML = buildIngredientRowsFromText(recipe.ingredients, recipe.id);
    }
  }

  if (stepsList) {
    if (Array.isArray(recipe.steps)) {
      stepsList.innerHTML = recipe.steps.map((step, index) => `
        <div class="card step-card">
          <div class="card-body d-flex align-items-start gap-3 p-3">
            <div class="step-number">${index + 1}</div>
            <div class="step-content">
              <h6>${step.title || `Step ${index + 1}`}</h6>
              <p>${step.text || ""}</p>
            </div>
          </div>
        </div>
      `).join("");
    } else {
      stepsList.innerHTML = buildStepCardsFromText(recipe.steps);
    }
  }

  initIngredientCheckboxes();
}

