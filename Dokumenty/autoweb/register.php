<?php

include 'connect.php';

if (isset($_POST['register'])) {

    $prihlasovaci_jmeno = $_POST['registerName'];
    $email = $_POST['registerEmail'];
    $heslo = $_POST['registerPassword'];
    $confirm_password = $_POST['registerConfirmPassword'];


    if ($heslo !== $confirm_password) {
        echo "Hesla se neshodují";
        exit();
        
    }

    $checkUser = "SELECT * FROM prihlaseni WHERE prihlasovaci_jmeno = '$prihlasovaci_jmeno' OR email = '$email'";
    $result=$conn->query($checkUser);
    
    if ($result->num_rows > 0) {
        echo "Uživatel s tímto jménem nebo emailem již existuje!";
        exit();
    }
    else{
        $insertUser="INSERT INTO prihlaseni (prihlasovaci_jmeno, heslo, email) VALUES ('$prihlasovaci_jmeno', '$heslo', '$email')";
            if($conn->query($insertUser)==TRUE){
                echo "Registrace úspěšná!";
                exit();
            }
            else{
                echo "Registrace selhala!";
                exit();
            }
    }

}
$conn->close();
?>