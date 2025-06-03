<?php
require_once 'db_connect.php';

// Create messages table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$conn->query($sql);

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"] ?? 'No Subject');
    $message = trim($_POST["message"]);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($message)) {
        die("Please fill in all required fields.");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        // Send email notification (optional)
        $to = "kalangijoachim@gmail.com";
        $email_subject = "New Contact Form Submission: $subject";
        $email_body = "You have received a new message from $name ($email).\n\n".
                      "Message:\n$message";
        $headers = "From: $email";
        
        mail($to, $email_subject, $email_body, $headers);
        
        // Redirect with success message
        header("Location: index.php?contact=success#contact");
        exit();
    } else {
        die("Error: " . $stmt->error);
    }
    
    $stmt->close();
}

$conn->close();
?>