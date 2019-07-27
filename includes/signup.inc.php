<?php
if(isset($_POST['signup-submit'])){
    require 'dbh.inc.php';

    $name           = $_POST['name'];
    $surname        = $_POST['surname'];
    $email          = $_POST['mail'];
    $password       = $_POST['pwd'];
    $passwordRepeat = $_POST['pwdRepeat'];

    if(empty($name) || empty($surname) || empty($email) || empty($password) ||
       empty($passwordRepeat)){
        header("Location: ../register.html?error=emptyfields&name=".$name.
               "&surname=".$surname."&mail=".$email);
        exit();
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL) &&
            !preg_match("/^[a-zA-Z]*/", $name) &&
            !preg_match("/^[a-zA-Z]*/", $surname)){
        header("Location: ../register.html?error=invalidmailnamesurname");
        exit();
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../register.html?error=invalidmail&name=".$name.
               "&surname=".$surname);
        exit();
    }
    else if(!preg_match("/^[a-zA-Z]*/", $name)){
        header("Location: ../register.html?error=invalidname&mail=".$email.
               "&surname=".$surname);
        exit();
    }
    else if(!preg_match("/^[a-zA-Z]*/", $surname)){
        header("Location: ../register.html?error=invalidsurname&name=".$name.
               "&mail=".$email);
        exit();
    }
    else if($password !== $passwordRepeat){
        header("Location: ../register.html?error=passwordcheck&name=".$name.
               "&surname=".$surname."&mail=".$email);
        exit();
    }
    else{
        $sql = "SELECT emailUsers FROM users WHERE emailUsers=?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../register.html?error=sqlerror");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if($resultCheck > 0) {
                header("Location: ../register.html?error=passwordcheck&name=".$name.
                       "&surname=".$surname);
                exit();
            }
            else{
                $sql = "INSERT INTO users (firstName, secondName, emailUsers, pwdUsers) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../register.html?error=sqlerror");
                    exit();
                }
                else{
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "ssss", $name, $surname, $email, $hashedPwd);
                    mysqli_stmt_execute($stmt);
                    header("Location: ../register.html?signup=success");
                    exit();
                }
            }
        }

    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
else{
    header("Location: ../register.html");
    exit();
}
