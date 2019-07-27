<?php
if(isset($_POST['login-submit'])){
    require 'dbh.inc.php';

    $email = $_POST['mail'];
    $password = $_POST['pwd'];

    if(empty($email) || empty($password)){
        header("Location: ../sigin.php?error=emptyfields&mail=".$email);
        exit();
    }

}
