<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['id'])) {
    // Get specific teacher by ID
    $stmt = $pdo->prepare('SELECT * FROM المعلمين WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $teacher = $stmt->fetch();
    if ($teacher) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teacher);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
    }
} elseif (isset($_GET['all'])) {
    // Get all teachers
    $stmt = $pdo->prepare('SELECT * FROM المعلمين');
    $stmt->execute();
    $teachers = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($teachers);
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}

// Handle POST request
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])) {
    // Validate input data
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid email'));
        exit;
    }
    
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    
    // Insert new teacher
    $stmt = $pdo->prepare('INSERT INTO المعلمين (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(array('message' => 'Teacher created successfully'));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}

// Handle PUT request
if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])) {
    // Validate input data
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid email'));
        exit;
    }
    
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    
    // Update existing teacher
    $stmt = $pdo->prepare('UPDATE المعلمين SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Teacher updated successfully'));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}

// Handle DELETE request
if (isset($_GET['id'])) {
    // Delete teacher by ID
    $stmt = $pdo->prepare('DELETE FROM المعلمين WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Teacher deleted successfully'));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}

?>