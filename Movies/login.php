<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Start a new session or resume the existing session

$response = array('success' => false, 'message' => '');

// Retrieve form data
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Debugging: Print received values to error log
error_log("Login attempt: username=$username");

// Create connection
$conn = new mysqli('localhost', 'root', 'Bakule1234', 'movies');

// Check connection
if ($conn->connect_error) {
    $response['message'] = "Connection failed: " . $conn->connect_error;
} else {
    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
    if ($stmt === false) {
        error_log('Prepare failed: ' . $conn->error);
        $response['message'] = 'Failed to prepare the SQL statement.';
    } else {
        $stmt->bind_param("s", $username);
        
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password);
                $stmt->fetch();
                
                // Verify the password
                if (password_verify($password, $hashed_password)) {
                    // Set session variables
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    
                    $response['success'] = true;
                    $response['message'] = "Login successful!";
                } else {
                    $response['message'] = "Invalid username or password.";
                }
            } else {
                $response['message'] = "Invalid username or password.";
            }
            
            $stmt->close();
        } else {
            error_log('Execute failed: ' . $stmt->error);
            $response['message'] = "Error executing query.";
        }
    }
    
    $conn->close();
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
