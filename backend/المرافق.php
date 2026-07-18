<?php

require_once 'db.php';

// Get user role and logged-in status
$userRole = $_SESSION['userRole'];
$loggedIn = $_SESSION['loggedIn'];

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is logged in
    if (!$loggedIn) {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Select all or specific record
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM المرافق WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($row);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Record not found'));
        }
    } else {
        $stmt = $pdo->prepare('SELECT * FROM المرافق');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($rows);
    }
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Check if user is logged in
    if (!$loggedIn) {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Insert new record
    $stmt = $pdo->prepare('INSERT INTO المرافق (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Record created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create record'));
    }
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $data = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Check if user is logged in and has admin role
    if (!$loggedIn || $userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Update existing record
    $stmt = $pdo->prepare('UPDATE المرافق SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Record updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update record'));
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is logged in and has admin role
    if (!$loggedIn || $userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Delete existing record
    $stmt = $pdo->prepare('DELETE FROM المرافق WHERE id = :id');
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Record deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete record'));
    }
}

// Handle invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}