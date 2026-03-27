<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once "../config/db.php";

$term = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($term === '') {
    echo json_encode([
        "success" => false,
        "message" => "Search term is required."
    ]);
    exit;
}

$database = new Database();
$conn = $database->connect();

try {
    $query = "SELECT id, title, category, description, image, ingredients, steps, created_at
              FROM recipes
              WHERE title LIKE :term
                 OR category LIKE :term
                 OR description LIKE :term
                 OR ingredients LIKE :term
                 OR steps LIKE :term
              ORDER BY created_at DESC, id DESC";

    $stmt = $conn->prepare($query);
    $search = "%" . $term . "%";
    $stmt->bindParam(":term", $search);
    $stmt->execute();

    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $recipes
    ]);
} catch(PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error searching recipes: " . $e->getMessage()
    ]);
}
?>