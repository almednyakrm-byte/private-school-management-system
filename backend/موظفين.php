<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM موظفين');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Output data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        // Validate input
        if (!isset($input['id']) || !is_numeric($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM موظفين WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Output data
        if ($result) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_by_name') {
    try {
        // Validate input
        if (!isset($input['name']) || empty($input['name'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM موظفين WHERE name = :name');
        $stmt->bindParam(':name', $input['name']);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Output data
        if ($result) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    try {
        // Validate input
        if (!isset($input['name']) || empty($input['name']) || !isset($input['email']) || empty($input['email'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Sanitize input
        $input['name'] = htmlspecialchars($input['name']);
        $input['email'] = htmlspecialchars($input['email']);
        
        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO موظفين (name, email) VALUES (:name, :email)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->execute();
        
        // Output data
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    try {
        // Validate input
        if (!isset($input['id']) || !is_numeric($input['id']) || !isset($input['name']) || empty($input['name']) || !isset($input['email']) || empty($input['email'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Sanitize input
        $input['name'] = htmlspecialchars($input['name']);
        $input['email'] = htmlspecialchars($input['email']);
        
        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE موظفين SET name = :name, email = :email WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->execute();
        
        // Output data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    try {
        // Validate input
        if (!isset($input['id']) || !is_numeric($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM موظفين WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        
        // Output data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

?>