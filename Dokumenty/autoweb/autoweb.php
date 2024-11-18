<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("connect.php");
?>


<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMagazín - Časopis o autech</title>
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
    </style>
	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
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

        if (isset($_SESSION['role']) && $_SESSION['role'] === 'autor') {
            echo '<div class="clickButton" id="nahratBtn">Nahrát článek</div>';
        }
        echo '<div class="clickButton">Vaše jméno: ' . $_SESSION['prihlasovaci_jmeno'] . '</div>';
        echo '<div class="clickButton" id="logoutBtn">Logout</div>';
    } else {

        echo '<div class="clickButton" id="registerBtn">Registrace</div>';
        echo '<div class="clickButton" id="loginBtn">Přihlásit</div>';
    }
    ?>
    </div></header>

<div class="slider" id="slider">
    <div class="slides">
        <div class="slide" style="background-image: url('superb.jfif');">
            <h2>Škoda Superb</h2>
        </div>
        <div class="slide" style="background-image: url('kodiaq.jpeg');">
            <h2>Škoda Kodiaq</h2>
        </div>
        <div class="slide" style="background-image: url('superbiv.jfif');">
            <h2>Škoda Superb iV</h2>
        </div>
    </div>
</div>

<div class="content">
    <div class="article-preview">
        <img src="elektromobil.jpeg" alt="Elektromobil XYZ">
        <h3>Test nového elektromobilu XYZ</h3>
        <p>„Nový elektromobil XYZ od značky ABC přináší revoluční výkon a nečekaně dlouhý dojezd. Přečtěte si náš test!“</p>
        <a href="#">Číst dál</a>
    </div>

    <div class="article-preview">
        <img src="suv.jfif" alt="Nejlepší SUV">
        <h3>Srovnání nejlepších SUV roku 2024</h3>
        <p>„SUV stále vedou, ale které modely opravdu stojí za to? Přečtěte si náš přehled!“</p>
        <a href="#">Číst dál</a>
    </div>

    <div class="article-preview">
        <img src="hve.jpeg" alt="Hybridní auta">
        <h3>Hybridní vs. Elektrická auta</h3>
        <p>„Rozebíráme výhody a nevýhody obou technologií.“</p>
        <a href="#">Číst dál</a>
    </div>
</div>

<footer>
    <p>&copy; 2024 AutoMagazín. Všechna práva vyhrazena.</p>
</footer>

<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeRegisterModal">&times;</span>
        <h3>Registrace</h3>
        <form id="registerForm">
            <input type="text" id="registerName" name="registerName" placeholder="Vaše jméno" required>
            <input type="email" id="registerEmail" name="registerEmail" placeholder="Váš email" required>
            <input type="password" id="registerPassword" name="registerPassword" placeholder="Heslo" required>
            <input type="password" id="registerConfirmPassword" name="registerConfirmPassword" placeholder="Potvrďte heslo" required>
            <div class="password-strength" id="passwordStrength"></div>
            <button type="button" onclick="registerUser()">Registrovat</button>
        </form>
    </div>
</div>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeLoginModal">&times;</span>
        <h3>Přihlášení</h3>
        <form id="loginForm">
            <input type="text" id="username" name="username" placeholder="Uživatelské jméno" required>
            <input type="password" id="password" name="password" placeholder="Heslo" required>
            <button type="button" onclick="loginUser()">Přihlásit</button>
        </form>
    </div>
</div>

<script>

	function registerUser() {
    
        var registerName = document.getElementById('registerName').value;
        var registerEmail = document.getElementById('registerEmail').value;
        var registerPassword = document.getElementById('registerPassword').value;
        var registerConfirmPassword = document.getElementById('registerConfirmPassword').value;

        
        var formData = new FormData();
        formData.append('register', '1');  
        formData.append('registerName', registerName);
        formData.append('registerEmail', registerEmail);
        formData.append('registerPassword', registerPassword);
        formData.append('registerConfirmPassword', registerConfirmPassword);

        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'register.php', true);

        
        xhr.onload = function() {
            if (xhr.status === 200) {
                
                alert(xhr.responseText);
                if (xhr.responseText === "Registrace úspěšná!") {
                    
                    document.getElementById('registerModal').style.display = 'none';
                    document.getElementById('registerForm').reset();
                }
            } else {
                alert('Chyba při registraci!');
            }
        };

        xhr.send(formData);
    }

    function loginUser() {
        
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;

        
        var formData = new FormData();
        formData.append('login', '1');  
        formData.append('username', username);
        formData.append('password', password);

        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'login.php', true);

        
        xhr.onload = function() {
            if (xhr.status === 200) {
                
                alert(xhr.responseText);
                if (xhr.responseText === "Přihlášení úspěšné!") {
                    
                    document.getElementById('loginModal').style.display = 'none';
                    document.getElementById('loginForm').reset();
                    window.location.reload();
                }
            } else {
                alert('Chyba při přihlášení!');
            }
        };

        
        xhr.send(formData);
    }

    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.onclick = () => window.location.href = 'logout.php';
    }

const nahratBtn = document.getElementById("nahratBtn");
    if (nahratBtn) {
        nahratBtn.onclick = () => window.location.href = 'vlozeni.php';
    }

    const slider = document.getElementById('slider');
    const slides = slider.querySelector('.slides');
    const slideCount = slides.children.length;
    let currentIndex = 0;

    function moveSlider() {
        currentIndex = (currentIndex + 1) % slideCount;
        slides.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    setInterval(moveSlider, 4000);
	
	
	const registerModal = document.getElementById("registerModal");
    const loginModal = document.getElementById("loginModal");
    const registerBtn = document.getElementById("registerBtn");
    const loginBtn = document.getElementById("loginBtn");
    const closeRegisterModal = document.getElementById("closeRegisterModal");
    const closeLoginModal = document.getElementById("closeLoginModal");
    registerBtn.onclick = () => registerModal.style.display = "flex";
    loginBtn.onclick = () => loginModal.style.display = "flex";

    closeRegisterModal.onclick = () => registerModal.style.display = "none";
    closeLoginModal.onclick = () => loginModal.style.display = "none";

    window.onclick = (event) => {
        if (event.target === registerModal) registerModal.style.display = "none";
        if (event.target === loginModal) loginModal.style.display = "none";
    };

    const passwordInput = document.getElementById("registerPassword");
    const confirmPasswordInput = document.getElementById("registerConfirmPassword");
    const passwordStrength = document.getElementById("passwordStrength");
    const registerForm = document.getElementById("registerForm");

    passwordInput.addEventListener("input", () => {
        const value = passwordInput.value;
        let strength = "weak";

        if (value.length >= 8 && /[A-Z]/.test(value)) strength = "strong";
        else if (value.length >= 8) strength = "medium";

        passwordStrength.className = `password-strength ${strength}`;
    });
	
</script>

</body>
</html>




