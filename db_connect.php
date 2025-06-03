<?php
// Database connection settings
define('DB_SERVER', 'localhost');   // Or your host's specific server name
define('DB_USERNAME', 'cpanelusername_dbuser'); // Often prefixed by cPanel username
define('DB_PASSWORD', 'your_strong_password');  // The one you set in cPanel
define('DB_NAME', 'cpanelusername_personal_website'); // Often prefixed

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . $conn->connect_error);
}
?>