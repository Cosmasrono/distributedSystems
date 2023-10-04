<?php
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email_to_check = $_POST["email"];
    $password_to_check = $_POST["password"];

    $select_sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    
    $stmt = $conn->prepare($select_sql);
    $stmt->bind_param("s", $email_to_check);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"];

        if (password_verify($password_to_check, $hashed_password)) {
            // Login successful, user found
            session_start();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_name"] = $row["name"];
            echo "Welcome, " . $row["name"];
        } else {
            // Incorrect password
            echo "Invalid email or password";
        }
    } else {
        // User not found
        echo "Invalid email or password";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
