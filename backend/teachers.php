<?php

require_once 'db.php';

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate the input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $id = intval($input['id']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('SELECT * FROM teachers WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch();

    // Check if the result exists
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate the input
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $name = trim($input['name']);
    $email = trim($input['email']);
    $phone = trim($input['phone']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('INSERT INTO teachers (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Get the last inserted ID
    $id = $pdo->lastInsertId();

    // Return the result
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate the input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $id = intval($input['id']);
    $name = trim($input['name']);
    $email = trim($input['email']);
    $phone = trim($input['phone']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('UPDATE teachers SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Check if the result exists
    $stmt = $pdo->prepare('SELECT * FROM teachers WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch();

    // Return the result
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate the input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $id = intval($input['id']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('DELETE FROM teachers WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if the result exists
    $stmt = $pdo->prepare('SELECT * FROM teachers WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch();

    // Return the result
    if (!$result) {
        http_response_code(204);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
}