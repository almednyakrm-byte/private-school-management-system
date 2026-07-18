<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if input data is valid
if (empty($input)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid input'));
    exit;
}

// Check if user is admin for edit/deletion operations
if (isset($input['action']) && in_array($input['action'], array('edit', 'delete'))) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Handle CRUD operations
switch ($input['action']) {
    case 'get':
        // Get all students
        $stmt = $pdo->prepare('SELECT * FROM students');
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($students);
        break;
    case 'create':
        // Validate input data
        if (!isset($input['name']) || !isset($input['email']) || !isset($input['grade'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }

        // Sanitize input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $grade = filter_var($input['grade'], FILTER_SANITIZE_NUMBER_INT);

        // Insert new student
        $stmt = $pdo->prepare('INSERT INTO students (name, email, grade) VALUES (:name, :email, :grade)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':grade', $grade);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('message' => 'Student created successfully'));
        break;
    case 'edit':
        // Validate input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['grade'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }

        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $grade = filter_var($input['grade'], FILTER_SANITIZE_NUMBER_INT);

        // Update existing student
        $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email, grade = :grade WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':grade', $grade);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Student updated successfully'));
        break;
    case 'delete':
        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }

        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

        // Delete student
        $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Student deleted successfully'));
        break;
    default:
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid action'));
        break;
}