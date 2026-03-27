<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once "../config/db.php";

$database = new Database();
$conn = $database->connect();

try {
    $query = "SELECT id, title, email, category, description, ingredients, steps, image, created_at
              FROM recipes
              ORDER BY created_at DESC, id DESC";

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
        "message" => "Error fetching recipes: " . $e->getMessage()
    ]);
}
?>