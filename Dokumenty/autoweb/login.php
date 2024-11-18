<?php

include 'connect.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM prihlaseni WHERE prihlasovaci_jmeno = '$username' AND heslo = '$password'";
    $result=$conn->query($sql);

    if($result->num_rows>0){
        session_start();
        $row=$result->fetch_assoc();
 	$_SESSION['prihlasovaci_jmeno']=$row['prihlasovaci_jmeno'];
        $_SESSION['role']=$row['role'];        
        echo "Přihlášení úspěšné!";
        exit();
    }
    else{
        echo "Nesprávné údaje přihlášení!";
        exit();
    }

}

$conn->close();
?>