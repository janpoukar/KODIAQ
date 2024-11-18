<?php

$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Chyba při připojení k databázi: " . $conn->connect_error);
}

//vypsání všech článků z databáze
$sql = "SELECT id, nazev FROM clanky";
$result = $conn->query($sql);

// Zpracování formuláře
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_autora = intval($_POST['author_id']);
    $id_clanku = intval($_POST['article_id']);
    $aktualnost = intval($_POST['aktualnost']);
    $originalita = intval($_POST['originalita']);
    $odborna_uroven = intval($_POST['odborna_uroven']);
    $jazykova_stylistika = intval($_POST['jazykova_stylistika']);
    $otevrena_odpoved = $conn->real_escape_string($_POST['otevrena_odpoved']);
    $datum_rec = $conn->real_escape_string($_POST['datum_rec']);

    // Vložení dat do databáze
    $sql = "INSERT INTO recenze (id_autora, id_clanku, aktualnost, originalita, odborna_uroven, jazykova_stylistika, otevrena_odpoved, datum_rec) 
            VALUES ('$id_autora', '$id_clanku', '$aktualnost', '$originalita', '$odborna_uroven', '$jazykova_stylistika', '$otevrena_odpoved', '$datum_rec')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Recenze byla úspěšně odeslána.";
    } else {
        $message = "Chyba při ukládání recenze: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recenzní formulář</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f8fc;
            color: #333;
        }
        
         header {
            background: #0d47a1;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        header .logo {
            max-width: 200px;
        }

        nav {
            background-color: #1565c0;
            padding: 15px;
            text-align: center;
            display: flex;
            justify-content: center;
            position: relative;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        nav .button {
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

        nav .button:hover {
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

        .form-group select,
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
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
    <img src="logoK2.png" alt="Kodiaq Logo" class="logo">
</header>

<nav>
    <div class="button" id="registerBtn">Registrace</div>
    <div class="button" id="loginBtn">Přihlásit</div>
</nav>

<div class="form-container">
    <h1>Recenzní formulář</h1>
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="article_id">ID článku:</label>
            <input type="text" id="article_id" name="article_id" placeholder="Zadejte ID článku" required>
        </div>
        <div class="form-group">
            <label for="aktualnost">Aktuálnost, zajímavost a přínosnost (1-5):</label>
            <select id="aktualnost" name="aktualnost" required>
                <option value="1">1 - Nejlepší</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5 - Nejhorší</option>
            </select>
        </div>
        <div class="form-group">
            <label for="originalita">Originalita (1-5):</label>
            <select id="originalita" name="originalita" required>
                <option value="1">1 - Nejlepší</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5 - Nejhorší</option>
            </select>
        </div>
        <div class="form-group">
            <label for="odborna_uroven">Odborná úroveň (1-5):</label>
            <select id="odborna_uroven" name="odborna_uroven" required>
                <option value="1">1 - Nejlepší</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5 - Nejhorší</option>
            </select>
        </div>
        <div class="form-group">
            <label for="jazykova_stylistika">Jazyková a stylistická úroveň (1-5):</label>
            <select id="jazykova_stylistika" name="jazykova_stylistika" required>
                <option value="1">1 - Nejlepší</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5 - Nejhorší</option>
            </select>
        </div>
        <div class="form-group">
            <label for="otevrena_odpoved">Otevřená odpověď:</label>
            <textarea id="otevrena_odpoved" name="otevrena_odpoved" rows="5" placeholder="Napište své poznámky" required></textarea>
        </div>
        <button type="submit">Odeslat recenzi</button>
    </form>
</div>

<footer>
    <p>&copy; 2024 AutoMagazin. Všechna práva vyhrazena.</p>
</footer>

</body>
</html>
