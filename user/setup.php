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
        <title>Setup</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body style = "background:linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));">

        <?php
            $name = $description = $gender = $dob = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $name = $_POST['name'];
                $description=$_POST['description'];
                $gender=$_POST['gender'];
                $dob=$_POST['dob'];

                if (!empty($_POST['file-image'])){
                    $path=$_POST['file-image'];
                    $temp=explode('\\', $path);
                    $picture=array_pop($temp);
                }
                else{
                    $picture="defaultdp.jpeg";
                }

                require_once "../includes/db_connect.php";

                $uUpdate = "UPDATE  user
                            SET     name=".$conn->quote($name).",  gender='$gender', dob='$dob', profiledescription=".$conn->quote($description).", profilepicture='$picture'
                            WHERE   username='$user'";
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $uResult=$conn->exec($uUpdate);
                if ($uUpdate){
                    header("Location: preference.php?referer=setup");
                }
            }
        ?>

        <form id="msform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <fieldset>
                <h2 class="fs-title">Set up profile</h2>
                <h3 class="fs-subtitle">Almost done</h3>
                <img src="../images/defaultdp.jpeg" width="200px" height="150px">
                <input type="file" id="file-input" name="imagefile"
                    onchange="document.getElementsByName('file-image')[0].value = document.getElementById('file-input').value;">
                <input type="hidden" name="file-image" value="">
                <input type="text" value="<?php echo $name;?>" id="name" name="name" placeholder="Name"/><br/><br/>
                <textarea rows="7" cols="30" name="description" id="description" placeholder="Description"></textarea><br/><br/>
                <input type="text" id="gender" name="gender" placeholder="Gender" />
                <input type="date" id="birthday" name="dob">
                <input type="submit" name="submit" class = "submit action-button" value="Submit" />
            </fieldset>
        </form>
    </body>
</head>
