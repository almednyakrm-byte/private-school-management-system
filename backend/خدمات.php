<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($input_data['id']) && !isset($input_data['name']) && !isset($input_data['description'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request data'));
    exit;
}

// Sanitize input data
$input_data['name'] = htmlspecialchars($input_data['name']);
$input_data['description'] = htmlspecialchars($input_data['description']);

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// GET all services
if (isset($_GET['id'])) {
    // GET single service
    $stmt = $db->prepare('SELECT * FROM services WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($service) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($service);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Service not found'));
    }
} elseif (isset($_GET['limit']) && isset($_GET['offset'])) {
    // GET paginated services
    $stmt = $db->prepare('SELECT * FROM services LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $_GET['limit']);
    $stmt->bindParam(':offset', $_GET['offset']);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($services);
} else {
    // GET all services
    $stmt = $db->prepare('SELECT * FROM services');
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($services);
}

// POST create service
if (isset($input_data['name']) && isset($input_data['description'])) {
    if ($user_role == 'admin') {
        // Create service
        $stmt = $db->prepare('INSERT INTO services (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $input_data['name']);
        $stmt->bindParam(':description', $input_data['description']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Service created successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
}

// PUT update service
if (isset($input_data['id']) && isset($input_data['name']) && isset($input_data['description'])) {
    if ($user_role == 'admin') {
        // Update service
        $stmt = $db->prepare('UPDATE services SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $input_data['id']);
        $stmt->bindParam(':name', $input_data['name']);
        $stmt->bindParam(':description', $input_data['description']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Service updated successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
}

// DELETE service
if (isset($input_data['id'])) {
    if ($user_role == 'admin') {
        // Delete service
        $stmt = $db->prepare('DELETE FROM services WHERE id = :id');
        $stmt->bindParam(':id', $input_data['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Service deleted successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
}

$db = null;