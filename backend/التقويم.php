<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
if ($input === null) {
    $input = $_POST;
}

// Validate input data
if (!isset($input['id']) && !isset($input['title']) && !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET request
if (isset($_GET['id'])) {
    $stmt = $db->prepare('SELECT * FROM التقويم WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
} elseif (isset($_GET['all'])) {
    $stmt = $db->prepare('SELECT * FROM التقويم');
    $stmt->execute();
    $rows = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}

// Handle POST request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
    $stmt = $db->prepare('INSERT INTO التقويم (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $input['title']);
    $stmt->bindParam(':description', $input['description']);
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal server error']);
    }
}

// Handle PUT request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (!isset($input['id']) || !isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $db->prepare('UPDATE التقويم SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':title', $input['title']);
    $stmt->bindParam(':description', $input['description']);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal server error']);
    }
}

// Handle DELETE request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $db->prepare('DELETE FROM التقويم WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    if ($stmt->execute()) {
        http_response_code(204);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal server error']);
    }
}

$db = null;

?>