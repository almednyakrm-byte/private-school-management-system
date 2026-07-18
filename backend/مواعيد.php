<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['role'];
$userID = $_SESSION['id'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if user is logged in
if ($userRole !== 'admin' && $userRole !== 'user') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle GET request
if ($method === 'GET') {
    // Get all appointments
    $stmt = $pdo->prepare('SELECT * FROM مواعيد');
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($appointments);
}

// Handle POST request
 elseif ($method === 'POST') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['title']) || !isset($input['date']) || !isset($input['time'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $title = htmlspecialchars($input['title']);
    $date = htmlspecialchars($input['date']);
    $time = htmlspecialchars($input['time']);

    // Insert new appointment
    $stmt = $pdo->prepare('INSERT INTO مواعيد (title, date, time) VALUES (:title, :date, :time)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Appointment created successfully']);
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['id']) || !isset($input['title']) || !isset($input['date']) || !isset($input['time'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);
    $title = htmlspecialchars($input['title']);
    $date = htmlspecialchars($input['date']);
    $time = htmlspecialchars($input['time']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Update appointment
    $stmt = $pdo->prepare('UPDATE مواعيد SET title = :title, date = :date, time = :time WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Appointment updated successfully']);
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Delete appointment
    $stmt = $pdo->prepare('DELETE FROM مواعيد WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Appointment deleted successfully']);
}