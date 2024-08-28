<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = array('success' => false, 'message' => '');

// Retrieve form data
$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : ''; // Ensure phone is a string
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Debugging: Print received values to error log
error_log("Received values: first_name=$first_name, last_name=$last_name, username=$username, phone=$phone, email=$email, password=$password");

// Validate passwords
if ($password !== $confirm_password) {
    $response['message'] = "Passwords do not match.";
} else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Create connection
    $conn = new mysqli('localhost', 'root', 'Bakule1234', 'movies');

    // Check connection
    if ($conn->connect_error) {
        $response['message'] = "Connection failed: " . $conn->connect_error;
    } else {
        // Prepare and execute SQL statement
        // Ensure email is handled as a string
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, phone, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $username, $phone, $email, $hashed_password);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Registration successful!";
        } else {
            // Log SQL errors
            error_log("SQL Error: " . $stmt->error);
            $response['message'] = "Error: " . $stmt->error;
        }
        
        $stmt->close();
        $conn->close();
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
