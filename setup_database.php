<?php
require_once 'db_connect.php';

// SQL to create blog posts table
$sql = "CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    post_date DATETIME NOT NULL,
    author VARCHAR(100) DEFAULT 'Joachim Kalangi'
)";

if ($conn->query($sql) === TRUE) {
    echo "Table blog_posts created successfully<br>";
    
    // Insert sample blog posts if table is empty
    $check_empty = "SELECT COUNT(*) as count FROM blog_posts";
    $result = $conn->query($check_empty);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $sample_posts = [
            [
                'title' => 'The journey to A Voyage of Songs',
                'content' => 'To prepare for the competition, we held regular rehearsals three times a week for several months... [rest of your existing blog content]',
                'post_date' => '2023-08-05 00:00:00'
            ],
            // Add more sample posts if needed
        ];
        
        foreach ($sample_posts as $post) {
            $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, post_date) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $post['title'], $post['content'], $post['post_date']);
            $stmt->execute();
        }
        
        echo "Sample blog posts inserted successfully";
    }
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>