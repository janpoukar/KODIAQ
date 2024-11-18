<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("connect.php"); // Include database connection

// Handle form submission for new posts
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'], $_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = isset($_SESSION['prihlasovaci_jmeno']) ? $_SESSION['prihlasovaci_jmeno'] : 'Anonymous';

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert new post into the forum_posts table
        $sql = "INSERT INTO forum_posts (title, content, author) VALUES (:title, :content, :author)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'author' => $author
        ]);
    } catch (PDOException $e) {
        echo '<p>Chyba připojení k databázi: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMagazín - Diskuze</title>
    <style>
        /* General styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f8fc;
            color: #333;
        }

        /* Header styles */
        header {
            background-color: #0d47a1;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        header .logo img {
            max-height: 40px;
        }

        header .logo h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        nav {
            display: flex;
            gap: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        nav a:hover {
            background-color: #1565c0;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .auth-buttons div {
            color: white;
            font-size: 1rem;
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 5px;
            background-color: #42a5f5;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .auth-buttons div:hover {
            background-color: #1e88e5;
        }

        /* Content styles */
        .content {
            margin: 40px auto;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .forum-post {
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .forum-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .forum-post h3 {
            margin: 0;
            color: #0d47a1;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .forum-post p {
            color: #666;
            margin: 10px 0;
        }

        /* New post form */
        .new-post-form {
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .new-post-form input, .new-post-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .new-post-form button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #0d47a1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .new-post-form button:hover {
            background-color: #1565c0;
        }

        footer {
            background-color: #0d47a1;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }

        footer a {
            color: #bbdefb;
            text-decoration: none;
        }

        footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="logoK2.png" alt="Logo">
        <h1>AutoMagazín</h1>
    </div>
    <nav>
        <a href="autoweb.php">Domovská stránka</a>
        <a href="clanky.php">Články</a>
        <a href="#">Recenze</a>
        <a href="#">Diskuze</a>
        <a href="#">Kontakt</a>
    </nav>
    <div class="auth-buttons">
        <?php 
        if (isset($_SESSION['prihlasovaci_jmeno'])) {
            echo '<div>Vaše jméno: ' . $_SESSION['prihlasovaci_jmeno'] . '</div>';
            echo '<div id="logoutBtn">Logout</div>';
        } else {
            echo '<div id="loginBtn">Přihlásit</div>';
        }
        ?>
    </div>
</header>

<!-- New Post Form -->
<div class="new-post-form">
    <h2>Nový příspěvek</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Název příspěvku" required>
        <textarea name="content" placeholder="Obsah příspěvku" rows="5" required></textarea>
        <button type="submit">Odeslat</button>
    </form>
</div>

<!-- Display Forum Posts -->
<div class="content">
    <?php
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch all posts
        $sql = "SELECT * FROM forum_posts ORDER BY created_at DESC";
        $stmt = $pdo->query($sql);

        while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="forum-post">';
            echo '<h3>' . htmlspecialchars($post["title"]) . '</h3>';
            echo '<p><strong>Autor:</strong> ' . htmlspecialchars($post["author"]) . '</p>';
            echo '<p>' . nl2br(htmlspecialchars($post["content"])) . '</p>';
            echo '<p><strong>Datum:</strong> ' . htmlspecialchars($post["created_at"]) . '</p>';
            echo '</div>';
        }

    } catch (PDOException $e) {
        echo '<p>Chyba připojení k databázi: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
</div>

<footer>
    <p>&copy; 2024 AutoMagazín. Všechna práva vyhrazena.</p>
</footer>

<script>
    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.onclick = () => window.location.href = 'logout.php';
    }
</script>

</body>
</html>
