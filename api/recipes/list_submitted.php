<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once "../../includes/db.php";
$conn = getDB();

try {
    $query = "SELECT id, title, email, category, description, ingredients, steps, image, cooking_time, servings, difficulty, created_at
              FROM recipes
              ORDER BY id DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $recipes
    ]);
} catch(PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching submitted recipes: " . $e->getMessage()
    ]);
}
?>