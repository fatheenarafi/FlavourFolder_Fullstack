<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Recipe ID is required."
    ]);
    exit;
}

$id = (int) $_GET['id'];

require_once "../../includes/db.php";
$conn = getDB();

try {
    $query = "SELECT * FROM recipes WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recipe) {
        echo json_encode([
            "success" => true,
            "data" => $recipe
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Recipe not found."
        ]);
    }
} catch(PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching recipe: " . $e->getMessage()
    ]);
}
?>