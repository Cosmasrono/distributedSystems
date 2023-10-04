<?php
// Include necessary libraries
require 'db_connection.php';
require 'vendor/autoload.php';
require __DIR__ . '/vendor/autoload.php';

use SendGrid\Mail\Mail;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize the email address
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Generate a unique reset token (you can use a library like random_bytes)
    $reset_token = bin2hex(random_bytes(16));

    // updte the user's reset_token in the database
    $insert_sql = "UPDATE users SET reset_token = ? WHERE email = ?";
    

    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ss", $reset_token, $email);


    if ($stmt->execute()) {
        // Send an email with the reset token using SendGrid
        $emailObject = new Mail();
        $emailObject->setFrom("francismwanik254@gmail.com", "Francis Mwaniki");
        $emailObject->setSubject("Password Reset Link");
        $emailObject->addTo($email); // Use $emailAddress instead of $email to avoid conflicts
        $emailObject->addContent(
            "text/html",
            "Click the following link to reset your password: <a href='http://localhost/dashboard/update-password.html?token=$reset_token'>Reset Password</a>"
        );

        $sendgrid = new \SendGrid('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'); // Replace 'SENDGRID_API_KEY' with your actual SendGrid API key

        try {
            $response = $sendgrid->send($emailObject);
            if ($response->statusCode() == 202) {
                echo "Password reset email sent successfully";
            } else {
                echo "Error sending password reset email. Status code: " . $response->statusCode();
            }
        } catch (Exception $e) {
            echo "Error sending password reset email: " . $e->getMessage();
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
