<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM الفواتير');
    $stmt->execute();
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($invoices);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Validate and sanitize input data
    $required_fields = ['invoice_number', 'invoice_date', 'total_amount'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
        $data[$field] = trim($data[$field]);
    }

    // Insert new invoice
    $stmt = $pdo->prepare('INSERT INTO الفواتير (invoice_number, invoice_date, total_amount) VALUES (:invoice_number, :invoice_date, :total_amount)');
    $stmt->bindParam(':invoice_number', $data['invoice_number']);
    $stmt->bindParam(':invoice_date', $data['invoice_date']);
    $stmt->bindParam(':total_amount', $data['total_amount']);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Invoice created successfully']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create invoice']);
        exit;
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Validate and sanitize input data
    $required_fields = ['invoice_id', 'invoice_number', 'invoice_date', 'total_amount'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required field: ' . $field]);
            exit;
        }
        $data[$field] = trim($data[$field]);
    }

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Update existing invoice
    $stmt = $pdo->prepare('UPDATE الفواتير SET invoice_number = :invoice_number, invoice_date = :invoice_date, total_amount = :total_amount WHERE id = :id');
    $stmt->bindParam(':id', $data['invoice_id']);
    $stmt->bindParam(':invoice_number', $data['invoice_number']);
    $stmt->bindParam(':invoice_date', $data['invoice_date']);
    $stmt->bindParam(':total_amount', $data['total_amount']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Invoice updated successfully']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update invoice']);
        exit;
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Validate and sanitize input data
    if (!isset($data['invoice_id']) || empty($data['invoice_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required field: invoice_id']);
        exit;
    }
    $data['invoice_id'] = trim($data['invoice_id']);

    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Delete existing invoice
    $stmt = $pdo->prepare('DELETE FROM الفواتير WHERE id = :id');
    $stmt->bindParam(':id', $data['invoice_id']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Invoice deleted successfully']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete invoice']);
        exit;
    }
}