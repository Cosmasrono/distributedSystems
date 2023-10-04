<?php
// Include database connection
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reg_no = $_POST["reg_no"];

    // Query user data based on reg_no
    $query = "SELECT * FROM users WHERE reg_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $reg_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "No user found with that registration number.";
    } else {
        // Display user data in a card
        while ($row = $result->fetch_assoc()) {
            echo "<div class='container'>";
            echo "<div class='user-card'>";
            echo "<h2>User Information</h2>";
            echo "<p>Name: " . htmlspecialchars($row['name']) . "</p>";
            echo "<p>Email: " . htmlspecialchars($row['email']) . "</p>";
            echo "<p>Mobile: " . htmlspecialchars($row['mobile']) . "</p>";
            echo "<p>Address: " . htmlspecialchars($row['address']) . "</p>";
            echo "<p>Registration Number: " . htmlspecialchars($row['reg_no']) . "</p>";
            echo "</div>";
            echo "</div>";
        }
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
