<?php
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash the password for security
    $mobile = $_POST["mobile"];
    $address = $_POST["address"];
    $reg_no = $_POST["reg_no"];

    $insert_sql = "INSERT INTO users (name, email, password, mobile, address, reg_no) VALUES (?, ?, ?, ?, ?, ?)";

    
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssssss", $name, $email, $password, $mobile, $address, $reg_no);


    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Registration successful',
        ];
        echo json_encode($response);
    } else {
        $response = [
            'success' => false,
            'message' => 'Error: ' . $stmt->error,
        ];
        echo json_encode($response);
    }
    

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
