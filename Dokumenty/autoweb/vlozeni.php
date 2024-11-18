<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include ("connect.php");

// Zpracování formuláře
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nazev_clanku = $conn->real_escape_string($_POST['articleName']);
    $soubor = $_FILES['articleFile'];

    // Kontrola, zda byl soubor nahrán
    if ($soubor['error'] == UPLOAD_ERR_OK) {
        // Získání obsahu souboru do proměnné
        $file_data = file_get_contents($soubor['tmp_name']);
	$file_type = strtolower(pathinfo($soubor['name'], PATHINFO_EXTENSION));

        // Kontrola přípony souboru
        if (in_array($file_type, ['pdf'])) {
            // Uložení dat do databáze
            $stmt = $conn->prepare("INSERT INTO clanky (nazev, autor, soubor) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nazev_clanku, $_SESSION['prihlasovaci_jmeno'], $file_data);

            if ($stmt->execute()) {
                $message = "Článek byl úspěšně nahrán!";
            } else {
                $message = "Chyba při ukládání článku: " . $stmt->error;
            }
        } else {
            $message = "Povolené jsou pouze soubory ve formátu PDF.";
        }
    } else {
        $message = "Chyba při nahrávání souboru: " . $soubor['error'];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMagazín - Formulář pro autora</title>
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

        /* Slider styles */
        .slider {
            max-width: 100%;
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            position: relative;
            background-position: center;
            background-size: cover;
            height: 450px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .slide h2 {
            color: white;
            background-color: rgba(13, 71, 161, 0.8);
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 2.5rem;
            margin: 0;
        }

        /* Article styles */
        .content {
            margin: 40px auto;
            max-width: 1200px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .article-preview {
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .article-preview img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .article-preview:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .article-preview h3 {
            margin: 0;
            color: #0d47a1;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .article-preview p {
            color: #666;
            margin-top: 10px;
            flex-grow: 1;
        }

        .article-preview a {
            color: #1565c0;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            display: inline-block;
        }

        /* Footer styles */
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
		
		        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .modal-content h3 {
            text-align: center;
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .modal-content input {
            width: 94%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal-content button {
            width: 100%;
            padding: 12px;
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .modal-content button:hover {
            background-color: #0c57c6;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover, .close:focus {
            color: black;
        }

        .password-strength {
            height: 10px;
            width: 100%;
            margin-top: 5px;
            border-radius: 4px;
            background-color: #ccc;
            transition: background-color 0.3s ease;
        }

        .password-strength.weak {
            background-color: red;
        }

        .password-strength.medium {
            background-color: orange;
        }

        .password-strength.strong {
            background-color: green;
        }
		
		.clickButton {
            color: white;
            margin: 0 20px;
            text-decoration: none;
            position: relative;
            padding: 10px 15px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            background-color: #42a5f5;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .clickButton:hover {
            background-color: #1e88e5;
        }

.form-container {
            background-color: white;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            width: 60%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            color: #0d47a1;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-container button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #0d47a1;
            color: white;
            font-size: 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #1e88e5;
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
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
        <a href="mluv.php">Diskuze</a>
        <a href="#">Kontakt</a>
    </nav>
    <div class="auth-buttons"> <?php 
    if (isset($_SESSION['prihlasovaci_jmeno'])) {

        echo '<div class="clickButton">Vaše jméno: ' . $_SESSION['prihlasovaci_jmeno'] . '</div>';
        echo '<div class="clickButton" id="logoutBtn">Logout</div>';
    } else {

        echo '<div class="clickButton" id="registerBtn">Registrace</div>';
        echo '<div class="clickButton" id="loginBtn">Přihlásit</div>';
    }
    ?>
    </div>
</header>


<div class="form-container">
    <h1>Přidání článku</h1>
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="articleName">Název článku:</label>
            <input type="text" id="articleName" name="articleName" placeholder="Zadejte název článku" required>
        </div>
        <div class="form-group">
            <label for="articleFile">Nahrajte soubor (Word nebo PDF):</label>
            <input type="file" id="articleFile" name="articleFile" accept=".doc,.docx,.pdf" required>
        </div>
        <button type="submit">Odeslat článek</button>
    </form>
</div>

<footer>
    <p>&copy; 2024 AutoMagazin. Všechna práva vyhrazena.</p>
</footer>

</body>
</html>
