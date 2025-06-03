<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Personal Website</title>
</head>
<body>
    <header>
        <h1>Welcome to My Personal Website</h1>
    </header>
    
    <nav>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#gallery">Gallery</a></li>
            <li><a href="#blog">Blog</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <!-- Home Section -->
        <section id="home">
            <h2>About Me</h2>
            <img src="asset/Me.jpg" alt="Profile Picture" style="float: left; margin-right: 20px; width: 230px; height: 310px;">
            <p>Hello! My name is Joachim Kalangi. Welcome to my personal website where I share information about myself, my hobbies, and my thoughts on various topics.</p>
            <p>I'm passionate about art especially in music. I really enjoy listening to music of various genres, whether it's pop, rock, country, and more.
                Currently, I am a bass singer in a choir that I joined, the Sam Ratulangi University Choir, and we have participated in various competitions.
            </p>
            <p>I've been part of the choir communities since high school, and since then, I've frequently participated in competitions, as well as performed in concerts.
                To this day, I remain active in choirs, both at my church and at university (Sam Ratulangi University Choir) and a vocal ensemble I'm currently part of, the Vox Angelica Mix Choir.
            </p>
            <div style="clear: both;"></div>
        </section>
        
        <!-- Gallery Section -->
        <section id="gallery" style="display: none;">
            <h2>My Photo Gallery</h2>
            <div class="gallery">
                <img src="asset/IMG-20250413-WA0022.jpg" alt="Photo 1">
                <img src="asset/IMG-20250419-WA0017.jpg" alt="Photo 2">
                <img src="asset/IMG-20250419-WA0006.jpg" alt="Photo 3">
                <img src="asset/IMG-20250413-WA0023.jpg" alt="Photo 4">
                <img src="asset/IMG-20250419-WA0021.jpg" alt="Photo 5">
                <img src="asset/IMG-20250419-WA0013.jpg" alt="Photo 6">
            </div>
        </section>
        
        <!-- Blog Section -->
        <section id="blog" style="display: none;">
            <h2>My Blog</h2>
            
            <?php
            require_once 'db_connect.php';
            
            $sql = "SELECT * FROM blog_posts ORDER BY post_date DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<article class="blog-post">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p><small>Posted on ' . date('F j, Y', strtotime($row['post_date'])) . '</small></p>';
                    echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                    echo '</article>';
                }
            } else {
                echo '<p>No blog posts yet. Check back soon!</p>';
            }
            
            $conn->close();
            ?>
        </section>
        
        <!-- Contact Section -->
        <section id="contact" style="display: none;">
            <h2>Contact Me</h2>
            <div class="contact-form">
                <form id="contactForm" action="process_contact.php" method="POST">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div>
                        <label for="subject">Subject:</label>
                        <input type="text" id="subject" name="subject">
                    </div>
                    <div>
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit">Send Message</button>
                </form>
            </div>
            <p>Or reach me directly at: <a href="mailto:kalangijoachim@gmail.com">kalangijoachim@gmail.com</a></p>
        </section>
    </div>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> My Personal Website. All rights reserved.</p>
    </footer>
    
    <script src="script.js"></script>
</body>
</html>