<?php
    session_start();

    if (!isset($_SESSION['username'])){
        header("Location: login.php");
    }
    else{
        $user=$_SESSION['username'];
    }
?>

<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php
            $activemenu="";
            include('../includes/menu.php');
            require_once "../includes/db_connect.php";
            if (isset($_GET['profile'])){
                $profile=$_GET['profile'];
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $profile=$_POST['profile'];
                if (isset($_POST['submit'])){
                    if ($_POST['submit'] == 'block'){
                        $userQuery="    SELECT  *
                                        FROM    block
                                        WHERE   username='$user'
                                        AND     user='$profile'";
                        $queryResult=$conn->query($userQuery);
                        $queryRows=$queryResult->rowCount();
                        if ($queryRows == 0){
                            $block="    INSERT INTO block(username, user, blocked)
                                        VALUES      ('$user', '$profile', 1)";
                        }
                        else{
                            $block="    UPDATE  block
                                        SET     mute=0, blocked=1, report=0
                                        WHERE   username='$user'
                                        AND     user='$profile'";
                        }
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $blockResult=$conn->exec($block);
                        if ($blockResult){
                            header("Location: home.php");
                        }
                    }

                    if ($_POST['submit'] == "cancel"){
                        header("Location: profile.php?profile=".$profile);
                    }
                }
            }
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            Are you sure you want to block this user?
            <br/>
            <input type="hidden" name="profile" value="<?php echo $profile; ?>">
            <button name="submit" type="submit" value="block">Block</button>
            <button name="submit" type="submit" value="cancel">Cancel</button>
        </form >

    </body>
</html>
