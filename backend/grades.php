<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    '/grades' => ['GET' => 'getGrades', 'POST' => 'createGrade'],
    '/grades/:id' => ['GET' => 'getGrade', 'PUT' => 'updateGrade', 'DELETE' => 'deleteGrade']
];

// Get route
$match = null;
foreach ($routes as $route => $methods) {
    if (strpos($route, '/') === 0 && strpos($route, ':id') === false) {
        if (strpos($_SERVER['REQUEST_URI'], $route) === 0) {
            $match = $route;
            break;
        }
    } elseif (strpos($route, '/') === 0 && strpos($route, ':id') !== false) {
        $id = explode('/', $route)[2];
        if (strpos($_SERVER['REQUEST_URI'], $route) === 0 && (int)$id === (int)explode('/', $_SERVER['REQUEST_URI'])[3]) {
            $match = $route;
            break;
        }
    }
}

// Get method
$method = $_SERVER['REQUEST_METHOD'];

// Check if route and method match
if (!isset($match) || !isset($methods[$method])) {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Get route and method
list($route, $func) = explode(' ', $methods[$method]);

// Call function
$result = call_user_func([$this, $func], $input);

// Output result
if (is_array($result)) {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    http_response_code($result);
    echo json_encode(['error' => $result]);
}

// Functions

function getGrades($input) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM grades');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createGrade($input) {
    global $pdo;
    // Validate input
    if (!isset($input['student_id']) || !isset($input['subject_id']) || !isset($input['grade'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    // Sanitize input
    $input['student_id'] = (int)$input['student_id'];
    $input['subject_id'] = (int)$input['subject_id'];
    $input['grade'] = (float)$input['grade'];
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    // Insert data
    $stmt = $pdo->prepare('INSERT INTO grades (student_id, subject_id, grade) VALUES (:student_id, :subject_id, :grade)');
    $stmt->execute($input);
    return $stmt->rowCount();
}

function getGrade($input) {
    global $pdo;
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    // Sanitize input
    $input['id'] = (int)$input['id'];
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    // Select data
    $stmt = $pdo->prepare('SELECT * FROM grades WHERE id = :id');
    $stmt->execute($input);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateGrade($input) {
    global $pdo;
    // Validate input
    if (!isset($input['id']) || !isset($input['student_id']) || !isset($input['subject_id']) || !isset($input['grade'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    // Sanitize input
    $input['id'] = (int)$input['id'];
    $input['student_id'] = (int)$input['student_id'];
    $input['subject_id'] = (int)$input['subject_id'];
    $input['grade'] = (float)$input['grade'];
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    // Update data
    $stmt = $pdo->prepare('UPDATE grades SET student_id = :student_id, subject_id = :subject_id, grade = :grade WHERE id = :id');
    $stmt->execute($input);
    return $stmt->rowCount();
}

function deleteGrade($input) {
    global $pdo;
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    // Sanitize input
    $input['id'] = (int)$input['id'];
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    // Delete data
    $stmt = $pdo->prepare('DELETE FROM grades WHERE id = :id');
    $stmt->execute($input);
    return $stmt->rowCount();
}