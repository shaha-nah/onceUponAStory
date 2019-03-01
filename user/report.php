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
            $reason=$reasonErr="";
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $profile=$_POST['profile'];
                if (isset($_POST['submit'])){
                    if ($_POST['submit'] == "report"){
                        if (empty($_POST['reportreason'])){
                            $reasonErr="Please select a reason";
                        }
                        else{
                            $reason=$_POST['reportreason'];
                        }

                        if ($reasonErr==""){
                            $userQuery="    SELECT  *
                                            FROM    block
                                            WHERE   username='$user'
                                            AND     user='$profile'";
                            $queryResult=$conn->query($userQuery);
                            $queryRows=$queryResult->rowCount();
                            if ($queryRows == 0){
                                $report="   INSERT INTO block(username, user, report, reportreason)
                                            VALUES      ('$user', '$profile', 1, '$reason')";
                            }
                            else{
                                $report="   UPDATE  block
                                            SET     mute=0, blocked=0, report=1, reportreason='$reason'
                                            WHERE   username='$user'
                                            AND     user='$profile'";
                            }
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $reportResult=$conn->exec($report);
                            if ($reportResult){
                                header("Location: home.php");
                            }
                        }

                    }

                    if ($_POST['submit'] == "cancel"){
                        header("Location: profile.php?profile=".$profile);
                    }
                }
            }
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <input type="hidden" name="profile" value="<?php echo $profile; ?>">
            Please tell us why you want to report this user
            <br/>
            <input type="radio" name="reportreason" value="Inappropriate Content"/>Inappropriate Content: offensive or abusive language
            <br/>
            <input type="radio" name="reportreason" value="Spam"/>Spam
            <br/>
            <input type="radio" name="reportreason" value="Fake profile"/>User is pretending to be someone else
            <br/>
            <span class="error"><?php echo $reasonErr;?></span><br/><br/>
            <button name="submit" type="submit" value="report">Report</button>
            <button name="submit" type="submit" value="cancel">Cancel</button>
        </form >

    </body>
</html>
