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
                                    WHERE   username = '$username'
                                    AND     accounttype = 'admin' ";
                        $Result = $conn->query($sQuery) ;
                        $userResults = $Result->fetch();
                        if($userResults['username'] ){
                        // $hashed_password = $userResults['password'];
                        // if(password_verify($password,$hashed_password)){
                            $userpassword = $userResults['password'];
                            if ($password==$userpassword){
                                $_SESSION['username'] = $username;
                                header("Location: dashboard.php");
                            }
                            else{
                                $passwordErr = "Incorrect password";
                            }
                        }
                        else{
                            $usernameErr = "Incorrect username";
                        }
                    }
                }
            ?>

            <form id="msform" method="post">
                <fieldset>
                    <h2 class="fs-title">Login</h2>
                    <h3 class="fs-subtitle">Enter your credentials</h3>
                    <input type="text" value="<?php echo $username;?>" id="username" name="username" onblur="blurFunction('username')" placeholder="Username"/>
                    <span class="fs-subtitle"><?php echo $usernameErr;?></span><br/><br/>
                    <input type="password" id="password" name="password" onblur="blurFunction('password')" placeholder="Password"/>
                    <span class="fs-subtitle"><?php echo $passwordErr;?></span><br/><br/>
                    <input type="submit" name="login" class="login action-button" value="Login"/>
                </fieldset>
            </form>
            <script  src="js/index.js"></script>
            <script src="js/validate.js"></script>
    </body>
</html>
