<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate and sanitize input data
if (!isset($inputData['name']) || !isset($inputData['age']) || !isset($inputData['class'])) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

$name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
$age = filter_var($inputData['age'], FILTER_SANITIZE_NUMBER_INT);
$class = filter_var($inputData['class'], FILTER_SANITIZE_STRING);

// Handle GET request to retrieve all students
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepare SQL query to retrieve all students
        $stmt = $pdo->prepare('SELECT * FROM التلاميذ');
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return HTTP response with students data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($students);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Prepare SQL query to insert new student
        $stmt = $pdo->prepare('INSERT INTO التلاميذ (name, age, class) VALUES (:name, :age, :class)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':class', $class);
        $stmt->execute();
        
        // Return HTTP response with inserted student data
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Student inserted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    try {
        // Prepare SQL query to update existing student
        $stmt = $pdo->prepare('UPDATE التلاميذ SET name = :name, age = :age, class = :class WHERE id = :id');
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':class', $class);
        $stmt->execute();
        
        // Return HTTP response with updated student data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Student updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    try {
        // Prepare SQL query to delete existing student
        $stmt = $pdo->prepare('DELETE FROM التلاميذ WHERE id = :id');
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->execute();
        
        // Return HTTP response with deleted student data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Student deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error']);
    }
}