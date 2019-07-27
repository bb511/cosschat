<?php
if(isset($_POST['login-submit'])){
    require 'dbh.inc.php';

    $email = $_POST['mail'];
    $password = $_POST['pwd'];

    if(empty($email) || empty($password)){
        header("Location: ../index.html?error=emptyfields&mail=".$email);
        exit();
    }
    else{
        $sql = "SELECT * FROM users WHERE emailUsers=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../index.php?error=sqlerror");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt, "ss", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if($row = mysqli_fetch_assoc($result)){
                $pwdCheck = password_verify($password, $row['pwdUsers']);
                if($pwdCheck == false){
                    header("Location: ../index.html?error=wrongpwd");
                    exit();
                }
                else if($pwdCheck == true){
                    session_start();
                    $_SESSION['userId'] = $row['idUsers'];
                    $_SESSION['userEmail'] = $row['emailUsers'];
                    header("Location: ../index.html?login=success");
                }
                else{
                    header("Location: ../index.html?error=wrongpwd");
                    exit();
                }
            }
            else{
                header("Location: ../index.html?error=nouser");
            }
        }
    }

}
else{
    header("Location: ../chat.html");
    exit();
}
