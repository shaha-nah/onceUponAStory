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
        <?php
            $activemenu="";
            include('../includes/menu.php');
            include('../includes/vsettingsmenu.php');
        ?>
        <div style="margin-left:15%;padding:1px 16px;height:1000px;">
        <?php
            require_once "../includes/db_connect.php";

            $opasswordErr = $npasswordErr = $cpasswordErr = $updatepassword = "";

            $infoQuery = "  SELECT  password
                            FROM    user
                            WHERE   username='$user'";
            $infoResult = $conn->query($infoQuery);
            $row = $infoResult->fetch();

            $password=$row['password'];

            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                if (isset($_POST['submit'])){
                    if ($_POST['submit'] == 'cancel'){
                        header("Location: profile.php");
                    }
                    if ($_POST['submit'] == 'save'){
                        if (!empty($_POST['oldpassword']) || !empty($_POST['newpassword']) || !empty($_POST['cpassword'])){
                            if (empty($_POST['oldpassword'])){
                                $opasswordErr="Please enter old password first";
                            }
                            else{
                                $opassword=$_POST['oldpassword'];
                                if ($password==$opassword){
                                    if (empty($_POST['newpassword'])){
                                        $npasswordErr="Please enter a new password";
                                    }
                                    else{
                                        $npassword=$_POST['newpassword'];
                                        if (empty($_POST['cpassword'])){
                                            $cpasswordErr="Please confirm password";
                                        }
                                        else{
                                            if ($npassword == $_POST['cpassword']){
                                                $cpassword=$_POST['cpassword'];
                                            }
                                            else{
                                                $cpasswordErr="Passwords do not match";
                                            }
                                        }
                                    }
                                }
                                else{
                                    $opasswordErr="Incorrect password";
                                }
                            }
                        }

                        if ($opasswordErr=="" && $npasswordErr=="" && $cpasswordErr==""){
                            $update = " UPDATE  user
                                        SET     password='$cpassword'
                                        WHERE   username='$user'";
                        }
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $Result=$conn->exec($update);
                        if ($update){
                            header("Location: profile.php");
                        }
                    }
                }

            }
        ?>

        <form id="msform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            Old password<input type="password" id="oldpassword" name="oldpassword" placeholder="Old Password"/>
            <span class="fs-subtitle"><?php echo $opasswordErr;?></span><br/><br/>
            New password<input type="password" id="newpassword" name="newpassword" placeholder="New Password"/>
            <span class="fs-subtitle"><?php echo $npasswordErr;?></span><br/><br/>
            Confirm password<input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password"/>
            <span class="fs-subtitle"><?php echo $cpasswordErr;?></span><br/><br/>
            <button name="submit" type="submit" value="save">Save</button>
            <button name="submit" type="submit" value="cancel">Cancel</button>
        </form>
    </body>
</html>
