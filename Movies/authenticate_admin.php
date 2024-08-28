<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "Bakule1234";
$dbname = "movies";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminUsername = $_POST['username'];
    $adminPassword = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $adminUsername, $adminPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $response['success'] = true;
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
