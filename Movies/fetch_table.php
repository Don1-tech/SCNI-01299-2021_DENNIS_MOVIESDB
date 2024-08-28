<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change to your database username
$password = "Bakule1234"; // Change to your database password
$dbname = "movies"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the table name from the request
$table = isset($_GET['table']) ? $_GET['table'] : 'movies';

// Validate the table name
$validTables = ['cast', 'directors', 'movies', 'trailers'];
if (!in_array($table, $validTables)) {
    echo json_encode(["error" => "Invalid table."]);
    exit;
}

// Fetch data from the specified table
$sql = "SELECT * FROM $table";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    // Output table rows
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
