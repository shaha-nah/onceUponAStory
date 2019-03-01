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
        <title>Editor</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <link rel="stylesheet" href="../css/story.css">
        <link rel="stylesheet" href="../css/editor.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php
            $activemenu="";
            include('../includes/menu.php');
            require_once '../includes/db_connect.php';
            $chaptername=$story=$warning1=$warning2=$warning3="";
            if (isset($_GET['storyid'])){
                $storyid=$_GET['storyid'];
                $sQuery= "  SELECT  storyname
                            FROM    story
                            WHERE   storyid='$storyid'";
                $sResult=$conn->query($sQuery);
                $row=$sResult->fetch();
                echo '<h1>'.$row['storyname'].'</h1>';

                if (isset($_GET['chapterno'])){
                    $chapterno=$_GET['chapterno'];
                    $chapterQuery=" SELECT  chaptername, story, warning
                                    FROM    chapter
                                    WHERE   storyid=$storyid
                                    AND     chapterno=$chapterno";
                    $chapterResult=$conn->query($chapterQuery);
                    $row=$chapterResult->fetch();
                    $warning1=$warning2=$warning3="";
                    $chaptername=$row['chaptername'];
                    $story=$row['story'];
                    $warning=$row['warning'];
                    if ($warning != ""){
                        list($warning1, $warning2, $warning3) = explode(",", $warning);
                    }
                }

            }

            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                if (isset($_POST['submit'])){
                    if ($_POST['submit'] == 'cancel'){
                        header("Location:stories.php");
                    }
                    else{
                        $storyid=$_POST['storyid'];
                        $cnoQuery=" SELECT  chapterno
                                    FROM    chapter
                                    WHERE   storyid=$storyid";
                        $cnoResult=$conn->query($cnoQuery);
                        $cRows=$cnoResult->rowCount();
                        $chapterno=$cRows+1;

                        $chaptername=$_POST['chaptername'];
                        $datecreated=date("Y/m/d");
                        if ($_POST['submit'] == 'save'){
                           $publish=0;
                        }
                        if ($_POST['submit'] == 'publish'){
                            $publish=1;
                        }

                        $warning1=$warning2=$warning3=$warning="";

                        if (!empty($_POST['warning1'])){
                            $warning=$warning.$_POST['warning1'].',';
                        }
                        if (!empty($_POST['warning2'])){
                            $warning=$warning.$_POST['warning2'].',';
                        }
                        if (!empty($_POST['warning3'])){
                            $warning=$warning.$_POST['warning3'];
                        }

                        if(empty($_POST['story'])){
                            echo 'alert("You cannot publish an empty chapter. Saved to drafts")';
                            $publish=0;
                        }
                        else{
                            $story=$_POST['story'];
                        }

                        if (!empty($_POST['chapterno'])){
                            $chapterno=$_POST['chapterno'];
                            $datemodified=date("Y/m/d");
                            $chapterUpdate="    UPDATE  chapter
                                                SET     chaptername=".$conn->quote($chaptername).", story=".$conn->quote($story).", datemodified='$datemodified' published=$publish, warning='$warning'
                                                WHERE   chapterno=$chapterno
                                                AND     storyid=$storyid";
                            $cResult=$conn->exec($chapterUpdate);
                        }
                        else{
                            $cInsert="  INSERT INTO chapter(chapterno, storyid, chaptername, story, datecreated, datemodified, published, warning)
                                        VALUES      ($chapterno, $storyid, ".$conn->quote($chaptername).", ".$conn->quote($story).", '$datecreated', '$datecreated', $publish, '$warning')";
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $cResult=$conn->exec($cInsert);

                        }
                        if ($cResult){
                            header("Location: profile.php");
                        }
                    }

                }
            }
        ?>
        <div class="editor">
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
                <input type="hidden" name="storyid" value="<?php echo htmlspecialchars($_GET['storyid']);?>">
                <?php
                    if (isset($_GET['chapterno'])){
                        echo '<input type="hidden" name="chapterno" value="'.$_GET['chapterno'].'">';
                    }
                ?>
                <input type="text" id="chaptername" style='color:black' name="chaptername" value="<?php echo $chaptername; ?>" placeholder="Chapter name"/>
                <h5>Write your story here</h5>
                <textarea name="story" rows="30" cols="120" ><?php echo $story; ?></textarea>
                <br/>
                Check warnings(if any)
                <br/>
                <input type="checkbox" name="warning1" value="Strong language" <?php if ($warning1 != ""){echo "checked";}?>>Strong language<br/>
                <input type="checkbox" name="warning2" value="Violence" <?php if ($warning2 != ""){echo "checked";}?>>Violence<br/>
                <input type="checkbox" name="warning3" value="Mature content" <?php if ($warning3 != ""){echo "checked";}?>>Mature content<br/><br/>
                <button name="submit" type="submit" value="save">Save as draft</button>
                <button name="submit" type="submit" value="publish">Publish</button>
                <button name="submit" type="submit" value="cancel">Cancel</button>
            </form>
        </div>
    </body>
</html>
