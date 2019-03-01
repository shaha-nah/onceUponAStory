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

                $infoQuery = "  SELECT  name, gender, dob, profiledescription, location, language, profilepicture, password
                                FROM    user
                                WHERE   username='$user'";
                $infoResult = $conn->query($infoQuery);
                $row = $infoResult->fetch();
                $name=$row['name'];
                $gender=$row['gender'];
                $dob=$row['dob'];
                $description=$row['profiledescription'];
                $location=$row['location'];
                $language=$row['language'];
                $profilepicture=$row['profilepicture'];
                $password=$row['password'];

                if ($_SERVER["REQUEST_METHOD"] == "POST"){
                    if (isset($_POST['submit'])){
                        if ($_POST['submit'] == 'cancel'){
                            header("Location: profile.php");
                        }
                        if ($_POST['submit'] == 'reset'){
                            header("Location: settings.php");
                        }
                        if ($_POST['submit'] == 'save'){
                            $name=$_POST['name'];
                            $gender=$_POST['gender'];
                            $dob=$_POST['dob'];
                            $description=$_POST['description'];
                            $location=$_POST['location'];
                            $language=$_POST['language'];

                            if (!empty($_POST['file-image'])){
                                $path=$_POST['file-image'];
                                $temp=explode('\\', $path);
                                $picture=array_pop($temp);
                            }else{
                                $picture=$profilepicture;
                            }

                            $update = " UPDATE  user
                                        SET     name=".$conn->quote($name).", gender='$gender', dob='$dob', profiledescription=".$conn->quote($description).", location='$location', language='$language', profilepicture='$picture'
                                        WHERE   username='$user'";

                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $Result=$conn->exec($update);
                            if ($Result){
                                header("Location: profile.php");
                            }
                        }
                    }

                }
            ?>

            <form id="msform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
                Display Picture<br/>
                <?php echo '<img src="../images/'.$profilepicture.'" width="250px" height="150px">'?>
                <input type="file" id="file-input" name="imagefile"
                    onchange="document.getElementsByName('file-image')[0].value = document.getElementById('file-input').value;">
                <input type="hidden" name="file-image" value="">
                Name<input type="text" value="<?php echo $name;?>" id="name" name="name" placeholder="Name"/><br/><br/>
                Description<textarea rows="7" cols="30" name="description" id="description"><?php echo $description;?></textarea><br/><br/>
                Gender<input type="text" value="<?php echo $gender;?>" id="gender" name="gender" placeholder="Gender"/><br/><br/>
                Date of birth<input type="date" value="<?php echo $dob;?>" id="birthday" name="dob">
                Location<input type="text" value="<?php echo $location;?>" id="location" name="location" placeholder="Location"/><br/><br/>
                Language<input type="text" value="<?php echo $language;?>" id="language" name="language" placeholder="Language"/><br/><br/>
                <button name="submit" type="submit" value="save">Save</button>
                <button name="submit" type="submit" value="reset">Reset</button>
                <button name="submit" type="submit" value="cancel">Cancel</button>
            </form>
        </div>
    </body>
</html>
