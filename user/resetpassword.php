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
        <title>Register</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body style = "background:linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));">

        <?php
            $password = $cpassword = "";
            $passwordErr = $cpasswordErr = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                require_once "../includes/db_connect.php";

                if (empty($_POST['password'])){
                    $passwordErr="Password cannot be blank";
                }
                else{
                    if (empty($_POST['cpassword'])){
                        $cpasswordErr="Please confirm password";
                    }
                    else{
                        $pass=$_POST['password'];
                        $cpass=$_POST['cpassword'];
                        if ($pass!=$cpass){
                            $cpasswordErr="Password does not match. Try again";
                        }
                        else{
                            $password=$_POST['password'];
                        }
                    }
                }

                if ($passwordErr == "" && $cpasswordErr == ""){
                    $update = " UPDATE  user
                                SET     password='$password'
                                WHERE   username='$user'";
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $uResult = $conn->exec($update);

                    if ($uResult){
                        header("Location: home.php");
                    }
                }

            }
        ?>

        <form id="msform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" >
            <fieldset>
                <h2 class="fs-title">Forgot Password</h2>
                <h3 class="fs-subtitle">Reset password</h3>
                <input type="password" id="password" name="password" onblur="blurFunction('password')" placeholder="Password"/>
                <span class="fs-subtitle"><?php echo $passwordErr;?></span><br/><br/>
                <input type="password" id="cpassword" name="cpassword" onblur="blurFunction('cpassword')" placeholder="Confirm Password" onfocus="focusFunction('cpassword')"/>
                <span class="fs-subtitle"><?php echo $cpasswordErr;?></span><br/>
                <input type="submit" name="submit" class = "submit action-button" value="Submit"/>
            </fieldset>
        </form>
        <script  src="../js/index.js"></script>
        <script src="../js/validate.js"></script>
    </body>
</html>
