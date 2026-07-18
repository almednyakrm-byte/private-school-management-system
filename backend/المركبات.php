<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all vehicles
    $stmt = $pdo->prepare('SELECT * FROM المركبات');
    $stmt->execute();
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($vehicles);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['model']) || !isset($input['year'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $model = filter_var($input['model'], FILTER_SANITIZE_STRING);
    $year = filter_var($input['year'], FILTER_SANITIZE_NUMBER_INT);

    // Insert new vehicle
    $stmt = $pdo->prepare('INSERT INTO المركبات (name, model, year) VALUES (:name, :model, :year)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':model', $model);
    $stmt->bindParam(':year', $year);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Vehicle created successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['model']) || !isset($input['year'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $model = filter_var($input['model'], FILTER_SANITIZE_STRING);
    $year = filter_var($input['year'], FILTER_SANITIZE_NUMBER_INT);

    // Update vehicle
    $stmt = $pdo->prepare('UPDATE المركبات SET name = :name, model = :model, year = :year WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':model', $model);
    $stmt->bindParam(':year', $year);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Vehicle updated successfully'));
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete vehicle
    $stmt = $pdo->prepare('DELETE FROM المركبات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Vehicle deleted successfully'));
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}