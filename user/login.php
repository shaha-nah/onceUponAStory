<?php
    session_start();
?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script>
            $(document).ready(function(){
                $("#username").focus(function(){
                    $("#usernameError").text("");
                });
            });
        </script>
    </head>

    <body>

    <?php
            $username = $password = "";
            $usernameErr = $passwordErr = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                if (empty($username=$_POST['username'])){
                    $usernameErr="Please enter username";
                }
                else{
                    $username=$_POST['username'];
                }

                if (empty($password=$_POST['password'])){
                    $passwordErr="Please enter password";
                }
                else{
                    $password=$_POST['password'];
                }

                require_once "../includes/db_connect.php";
                if ($usernameErr=="" && $passwordErr==""){
                    $sQuery = " SELECT  *
                                FROM    user
                                WHERE   username = '$username'  ";
                    $Result = $conn->query($sQuery) ;
                    $userResults = $Result->fetch();
                    if($userResults['username'] ){
                    // $hashed_password = $userResults['password'];
                    // if(password_verify($password,$hashed_password)){
                        $userpassword = $userResults['password'];
                        if ($password==$userpassword){
                            $_SESSION['username'] = $username;
                            if (isset($_GET['referrer'])){
                                header("Location: ".$_GET['referrer']);
                            }
                            else{
                                header("Location: home.php");
                            }
                        }
                        else{
                            $passwordErr = "Incorrect password";
                        }
                    }
                    else{
                        $usernameErr = "Username does not exist";
                    }
                }
            }
        ?>

        <form id="msform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" >
            <fieldset>
                <h2 class="fs-title">Login</h2>
                <h3 class="fs-subtitle">Enter your credentials</h3>
                <input type="text" value="<?php echo $username;?>" id="username" name="username" onblur="blurFunction('username')" placeholder="Username"/>
                <span id="usernameError" class="fs-error"><?php echo $usernameErr;?></span><br/><br/>
                <input type="password" id="password" name="password" onblur="blurFunction('password')" placeholder="Password"/>
                <span id="passwordError" class="fs-error"><?php echo $passwordErr;?></span><br/><br/>
                <p>
                    <a href="forgotpassword.php">Forgot password?</a>
                </p>
                <input type="submit" name="login" class="login action-button" value="Login"/>
                <p>
                    <a href="register.php">Not a registered user?</a>
                </p>
            </fieldset>
        </form>
        <script  src="../js/submit.js"></script>
        <script src="../js/validate.js"></script>
    </body>
</html>
