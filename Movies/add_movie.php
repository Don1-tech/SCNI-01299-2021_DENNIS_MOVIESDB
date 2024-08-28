<?php
// add_movie.php

$servername = "localhost"; // Update with your database server
$username = "root"; // Update with your database username
$password = "Bakule1234"; // Update with your database password
$dbname = "movies"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve POST data
$title = $_POST['title'];
$description = $_POST['description'];
$release_date = $_POST['release_date'];
$poster_url = $_POST['poster_url'];

// Prepare and execute SQL query
$sql = "INSERT INTO movies (title, description, release_date, poster_url) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $title, $description, $release_date, $poster_url);

if ($stmt->execute()) {
    echo "Movie added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
