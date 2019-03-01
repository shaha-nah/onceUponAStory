<?php
    require_once "../includes/db_connect.php";
    if (isset($_POST['email'])){
        $email = $_POST['email'];
        $emailQuery="   SELECT  *
                        FROM    user
                        WHERE   email='$email'
                        AND     username != 'admin'";
        $emailResult=$conn->query($emailQuery);
        $emailRows=$emailResult->rowCount();
        if ($emailRows != 0){
            echo 'taken';
        }
    }

    if (isset($_POST['username'])){
        $username = $_POST['username'];
        $usernameQuery="   SELECT  *
                        FROM    user
                        WHERE   username='$username'
                        AND     username != 'admin'";
        $usernameResult=$conn->query($usernameQuery);
        $usernameRows=$usernameResult->rowCount();
        if ($usernameRows != 0){
            echo 'taken';
        }
    }
?>
