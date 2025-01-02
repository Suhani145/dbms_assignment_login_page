<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT first_name, last_name, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $user;
            echo "Login successful! Welcome, " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . ".";
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this username.";
    }

    $stmt->close();
}
$conn->close();
?>
