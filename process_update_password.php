<?php
require 'db_connection.php';

function updatePassword($conn, $token, $new_password) {

    if(!isset($token) || empty($token)){
        echo "Invalid token";
        return "Invalid token";
    }

    if(!isset($new_password) || empty($new_password)){
        echo "Invalid password";
        return "Invalid password";
    }
    // Prepare and execute the SQL query
    $update_sql = "UPDATE users SET password = ?, reset_token = null WHERE reset_token = ?";
    $stmt = $conn->prepare($update_sql);
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt->bind_param("ss", $hashed_password, $token);

    try {
        if ($stmt->execute()) {
            echo "Password updated successfully";
        } else {
            echo "Error updating password: " . $stmt->error;
        }
    } catch (Exception $e) {
        echo "Error updating password: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $new_password = $_POST["new_password"];

    echo $token;
    

    // Validate inputs
    if (empty($token) || empty($new_password)) {
        echo "Invalid input data.";
    } else {
        // Update the password and handle the result
        $updateResult = updatePassword($conn, $token, $new_password);
        echo $updateResult;
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>

