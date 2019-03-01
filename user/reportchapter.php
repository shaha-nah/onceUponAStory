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
            if (isset($_GET['storyid'])){
                $storyid=$_GET['storyid'];
            }
            if (isset($_GET['chapterno'])){
                $chapterno=$_GET['chapterno'];
            }
            $reason=$reasonErr="";
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $storyid=$_POST['storyid'];
                $chapterno=$_POST['chapterno'];
                if (isset($_POST['submit'])){
                    if ($_POST['submit'] == "flag"){
                        if (empty($_POST['flagreason'])){
                            $reasonErr="Please select a reason";
                        }
                        else{
                            $reason=$_POST['flagreason'];
                        }

                        if ($reasonErr==""){
                            $flag=" UPDATE  chapter
                                    SET     flag=1, flagreason='$reason'
                                    WHERE   storyid=$storyid
                                    AND     chapterno=$chapterno";

                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $flagResult=$conn->exec($flag);
                            if ($flagResult){
                                header("Location: home.php");
                            }
                        }
                    }

                }

                if ($_POST['submit'] == "cancel"){
                    header("Location: reader.php?storyid=".$storyid."&chapterno=".$chapterno);
                }
            }

        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <input type="hidden" name="storyid" value="<?php echo $storyid; ?>">
            <input type="hidden" name="chapterno" value="<?php echo $chapterno; ?>">
            Please tell us why you want to report this chapter
            <br/>
            <input type="radio" name="flagreason" value="Inappropriate Content"/>Inappropriate Content: offensive or abusive language
            <br/>
            <input type="radio" name="flagreason" value="Plagiarism"/>Plagiarism
            <br/>
            <span class="error"><?php echo $reasonErr;?></span><br/><br/>
            <button name="submit" type="submit" value="flag">Report</button>
            <button name="submit" type="submit" value="cancel">Cancel</button>
        </form >

    </body>
</html>
