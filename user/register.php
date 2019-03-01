<?php
    session_start();
?>

<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- EMAIL VALIDATION -->
        <script>
            $(document).ready(function(){
                $("#email").blur(function(){
                    $.ajax({
                        type:   "post",
                        url:    "validateRegister.php",
                        data:  { email:$("#email").val()}
                    }).done(function(msg){
                        if (msg=="taken"){
                            $("#email").css("border-color", "red");
                            $("#emailError").text("Email already in use");
                        }
                        else{
                            $("#emailError").text("");
                            $("#email").filter(function(){
                                var email=$("#email").val();
                                var emailRegEx=/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                                if (email != ""){
                                    if (!emailRegEx.test(email)){
                                        $("#email").css("border-color", "red");
                                        $("#emailError").text("Wrong email format");
                                    }
                                    else{
                                        $("#email").css("border-color", "green");
                                        $("#emailError").text("");
                                    }
                                }
                                else{
                                    $("#email").css("border-color", "red");
                                    $("#emailError").text("Please enter email");
                                }
                            });
                        }

                    });
                });
            });
        </script>

        <!-- USERNAME VALIDATION -->
        <script>
            $(document).ready(function(){
                $("#username").blur(function(){
                    $.ajax({
                        type:   "post",
                        url:    "validateRegister.php",
                        data:  { username:$("#username").val()}
                    }).done(function(msg){
                        if (msg=="taken"){
                            $("#username").css("border-color", "red");
                            $("#usernameError").text("Username already in use");
                        }
                        else{
                            $("#usernameError").text("");
                            $("#username").filter(function(){
                                var username=$("#username").val();
                                var usernameRegEx=/^[a-z 0-9 _ ]+$/;
                                if (username != ""){
                                    if (!usernameRegEx.test(username)){
                                        $("#username").css("border-color", "red");
                                        $("#usernameError").text("Invalid characters");
                                    }
                                    else{
                                        $("#username").css("border-color", "green");
                                        $("#usernameError").text("");
                                    }
                                }
                                else{
                                    $("#username").css("border-color", "red");
                                    $("#usernameError").text("Please enter username");
                                }
                            });
                        }
                    });
                });
            });
        </script>

        <!-- PASSWORD VALIDATION -->
        <script>
            $(document).ready(function(){
                $("#password").blur(function(){
                    if ($("#password").val() == ""){
                        $("#password").css("border-color", "red");
                        $("#passwordError").text("Please enter password");
                    }
                    if ($("#cpassword").val() == ""){
                        $("#cpassword").css("border-color", "red");
                    }
                    
                })
            })
        </script>
        <script>
            $(document).ready(function(){
                $("#cpassword").keyup(function(){
                    if ($("#cpassword").val() != ""){
                        if ($("#password").val() != $("#cpassword").val()){
                            $("#cpasswordError").text("Password does not match");
                        }
                        else{
                            $("#cpasswordError").text("");
                        }
                    }
                });
            });
        </script>
    </head>

    <body>
        <?php
            $email = $username = $password = $cpassword = $usernametemp = $emailtemp = "";
            $emailErr = $usernameErr = $passwordErr = $cpasswordErr = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                require_once "../includes/db_connect.php";

                if (empty($_POST['email'])){
                    $emailErr="Email cannot be blank";
                }
                else{
                    $emailentered=$_POST['email'];
                    $emailcheck = " SELECT  *
                                    FROM    user
                                    WHERE   user.email='$emailentered'";
                    $emailResult = $conn->query($emailcheck);
                    $emailrows=$emailResult->rowCount();
                    if ($emailrows == 0){
                        $email = $_POST['email'];
                        $emailtemp=$_POST['email'];
                    }
                    else{
                        $emailErr="This account already exists.";
                        $emailtemp=$_POST['email'];
                    }
                }

                if (empty($_POST['username'])){
                    $usernameErr="Username cannot be blank";
                }
                else{
                    $usernameentered = $_POST['username'];
                    $usernamecheck = "  SELECT  *
                                        FROM    user
                                        WHERE   username='$usernameentered'";
                    $usernameResult = $conn->query($usernamecheck);
                    $usernamerows=$usernameResult->rowCount();
                    if ($usernamerows == 0){
                        $username=$_POST['username'];
                        $usernametemp=$_POST['username'];
                    }
                    else{
                        $usernametemp=$_POST['username'];
                        $usernameErr="This username is already taken";
                    }
                }

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

                $dateregistered=date("Y/m/d");
                if ($emailErr == "" && $usernameErr == "" && $passwordErr == "" && $cpasswordErr == ""){

                    $uInsert = "INSERT INTO user(username, email, password, dateregistered)
                                VALUES('$username', '$email', '$password', '$dateregistered')";
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $uResult = $conn->exec($uInsert);

                    if ($uResult){
                        $_SESSION['username'] = $username;
                        header("Location: setup.php?referer=register");
                    }
                }

            }
        ?>

        <form id="msform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" >
            <fieldset>
                <h2 class="fs-title">Account Setup</h2>
                <h3 class="fs-subtitle">Create an account</h3>
                <!-- EMAIL -->
                <input type="text" value="<?php echo $emailtemp;?>" id="email" name="email" onblur="blurFunction('email')" placeholder="Email"  />
                <span id="emailError" class="fs-error"><?php echo $emailErr;?></span><br/><br/>
                <!-- USERNAME -->
                <input type="text" value="<?php echo $usernametemp;?>" id="username" name="username" onblur="blurFunction('username')" placeholder="Username"/>
                <span id="usernameError" class="fs-error"><?php echo $usernameErr;?></span><br/><br/>
                <!-- PASSWORD -->
                <input type="password" id="password" name="password" onblur="blurFunction('password')" placeholder="Password"/>
                <span id="passwordError" class="fs-error"><?php echo $passwordErr;?></span><br/><br/>
                <!-- CONFIRM PASSWORD -->
                <input type="password" id="cpassword" name="cpassword" onblur="blurFunction('cpassword')" placeholder="Confirm Password" onfocus="focusFunction('cpassword')"/>
                <span id="cpasswordError" class="fs-error"><?php echo $cpasswordErr;?></span><br/>

                <input type="submit" name="submit" class = "submit action-button" value="Submit"/>
                <p class="loginhere">
                    <a href="login.php" class="loginhere-link">Already have an account?</a>
                </p>
            </fieldset>
        </form>
        <script src="jquery-3.2.1.min.js"></script>
        <script  src="../js/submit.js"></script>
        <!-- <script src="../js/validate.js"></script> -->
    </body>
</html>
