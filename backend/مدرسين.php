<?php

require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'You must be logged in to access this resource.']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'You do not have permission to access this resource.']);
        exit;
    }

    // Get all teachers
    try {
        $stmt = $pdo->prepare('SELECT * FROM teachers');
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        echo json_encode($teachers);
    } catch (PDOException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'An error occurred while retrieving teachers.']);
    }
}

// Handle POST request
elseif ($method === 'POST') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Invalid input data.']);
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Insert new teacher
    try {
        $stmt = $pdo->prepare('INSERT INTO teachers (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        header('HTTP/1.1 201 Created');
        echo json_encode(['message' => 'Teacher created successfully.']);
    } catch (PDOException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'An error occurred while creating a new teacher.']);
    }
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'You do not have permission to access this resource.']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Invalid input data.']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Update existing teacher
    try {
        $stmt = $pdo->prepare('UPDATE teachers SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        header('HTTP/1.1 200 OK');
        echo json_encode(['message' => 'Teacher updated successfully.']);
    } catch (PDOException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'An error occurred while updating a teacher.']);
    }
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'You do not have permission to access this resource.']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['id'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Invalid input data.']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete existing teacher
    try {
        $stmt = $pdo->prepare('DELETE FROM teachers WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header('HTTP/1.1 200 OK');
        echo json_encode(['message' => 'Teacher deleted successfully.']);
    } catch (PDOException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'An error occurred while deleting a teacher.']);
    }
}

// Handle unknown request method
else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Invalid request method.']);
}