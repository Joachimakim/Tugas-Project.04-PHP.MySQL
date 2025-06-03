<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Hardcoded credentials for demo (in production, use proper authentication)
$valid_username = "admin";
$valid_password = "password123"; // Change this to a strong password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .login-form { max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .login-form h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .form-group button { width: 100%; padding: 10px; background: #2c3e50; color: white; border: none; cursor: pointer; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
[file content end]

[file name]: admin/dashboard.php
[file content begin]
<?php
session_start();
require_once '../db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_post'])) {
        // Add new blog post
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $post_date = date('Y-m-d H:i:s');
        
        if (!empty($title) && !empty($content)) {
            $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, post_date) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $content, $post_date);
            $stmt->execute();
            $stmt->close();
        }
    } elseif (isset($_POST['delete_post'])) {
        // Delete blog post
        $id = intval($_POST['post_id']);
        $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Get all blog posts
$posts = [];
$result = $conn->query("SELECT * FROM blog_posts ORDER BY post_date DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// Get all messages
$messages = [];
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header { background: #2c3e50; color: white; padding: 15px; display: flex; justify-content: space-between; }
        .container { display: flex; }
        .sidebar { width: 200px; background: #34495e; color: white; padding: 15px; min-height: calc(100vh - 60px); }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px; }
        .sidebar a:hover { background: #2c3e50; }
        .main-content { flex: 1; padding: 20px; }
        .section { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; }
        .form-group textarea { min-height: 150px; }
        button { padding: 8px 15px; background: #2c3e50; color: white; border: none; cursor: pointer; }
        button:hover { background: #1a252f; }
        .logout { color: white; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <h2>Admin Dashboard</h2>
        <a href="logout.php" class="logout">Logout</a>
    </header>
    
    <div class="container">
        <div class="sidebar">
            <a href="#posts">Manage Posts</a>
            <a href="#messages">View Messages</a>
        </div>
        
        <div class="main-content">
            <section id="posts" class="section">
                <h2>Add New Blog Post</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content:</label>
                        <textarea id="content" name="content" required></textarea>
                    </div>
                    <button type="submit" name="add_post">Add Post</button>
                </form>
                
                <h2>Existing Blog Posts</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($post['post_date'])); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                    <button type="submit" name="delete_post">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            
            <section id="messages" class="section">
                <h2>Contact Messages</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($message['name']); ?></td>
                            <td><?php echo htmlspecialchars($message['email']); ?></td>
                            <td><?php echo htmlspecialchars($message['subject']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($message['created_at'])); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
</html>
[file content end]

[file name]: admin/logout.php
[file content begin]
<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>