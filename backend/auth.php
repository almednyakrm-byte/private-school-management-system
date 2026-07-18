<?php
// Start the session to store user data
session_start();

// Import the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response with their status
    echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
    exit;
}

// Handle the login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to select the user
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows == 1) {
            // Get the user's data
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // If the password is correct, log the user in
                $_SESSION['user_id'] = $user['id'];
                echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
            } else {
                // If the password is incorrect, send an error message
                echo json_encode(array('status' => 'error', 'message' => 'Invalid password'));
            }
        } else {
            // If the user does not exist, send an error message
            echo json_encode(array('status' => 'error', 'message' => 'Invalid username'));
        }
    } else {
        // If the username or password is missing, send an error message
        echo json_encode(array('status' => 'error', 'message' => 'Missing username or password'));
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to insert the new user
        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, password_hash($password, PASSWORD_DEFAULT));
        $stmt->execute();

        // Check if the user was created successfully
        if ($stmt->affected_rows == 1) {
            // If the user was created, send a success message
            echo json_encode(array('status' => 'success', 'message' => 'User created successfully'));
        } else {
            // If the user was not created, send an error message
            echo json_encode(array('status' => 'error', 'message' => 'Failed to create user'));
        }
    } else {
        // If the username, email, or password is missing, send an error message
        echo json_encode(array('status' => 'error', 'message' => 'Missing username, email, or password'));
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Log the user out by destroying the session
    session_destroy();
    echo json_encode(array('status' => 'logged_out'));
}

// Close the database connection
$mysqli->close();
?>