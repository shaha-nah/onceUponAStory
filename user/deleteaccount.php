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
        <title>Settings</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <link rel="stylesheet" href="../css/vmenu.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <script>
            function msg(){
                alert('Account deleted');
            }
        </script>
        <?php
            $activemenu="";
            include('../includes/menu.php');
            include('../includes/vsettingsmenu.php');
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                require_once '../includes/db_connect.php';
                if ($_POST['submit'] == "yes"){

                    $storyDelete =" DELETE
                                    FROM    story
                                    WHERE   author='$user'";

                    $sdelResult=$conn->query($storyDelete);

                    $userDelete = " DELETE
                                    FROM    user
                                    WHERE   username='$user'";
                    $udelResult=$conn->query($userDelete);

                    if ($udelResult && $sdelResult){
                        session_destroy();
                        header("Location: ouas.php");
                    }
                }
                if ($_POST['submit'] == "no"){
                    header("Location: profile.php");
                }
            }
        ?>

        <form id="msform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <?php
                echo "Are you sure you want to delete your account?";
            ?>
            <p>
                <button name="submit" type="submit" value="yes" onclick="msg()">Yes</button>
                <button name="submit" type="submit" value="no">No</button>
            </p>
        </form>
    </body>
</html>
