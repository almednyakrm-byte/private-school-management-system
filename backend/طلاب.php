<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define table name
$table_name = 'طلاب';

// Define allowed columns for CRUD operations
$allowed_columns = ['name', 'email', 'phone', 'address'];

// Define validation rules
$validation_rules = [
    'name' => 'required',
    'email' => 'required|email',
    'phone' => 'required|numeric',
    'address' => 'required',
];

// Validate input data
foreach ($validation_rules as $column => $rule) {
    if (!isset($input[$column])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $column"]);
        exit;
    }
    if ($rule == 'email') {
        if (!filter_var($input[$column], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => "Invalid email address"]);
            exit;
        }
    } elseif ($rule == 'numeric') {
        if (!is_numeric($input[$column])) {
            http_response_code(400);
            echo json_encode(['error' => "Invalid phone number"]);
            exit;
        }
    }
}

// Sanitize input data
foreach ($input as $column => $value) {
    $input[$column] = htmlspecialchars($value);
}

// Handle CRUD operations
if (isset($_GET['id'])) {
    // Get student by ID
    $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $student = $stmt->fetch();
    if (!$student) {
        http_response_code(404);
        echo json_encode(['error' => 'Student not found']);
        exit;
    }
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    // Handle edit operation
    if (isset($_POST['edit'])) {
        // Validate input data
        foreach ($validation_rules as $column => $rule) {
            if (!isset($input[$column])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $column"]);
                exit;
            }
        }
        // Update student data
        $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->bindParam(':address', $input['address']);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(['message' => 'Student updated successfully']);
        exit;
    }
    // Handle delete operation
    elseif (isset($_POST['delete'])) {
        // Check if user is admin
        if ($_SESSION['role'] != 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        // Delete student data
        $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(['message' => 'Student deleted successfully']);
        exit;
    }
} else {
    // Handle create operation
    if (isset($_POST['create'])) {
        // Validate input data
        foreach ($validation_rules as $column => $rule) {
            if (!isset($input[$column])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $column"]);
                exit;
            }
        }
        // Insert student data
        $stmt = $pdo->prepare("INSERT INTO $table_name (name, email, phone, address) VALUES (:name, :email, :phone, :address)");
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->bindParam(':address', $input['address']);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(['message' => 'Student created successfully']);
        exit;
    }
    // Handle read operation
    elseif (isset($_GET['page'])) {
        // Get students by page
        $stmt = $pdo->prepare("SELECT * FROM $table_name LIMIT :page, 10");
        $stmt->bindParam(':page', $_GET['page']);
        $stmt->execute();
        $students = $stmt->fetchAll();
        http_response_code(200);
        echo json_encode(['students' => $students]);
        exit;
    } else {
        // Get all students
        $stmt = $pdo->prepare("SELECT * FROM $table_name");
        $stmt->execute();
        $students = $stmt->fetchAll();
        http_response_code(200);
        echo json_encode(['students' => $students]);
        exit;
    }
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;