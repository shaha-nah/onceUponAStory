<?php
    session_start();
?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>

    <?php
            $email = $username = "";
            $emailErr = $usernameErr = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                if (empty($email=$_POST['email'])){
                    $usernameErr="Please enter email";
                }
                else{
                    $email=$_POST['email'];
                }

                if (empty($username=$_POST['username'])){
                    $usernameErr="Please enter username";
                }
                else{
                    $username=$_POST['username'];
                }

                require_once "../includes/db_connect.php";
                if ($usernameErr==""){
                    $sQuery = " SELECT  username, email
                                FROM    user
                                WHERE   username='$username'";
                    $Result = $conn->query($sQuery);
                    $userResults = $Result->fetch();
                    if($userResults['username']){
                        $useremail = $userResults['email'];
                        if ($email==$useremail){
                            $_SESSION['username'] = $username;
                            header("Location: resetpassword.php");
                        }
                        else{
                            $emailErr = "Incorrect email";
                        }
                    }
                    else{
                        $usernameErr = "Incorrect username";
                    }
                }
            }
        ?>

        <form id="msform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" >
            <fieldset>
                <h2 class="fs-title">Forgot Password</h2>
                <h3 class="fs-subtitle">Verify your account</h3>
                <input type="text" value="<?php echo $email;?>" id="email" name="email" onblur="blurFunction('email')" placeholder="Enter email"/>
                <span class="fs-subtitle"><?php echo $emailErr;?></span><br/><br/>
                <input type="text" value="<?php echo $username;?>" id="username" name="username" onblur="blurFunction('username')" placeholder="Username"/>
                <span class="fs-subtitle"><?php echo $usernameErr;?></span><br/><br/>
                <input type="submit" name="login" class="login action-button" value="Submit"/>
            </fieldset>
        </form>
        <script  src="../js/index.js"></script>
        <script src="../js/validate.js"></script>
    </body>
</html>
