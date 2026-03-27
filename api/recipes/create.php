<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require_once "../../includes/db.php";

$db = getDB();

$title        = isset($_POST['title'])        ? trim($_POST['title'])        : '';
$email        = isset($_POST['email'])        ? trim($_POST['email'])        : '';
$category     = isset($_POST['category'])     ? trim($_POST['category'])     : '';
$description  = isset($_POST['description'])  ? trim($_POST['description'])  : '';
$ingredients  = isset($_POST['ingredients'])  ? trim($_POST['ingredients'])  : '';
$steps        = isset($_POST['steps'])        ? trim($_POST['steps'])        : '';
$cooking_time = isset($_POST['cooking_time']) ? trim($_POST['cooking_time']) : '';
$servings     = isset($_POST['servings'])     ? trim($_POST['servings'])     : '';
$difficulty   = isset($_POST['difficulty'])   ? trim($_POST['difficulty'])   : '';
$user_id      = isset($_POST['user_id'])      ? (int)$_POST['user_id']       : null;

if ($title === '' || $email === '' || $category === '' || $description === '' || $ingredients === '' || $steps === '') {
    echo json_encode(["success" => false, "message" => "All required fields are required."]);
    exit;
}

$imagePath = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . "/../../img/uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $tmpName      = $_FILES['image']['tmp_name'];
    $originalName = $_FILES['image']['name'];
    $fileSize     = $_FILES['image']['size'];
    $extension    = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExts  = ['jpg','jpeg','png','webp'];

    if (!in_array($extension, $allowedExts)) {
        echo json_encode(["success" => false, "message" => "Only JPG, JPEG, PNG, and WEBP images are allowed."]);
        exit;
    }
    if ($fileSize > 5 * 1024 * 1024) {
        echo json_encode(["success" => false, "message" => "Image size must be less than 5MB."]);
        exit;
    }
    $newFileName = "recipe_" . time() . "_" . uniqid() . "." . $extension;
    $destination = $uploadDir . $newFileName;
    if (!move_uploaded_file($tmpName, $destination)) {
        echo json_encode(["success" => false, "message" => "Failed to upload image."]);
        exit;
    }
    $imagePath = "img/uploads/" . $newFileName;
}

try {
    $query = "INSERT INTO recipes
        (title, email, category, description, ingredients, steps, image, cooking_time, servings, difficulty, user_id)
        VALUES (:title, :email, :category, :description, :ingredients, :steps, :image, :cooking_time, :servings, :difficulty, :user_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":title",        $title);
    $stmt->bindParam(":email",        $email);
    $stmt->bindParam(":category",     $category);
    $stmt->bindParam(":description",  $description);
    $stmt->bindParam(":ingredients",  $ingredients);
    $stmt->bindParam(":steps",        $steps);
    $stmt->bindParam(":image",        $imagePath);
    $stmt->bindParam(":cooking_time", $cooking_time);
    $stmt->bindParam(":servings",     $servings);
    $stmt->bindParam(":difficulty",   $difficulty);
    $stmt->bindParam(":user_id",      $user_id);
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "Recipe created successfully.", "id" => $db->lastInsertId()]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error creating recipe: " . $e->getMessage()]);
}
?>
